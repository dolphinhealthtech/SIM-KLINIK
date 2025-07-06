<?php

namespace App\Imports;

use App\Models\radiologi_jenis;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
class Radiologi_JenisImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new radiologi_jenis([
            //
            'nama'  => $row[0],
        ]);
    }
            public function startRow(): int
    {
        return 2;
    }
}
