<?php

namespace App\Exports;

use App\Models\gudang_kategori;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Gudang_kategoriExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return gudang_kategori::select('nama')->get();
    }

    public function headings(): array
    {
        return ["Nama Kategori"];
    }
}
