<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class perawatan_tindakan extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode',
        'nama',
        'perawatan_kategori_id',
        'tarif_dokter',
        'tarif_perawat',
        'tarif_total'
    ];

    public function perawatan_kategori() {
        return $this->belongsTo(perawatan_kategori::class);
    }
}
