<?php

namespace App\Exports;

use App\Models\bank;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BankExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return bank::select('nama', 'kode')->get();
    }

    public function headings(): array
    {
        return ["Nama Bank", "Kode Bank"];
    }
}
