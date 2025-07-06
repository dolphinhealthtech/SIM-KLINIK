<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kecamatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'name',
        'kode_kabupaten',
    ];

    public function kabupaten()
    {
        return $this->belongsTo(kabupaten::class, 'kode_kabupaten', 'kode_kabupaten');
    }

    public function desa()
    {
        return $this->hasMany(desa::class, 'kode_kecamatan', 'kode_kecamatan');
    }

    public function pasien()
    {
        return $this->hasMany(pasien::class, 'kode', 'kecamatan_kode');
    }
}
