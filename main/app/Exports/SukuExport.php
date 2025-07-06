<?php

namespace App\Exports;

use App\Models\suku;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SukuExport implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return suku::select('nama')->get();
    }
    public function headings(): array
    {
        return ["Nama Suku"];
    }

}
