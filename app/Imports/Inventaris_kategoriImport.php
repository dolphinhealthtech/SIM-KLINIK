<?php

namespace App\Imports;

use App\Models\inventaris_kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Inventaris_kategoriImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new inventaris_kategori([
            'nama'  => $row[0]
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
