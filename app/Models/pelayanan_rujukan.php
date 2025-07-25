<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pelayanan_rujukan extends Model
{
    use HasFactory;


    protected $fillable = [
        'nomor_rm',
        'no_rawat',
        'penjamin',
        'tujuan_rujukan',
        'opsi_rujukan',
        'tanggal_rujukan',
        'sarana',
        'rujukan_lanjut',
        'sub_spesialis',
    ];

}
