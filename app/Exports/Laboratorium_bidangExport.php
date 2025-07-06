<?php

namespace App\Exports;

use App\Models\laboratorium_bidang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Laboratorium_bidangExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return laboratorium_bidang::select('nama')->get();
    }
    public function headings(): array
    {
        return ["Nama Bidang Laboratorium"];
    }
}
