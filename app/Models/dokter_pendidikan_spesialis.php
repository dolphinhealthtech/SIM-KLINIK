<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dokter_pendidikan_spesialis extends Model
{
    use HasFactory;
    protected $fillable = [
        'dokter_verifikasi_id',
        'nama',
        'institusi',
        'tahun_lulus',
        'ijasah',
    ];

    public function verifikasi()
    {
        return $this->belongsTo(dokter_verifikasi::class, 'dokter_verifikasi_id');
    }
}
