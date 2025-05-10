<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pelayanan_soap_perawat extends Model
{
    use HasFactory;
    protected $fillable = [
        'nomor_rm',
        'nama',
        'no_rawat',
        'sex',
        'penjamin',
        'tanggal_lahir',
        'umur',
        'tableData',
        'sistol',
        'distol',
        'tensi',
        'suhu',
        'nadi',
        'rr',
        'tinggi',
        'berat',
        'spo2',
        'lingkar_perut',
        'nilai_bmi',
        'status_bmi',
        'jenis_alergi',
        'alergi',
        'eye',
        'verbal',
        'motorik',
        'summernote',
        'files',
    ];

    protected $casts = [
        'tableData' => 'array', // Mengonversi kolom JSON menjadi array
    ];
}
