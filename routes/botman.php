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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Quotation;



$botman = resolve('botman');

/* Nhóm kịch bản đơn giản */

/** ******************************************************** */
$botman->hears('.*', function ($bot) {
    $incomingMessageText = $bot->getMessage()->getText();
    $result = DB::table('chatbot')->where('ask','LIKE',"%". $incomingMessageText ."%")->first();
    if($result){
        $anwserResults = json_decode($result->answer);
        
        if(count($anwserResults) > 0)
        {
            foreach($anwserResults as $child) 
            {
                $bot->reply("$child");
            }
            
                
        }
        else{
                // question without anwser in database - table [botanwser]
            $bot->reply('Hiện tại chúng tôi đang cập nhật ! ');
        }
    }
    else{
        /** ********* HANDLE RESULTS NOT EXIST IN DB??  ***************** */
        $botman->hears($incomingMessageText ."{name}", function ($bot, $name) {
            $bot->userStorage()->save([
                'name' => $name
            ]);
        
            $bot->reply('Xin chào '.$name.', bạn cần hỗ trợ gì ạ? ');
        });
        
        $botman->hears("tôi là {name}, năm nay {age} tuổi", function ($bot, $name,$age) {
            $bot->reply('Chào '.$name . ', năm nay ' .$name . ' '. $age. ' tuổi ha!');
        });
        
        
        $botman->hears('Tôi tên gì|My name is', function ($bot) {
            $name = $bot->userStorage()->get('name');
            $bot->reply('Chào '.$name.' !');
        });
        
        $botman->hears('([0-9]+) tuổi', function ($bot,$age) {
            $bot->userStorage()->save([
                'age'=>$age
            ]);
        });
        $botman->hears('how old am i|tôi bao nhiêu tuổi', function ($bot) {
            $age = $bot->userStorage()->get('age');
            if(isset($age)){
                $bot->reply('Bạn '.$age.' tuổi.');
            }
            else{
                $bot->reply("Tôi chưa có thông tin về tuổi của bạn. Bạn sinh năm nào?");
            }
        });
        
        
        $botman->hears('năm ([0-9]+)', function ($bot,$year) {
            $now = date('Y');
            $age = $now - $year;
            if($age < 100){
                $bot->userStorage()->save([
                    'age'=>$age
                ]);
                $bot->reply("Bạn $age tuổi.");
            }
            else{
                $bot->reply("Tôi chưa hiểu ý của bạn đâu!");
            }
        });
        
        $botman->hears('email là {email}', function ($bot,$email) {
            $bot->reply('Đã xác nhận email: '.$email);
            $bot->userStorage()->save([
                'email'=>$email
            ]);
        });
        
        $botman->hears('địa chỉ của tôi là {address}', function ($bot,$address) {
            $bot->reply('Đã xác nhận: '.$address);
            $bot->userStorage()->save([
                'address'=>$address
            ]);
        });
    
    }
});
/** ******************************************************** */

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

$botman->hears("who am i|thông tin của tôi|tôi là ai", function ($bot) {
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
$botman->hears('show cate group', 'App\Http\Controllers\UserController\ChatBoxController@handleGetTitles');

// tìm sản phẩm theo tên
$botman->hears('show me {nameProduct}', 'App\Http\Controllers\UserController\ChatBoxController@handleGetCateProductID');

$botman->hears('bill','App\Http\Controllers\UserController\ChatBoxController@handleGetBillID');

/** danh sách trang hàng khuyến mãi */
$botman->hears('discount|khuyến mãi|coupon','App\Http\Controllers\UserController\ChatBoxController@handleGetDiscount');
/**
 * Lấy thông tin từ bảng chatbot và trả lời theo KEY-VALUE
 */
$botman->hears('ask|hỏi','App\Http\Controllers\UserController\ChatBoxController@handleFromDB');

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
});

$botman->exception(Exception::class, function($exception, $bot) {
	$bot->reply('Opps, hỏng rồi! Tôi đang đi vắng, sẽ liên hệ ngay lại với bạn!');
});


