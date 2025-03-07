<?php

namespace App\Exports;

use App\Models\pendidikan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PendidikanExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return pendidikan::select('nama', 'Kode')->get();
    }
    public function headings(): array
    {
        return ["Nama Pendidikan", "kode"];
    }
}
