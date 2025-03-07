<?php

namespace App\Imports;

use App\Models\kelamin;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KelaminImport implements ToModel,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new kelamin([
            'nama'  => $row[0], // Ambil dari kolom pertama
            'kode' => $row[1], // Ambil dari kolom kedua
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
