<?php

namespace App\Exports;

use App\Models\kelamin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KelaminExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return kelamin::select('nama', 'Kode')->get();
    }
    public function headings(): array
    {
        return ["Jenis Kelamin", "kode"];
    }
}
