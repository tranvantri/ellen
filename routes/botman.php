<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});


$botman->hears('i want', function ($bot) {
    $bot->reply('Give me more information!');
});

$botman->hears('i am ([0-9]+) years old', function ($bot,$age) {
    $bot->reply('Got that, you are '.$age);
});

$botman->hears('.*Bonjour.*', function ($bot) {
    $bot->reply('Nice to meet you!');
});

$botman->hears('Hey ([a-z]+)', function ($bot,$alpha) {
    $bot->reply('You typed: '.$alpha);
});

$botman->fallback(function($bot){
    $bot->reply("Please be nice to me!");
});


$botman->hears('Start conversation', BotManController::class.'@startConversation');
