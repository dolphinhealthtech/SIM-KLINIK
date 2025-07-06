<?php

namespace App\Exports;

use App\Models\pekerjaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PekerjaanExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return pekerjaan::select('nama')->get();
    }
    public function headings(): array
    {
        return ["Nama Pekerjaan"];
    }
}
