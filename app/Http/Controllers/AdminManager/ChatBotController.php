<?php
namespace App\Http\Controllers\AdminManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
class ChatBotController extends Controller
{   
   
    public function getView()
    {
        return view('admin.chatbot.view');
    }
    
    
}
