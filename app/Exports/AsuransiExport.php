<?php

namespace App\Exports;

use App\Models\asuransi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AsuransiExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return asuransi::select('nama', 'kode')->get();
    }

    public function headings(): array
    {
        return ["Nama Asuransi", "Kode Asuransi"];
    }
}
