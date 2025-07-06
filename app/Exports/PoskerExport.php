<?php

namespace App\Exports;

use App\Models\posker;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PoskerExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return posker::select('nama')->get();
    }

    public function headings(): array
    {
        return ["Nama posisi Kerja"];
    }
}
