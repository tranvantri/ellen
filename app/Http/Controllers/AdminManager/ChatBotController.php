<?php
namespace App\Http\Controllers\AdminManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Imports\AskImport;
use App\Ask;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Facades\Excel;

class ChatBotController extends Controller
{   
   
    public function getView()
    {
        return view('admin.chatbot.view');
    }
    
    public function import(Request $request)
    {
       $asks =  Excel::toCollection(new AskImport(),$request->file('import_file'));

       $asks[0]->shift();
       
       foreach($asks[0] as $child){
            $contentAsks = Ask::updateOrCreate(
                ['id' => $child[0]],
                ['content' => $child[1]]
            );
       }
        return redirect()->route('view_chatbox_admin');
    }
    
    
}
