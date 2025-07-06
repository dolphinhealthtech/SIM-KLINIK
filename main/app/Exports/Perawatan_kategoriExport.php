<?php

namespace App\Exports;

use App\Models\perawatan_kategori;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Perawatan_kategoriExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return perawatan_kategori::select('nama')->get();
    }

    public function headings(): array
    {
        return ["Nama Kategori Perawatan"];
    }
}
