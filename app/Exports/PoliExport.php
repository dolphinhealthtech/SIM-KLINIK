<?php

namespace App\Exports;

use App\Models\poli;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PoliExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return poli::select('nama','kode','jenis')->get();
    }

    public function headings(): array
    {
        return ["Nama Poli" ,"Kode Poli", "Jenis Poli"];
    }
}
