<?php

namespace App\Imports;

use App\Models\inventaris_data_barang_utama;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Inventaris_data_barangImport_utama  implements ToModel,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new inventaris_data_barang_utama([
            'kode_barang' => $row[0],
            'nama_barang' => $row[1],
            'kategori_barang' => $row[2],
            'satuan_barang' => $row[3],
            'jenis_barang' => $row[4],
            'masa_pakai_barang' => $row[5],
            'masa_pakai_waktu_barang' => $row[6],
            'deskripsi_barang' => $row[7],
            'user_input_id' => $row[8],
            'user_input_name' => $row[9],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
