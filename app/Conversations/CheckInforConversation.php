<?php

namespace App\Conversations;
use App\Http\Controllers\BotManController;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\BotMan;
use Auth;
use DB;
class CheckInforConversation extends Conversation
{
   
     public function askResult()
     {
          $question = Question::create("Xác nhận?")
               ->fallback('Hỏng rồi')
               ->callbackId('ask_reason')
               ->addButtons([
                    Button::create('Đúng')->value('yes'),
                    Button::create('Sai')->value('no'),
               ]);
               return $this->ask($question, function (Answer $answer) {
                    if ($answer->isInteractiveMessageReply()) {
                         if ($answer->getValue() === 'yes') {
                              $this->say('Cảm ơn!');
                    } 
                    else {
                         $this->say('Thông tin nào sai thế?!');
                    }
               }
          });
     }
     /**
      * User đã đăng nhập, thông tin được lấy ra từ DB
      */
     public function askUserChangeInformation(){
          $question = Question::create("Xác nhận?")
               ->fallback('Unable to ask question')
               ->callbackId('ask_reason')
               ->addButtons([
                    Button::create('Đúng')->value('yes'),
                    Button::create('Sai')->value('no'),
               ]);
               return $this->ask($question, function (Answer $answer) {
                    if ($answer->isInteractiveMessageReply()) {
                         if ($answer->getValue() === 'yes') {
                              $this->say('Cảm ơn!');
                    } 
                    else {
                         $this->askForChange();
                    }
               }
          });
     }

     /**
      * Lấy thông tin khách hàng muốn sửa
          *Đẩy thông tin sửa vào DB
      */
     public function askForChange(){
          $question = Question::create("Sửa lại thông tin của bạn:")
               ->fallback('Hỏng rồi')
               ->callbackId('ask_reason')
               ->addButtons([
                    Button::create('Tên')->value('name'),
                    Button::create('Email')->value('email'),
                    Button::create('Số điện thoại')->value('phone'),
                    Button::create('Địa chỉ')->value('address'),
               ]);
               return $this->ask($question, function (Answer $answer) {
                    
                    if ($answer->isInteractiveMessageReply()) 
                    {
                         
                         switch($answer->getValue())
                         {
                              case 'name':
                                   $field = 'name';
                                   $this->ask('Tên của bạn là:', function(Answer $ans) {
                                        $valueField = $ans->getText();
                                       $this->askChangeResult("name",$valueField);
                                   });
                                   break;
                              case 'email':
                                   $field = 'email';
                                   $this->ask('Email của bạn là:', function(Answer $ans) {
                                        $valueField = $ans->getText(); 
                                        $this->askChangeResult("email",$valueField);

                                   });
                                   break;
                              case 'phone':
                                   $field = 'phone';
                                   $this->ask('Số điện thoại của bạn là:', function(Answer $ans) {
                                        $valueField = $ans->getText(); 
                                        $this->askChangeResult("phone",$valueField);

                                   });
                                   break;
                              case 'address':
                                   $field = 'address';
                                   $this->ask('Địa chỉ của bạn là:', function(Answer $ans) {
                                        $valueField = $ans->getText(); 
                                        $this->askChangeResult("address",$valueField);

                                   });
                                   break;
                              default:
                                   $this->say('Default?');
                                   $this->stopsConversation();
                                   break;
                         }
                         

                    }
                    
          });
          
     }

     public function askChangeResult($field, $value){
          $value = strip_tags($value);
          DB::table('users')
            ->where('id', Auth::id())
            ->update([$field => $value]);
            $result = DB::table('users')->where('id',Auth::id())->first();
            $message = '--------<br>';
            $message .= 'Tên của bạn : ' . $result->name . '<br>';
            $message .= 'Email: '.$result->email. '<br>';
            $message .= 'Số điện thoại: '.$result->phone. '<br>';
            $message .= 'Địa chỉ nhà: '.$result->address. '<br>';
            
            $this->say('Thông tin của bạn là:. <br>' . $message);
            $this->run();
     }


     public function run()
     {
          // This will be called immediately
          if(Auth::id()){
               $this->askUserChangeInformation();
          }
          else{
               $this->askResult();
          }
          
     } 
}
