<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;
use App\Models\Channel;
use App\Models\Message;
use App\Models\Log;

class GrabMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:grab-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = (new \danog\MadelineProto\Settings\AppInfo)
            ->setApiId(env('TELEGRAM_API_ID'))
            ->setApiHash(env('TELEGRAM_API_HASH'));

        $API = new \danog\MadelineProto\API(env('TELEGRAM_GRAB_SESSION'), $settings);

        $API->start();

        $channels = Channel::where('status', 1)->get();

        foreach ($channels as $c) {
            if ($c->last_message_id == 1) {
                $c->last_message_id = -1;
            }

            // echo $c->link . PHP_EOL;

            /* Получим историю сообщений */
            $messages = $API->messages->getHistory([
                    /* Название канала, без @ */
                'peer' => $c->username, 
                'offset_id' => 0, 
                'offset_date' => 0, 
                'add_offset' => 0,
                'limit' => 10,
                'max_id' => 9999999, 
                'min_id' => $c->last_message_id, 
            ]);
            // https://t.me/zakaz_design/2667
            // https://t.me/rx_john_galt/24
            // var_dump($messages);

            if (!$messages || !isset($messages['messages'][0])) {
                continue;
            } 

            $maxId = $messages['messages'][0]['id'];
            $c->last_message_id = $maxId;
            $c->save();

            foreach ($messages['messages'] as $m) {
                if (!isset($m['message'])) continue;
                $possibleMessage = Message::where('content', $m['message'])->first();
                if (!$possibleMessage) {
                    $mes = Message::create([
                        'channel_id' => $c->id,
                        'channel_message_id' => $m['id'],
                        'peer_id' => $m['peer_id'],
                        'content' => $m['message'],
                        'data' => $m['date'],
                    ]);
                }
                
            }
        }


        // Log::create([
        //     'type' => 'grab',
        //     'chat_id' => '1',
        //     'message_id' => '1',
        //     'post_id' => '1',
        // ]);

        file_put_contents(storage_path('logs/grab.log'),  date('d-m-Y H:i') . PHP_EOL, FILE_APPEND);
    }
}
