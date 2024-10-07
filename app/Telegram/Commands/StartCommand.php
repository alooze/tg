<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\User;
use App\Models\TgUser;
use App\Models\Option;
use App\Models\Category;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Команда Start при запуске бота';
    protected TgUser $tgUser;

    public function __construct(TgUser $tgUser)
    {
        $this->tgUser = $tgUser;
    }

    public function handle()
    {
        //Получаем всю информацию о пользователе
        $userData = $this->getUpdate()->message->from;
        // dd($userData);

        //Получаем его уникальный ID
        $userId = $userData['id'];

        //Пробуем найти юзера в БД
        $tgUser = TgUser::where('user_id', $userId)->first();

        //Проверяем, если нашли пользователя отправляем сообщение как старому
        //Иначе добавляем его в бд и отправялем сообщение как новому
        if ($tgUser) {
            if ($tgUser->status == 0) {
                return $this->sendBlocked();
            }

            $this->tgUser = $tgUser;
            $this->sendWelcomeBack();
        } else {
            $this->addNewTelegramUser($userData);
            $this->sendGreet();
        }

        $this->sendKeyboard();
    }

    public function sendGreet()
    {
        $opt = Option::where('name', 'new_user_welcome')->first();

        /* {
            "update_id": 607940345,
            "message": {
                "message_id": 158,
                "from": {
                    "id": 1306676998,
                    "is_bot": false,
                    "first_name": "alooze",
                    "username": "alooze_ph",
                    "language_code": "ru"
                },
                "chat": {
                    "id": 1306676998,
                    "first_name": "alooze",
                    "username": "alooze_ph",
                    "type": "private"
                },
                "date": 1727960424,
                "text": "/start",
                "entities": [
                    {
                        "offset": 0,
                        "length": 6,
                        "type": "bot_command"
                    }
                ]
            }
        } */

        if (!$opt) {
            $text = 'Привет, <strong>[firstname]</strong>! Добро пожаловать!';
        } else {
            $text = $opt->value;
        }

        $text = $this->parsePh($text);

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }

    public function sendKeyboard()
    {
        $cats = Category::where('status', 1)->orderBy('position', 'asc')->get();

        $replyMarkup = Keyboard::make()
                ->setResizeKeyboard(true)
                ->setOneTimeKeyboard(true);
        foreach ($cats as $c) {
            $replyMarkup->row([
                Keyboard::button($c->title),
            ]);
        }

        /*$reply_markup = Keyboard::make()
                ->setResizeKeyboard(true)
                ->setOneTimeKeyboard(true)
                ->row([
                    Keyboard::button([
                        'text' => '11',
                        'callback_data' => '111',
                    ]),
                    Keyboard::button('22'),
                    Keyboard::button('33'),
                ])
                ->row([
                    Keyboard::button('44'),
                    Keyboard::button('55'),
                    Keyboard::button('66'),
                ])
                ->row([
                    Keyboard::button('77'),
                    Keyboard::button('88'),
                    Keyboard::button('99'),
                ])
                ->row([
                    Keyboard::button('00'),
                ]);*/

        $response = Telegram::sendMessage([
            'chat_id' => $this->tgUser->chat_id,
            'text' => 'Выберите категорию',
            'reply_markup' => $replyMarkup
        ]);

        // $messageId = $response->getMessageId();
        // var_dump($response);
        // die();
    }

    private function addNewTelegramUser($data)
    {
        $this->tgUser = TgUser::create([
            'user_id' => $data['id'],
            'chat_id' => $data['id'],
            'username' => $data['username'] ?? 'user',
            'first_name' => $data['first_name'] ?? 'first_name',
            'last_name' => $data['last_name'] ?? 'last_name',
            'language_code' => $data['language_code'] ?? 'ru',
            'is_premium' => $data['is_premium'] ?? 0,
            'is_bot' => $data['is_bot'] ?? 0,
            'status' => 1,
        ]);

        return;
    }

    public function sendWelcomeBack()
    {
        $opt = Option::where('name', 'user_welcome_back')->first();

        if (!$opt) {
            $text = 'Привет, <strong>[firstname]</strong>! С возвращением!';
        } else {
            $text = $opt->value;
        }

        $text = $this->parsePh($text);

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }

    private function sendBlocked()
    {
        $opt = Option::where('name', 'user_you_banned')->first();

        if (!$opt) {
            $text = 'Внимание, <strong>[username]</strong>! Вы не можете использовать этого бота в связи с блокировкой!';
        } else {
            $text = $opt->value;
        }

        $text = $this->parsePh($text);

        $this->replyWithMessage([
            'text' => $text,
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
            $this->tgUser->id,
            $this->tgUser->first_name,
            $this->tgUser->last_name
        ], $text);
    }
            
}