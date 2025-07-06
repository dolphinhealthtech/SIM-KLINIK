<?php

namespace App\Exports;

use App\Models\icd9;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Icd9Export implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return icd9::select('nama_icd9','kode_icd9')->get();
    }
    public function headings(): array
    {
        return ["Nama Nama","kode ICD"];
    }
}
