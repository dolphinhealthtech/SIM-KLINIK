<?php

namespace App\Exports;

use App\Models\gudang_satuan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Gudang_satuanExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return gudang_satuan::select('nama')->get();
    }

    public function headings(): array
    {
        return ["Nama Satuan"];
    }
}
