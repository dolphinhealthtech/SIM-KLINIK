<?php

namespace App\Imports;

use App\Models\gudang_barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class Gudang_barangImport implements ToModel,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new gudang_barang([
            'kode_barang' => $row[0],
            'nama_barang' => $row[1],
            'jenis_formularium' => $row[2],
            'kfa_kode' => $row[3],
            'nama_industri_barang' => $row[4],
            'satuan_kecil' => $row[7],
            'satuan_sedang' => $row[9],
            'satuan_besar' => $row[11],
            'nilai_satuan_kecil' => $row[8],
            'nilai_satuan_sedang' => $row[10],
            'nilai_satuan_besar' => $row[12],
            'tempat_penyimpanan' => $row[13],
            'barcode' => $row[14],
            'gudang_kategori' => $row[15],
            'jenis_obat' => $row[5],
            'jenis_generik' => $row[6],
            'bentuk_sediaan' => $row[16],
            'user_input_id' => $row[17],
            'user_input_nama' => $row[18],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
