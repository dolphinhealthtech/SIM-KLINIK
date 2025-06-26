<?php

namespace App\Imports;

use App\Models\goldar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GoldarImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new goldar([
            'nama'  => $row[0], // Ambil dari kolom pertama
            'resus' => $row[1], // Ambil dari kolom kedua
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
