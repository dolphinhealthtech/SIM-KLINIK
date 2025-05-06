<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pelayanan extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'nomor_rm',
        'pasien_id',
        'nomor_register',
        'tanggal_kujungan',
        'poli_id',
        'dokter_id'
    ];

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'poli_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }
    public function pasien()
    {
        return $this->belongsTo(pasien::class, 'pasien_id');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran_rawat_jalan::class, 'nomor_register','nomor_register');
    }
}
