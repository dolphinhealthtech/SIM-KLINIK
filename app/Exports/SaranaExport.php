<?php

namespace App\Exports;

use App\Models\sarana;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SaranaExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return sarana::select('nama','kode')->get();
    }

    public function headings(): array
    {
        return ["Nama Sarana" ,"Kode Sarana"];
    }

}
