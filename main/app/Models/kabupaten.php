<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kabupaten extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'name',
        'kode_provinsi',
    ];

    public function provinsi()
    {
        return $this->belongsTo(provinsi::class, 'kode_provinsi', 'kode_provinsi');
    }

    public function kecamatan()
    {
        return $this->hasMany(kecamatan::class, 'kode_kabupaten', 'kode_kabupaten');
    }

    public function pasien()
    {
        return $this->hasMany(pasien::class, 'kode', 'kabupaten_kode');
    }
}
