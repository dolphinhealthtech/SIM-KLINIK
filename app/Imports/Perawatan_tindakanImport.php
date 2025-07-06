<?php

namespace App\Imports;

use App\Models\perawatan_tindakan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Perawatan_tindakanImport implements ToModel,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new perawatan_tindakan([
            'kode'  => $row[0],
            'nama'  => $row[1],
            'perawatan_kategori_id'  => $row[2],
            'tarif_dokter'  => $row[3],
            'tarif_perawat'  => $row[4],
            'tarif_total'  => $row[5]
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
