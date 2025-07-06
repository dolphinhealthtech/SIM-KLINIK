<?php

namespace App\Imports;

use App\Models\jenis_diet;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Jenis_DietImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new jenis_diet([
            'nama'  => $row[0], // Ambil dari kolom pertama

        ]);
    }
        public function startRow(): int
    {
        return 2;
    }
}
