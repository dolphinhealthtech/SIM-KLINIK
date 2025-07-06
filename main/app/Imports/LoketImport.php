<?php

namespace App\Imports;

use App\Models\loket;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class LoketImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new loket([
            'nama'  => $row[0], // Ambil dari kolom pertama
            'poli_id' => $row[1], // Ambil dari kolom kedua
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
