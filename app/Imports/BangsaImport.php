<?php

namespace App\Imports;

use App\Models\bangsa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BangsaImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return bangsa::updateOrCreate([
            'nama'  => $row[0], // Ambil dari kolom pertama
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
