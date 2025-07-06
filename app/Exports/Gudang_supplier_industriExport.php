<?php

namespace App\Exports;

use App\Models\gudang_supplier_industri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Gudang_supplier_industriExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return gudang_supplier_industri::select(
            'kode',
            'nama',
            'nama_pic',
            'telepon_pic',
        )->get();
    }

    public function headings(): array
    {
        return [
            "Kode Supplier Industri",
            "Nama Supplier Industri",
            "Nama PIC Supplier",
            "No. Telepon PIC Supplier",
        ];
    }
}
