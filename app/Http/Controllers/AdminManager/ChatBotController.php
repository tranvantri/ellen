<?php
namespace App\Http\Controllers\AdminManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Imports\AskImport;
use App\Ask;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\UsersExport;
use App\Exports\AskExport;


class ChatBotController extends Controller
{   
   
    public function getView()
    {
        return view('admin.chatbot.view');
    }

    public function export() 
    {
        return Excel::download(new AskExport, 'Asks.xlsx');
    }
    
    public function import(Request $request)
    {
       $asks =  Excel::toCollection(new AskImport(),$request->file('import_file'));

       // xóa dòng [Mã câu hỏi] và [Nội dung câu hỏi]
       $asks[0]->shift();
       
       /* add data to table ask
        * if the table had the content, just modify
        * otherwise create new one
        */
       foreach($asks[0] as $child){
            $contentAsks = Ask::updateOrCreate(
                ['id' => $child[0]],
                ['content' => $child[1]]
            );
       }
        return redirect()->route('view_chatbox_admin');
    }
    
    public function getViewAnwser()
    {
        return view('admin.chatbot.viewAnwser');
    }
    
    
    public function exportExcelAnwser() 
    {
        return Excel::download(new AskExport, 'Asks.xlsx');
    }
    
    public function importExcelAnwser(Request $request)
    {
       $asks =  Excel::toCollection(new AskImport(),$request->file('import_file'));

       // xóa dòng [Mã câu hỏi] và [Nội dung câu hỏi]
       $asks[0]->shift();
       
       /* add data to table ask
        * if the table had the content, just modify
        * otherwise create new one
        */
       foreach($asks[0] as $child){
            $contentAsks = Ask::updateOrCreate(
                ['id' => $child[0]],
                ['content' => $child[1]]
            );
       }
        return redirect()->route('view_chatbox_admin');
    }

}
