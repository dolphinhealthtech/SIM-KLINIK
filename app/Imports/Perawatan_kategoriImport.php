<?php

namespace App\Imports;

use App\Models\perawatan_kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Perawatan_kategoriImport implements ToModel,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new perawatan_kategori([
            'nama'  => $row[0] // Ambil dari kolom pertama
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
