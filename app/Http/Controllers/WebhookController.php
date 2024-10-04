<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\TgUser;

class WebhookController extends Controller
{
    protected BotsManager $botsManager;
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
        $update = Telegram::commandsHandler(true);

        $tgUser = TgUser::where('chat_id', $update->message->chat->id)->first();

        if ($tgUser && $tgUser->status == 0) {
            Telegram::sendMessage([
                'text' => 'Вы находитесь в блок-листе в этом боте!',
                'chat_id' => $update->message->chat->id,
            ]);
            return response(null, 200);
        }

        // var_dump($update->message->text);
        // var_dump($update->message->entities);
        // die();

        if (substr($update->message->text, 0, 1) != '/' 
            // && isset($updates->message->entities) 
            // && $updates->message->entities == 'bot_command'
        ) {
            // $this->botsManager->bot()->commandsHandler(true);
            // $update = Telegram::commandsHandler(true);
        // } else {
            $this->replyOnKeyboard($update);
        }
        return response(null, 200);
    }

    public function replyOnKeyboard($upd)
    {
        Telegram::sendMessage([
            'text' => 'Вы нажали ' . $upd->message->text,
            'chat_id' => $upd->message->chat->id,
        ]);
    }
}