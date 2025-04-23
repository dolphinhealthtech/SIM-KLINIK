<?php

namespace App\Exports;

use App\Models\loket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoketExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return loket::select('nama','poli_id')->get();
    }
    public function headings(): array
    {
        return ["Nama Loket","Poli Id"];
    }
}
