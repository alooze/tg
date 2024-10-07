<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;

class ChannelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $channels = Channel::all();

        return view('channels.index', compact('channels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('channels.add', [
            'c' => new Channel, 
            'title' => 'Добавить канал/чат',
            'action' => route('channels.store'),
            'm' => 'post',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $settings = (new \danog\MadelineProto\Settings\AppInfo)
            ->setApiId(env('TELEGRAM_API_ID'))
            ->setApiHash('TELEGRAM_API_HASH');
        $API = new \danog\MadelineProto\API(env('TELEGRAM_GRAB_SESSION'), $settings);
        $API->start();

        // echo '<pre>';
        $chat = $request->link;
        // $chat = 'https://t.me/zakaz_design';
        // $chat = 'https://t.me/+h8tJkUNM_wc5MzEy';
        $res = $API->getInfo($chat);

        // dump($res);

        if (!$res || !$res['Chat']) {
            return back()->with('alert', 'Не удалось получить данные!');
        }

        // dd($request);
        $c = Channel::create([
            'link' => $request->link,
            'channel_id' => $res['channel_id'],
            'channel_type' => $res['type'],
            'title' => $res['Chat']['title'],
            'username' => $res['Chat']['username'],
            'access_hash' => $res['Chat']['access_hash'],
            'status' => 1,
        ]);
        return redirect()->route('channels.edit', ['channel' => $c->id])->with('status', 'Данные канала получены!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $c = Category::firstOrFail($id);
        // return view('cats.form', [
        //     'c' => $c, 
        //     'title' => 'Редактировать категорию',
        //     'action' => route('cats.update'),
        // ]);
        return redirect()->route('cats.edit', ['cat' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $c = Channel::findOrFail($id);
        return view('channels.form', [
            'c' => $c, 
            'title' => 'Редактировать канал/чат',
            'action' => route('channels.update', ['channel' => $id]),
            'm' => 'patch',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $c = Channel::findOrFail($id);
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            $c->$key = $value;
        }

        if ($request->has('status') && $request->status == 1) {
            $c->status = 1;
        } else {
            $c->status = 0;
        }

        $c->save();
        return redirect()->route('channels.index')->with('status', 'Канал обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        dd('Удаление отключено до возникновения необходимости');
    }
}
