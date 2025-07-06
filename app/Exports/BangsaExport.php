<?php

namespace App\Exports;

use App\Models\bangsa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BangsaExport implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return bangsa::select('nama')->get();
    }
    public function headings(): array
    {
        return ["Nama Bangsa"];
    }
}
