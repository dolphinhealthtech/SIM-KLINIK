<?php

namespace App\Exports;

use App\Models\Goldar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GoldarExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Goldar::select('nama', 'resus')->get();
    }
    public function headings(): array
    {
        return ["Nama Golongan Darah", "Rhesus"];
    }

}
