<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\ChannelsController;
use App\Http\Controllers\TgUserController;


Route::get('/', [AdminController::class, 'index'])->name('start');
Route::post('send', [AdminController::class, 'sendAll'])->name('send');

Route::resource('cats', CategoryController::class);
Route::resource('channels', ChannelsController::class);
Route::resource('options', OptionsController::class);

Route::get('users', [TgUserController::class, 'index'])->name('users.index');
Route::get('users/{user}/edit', [TgUserController::class, 'edit'])->name('users.edit');
Route::patch('users/{user}/update', [TgUserController::class, 'update'])->name('users.update');

// Route::post('/webhook', function() {
//     // return response(content: 'OK', status: 200);
//     // https://api.telegram.org/bot6389811565:AAGeYGy_kAdQy-otfxBI75NR494ZSiJwRsw/setWebhook?url=https://ba89-38-180-33-70.ngrok-free.app/webhook
// https://api.telegram.org/bot6389811565:AAGeYGy_kAdQy-otfxBI75NR494ZSiJwRsw/setWebhook?url=https://tg.alooze.beget.tech/webhook
// });


Route::post('/webhook', WebhookController::class);

// Route::get('/bot', [BotController::class, 'index'])->name('bot.index');

Route::any('/read', [BotController::class, 'read'])->name('reader.test');

Route::any('/ai', [BotController::class, 'ai'])->name('ai.test');
