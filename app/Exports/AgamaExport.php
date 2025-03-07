<?php

namespace App\Exports;

use App\Models\agama;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgamaExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return agama::select('nama')->get();
    }
    public function headings(): array
    {
        return ["Nama Bahasa"];
    }
}
