<?php

namespace App\Exports;

use App\Models\radiologi_pemeriksaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Radiologi_pemeriksaanExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return radiologi_pemeriksaan::select('nama')->get();
    }

    public function headings(): array
    {
        return ["Nama Pemeriksaan Radiologi"];
    }

}
