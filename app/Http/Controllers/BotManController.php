<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;
use App\Conversations\OnboardingConversation;
use App\Conversations\CheckInforConversation;
use App\Conversations\CheckBillConversation;
use App\Conversations\CheckUserInformationForBillConversation;
use App\Conversations\ChatFromDBConversation;
use App\Conversations\DiscountConversation;
use App\Conversations\SizeConversation;

class BotManController extends Controller
{

    public function handle()
    {
        $botman = app('botman');
        $botman->listen();
    }

    public function tinker()
    {
        return view('tinker');
    }

    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
        $bot->startConversation(new OnboardingConversation());
        $bot->startConversation(new CheckInforConversation());
        $bot->startConversation(new CheckBillConversation());
        $bot->startConversation(new CheckUserInformationForBillConversation());
        $bot->startConversation(new ChatFromDBConversation());
        $bot->startConversation(new DiscountConversation());
        $bot->startConversation(new SizeConversation());
        
    }
}
