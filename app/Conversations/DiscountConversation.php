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
class DiscountConversation extends Conversation
{
     public function askResult()
     {
          $now = date("Y-m-d");
     
          $results = DB::table('promotion')
               ->where('start_date_sale','<',$now)
               ->where('end_date_sale','>',$now)
               ->where('id','<>',1)
               ->where('enable',1)
               ->get();

          if($results->count() > 0){
               $array_discount = array();
               $i = 0;
               foreach ($results as $child) { 
                    $array_discount[$i]["id"]= $child->id;
                    $array_discount[$i]["name"]= $child->name;
                    $i++;
                    
               }

               $question = Question::create('Các chương trình khuyến mãi hiện tại của Ellen:')
                    ->callbackId('select_service')
                    ->addButtons([
                         Button::create($array_discount[0]["name"])->value("/khuyen-mai/".str_slug($array_discount[0]["name"])."/".$array_discount[0]["id"]),
                         Button::create($array_discount[1]["name"])->value("/khuyen-mai/".str_slug($array_discount[1]["name"])."/".$array_discount[1]["id"]),
                         // Button::create($array_discount[2]["name"])->value('Beauty'),
                    ]);

               $this->ask($question, function(Answer $answer) {
                    if ($answer->isInteractiveMessageReply()) {
                         # how to redirect to Discount page ???
                         $this->bot->reply("<a target='_blank' href='https://ellen.dev".$answer->getValue()."'>Xem chi tiết</a>");
                        
                    }
               });
          }
          else{
               // không có sp trong DB
               $this->say("Hiện tại chúng tôi không có chương trình khuyến mãi nào!");
          }
     }


     public function run()
     {
          $this->askResult();          
     } 
}
