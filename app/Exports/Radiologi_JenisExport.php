<?php

namespace App\Exports;

use App\Models\radiologi_jenis;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Radiologi_JenisExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function collection()
    {
        return radiologi_jenis::select('nama')->get();
    }

        public function headings(): array
    {
        return ["Radiologi Jenis"];
    }
}
