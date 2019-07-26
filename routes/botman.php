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
use App\Conversations\DiscountConversation;
use App\Conversations\SizeConversation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Quotation;

use BotMan\BotMan\BotMan;

use BotMan\BotMan\Middleware\ApiAi;

$botman = resolve('botman');

/* Nhóm kịch bản đơn giản */

/** ******************************************************** */
$botman->hears('.*', function ($bot) {
    $incomingMessageText = $bot->getMessage()->getText();
    $result = DB::table('chatbot')->where('ask','LIKE',"%". $incomingMessageText ."%")->first();
    if($result){
        $bot->typesAndWaits(1);
        $anwserResults = json_decode($result->answer);
        
        if(count($anwserResults) > 0)
        {
            foreach($anwserResults as $child) 
            {
                $bot->reply("$child");
            }

            DB::table('user_ask_bot')->insert(
                [
                    'user_ask' => $incomingMessageText,
                    'bot_reply' =>  $result->answer,
                    'service' => 'chatbot_table'
                ]
            );
            


        }
        else{
                // question without anwser in database - table [botanwser]
            $bot->reply('Hiện tại chúng tôi đang cập nhật ! ');
            DB::table('user_ask_bot')->insert(
                [
                    'user_ask' => $incomingMessageText,
                    'bot_reply' =>  null,
                    'service' => 'none',
                    'intent_dialog_flow'=>null
                ]
            );
            
        }
    }
    
});


// hỏi thông tin người dùng, dùng cho việc quảng cáo marketing
$botman->hears('chat', function($bot) {
    $bot->startConversation(new OnboardingConversation);
});

//******************************************** */
$botman->hears("xóa|delete me", function ($bot) {
    // Delete all stored information. 
    $bot->userStorage()->delete();
    $bot->reply('Xóa thông tin của bạn hoàn tất !');
});


$botman->hears("who am i|thông tin của tôi", function ($bot) {
    if(Auth::id()){
        $result = DB::table('users')->where('id',Auth::id())->first();
        $message = '--------<br>';
        $message .= 'Tên của bạn : ' . $result->name . '<br>';
        $message .= 'Email: '.$result->email. '<br>';
        $message .= 'Số điện thoại: '.$result->phone. '<br>';
        $message .= 'Địa chỉ nhà: '.$result->address. '<br>';
        $bot->reply('Thông tin của bạn là:. <br>' . $message);
        $bot->startConversation(new CheckInforConversation);
    }
    else{
        $user = $bot->userStorage()->all();
        if ($user) {
            $message = '-------- <br>';
            $message .= 'Tên của bạn : ' . $bot->userStorage()->get('name') . '<br>';
            $message .= 'Email: '.$bot->userStorage()->get('email'). '<br>';
            $message .= 'Năm nay bạn: '.$bot->userStorage()->get('age').' tuổi.' . '<br>';
            $message .= 'Địa chỉ nhà: '.$bot->userStorage()->get('address'). '<br>';
            $bot->reply('Thông tin của bạn là:. <br>' . $message);
            $bot->startConversation(new CheckInforConversation);
        } else {
            $bot->reply('Tôi chưa có thông tin về bạn.');
        }
    }
});
/** -     -------     ------- Kết thúc nhóm Kịch bản đơn giản */


/**  *-----------Nhóm kịch bản lấy dữ liệu từ DB ***************** */
// xem danh sách nhóm sản phẩm
$botman->hears('show cate group|xem danh mục sản phẩm|xem danh muc san pham', 'App\Http\Controllers\UserController\ChatBoxController@handleGetTitles');

$botman->hears('bill|kiểm tra bill|tình trạng đơn hàng|tình trạng bill|tình trạng hóa đơn','App\Http\Controllers\UserController\ChatBoxController@handleGetBillID');

/** danh sách trang hàng khuyến mãi */
$botman->hears('discount|khuyến mãi|coupon|km|khuyến mại',function($bot) {
    $bot->startConversation(new DiscountConversation);

});

/** Size conversation */
$botman->hears('tư vấn size',function($bot) {
    $bot->startConversation(new SizeConversation);
});

/**
 * Lấy thông tin từ bảng chatbot và trả lời theo KEY-VALUE
 */
// $botman->hears('ask|hỏi','App\Http\Controllers\UserController\ChatBoxController@handleFromDB');

/**
 * Stop conversation
 */
$botman->hears('stop', function($bot) {
	$bot->reply('Dừng trò chuyện');
})->stopsConversation();

/***----------- Kết thúc Nhóm kịch bản lấy dữ liệu từ DB ***************** */

//------------ Bộ câu hỏi ngoài xử lý   ------------------------------------
$botman->fallback(function($bot){
    $bot->reply("Xin lỗi tôi chưa hiểu!Bạn vui lòng chờ trong lúc admin liên hệ lại với bạn nhé. Xin cảm ơn!");
    DB::table('user_ask_bot')->insert(
        [
            'user_ask' => $bot->getMessage()->getText(),
            'bot_reply' =>  null,
            'service' => 'none',
            'intent_dialog_flow'=>null
        ]
    );
});

$botman->exception(Exception::class, function($exception, $bot) {
    $bot->reply('Opps, hỏng rồi! Tôi đang đi vắng, sẽ liên hệ ngay lại với bạn!');
    DB::table('user_ask_bot')->insert(
        [
            'user_ask' => $bot->getMessage()->getText(),
            'bot_reply' =>  null,
            'service' => 'none',
            'intent_dialog_flow'=>null
        ]
    );
});


$dialogflow = ApiAi::create(env('DIALOGFLOW_TOKEN'))->listenForAction();
$botman->middleware->received($dialogflow);
$botman->hears('ellen(.*)', function (BotMan $bot) {
    $extras = $bot->getMessage()->getExtras();
    $apiReply = $extras['apiReply'];
    $apiAction = $extras['apiAction'];
    $apiIntent = $extras['apiIntent'];

    if($apiIntent == 'ellen.hoigia')
    {
        $apiReply = $apiReply . '000';
        $resultDB = DB::select($apiReply);
        if($resultDB){
            foreach($resultDB as $child){
                $bot->reply("Đây nè bạn yêu <a target='_blank' href='https://ellen.dev/chi-tiet-san-pham/" . str_slug($child->name) . "/" . $child->id ."'>".$child->name."</a>");
            }
        }
        else{
            $bot->reply('Sản phẩm không thỏa điều kiện.');
        }

        


    }
    else{
        $bot->reply($apiReply);
        $apiUserAsk = $bot->getMessage()->getText();
        DB::table('user_ask_bot')->insert(
            [
                'user_ask' => $apiUserAsk,
                'bot_reply' => $apiReply,
                'intent_dialog_flow' =>$apiIntent,
                'service' => 'dialog_flow'
            ]
        );
    }



     
})->middleware($dialogflow);

$botman->hears('input.unknown', function (BotMan $bot) {
    $extras = $bot->getMessage()->getExtras();
    $apiReply = $extras['apiReply'];
    $apiAction = $extras['apiAction'];
    $apiIntent = $extras['apiIntent'];
    $bot->reply($apiReply);
    DB::table('user_ask_bot')->insert(
        [
            'user_ask' => $bot->getMessage()->getText(),
            'bot_reply' => null,
            'intent_dialog_flow' =>$apiIntent,
            'service' => 'none'
        ]
    );
     
})->middleware($dialogflow);
