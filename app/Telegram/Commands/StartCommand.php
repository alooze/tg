<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\User;
use App\Models\TgUser;
use App\Models\Option;

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

        if (!$opt) {
            $text = 'Привет, <strong>' . $this->tgUser->first_name . '</strong>! Добро пожаловать!';
        } else {
            $text = $opt->value;
        }

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }

    public function sendKeyboard()
    {
        $reply_markup = Keyboard::make()
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
                ]);

        $response = Telegram::sendMessage([
            'chat_id' => $this->tgUser->chat_id,
            'text' => 'Выберите категорию',
            'reply_markup' => $reply_markup
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
            $text = 'Привет, <strong>' . $this->tgUser->first_name . '</strong>! С возвращением!';
        } else {
            $text = $opt->value;
        }

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }

    private function sendBlocked()
    {
        $this->replyWithMessage([
            'text' => 'Вы не можете использовать этого бота в связи с блокировкой',
        ]); 
    }
            
}