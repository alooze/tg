<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;

use danog\MadelineProto\Tools;
use danog\MadelineProto\Logger;
use danog\MadelineProto\Settings\Logger as LoggerSettings;

class BotController extends Controller
{
    protected $telegram;

    /**
     * Create a new controller instance.
     *
     * @param  Api  $telegram
     */
    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    public function index()
    {
        // $response = Telegram::getMe();
        // var_dump($response);
        $response = $this->telegram->getMe();

        // $botId = $response->getId();
        // $firstName = $response->getFirstName();
        // $username = $response->getUsername();

        return $response;
    }

    public function read()
    {
        $settings = (new \danog\MadelineProto\Settings\AppInfo)
            ->setApiId(20737516)
            ->setApiHash('51f71f08ab8790f1370c41e7c48805b1');

        $API = new \danog\MadelineProto\API('session.madeline2', $settings);

        $API->start();

        // $API->phoneLogin(Tools::readLine('Enter your phone number: '));
        // $authorization = $MadelineProto->completePhoneLogin(Tools::readLine('Enter the phone code: '));
        // if ($authorization['_'] === 'account.password') {
        //     $authorization = $API->complete2falogin(Tools::readLine('Please enter your password (hint '.$authorization['hint'].'): '));
        // }
        // if ($authorization['_'] === 'account.needSignup') {
        //     $authorization = $API->completeSignup(Tools::readLine('Please enter your first name: '), readline('Please enter your last name (can be empty): '));
        // }

        $me = $API->getSelf();

        $settings = (new LoggerSettings)
            ->setType(Logger::FILE_LOGGER)
            ->setExtra('custom.log')
            ->setMaxSize(50*1024*1024);
        $API->updateSettings($settings);

        echo '<pre>';

        // $dialogs = $API->getFullDialogs();
        // foreach ($dialogs as $dialog) {
        //     // $API->logger($dialog);
        //     // var_dump($dialog);
        //     if ($dialog['peer'] != '-1001960137529') continue;

        //     // $fullChat = $MadelineProto->getFullInfo($dialog['peer']);
        //     $chat = $API->getInfo($dialog['peer']);
        //     var_dump($chat);
        // }

        $chat = 'https://t.me/zakaz_design';
        // $chat = 'https://t.me/+h8tJkUNM_wc5MzEy';
        $res = $API->getInfo($chat);

        var_dump($res);


        // // \danog\MadelineProto\Logger::log($me);
        // $lastid = 24;

        // /* Получим историю сообщений */
        // $messages = $API->messages->getHistory([
        //         /* Название канала, без @ */
        //     'peer' => $chat, 
        //     'offset_id' => 0, 
        //     'offset_date' => 0, 
        //     'add_offset' => 0,
        //     'limit' => 10,
        //     'max_id' => 9999999, 
        //         /* ID сообщения, с которого начинаем поиск */
        //     'min_id' => 24, // https://t.me/rx_john_galt/24
        // ]);

        // var_dump($messages);

        /* Сообщения, сортировка по дате (новые сверху) */
        // $messages = $messages['messages'];
        // foreach(array_reverse($messages) as $i => $message){
        //         /* Шлем сообщение на свой канал */
        //         $API->messages->sendMessage([
        //               'peer' => 'Save42',
        //               'message' => $message['message']
        //         ]);
        // }
    }

    public function webhook()
    {
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
        return response(content: 'OK', status: 200);
    }
}
