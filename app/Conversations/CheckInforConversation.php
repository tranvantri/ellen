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

class CheckInforConversation extends Conversation
{
   
     public function askResult()
     {
          $question = Question::create("Correct?")
               ->fallback('Unable to ask question')
               ->callbackId('ask_reason')
               ->addButtons([
                    Button::create('Yes')->value('yes'),
                    Button::create('No')->value('no'),
               ]);
               return $this->ask($question, function (Answer $answer) {
                    if ($answer->isInteractiveMessageReply()) {
                         if ($answer->getValue() === 'yes') {
                              $this->say('Glad it is true!');
                    } 
                    else {
                         $this->say('Which one is wrong?!');
                    }
               }
          });
     }

     public function run()
     {
          // This will be called immediately
          $this->askResult();
     } 
}
