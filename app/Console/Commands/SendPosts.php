<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\Post;
use App\Models\TgUser;
use App\Models\CategoryUser;

class SendPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-posts';

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
        $posts = Post::where('status', 1)->get();

        foreach ($posts as $p) {
            // получаем всех подписчиков данной категории
            $userIds = CategoryUser::where('category_id', $p->category_id)->pluck('user_id');
            
            $users = TgUser::whereIn('id', $userIds)
                        ->where('status', 1)
                        ->get();

            foreach ($users as $u) {
                Telegram::sendMessage([
                    'text' => $p->content,
                    'chat_id' => $u->chat_id,
                    'parse_mode' => 'HTML',
                ]);

                $u->last_post_id = $p->id;
                $u->save();
            }

            $p->status = 0;
            $p->published_at = date('d.m.y H:i');
            $p->save();

            // пишем в лог
        }
    }
}
