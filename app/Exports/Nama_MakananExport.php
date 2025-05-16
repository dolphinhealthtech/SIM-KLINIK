<?php

namespace App\Exports;

use App\Models\nama_makanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Nama_MakananExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return nama_makanan::select('nama')->get();
    }
    public function headings(): array
    {
        return ["Nama Makanan"];
    }
}

