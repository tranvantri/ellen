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
// this is for Excel file
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class ChatFromDBConversation extends Conversation
{
     public $manAsk;
     public $botAnswer;
     public $QuestionID;
     public function askQuestion()
     {
          $this->ask('Bạn cần giúp đỡ về vấn đề gì ạ?!', function(Answer $answer) {    
               $this->manAsk = $answer->getText();  // man ask a question
               // get the question from DB
               $result = DB::table('chatbot')->where('ask','LIKE',"%". $this->manAsk ."%")->first();
               if($result){
                    $anwserResults = json_decode($result->answer);
                    
                    if(count($anwserResults) > 0)
                    {
                         foreach($anwserResults as $child) 
                         {
                              $this->say("$child");
                         }
                         $this->askQuestion();
                         
                    }
                    else{
                         // question without anwser in database - table [botanwser]
                         $this->say('Hiện tại chúng tôi đang cập nhật ! ');
                    }
               }
               else{
                    $this->say("Xin lỗi tôi chưa hiểu! Bạn vui lòng chờ trong lúc admin liên hệ lại với bạn nhé. Xin cảm ơn bạn!");
                    $this->askQuestion();
               }
          });               
          
          
     }

     public function run()
     {
          $this->say('Chào bạn !');
          $this->askQuestion();
     } 
}







