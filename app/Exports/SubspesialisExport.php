<?php

namespace App\Exports;

use App\Models\subspesialis;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubspesialisExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $kode;

    public function __construct($kode)
    {
        $this->kode = $kode;
    }
    public function collection()
    {
        return subspesialis::where('kode_spesialis', $this->kode)
        ->select('nama','kode','kode_rujukan','kode_spesialis')->get();

    }

    public function headings(): array
    {
        return ["Nama Sub Spesialis" ,"Kode Sub Spesialis","Kode Rujukan Sub Spesialis","Kode Spesialis"];
    }
}
