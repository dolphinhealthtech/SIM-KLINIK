<?php

namespace App\Imports;

use App\Models\icd9;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Icd9Import implements ToModel,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new icd9([
            'nama_icd9'  => $row[0], // Ambil dari kolom pertama
            'kode_icd9' => $row[1], // Ambil dari kolom kedua
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
