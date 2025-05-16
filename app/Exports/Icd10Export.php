<?php

namespace App\Exports;

use App\Models\icd10;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Icd10Export implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return icd10::select('nama_icd10','kode_icd10')->get();
    }
    public function headings(): array
    {
        return ["Nama Nama","kode ICD"];
    }
}
