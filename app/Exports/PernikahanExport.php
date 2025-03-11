<?php

namespace App\Exports;

use App\Models\pernikahan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PernikahanExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return pernikahan::select('nama')->get();
    }
    public function headings(): array
    {
        return ["Nama Bangsa"];
    }
}
