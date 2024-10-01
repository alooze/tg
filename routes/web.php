<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bot', [BotController::class, 'index'])->name('bot.index');
