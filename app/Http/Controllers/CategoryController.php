<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cats = Category::orderBy('position', 'asc')->get();

        return view('cats.index', compact('cats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cats.form', [
            'c' => new Category, 
            'title' => 'Создать категорию',
            'action' => route('cats.store'),
            'm' => 'post',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $c = Category::create($request->except('_token'));
        return redirect()->route('cats.index')->with('status', 'Категория добавлена!');
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
        $c = Category::findOrFail($id);
        return view('cats.form', [
            'c' => $c, 
            'title' => 'Редактировать категорию',
            'action' => route('cats.update', ['cat' => $id]),
            'm' => 'patch',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $c = Category::findOrFail($id);
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            $c->$key = $value;
        }

        if ($request->has('status') && $request->status == 1) {
            $c->status = 1;
        } else {
            $c->status = 0;
        }

        $c->save();
        return redirect()->route('cats.index')->with('status', 'Категория обновлена!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        dd('Удаление отключено до возникновения необходимости');
    }
}
