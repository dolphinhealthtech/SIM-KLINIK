<?php

namespace App\Exports;

use App\Models\inventaris_satuan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Inventaris_satuanExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return inventaris_satuan::select('nama')->get();
    }

    public function headings(): array
    {
        return ["Nama Satuan Inventaris"];
    }
}
