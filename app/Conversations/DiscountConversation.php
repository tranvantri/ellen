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
               // foreach ($results as $child) {
               //      $message = OutgoingMessage::create('<a target="_blank" href="/khuyen-mai/'.str_slug($child->name).'/'.$child->id.'"><b>'.$child->name."</b> giảm giá <b>". $child->per_decr. '</b>% <br/> kết thúc vào ' . $child->end_date_sale .'</a>');
               //      $this->say($message);  
               // }
               $bot->reply(ButtonTemplate::create('Do you want to know more about BotMan?')
                    ->addButton(ElementButton::create('Tell me more')
                    ->type('postback')
                    ->payload('tellmemore')
                    )
                    ->addButton(ElementButton::create('Show me the docs')
                    ->url('http://botman.io/')
                    )
               );
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
