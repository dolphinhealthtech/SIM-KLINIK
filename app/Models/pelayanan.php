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
        return $this->belongsTo(poli::class, 'poli_id');
    }

    public function dokter()
    {
        return $this->belongsTo(dokter::class, 'dokter_id');
    }
    public function pasien()
    {
        return $this->belongsTo(pasien::class, 'pasien_id');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran_rawat_jalan::class, 'nomor_register','nomor_register');
    }
    public function pelayanan_so()
    {
        return $this->belongsTo(pelayanan_soap_perawat::class,'nomor_register','no_rawat');
    }
    public function pelayanan_soap()
    {
        return $this->hasMany(pelayanan_soap_dokter::class,'no_rawat','nomor_register');
    }
}
