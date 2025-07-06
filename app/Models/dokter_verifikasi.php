<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dokter_verifikasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'dokter_id',
        'nama_bank',
        'norek',
        'cabang_bank',
    ];

    public function pendidikan()
    {
        return $this->hasMany(dokter_pendidikan::class);
    }

    public function spesialis()
    {
        return $this->hasMany(dokter_pendidikan_spesialis::class);
    }

    public function pelatihan()
    {
        return $this->hasMany(dokter_pelatihan::class);
    }

    public function dokter()
    {
        return $this->belongsTo(dokter::class);
    }
}
