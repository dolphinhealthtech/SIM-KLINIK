<?php

namespace App\Imports;

use App\Models\pernikahan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PernikahanImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new pernikahan([
            'nama'  => $row[0], // Ambil dari kolom pertama
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
