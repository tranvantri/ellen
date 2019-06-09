<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
/* -------- for conversation with botman */
use App\Conversations\OnboardingConversation;
use App\Conversations\CheckInforConversation;
use App\Conversations\CheckBillConversation;




/**  --------- end conversation */


$botman = resolve('botman');

/* Nhóm kịch bản đơn giản */
$botman->hears('Hi|Hello|Xin chào|Chào', function ($bot) {
    $bot->reply('Chào bạn!');
});


$botman->hears('.*Bonjour.*', function ($bot) {
    $bot->reply('Catch Bonjour!');
});

$botman->hears("call me {name}", function ($bot, $name) {
    // Store information for the currently logged in user.
    // You can also pass a user-id / key as a second parameter.
    $bot->userStorage()->save([
        'name' => $name
    ]);

    $bot->reply('I will call you '.$name);
});

$botman->hears("tôi là {name}, năm nay {age} tuổi", function ($bot, $name,$age) {
    $bot->reply('Chào '.$name . ', năm nay ' .$name . ' '. $age. ' tuổi ha!');
});


$botman->hears('what is my name', function ($bot) {
    $name = $bot->userStorage()->get('name');
    $bot->reply('Yep, you are '.$name.' !');
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

$botman->hears('I want ([0-9]+) portions of (Cheese|Cake)', function ($bot, $amount, $dish) {
    $bot->reply('You will get '.$amount.' portions of '.$dish.' served shortly.');

});

$botman->hears('my email is {email}', function ($bot,$email) {
    $bot->reply('Oke. Your email is: '.$email);
    $bot->userStorage()->save([
        'email'=>$email
    ]);
});

$botman->hears('my address is {address}', function ($bot,$address) {
    $bot->reply('Oke. Your address is: '.$address);
    $bot->userStorage()->save([
        'address'=>$address
    ]);
});


$botman->hears('chat', function($bot) {
    $bot->startConversation(new OnboardingConversation);
});

//******************************************** */
$botman->hears("forget me", function ($bot) {
    // Delete all stored information. 
    $bot->userStorage()->delete();
    $bot->reply('Clear your information !');
});


$botman->hears("who am i", function ($bot) {
    $user = $bot->userStorage()->all();
    if ($user) {
        $message = '-------------------------------------- <br>';
        $message .= 'You are : ' . $bot->userStorage()->get('name') . '<br>';
        $message .= 'Your email is: '.$bot->userStorage()->get('email'). '<br>';
        $message .= 'You are: '.$bot->userStorage()->get('age').' years old.' . '<br>';
        $message .= 'Your address is: '.$bot->userStorage()->get('address'). '<br>';
        
        $message .= '---------------------------------------';
        $bot->reply('Here is your information. <br>' . $message);

        $bot->startConversation(new CheckInforConversation);
        

    } else {
        $bot->reply('I do not know you yet.');
    }
});

//******************************************** */


/** -     -------     ------- Kết thúc nhóm Kịch bản đơn giản */



/**  *-----------Nhóm kịch bản lấy dữ liệu từ DB ***************** */
// xem danh sách nhóm sản phẩm
$botman->hears('show cate group', 'App\Http\Controllers\UserController\ChatBoxController@handleGetTitles');

// tìm sản phẩm theo tên
$botman->hears('show me {nameProduct}', 'App\Http\Controllers\UserController\ChatBoxController@handleGetCateProductID');

$botman->hears('bill','App\Http\Controllers\UserController\ChatBoxController@handleGetBillID');

$botman->hears('ask','App\Http\Controllers\UserController\ChatBoxController@handleFromDB');

/**
 * Stop conversation
 */
$botman->hears('stop', function($bot) {
	$bot->reply('Conversation stopped');
})->stopsConversation();

/**
 * Pause conversation
 */


 

/***----------- Kết thúc Nhóm kịch bản lấy dữ liệu từ DB ***************** */

//------------ Bộ câu hỏi ngoài xử lý   ------------------------------------
$botman->fallback(function($bot){
    $bot->reply("Sorry i don't know what are you talking about!");
});

$botman->exception(Exception::class, function($exception, $bot) {
	$bot->reply('Sorry, something went wrong');
});


