<?php

namespace App\Exports;

use App\ChatBot;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class AskExport implements FromCollection,WithHeadings,ShouldAutoSize
{ 
    use Exportable;

    public function collection()
    {
        $excel = ChatBot::all(); 
        $dl[] = array();    
        foreach($excel as $child){
            $answer_arr = json_decode($child->answer);
            $answer_str='';
            foreach($answer_arr as $value){
                $answer_str.=$value.',';
            }
            $answer_str = substr($answer_str, 0, strlen($answer_str) -1);
            
            $dl[] = array(
                '0'=>$child->id,
                '1'=>$child->ask,
                '2'=>$answer_str,
            );
        }
        // echo '<pre>';
        //     var_dump($dl);exit();
        //     echo '</pre>';
        
        return (collect($dl));
    }

    // chèn heading cho các cột
    public function headings(): array
    {
        return [
            'Mã câu hỏi',
            'Nội dung câu hỏi',
            'Câu trả lời',
        ];
    }
}
