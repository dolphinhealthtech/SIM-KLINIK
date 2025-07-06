<?php

namespace App\Imports;

use App\Models\nama_makanan; // Import your model
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Nama_MakananImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new nama_makanan([
            'nama'  => $row[0], // Ambil dari kolom pertama

        ]);
    }
        public function startRow(): int
    {
        return 2;
    }
}



