<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\Option;
use App\Models\TgUser;
use Telegram\Bot\Laravel\Facades\Telegram;

class AdminController extends Controller
{
    public function index()
    {
        return view('index');
    }

    // @todo переделать в отложенное задание

    public function sendAll(Request $request)
    {
        $users = TgUser::where('status', 1)->get();
        foreach ($users as $u) {
            $text = str_replace([
                '[username]',
                '[firstname]',
                '[lastname]',
            ], [
                $u->username,
                $u->id,
                $u->first_name,
                $u->last_name
            ], $request->content);

            Telegram::sendMessage([
                'text' => $text,
                'chat_id' => $u->chat_id,
                'parse_mode' => 'HTML',
            ]);
        }

        return back()->with('status', 'Отправка всем пользователям запущена');
    }
}
