<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Option;

class OptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $options = Option::get();

        return view('options.index', compact('options'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('options.form', [
            'o' => new Option, 
            'title' => 'Создать настройку',
            'action' => route('options.store'),
            'm' => 'post',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $o = Option::create($request->except('_token'));
        return redirect()->route('options.index')->with('status', 'Настройка добавлена!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('options.edit', ['cat' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $o = Option::findOrFail($id);
        return view('options.form', [
            'o' => $o, 
            'title' => 'Редактировать настройку',
            'action' => route('options.update', ['option' => $id]),
            'm' => 'patch',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $o = Option::findOrFail($id);
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            $o->$key = $value;
        }

        $o->save();
        return redirect()->route('options.index')->with('status', 'Настройка обновлена!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        dd('Удаление отключено до возникновения необходимости');
    }
}
