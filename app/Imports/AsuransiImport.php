<?php

namespace App\Imports;

use App\Models\asuransi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AsuransiImport implements ToModel,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new asuransi([
            'nama'              => $row[0],
            'kode'              => $row[1],
            'jenis_asuransi'    => $row[2],
            'verif_pasien'      => $row[3],
            'filter_obat'       => $row[4],
            'tanggal_mulai'     => $row[5],
            'tanggal_akhir'     => $row[6],
            'alamat_asuransi'   => $row[7],
            'no_telp_asuransi'  => $row[8],
            'faksimil'          => $row[9],
            'pic'               => $row[10],
            'no_telp_pic'       => $row[11],
            'jabatan_pic'       => $row[12],
            'bank'              => $row[13],
            'no_rekening'       => $row[14],
        ]);
    }

    public function startRow(): int
    {
        return 2; // Mulai dari baris kedua (abaikan header Excel)
    }
}
