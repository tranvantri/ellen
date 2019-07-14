<?php
namespace App\Http\Controllers\AdminManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Imports\AskImport;
use App\ChatBot;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Exports\AskExport;
use DB;

class ChatBotController extends Controller
{     
  public function getView()
  {
    return view('admin.chatbot.view');
  }
  public function thongkebot()
  {
    $db = DB::table('user_ask_bot')
      ->select('user_ask','bot_reply')
      ->where('service','chatbot_table')
      ->get();
    $dialog = DB::table('user_ask_bot')
        ->select('user_ask','bot_reply','intent_dialog_flow')
        ->where('service','dialog_flow')
        ->get();
    $none = DB::table('user_ask_bot')
        ->where('service','none')
        ->get();
    return view('admin.chatbot.thongke',compact('db','dialog','none'));
  }
  
  public function getList()
  {
    $askAndAns = ChatBot::all();
    return view('admin.chatbot.list',compact('askAndAns'));
  }

  public function getEdit($id)
  {
    $ask = ChatBot::findOrFail($id);
    return view('admin.chatbot.edit',compact('ask'));
  }

  public function postEdit(Request $request, $id)
  {
    $ask = ChatBot::findOrFail($id);
    $answer = array();
    foreach($request->answer as $value){
      if($value != ''){
        array_push($answer, $value);
      }
    }
    $answer = json_encode($answer);
    $ask->update([
      'ask'=>$request->ask,
      'answer'=>$answer
    ]);
    return redirect('admin/chatbot/edit/'.$id)->with('thongbao','Sửa thành công!');
  }

  public function getAdd()
  {
    return view('admin.chatbot.add');
  }

  public function postAdd(Request $request)
  {
    $answer = array();
    foreach($request->answer as $value){
      if($value != ''){
        array_push($answer, $value);
      }
    }
    $answer = json_encode($answer);
    $ask = ChatBot::create([
      'ask'=>$request->ask,
      'answer'=>$answer
    ]);

    
    return redirect('admin/chatbot/add')->with('thongbao','Thêm thành công!');
  }

  public function getDelete($id)
  {
        try {
            $check = ChatBot::where('id',$id)->delete();
            if(!$check)
                throw new QueryException;
        } catch (QueryException $e) {
            return redirect('admin/chatbot/list')->with('loi','Không thể xóa!');
        }
        
        return redirect('admin/chatbot/list')->with('thongbao','Xóa thành công!');
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

    // echo '<pre>';
    // var_dump($asks[0]);
    // echo '</pre>';
    // exit();
     
    /* add data to table ask
    * if the table had the content, just modify
    * otherwise create new one
    */
    foreach($asks[0] as $child){
      $answer = explode(',',$child[2]);
      
       $answer = json_encode($answer);
      // echo '<pre>';
      // var_dump($child);
      // echo '</pre>';exit();

      $contentAsks = ChatBot::updateOrCreate([
          // 'id' =>(int) $child[0],
          'ask' => $child[1],
          'answer' => $answer
      ]);
    }
    return redirect()->route('view_chatbox_admin');
  }
    
    
}
