<?php

namespace App\Exports;

use App\Models\jenis_diet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Jenis_DietExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return jenis_diet::select('nama')->get();
    }
    public function headings(): array
    {
        return ["Nama Diet"];
    }
}
