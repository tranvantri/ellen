<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

/* Nhóm kịch bản đơn giản */
$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello there!');
});

$botman->hears('Call me {name}', function ($bot,$name) {
    $bot->reply('Hello '.$name);
});

$botman->hears('i am ([0-9]+)', function ($bot,$age) {
    $bot->reply('Got that, you are '.$age.' years old.');
    $bot->userStorage()->save([
        'age'=>$age
    ]);
});
$botman->hears('how old am i', function ($bot) {
    $age = $bot->userStorage()->get('age');
    $bot->reply('You are '.$age.' years old.');
});

$botman->hears('my name is ([a-z]+)', function ($bot,$name) {
    $bot->reply('Xin chào '.$name.' nha!');
    $bot->userStorage()->save([
        'name'=>$name
    ]);
});
$botman->hears('what is my name', function ($bot) {
    $name = $bot->userStorage()->get('name');
    $bot->reply('Bạn là '.$name.' nè.');
});

$botman->hears('Hey ([a-z]+)', function ($bot,$alpha) {
    $bot->reply('You typed: '.$alpha);
});

$botman->hears('I want ([0-9]+) portions of (Cheese|Cake)', function ($bot, $amount, $dish) {
    $bot->reply('You will get '.$amount.' portions of '.$dish.' served shortly.');

});

$botman->hears('my email is ([a-z0-9]+)', function ($bot,$email) {
    $bot->reply('Oke. Your email is: '.$email);
    $bot->userStorage()->save([
        'email'=>$email
    ]);
});

/** -     -------     ------- Kết thúc nhóm Kịch bản đơn giản */

/**  *-----------Nhóm kịch bản lấy dữ liệu từ DB ***************** */
// xem danh sách nhóm sản phẩm
$botman->hears('show cate group', 'App\Http\Controllers\UserController\ChatBoxController@handleGetTitles');

// tìm sản phẩm theo tên
$botman->hears('show me {nameProduct}', 'App\Http\Controllers\UserController\ChatBoxController@handleGetCateProductID');


/***----------- Kết thúc Nhóm kịch bản lấy dữ liệu từ DB ***************** */

//------------ Bộ câu hỏi ngoài xử lý   ------------------------------------
$botman->fallback(function($bot){
    $bot->reply("Sorry i don't know what are you talking about!");
});
$botman->hears('Start conversation', BotManController::class.'@startConversation');
