<?php

namespace App\Exports;

use App\Models\htt_pemeriksaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Htt_pemeriksaanExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return htt_pemeriksaan::select('nama_pemeriksaan')->get();
    }

    public function headings(): array
    {
        return ["Nama Pemeriksaan"];
    }
}
