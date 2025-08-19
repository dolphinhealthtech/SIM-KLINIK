<?php

namespace App\Exports;

use App\Models\perawatan_tindakan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Perawatan_tindakanExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return perawatan_tindakan::with('perawatan_kategori')
        ->get()
        ->map(function ($item) {
            return [
                $item->kode,
                $item->nama,
                optional($item->perawatan_kategori)->nama, // hindari error jika null
                $item->tarif_dokter,
                $item->tarif_perawat,
                $item->tarif_total,
            ];
        });
    }

    public function headings(): array
    {
        return [
            "Kode Perawatan Tindakan",
            "Nama Perawatan Tindakan",
            "Kategori Perawatan Tindakan",
            "Tarif Dokter",
            "Tarif Perawat",
            "Tarif Tindakan",
            "Total Tarif",
        ];
    }
}
