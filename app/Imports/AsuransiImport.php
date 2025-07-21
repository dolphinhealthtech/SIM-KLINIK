<?php

namespace App\Imports;

use App\Models\asuransi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AsuransiImport implements ToModel,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new asuransi([
            'nama'  => $row[0], // Ambil dari kolom pertama
            'kode' => $row[1], // Ambil dari kolom kedua
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
