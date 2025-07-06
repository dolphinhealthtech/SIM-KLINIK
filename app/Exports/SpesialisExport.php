<?php

namespace App\Exports;

use App\Models\spesialis;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SpesialisExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return spesialis::select('nama','kode')->get();
    }

    public function headings(): array
    {
        return ["Nama Spesialis" ,"Kode Spesialis"];
    }
}
