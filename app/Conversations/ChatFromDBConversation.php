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


class ChatFromDBConversation extends Conversation
{
     public $manAsk;
     public $botAnswer;
     public $QuestionID;
     public function askQuestion()
     {
          $this->ask('What can i do for you ?!', function(Answer $answer) {    
               $this->manAsk = $answer->getText();  // man ask a question
               // get the question from DB
               $result = DB::table('ask')->where('content','LIKE',"%". $this->manAsk ."%")->first();
               if($result){
                    $anwserResults = DB::table('botanwser')
                         ->where('idAsk','=',$result->id)
                         ->get();
                    
                    if($anwserResults->count() > 0)
                    {
                         foreach($anwserResults as $child) 
                         {
                              $this->say("$child->content");
                         }
                         
                    }
                    else{
                         $this->say('Gonna mail you when i got more information');
                    }
               }
               else{
                    $this->say("Sorry i don't get what you say !!");
                    $this->askQuestion();
               }
          });               
          
          
     }

     public function run()
     {
          $this->say('Hi there !');
          $this->askQuestion();
     } 
}







