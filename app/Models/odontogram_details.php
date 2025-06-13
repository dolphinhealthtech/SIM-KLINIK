<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class odontogram_details extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_rm',
        'nama',
        'no_rawat',
        'sex',
        'penjamin',
        'tanggal_lahir',
        'Decayed',
        'Missing',
        'Filled',
        'Oclusi',
        'Palatinus',
        'Mandibularis',
        'Platum',
        'Diastema',
        'Anomali',
    ];
}
