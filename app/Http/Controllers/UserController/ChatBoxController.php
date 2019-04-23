<?php
namespace App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart; 
use App\User;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Quotation;


// for Botman usage
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Attachments\Video;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;


// end for Botman usage

class ChatBoxController extends Controller
{   
     public function handleGetTitles($bot){
          $cateGroup = DB::select('select * from category_group');
          $bot->reply('Look what i found for you: ');
          foreach($cateGroup as $child){
               $bot->reply("<a href=".$child->id.">".$child->name."</a>");       
          }           
     }

     public function handleGetCateProductID($bot,$nameProduct){
          // lấy tên sản phẩm, tìm trong DB
          $results = DB::table('product')->where('name','LIKE',"%".$nameProduct.'%')->get();
          // có sản phẩm trong DB
          if($results->count() > 0){
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


}