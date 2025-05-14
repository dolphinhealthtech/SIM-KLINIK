<?php

namespace App\Imports;

use App\Models\icd10;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Icd10Import implements ToModel,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new icd10([
            'nama_icd10'  => $row[0], // Ambil dari kolom pertama
            'kode_icd10' => $row[1], // Ambil dari kolom kedua
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
