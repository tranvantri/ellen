<?php

namespace App\Exports;

use App\Ask;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AskExport implements FromQuery,WithHeadings,ShouldAutoSize
{ 
    use Exportable;

    public function query()
    {
        return Ask::query()->select('id','content');
    }

    // chèn heading cho các cột
    public function headings(): array
    {
        return [
            'Mã câu hỏi',
            'Nội dung câu hỏi',
        ];
    }
}
