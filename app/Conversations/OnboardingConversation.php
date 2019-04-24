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
          $this->ask('Hello! What is your firstname?', function(Answer $answer) {
               // Save result
               $this->firstname = $answer->getText();

               $this->say('Nice to meet you '.$this->firstname);
               $name = $this->firstname;
               $this->askAge();
          });
     }

     public function askAge()
     {
          $this->ask('How old are you?', function(Answer $answer) {
               // Save result
               $this->age = $answer->getText();
               $this->say('Got your age: '.$this->age);
               $this->askAddress();
          });
     }

     public function askAddress()
     {
          $this->ask('And your address?', function(Answer $answer) {
               // Save result
               $this->address = $answer->getText();

               $this->say('Now i see. Your address is: '.$this->address);
               
               $this->askEmail();
          });
     }

     public function askEmail()
     {
          $this->ask('One more thing - what is your email?', function(Answer $answer) {
               // Save result
               $this->email = $answer->getText();

               $this->say('Great - that is all we need, '.$this->firstname);

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
