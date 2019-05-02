<?php

namespace App\Imports;

use App\Ask;
use Maatwebsite\Excel\Concerns\ToModel;

class AskImport implements ToModel
{

    public function model(array $row)
    {
        return new Ask([
            //
            'id'     => $row[0],
           'content'    => $row[1],
           'enable' => $row[2],
        ]);
    }
}
