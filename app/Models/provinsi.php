<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class provinsi extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode',
        'name',
    ];

    public function kabupaten()
    {
        return $this->hasMany(Kabupaten::class, 'kode_provinsi', 'kode_provinsi');
    }

    public function pasien()
    {
        return $this->hasMany(pasien::class, 'kode', 'provinsi_kode');
    }
}
