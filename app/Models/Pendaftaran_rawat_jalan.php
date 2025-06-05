<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran_rawat_jalan extends Model
{

    use HasFactory;
    protected $fillable =
    [
        'nomor_rm',
        'pasien_id',
        'nomor_register',
        'tanggal_kujungan',
        'poli_id',
        'dokter_id',
        'Penjamin',
        'antrian',
        'no_urut'
    ];

    public function status()
    {
        return $this->hasOne(Pendaftaran_rawat_jalan_status::class, 'register_id');
    }

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

    public function penjamin()
    {
        return $this->belongsTo(penjamin::class, 'Penjamin');
    }

    public function soap_dokter()
    {
        return $this->hasOne(pelayanan_soap_dokter::class, 'no_rawat', 'nomor_register');
    }

    public function soap_perawat()
    {
        return $this->hasOne(pelayanan_soap_perawat::class, 'no_rawat', 'nomor_register');
    }

    public function apotek()
    {
        return $this->hasOne(apotek::class, 'no_rawat', 'nomor_register');
    }

}
