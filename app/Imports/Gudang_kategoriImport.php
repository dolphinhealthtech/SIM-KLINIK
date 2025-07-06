<?php

namespace App\Imports;

use App\Models\gudang_kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Gudang_kategoriImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new gudang_kategori([
            'nama'  => $row[0]
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
