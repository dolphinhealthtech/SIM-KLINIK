<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class desa extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'name',
        'kode_kecamatan',
    ];


    public function kecamatan()
    {
        return $this->belongsTo(kecamatan::class, 'kode_kecamatan', 'kode_kecamatan');
    }

    public function pasien()
    {
        return $this->hasMany(pasien::class, 'kode', 'desa_kode');
    }
}
