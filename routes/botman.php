<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hi Hi CC!');
});
$botman->hears('Start conversation', BotManController::class.'@startConversation');
