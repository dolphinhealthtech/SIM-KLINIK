<?php

namespace App\Imports;

use App\Models\laboratorium_bidang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Laboratorium_bidangImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new laboratorium_bidang([
            'nama'  => $row[0], // Ambil dari kolom pertama
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
