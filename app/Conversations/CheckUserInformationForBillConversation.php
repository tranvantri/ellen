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

use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Http\Request;   
class CheckUserInformationForBillConversation extends Conversation
{
     public $email;
     public $billId;

     public function askEmail()
     {
          if(Auth::check()){
               /* If the user had log in, dont need to ask user's email */
               $this->askBillID();
          }
          else{
               /** User has not log in. ask for email to check security */
               $this->ask('what is your email?', function(Answer $answer) {    

                    $this->email = $answer->getText();  
                    $result = DB::table('bill')->where('email',$this->email)->first();
                    if($result){
                         $this->say("Great - $this->email");
                         $this->askBillID();
                    }
                    else{
                         $this->say("Please check your Email and try again!");
                         $this->askEmail();
                    }
               });               
          }
          
     }

     public function askBillID()
     {
          if(Auth::check()){
               /** User logged in, get the Email from database */
               $this->ask('What is your Bill ID ?', function(Answer $answer) 
               {      
                    $billId = $answer->getText();          
                    $resultBill = DB::table('bill')->where('id',$billId)->where('email','=', Auth::user()->email )->first();
                    if($resultBill){
                         // có kết quả là bill ID
                         if($resultBill->billStatus == 0){
                              $this->say("Chưa giao hàng và thanh toán..");
                         }
                         else{
                              $this->say("Đã giao hàng và thanh toán hóa đơn!");
                         }
                    }
                    else{
                         // sai Id hoặc chưa có bill đó
                         $this->say("Please check your Bill ID again!");
                         $this->askBillID();
                    }
               });
          }
          else{
               $this->ask('What is your Bill ID ?', function(Answer $answer) 
               {      
                    $billId = $answer->getText();          
                    $resultBill = DB::table('bill')->where('id',$billId)->where('email','=',$this->email)->first();
                    if($resultBill){
                         // có kết quả là bill ID
                         if($resultBill->billStatus == 0){
                              $this->say("Chưa giao hàng và thanh toán..");
                         }
                         else{
                              $this->say("Đã giao hàng và thanh toán hóa đơn!");
                         }
                    }
                    else{
                         // sai Id hoặc chưa có bill đó
                         $this->say("Please check your Bill ID again!");
                         $this->askBillID();
                    }
               });
          }

     }

     public function run()
     {
          $this->say('Please verify your information !');
          $this->askEmail();
     } 
}







