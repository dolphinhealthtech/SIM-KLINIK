<?php

namespace App\Imports;

use App\Models\gudang_satuan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Gudang_satuanImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new gudang_satuan([
            'nama'  => $row[0]
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
