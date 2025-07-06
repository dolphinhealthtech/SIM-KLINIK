<?php

namespace App\Exports;

use App\Models\inventaris_kategori;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Inventaris_kategoriExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return inventaris_kategori::select('nama')->get();
    }

    public function headings(): array
    {
        return ["Nama Inventaris Kategori"];
    }
}
