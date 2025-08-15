<?php

namespace App\Imports;

use App\Models\bahasa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BahasaImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return bahasa::updateOrCreate([
            'nama'  => $row[0], // Ambil dari kolom pertama
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
