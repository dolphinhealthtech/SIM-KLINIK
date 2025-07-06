<?php

namespace App\Imports;

use App\Models\gudang_supplier_industri;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Gudang_supplier_industriImport implements ToModel,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new gudang_supplier_industri([
            'kode'  => $row[0],
            'nama'  => $row[1],
            'nama_pic'  => $row[2],
            'telepon_pic'  => $row[3]
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
