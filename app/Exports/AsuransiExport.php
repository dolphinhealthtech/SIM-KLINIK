<?php

namespace App\Exports;

use App\Models\asuransi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AsuransiExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return asuransi::select([
            'nama',
            'kode',
            'jenis_asuransi',
            'verif_pasien',
            'filter_obat',
            'tanggal_mulai',
            'tanggal_akhir',
            'alamat_asuransi',
            'no_telp_asuransi',
            'faksimil',
            'pic',
            'no_telp_pic',
            'jabatan_pic',
            'bank',
            'no_rekening',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'Nama Asuransi',
            'Kode Asuransi',
            'Jenis Asuransi',
            'Verifikasi Pasien',
            'Filter Obat',
            'Tanggal Mulai',
            'Tanggal Akhir',
            'Alamat Asuransi',
            'No. Telp Asuransi',
            'Faksimil',
            'PIC',
            'No. Telp PIC',
            'Jabatan PIC',
            'Bank',
            'No. Rekening',
        ];
    }
}
