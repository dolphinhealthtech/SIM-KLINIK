<?php

namespace App\Imports;

use App\Models\subspesialis;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SubspesialisImport implements ToModel,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new subspesialis([
            'nama'  => $row[0], // Ambil dari kolom pertama
            'kode'  => $row[1], // Ambil dari kolom pertama
            'kode_rujukan' => $row[2], // Ambil dari kolom pertama
            'kode_spesialis' => $row[2], // Ambil dari kolom pertama
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
