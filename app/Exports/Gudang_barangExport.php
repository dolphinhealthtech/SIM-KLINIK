<?php

namespace App\Exports;

use App\Models\gudang_barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Gudang_barangExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return gudang_barang::select(
            'kode_barang',
            'nama_barang',
            'jenis_formularium',
            'kfa_kode',
            'nama_industri_barang',
            'jenis_obat',
            'jenis_generik',
            'satuan_kecil',
            'nilai_satuan_kecil',
            'satuan_sedang',
            'nilai_satuan_sedang',
            'satuan_besar',
            'nilai_satuan_besar',
            'tempat_penyimpanan',
            'barcode',
            'gudang_kategori',
            'bentuk_sediaan',
            'user_input_id',
            'user_input_nama',
        )->get();
    }

    public function headings(): array
    {
        return [
            "Kode Data Barang",
            "Nama Data Barang",
            "Jenis Formularium",
            "Kode KFA Data Barang",
            "Nama Industri / Pabrik Pembuat",
            "Jenis Obat",
            "Jenis Generik (Jika Obat Generik)",
            "Satuan Kecil",
            "Jumlah per Satuan Kecil",
            "Satuan Sedang",
            "Jumlah per Satuan Sedang",
            "Satuan Besar",
            "Jumlah per Satuan Besar",
            "Tempat Penyimpanan",
            "Barcode Produk",
            "Kategori Barang (Gudang)",
            "Bentuk Sediaan",
            "ID Penginput",
            "Nama Penginput",
        ];
    }
}
