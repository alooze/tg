<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotController;
use App\Http\Controllers\WebhookController;

Route::get('/', function () {
    return view('welcome');
});

// Route::post('/webhook', function() {
//     // return response(content: 'OK', status: 200);
//     // https://api.telegram.org/bot6389811565:AAGeYGy_kAdQy-otfxBI75NR494ZSiJwRsw/setWebhook?url=https://ba89-38-180-33-70.ngrok-free.app/webhook
// });

// Route::post('/webhook', [BotController::class, 'webhook'])->name('bot.webhook');
Route::post('/webhook', WebhookController::class);

Route::get('/bot', [BotController::class, 'index'])->name('bot.index');

Route::any('/read', [BotController::class, 'read'])->name('reader.test');
