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
        $resus = trim($row[1]); // ambil data dan hilangkan spasi
        $resus = strtolower($resus); // ubah ke huruf kecil
        $resus = ($resus === 'null' || $resus === '-' || $resus === '') ? null : $row[1];

        return Goldar::updateOrCreate(
            ['nama' => $row[0]],
            ['resus' => $resus]
        );


    }

    public function startRow(): int
    {
        return 2;
    }
}
