<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pelayanan_soap_dokter_icd extends Model
{
    use HasFactory;
    protected $fillable = [
        'nomor_rm',
        'nama',
        'no_rawat',
        'sex',
        'penjamin',
        'tanggal_lahir',
        'nama_icd10',
        'kode_icd10',
        'priority_icd10',
        'nama_icd9',
        'kode_icd9',
    ];

}
