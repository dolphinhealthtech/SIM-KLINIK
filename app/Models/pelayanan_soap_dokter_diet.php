<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pelayanan_soap_dokter_diet extends Model
{
    use HasFactory;
    protected $fillable = [
        'nomor_rm',
        'nama',
        'no_rawat',
        'sex',
        'penjamin',
        'tanggal_lahir',
        'Jenis_diet',
        'jenis_diet_makanan',
        'jenis_diet_makanan_tidak',
    ];
}
