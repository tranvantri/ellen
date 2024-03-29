<?php
namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Quotation;

// for Botman usage
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Attachments\Video;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

use App\Http\Controllers\BotManController;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\BotMan;
use App\Conversations\OnboardingConversation;
use App\Conversations\CheckUserInformationForBillConversation;
use App\Conversations\ChatFromDBConversation;


// end for Botman usage

class ChatBoxController extends Controller
{   
     /**
      * show the categories
      */
     public function handleGetTitles($bot){
          $cateGroup = DB::select('select * from category_product');
          $bot->reply('Look what i found for you: ');
          foreach($cateGroup as $child){
               $bot->reply("<a target='_blank' href="."/tat-ca-san-pham/".str_slug($child->name)."/".$child->id.">".$child->name."</a>");       
          }           
     }

     public function handleGetCateProductID($bot,$nameProduct){
          // lấy tên sản phẩm, tìm trong DB
          $results = DB::table('product')->where('name','LIKE',"%".$nameProduct.'%')->get();
          // có sản phẩm trong DB
          if($results->count() > 0){
               $bot->typesAndWaits(2);
               $bot->reply('Look what i found for you: ');
               foreach($results as $child){
                    $image = json_decode($child->otherImg);
                    $attachment = new Image($image[0]);
                    $message = OutgoingMessage::create('<a target="_blank" href="/chi-tiet-san-pham/'.str_slug($child->name).'/'.$child->id.'">'.$child->name.'</a>')->withAttachment($attachment);
                    // Reply message object
                    $bot->reply($message);      
               }   
          }
          else{
               // không có sp trong DB
               $bot->reply("Sorry we dont have these products!");
          }
     }

     public function handleGetBillID($bot){
          $bot->startConversation(new CheckUserInformationForBillConversation);
          
     }
     public function handleFromDB($bot){
          /** get question of User and then anwser with data from database */
          $bot->startConversation(new ChatFromDBConversation);
     }


}