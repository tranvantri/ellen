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

class OnboardingConversation extends Conversation
{
     
     public $firstname;
     public $name;
     public $email;
     protected $age,$address;


     public function askFirstname()
     {
          $this->ask('Hello! Tên của bạn là gì?!', function(Answer $answer) {
               // Save result
               $this->firstname = $answer->getText();

               $this->say('Nice to meet you '.$this->firstname);
               $name = $this->firstname;
               $this->askAge();
          });
     }

     public function askAge()
     {
          $this->ask('Bạn bao nhiêu tuổi?', function(Answer $answer) {
               // Save result
               $this->age = $answer->getText();
               if($this->age <0 && $this->age > 200)
               {
                    $this->askAge();
               }
               else{
                    $this->askAddress();
               }
               
          });
     }

     public function askAddress()
     {
          $this->ask('Và địa chỉ nhà của bạn?', function(Answer $answer) {
               // Save result
               $this->address = $answer->getText();

               $this->say('OKAY, địa chỉ của bạn là : '.$this->address);
               
               $this->askEmail();
          });
     }

     public function askEmail()
     {
          $this->ask('Còn email của bạn?', function(Answer $answer) {
               // Save result
               $this->email = $answer->getText();

               $this->say('Tuyệt - Cảm ơn thông tin của bạn, '.$this->firstname);

               //$this->bot->startConversation(new FavouriteLunchConversation());
               $this->bot->userStorage()->save([
                    'email' => $this->email,
                    'name' => $this->firstname,
                    'age' =>$this->age,
                    'address' =>$this->address
                ]);
          });
     }



     public function run()
     {
          // This will be called immediately
          $this->askFirstname();
     } 
}
