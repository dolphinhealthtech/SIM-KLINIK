<?php

namespace App\Exports;

use App\Models\bahasa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BahasaExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return bahasa::select('nama')->get();
    }
    public function headings(): array
    {
        return ["Nama Bahasa"];
    }
}
