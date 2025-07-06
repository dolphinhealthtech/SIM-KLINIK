<?php

namespace App\Exports;

use App\Models\inventaris_data_barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Inventaris_data_barangExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return inventaris_data_barang::select(
            'kode_barang',
            'nama_barang',
            'kategori_barang',
            'satuan_barang',
            'jenis_barang',
            'masa_pakai_barang',
            'masa_pakai_waktu_barang',
            'deskripsi_barang',
            'user_input_id',
            'user_input_name',
        )->get();
    }

    public function headings(): array
    {
        return [
            "Kode Data Barang Investasi",
            "Nama Data Barang Investasi",
            "Kategori Data Barang Investasi",
            "Satuan Data Barang Investasi",
            "Jenis Data Barang",
            "Masa Pakai Barang",
            "Waktu Masa Pakai Barang",
            "Deskripsi Data Barang Investasi",
            "User Input ID",
            "User Input Name",
        ];
    }
}
