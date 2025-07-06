<?php

namespace App\Exports;

use App\Models\penjamin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenjaminExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return penjamin::select('nama')->get();
    }

    public function headings(): array
    {
        return ["Nama Penjamin"];
    }
}
