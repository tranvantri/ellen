<?php

namespace App\Imports;

use App\ChatBot;
use Maatwebsite\Excel\Concerns\ToModel;


class AskImport implements ToModel
{

    public function model(array $row)
    {
        return new ChatBot([
            //
            'id'        => $row[0],
            'ask'    => $row[1],
            'answer' => $row[2],
        ]);
    }
}
