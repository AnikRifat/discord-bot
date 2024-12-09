<?php

use App\Http\Controllers\DiscordBotController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('bot-info', [DiscordBotController::class, 'getBotInfo']);
Route::post('send-message', [DiscordBotController::class, 'sendMessage']);
Route::get('roles', [DiscordBotController::class, 'getRoles']);
Route::post('assign-role', [DiscordBotController::class, 'assignRole']);
Route::post('remove-role', [DiscordBotController::class, 'removeRole']);
Route::get('/guilds/channels', [DiscordBotController::class, 'getChannels']);
Route::get('/guilds/users', [DiscordBotController::class, 'getUsers']);
Route::post('/subscribed', [DiscordBotController::class, 'subscribed']);
Route::any('/check-subscription', [DiscordBotController::class, 'unSubscribed']);
