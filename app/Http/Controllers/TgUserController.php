<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TgUser;

class TgUserController extends Controller
{
    public function index()
    {
        $users = TgUser::get();

        return view('users.index', compact('users'));
    }

    public function edit(string $id)
    {
        $u = TgUser::findOrFail($id);
        return view('users.form', [
            'u' => $u, 
            'title' => 'Редактировать подписчика',
            'action' => route('users.update', ['user' => $id]),
            'm' => 'patch',
        ]);
    }

    public function update(Request $request, string $id)
    {
        $u = TgUser::findOrFail($id);
        if ($request->has('status') && $request->status == 1) {
            $u->status = 1;
        } else {
            $u->status = 0;
        }

        $u->save();
        
        return redirect()->route('users.index')->with('status', 'Данные обновлены!');
    }
}
