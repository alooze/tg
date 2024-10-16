<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;

use danog\MadelineProto\Tools;
use danog\MadelineProto\Logger;
use danog\MadelineProto\Settings\Logger as LoggerSettings;

use App\Models\Channel;

use Iteks\Support\Facades\OpenAi;

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

    public function ai()
    {
        $response = OpenAi::chat()->create(
            [
                [   'role' => 'system', 'content' => 'You are a large language model. Carefully heed the user\'s instructions.',],
                // [ 'role' => 'user', 'content' => 'Напиши буквы русского алфавита от а до ш', ],
                [ 'role' => 'user', 
                'content' => 'Прочитай текст и ответь цифрой 0, если текст не является объявлением о вакансии. Ответь цифрой 1, если вакансия не связана с разработкой дизайна. 
Ответь цифрой 2, если вакансия связана с разработкой дизайна веб-сайтов, логотипов, соцсетей, youtube, Тильда, веб-баннеров.
Ответь цифрой 3, если вакансия связана с разработкой дизайна айдентики, фирменного стиля, бренд буков, визиток, обложек, постеров.
Ответь цифрой 4, если вакансия связана с разработкой дизайна для маркетплейсов, для wildberries, озон, рич-контентом.
Ответь цифрой 5, если вакансия связана с разработкой дизайна презентаций, резюме, рекламы, для highlights.
Ответь цифрой 6, если вакансия связана с разработкой дизайна полиграфии, листовок, буклетов, визиток, макетов для печати, принтов, наружных вывесок, прессы, наклеек, печатных каталогов, roll up.

Текст: 

#ищу 
Нужен граф.дизайнер для отрисовки стикеров на печать и в ТГ
Просьба откликаться в ЛС
Работа в иллюстраторе
@Anastasia_des
                ', ],
            ],
            'openai/gpt-4o-mini'
        );

        // Example of a required parameter
        // $response = OpenAi::chat('Напиши буквы русского алфавита от а до ш');

        dump($response['choices'][0]['message']['content']);

        // Example with optional parameters
        // $response = OpenAi::chat('Your message here', ['temperature' => 0.7, 'max_tokens' => 150]);
    }

    public function read()
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

            /* Получим историю сообщений */
            $messages = $API->messages->getHistory([
                    /* Название канала, без @ */
                'peer' => $c->channel_id, 
                'offset_id' => 0, 
                'offset_date' => 0, 
                'add_offset' => 0,
                'limit' => 10,
                'max_id' => 9999999, 
                'min_id' => $c->last_message_id, 
            ]);
            // https://t.me/zakaz_design/2667
            // https://t.me/rx_john_galt/24
            dump($messages);
        }


        // Log::create([
        //     'type' => 'grab',
        //     'chat_id' => '1',
        //     'message_id' => '1',
        //     'post_id' => '1',
        // ]);

        file_put_contents(storage_path('logs/grab.log'),  date('d-m-Y H:i') . PHP_EOL, FILE_APPEND);
    }

    public function read1()
    {
        $settings = (new \danog\MadelineProto\Settings\AppInfo)
            ->setApiId(env('TELEGRAM_API_ID'))
            ->setApiHash(env('TELEGRAM_API_HASH'));

        $API = new \danog\MadelineProto\API(env('TELEGRAM_GRAB_SESSION'), $settings);
        $API->start();

        $channels = Channel::where('status', 1)->get();

        $me = $API->getSelf();

        // $settings = (new LoggerSettings)
        //     ->setType(Logger::FILE_LOGGER)
        //     ->setExtra('custom.log')
        //     ->setMaxSize(50*1024*1024);
        // $API->updateSettings($settings);

        echo '<pre>';
        foreach ($channels as $c) {
            /* Получим историю сообщений */
            $messages = $API->messages->getHistory([
                    /* Название канала, без @ */
                'peer' => $c->channel_id, 
                'offset_id' => 0, 
                'offset_date' => 0, 
                'add_offset' => 0,
                'limit' => 10,
                // 'max_id' => 9999999, 
                // 'min_id' => 24, // https://t.me/rx_john_galt/24
            ]);
            // https://t.me/zakaz_design/2659
            dump($messages);
        }

        return;

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
