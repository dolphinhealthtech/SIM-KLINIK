<?php

namespace App\Imports;

use App\Models\htt_pemeriksaan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Htt_pemeriksaanImport implements ToModel,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new htt_pemeriksaan([
            'nama_pemeriksaan'  => $row[0],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
