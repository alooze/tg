<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\TgUser;
use App\Models\Category;
use App\Models\CategoryUser;

class WebhookController extends Controller
{
    protected BotsManager $botsManager;

    protected TgUser $tgUser;

    protected $update;

    public function __construct(BotsManager $botsManager)
    {
        $this->botsManager = $botsManager;
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        // $updates = Telegram::getWebhookUpdate();
        $this->update = Telegram::commandsHandler(true);

        $this->tgUser = TgUser::where('chat_id', $this->update->message->chat->id)->first();

        if ($this->tgUser && $this->tgUser->status == 0) {
            Telegram::sendMessage([
                'text' => 'Вы находитесь в блок-листе в этом боте!',
                'chat_id' => $this->update->message->chat->id,
                'parse_mode' => 'HTML',
            ]);
            return response(null, 200);
        }

        // var_dump($update->message->text);
        // var_dump($update->message->entities);
        // die();

        if (substr($this->update->message->text, 0, 1) != '/' 
            // && isset($updates->message->entities) 
            // && $updates->message->entities == 'bot_command'
        ) {
            // $this->botsManager->bot()->commandsHandler(true);
            // $update = Telegram::commandsHandler(true);
        // } else {
            $this->replyOnKeyboard();
        }
        return response(null, 200);
    }

    public function replyOnKeyboard()
    {
        $c = Category::where('title', $this->update->message->text)
                    ->where('status', 1)
                    ->first();

        if (!$c) {
            $text = 'Категория не найдена, попробуйте выбрать другую!';
        } else {
            $text = $c->description;
            $text = $this->parsePh($text);

            $uCat = CategoryUser::where('user_id', $this->tgUser->id)
                            ->first();

            if (!$uCat) {
                $uCat = CategoryUser::create([
                    'user_id' => $this->tgUser->id,
                    'category_id' => $c->id,
                ]);
            } else {
                $uCat->category_id = $c->id;
                $uCat->save();
            }
        }

        // Telegram::sendMessage([
        //     'text' => 'Вы нажали ' . $this->update->message->text,
        //     'chat_id' => $this->update->message->chat->id,
        // ]);

        Telegram::sendMessage([
            'text' => $text,
            'chat_id' => $this->update->message->chat->id,
            'parse_mode' => 'HTML',
        ]);
    }

    private function parsePh($text)
    {
        return $text = str_replace([
            '[username]',
            '[chat]',
            '[firstname]',
            '[lastname]',
        ], [
            $this->tgUser->username,
            $this->tgUser->chat_id,
            $this->tgUser->first_name,
            $this->tgUser->last_name
        ], $text);
    }
}