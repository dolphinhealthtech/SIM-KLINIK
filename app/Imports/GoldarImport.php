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
        return Goldar::updateOrCreate(
            ['nama' => $row[0]], // Kolom yang digunakan untuk mencari record
            ['resus' => strtolower(trim($row[1])) === 'null' ? null : $row[1]]
        );

    }

    public function startRow(): int
    {
        return 2;
    }
}
