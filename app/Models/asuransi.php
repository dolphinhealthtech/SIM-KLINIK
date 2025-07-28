<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class asuransi extends Model
{
    use HasFactory;
    protected $fillable = [
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
    ];
}
