<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;
use App\Models\Category;
use App\Models\Post;

class ParseMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse-messages';

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
        $messages = Message::where('status', 1)->get();

        foreach ($messages as $m) {
            // проверяем на уникальность

            // проверяем на соответствие категории
            $cat = Category::find(1);

            // парсим текст в каком-то виде
            $content = $this->prepareContent($m->content);

            // сохраняем в очередь на отправку
            $p = Post::create([
                'category_id' => $cat->id,
                'channel_id' => $m->channel_id,
                'original_id' => $m->channel_message_id,
                'link' => '',
                'content' => $content,
                'status' => 1,
                'published_at' => date('Y-m-d H:m:i'),
            ]);

            $m->status = 0;
            $m->save();
        }
    }

    private function prepareContent($text)
    {
        $ar = array_chunk(explode(' ', $text), 20);

        return implode(' ', $ar[0]);
    }
}
