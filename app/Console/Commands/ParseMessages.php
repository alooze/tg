<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;
use App\Models\Category;
use App\Models\Post;
use App\Models\Option;
use App\Models\Channel;
use Iteks\Support\Facades\OpenAi;

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

    protected $allMessages;

    protected $allPosts;

    protected $minificationArray;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messages = Message::where('status', 1)->get();
        // $messages = Message::where('status', 1)->limit(1)->get();

        // $this->allMessages = Message::where('status', 0)->get();
        $this->allPosts = Post::all();

        foreach ($messages as $m) {
            $this->minificationArray = $this->getMinificationArray($m->content);

            // проверяем на уникальность
            $res = $this->isSimilar($m);
            if ($res) {
                $m->status = $res;
                $m->save();
                continue;
            }

            // проверяем на схожесть с вакансией
            $res = $this->isVacancy($m);

            if (!$res) {
                $m->status = 2; // не вакансия
                $m->save();
                continue;
            }

            // обращаемся к GPT, чтобы получить id категории
            $res = $this->askGpt($m);
            if (!$res) {
                // GPT не ответил, работаем в ручном режиме

                // проверяем на соответствие категории
                // $cat = Category::find(1);
                $cat = $this->checkCategory($m);
                if (!$cat) {
                    $m->status = 3; // нет категории
                    $m->save();
                    continue;
                }
            } else {
                switch ($res) {
                    case 0: // не вакансия
                        $m->status = 2;
                        $m->save();
                        continue(2);

                    case 9: // не дизайн
                        $m->status = 9;
                        $m->save();
                        continue(2);

                    case 1: // категория
                    case 2: // категория
                    case 3: // категория
                    case 4: // категория
                    case 5: // категория
                        $cat = Category::find($res);
                        if (!$cat) {
                            $m->status = 3; // нет категории
                            $m->save();
                            continue(2);
                        }
                        break;
                    
                    default:
                        // хз, что ответил GPT
                        $m->status = 300; // непонятный ответ GPT
                        $m->save();
                        continue(2);
                }
            }
            

            // парсим текст в каком-то виде
            $content = $this->prepareContent($m);

            if (!$content || trim($m->content) == '') {
                $m->status = 4;
                $m->save();
                continue;
            }

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

            $p->content = str_replace('[id]', $p->id, $p->content);
            $p->save();

            $m->status = 0;
            $m->save();
        }
    }

    private function askGpt(Message $message)
    {
        $prompt = Option::where('name', 'gpt_prompt')->first();

        if (!$prompt) {
            return false;
        }

        $text = $prompt->value;

        $content = str_replace('[content]', $message->content, $text);

        if (!$content || trim($content) == '') {
            return false;
        }

        // var_dump($content);
        // die();


        $response = OpenAi::chat()->create(
            [
                [
                    'role' => 'system', 
                    'content' => 'You are a large language model. Carefully heed the user\'s instructions.',
                ],
                // [ 
                //     'role' => 'user', 
                //     'content' => 'Напиши буквы русского алфавита от а до ш', 
                // ],
                [ 
                    'role' => 'user', 
                    'content' => $content, 
                ],
            ],
            'openai/gpt-4o-mini'
        );

        file_put_contents(
            storage_path('logs/parse_gpt.log'), 
            $content . PHP_EOL . '=>' . var_export($response, true) . PHP_EOL . '-------------' . PHP_EOL,
            FILE_APPEND
        );

        if (!$response 
            || !isset($response['choices'])
            || !isset($response['choices'][0])
            || !isset($response['choices'][0]['message'])
            || !isset($response['choices'][0]['message']['content'])
        ) {
            return false;
        }

        return (int) $response['choices'][0]['message']['content'];
    }

    private function prepareContent(Message $message)
    {
        $template = Option::where('name', 'post_template')->first();
        if (!$template || trim($template->value) == '') {
            return false;
        }

        $content = $message->content;

        $channel = Channel::where('id', $message->channel_id)->first();

        if (!$channel) {
            return false;
        }

        // вырезать очевидные подписи
        


        /* <strong>Заказ № [id]</strong>

        [content]

        <em><a href="[link]">Связаться с заказчиком</a></em>
        */

        $ph = [
            'content' => $content,
            'link' => $channel->link . '/' . $message->channel_message_id,
        ];

        return $text = str_replace([
            '[content]',
            '[link]',
        ], [
            $content,
            $channel->link . '/' . $message->channel_message_id,
        ], $template->value);
    }

    private function checkCategory(Message $m)
    {
        // $probablyCatPc = 10;
        $minAr = $this->minificationArray;
        $count = count($minAr);

        if ($count < 1) {
            return false;
        }

        $outCat = 0;
        $max = 0;

        $cats = Category::where('status', 1)->get();
        foreach ($cats as $c) {
            $catVerifAr = $this->getMinificationArray($c->keywords);
            $similarCounter = 0;

            foreach ($catVerifAr as $verifWord) {
                foreach ($minAr as $myWord) {
                    if ($verifWord == $myWord) {
                        $similarCounter++;
                        break;
                    }
                }
            }

            $res = $similarCounter * 100 / $count;
            // echo $c->title . ': ' . $res . PHP_EOL;
            // if ($res > $probablyCatPc) {
            //     return $c;
            // }
            // $r[$c->id] = $res;
            if ($max < $res) {
                $max = $res;
                $outCat = $c;
            }
        }

        if (is_object($outCat)) {
            return $outCat;
        }
        return false;
    }

    private function isSimilar(Message $message)
    {
        $notUniquePc = 90;

        // $minAr = $this->getMinificationArray($message->content);
        $minAr = $this->minificationArray;
        $count = count($minAr);
        if ($count < 1) {
            return false;
        }

        // foreach ($this->allMessages as $m) {
        foreach ($this->allPosts as $m) {
            $verifAr = $this->getMinificationArray($m->content);
            $similarCounter = 0;

            foreach ($verifAr as $verifWord) {
                foreach ($minAr as $myWord) {
                    if ($verifWord == $myWord) {
                        $similarCounter++;
                        break;
                    }
                }
            }

            $res = $similarCounter * 100 / $count;
            if ($res > $notUniquePc) {
                // return $m->id;
                return $m->original_id;
            }
        }

        return false;
    }

    private function isVacancy(Message $message)
    {
        $words = Option::where('name', 'vacancy_keywords')->first();
        if (!$words) {
            return false;
        }

        $vacancyAr = explode(',', $words->value);
        // $minAr = $this->getMinificationArray($message->content);
        $minAr = $this->minificationArray;
        foreach ($minAr as $myWord) {
            foreach ($vacancyAr as $flag) {
                if (trim($myWord) == trim($flag)) {
                    return true;
                }
            }
        }
    }

    private function getMinificationArray($text)
    {
        // Удаление экранированных спецсимволов
        $text = stripslashes($text);    
        
        // Преобразование мнемоник 
        $text = html_entity_decode($text);
        $text = htmlspecialchars_decode($text, ENT_QUOTES); 
        
        // Удаление html тегов
        $text = strip_tags($text);
        
        // Все в нижний регистр 
        $text = mb_strtolower($text);   
        
        // Удаление лишних символов
        $text = str_ireplace('ё', 'е', $text);
        $text = mb_eregi_replace("[^a-zа-яй0-9 ]", ' ', $text);
        
        // Удаление двойных пробелов
        $text = mb_ereg_replace('[ ]+', ' ', $text);
        
        // Преобразование текста в массив
        $words = explode(' ', $text);
        
        // Удаление дубликатов
        $words = array_unique($words);
     
        // Удаление предлогов и союзов
        $array = array(
            'без',  'близ',  'в',     'во',     'вместо', 'вне',   'для',    'до', 
            'за',   'и',     'из',    'изо',    'из',     'за',    'под',    'к',  
            'ко',   'кроме', 'между', 'на',     'над',    'о',     'об',     'обо',
            'от',   'ото',   'перед', 'передо', 'пред',   'предо', 'по',     'под',
            'подо', 'при',   'про',   'ради',   'с',      'со',    'сквозь', 'среди',
            'у',    'через', 'но',    'или',    'по'
        );
     
        $words = array_diff($words, $array);
     
        // Удаление пустых значений в массиве
        $words = array_diff($words, array('')); 
     
        return $words;
    }
}
