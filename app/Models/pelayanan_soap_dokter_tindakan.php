<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pelayanan_soap_dokter_tindakan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nomor_rm',
        'nama',
        'no_rawat',
        'sex',
        'penjamin',
        'tanggal_lahir',
        'Jenis_tindakan',
        'jenis_pelaksana',
        'harga',
    ];

    public function apotek()
    {
        return $this->belongsTo(apotek::class, 'no_rawat', 'no_rawat');
    }

    public function cek_resep()
    {
        return $this->belongsTo(pelayanan_soap_dokter_obat::class, 'no_rawat', 'no_rawat');
    }

    public function data_soap()
    {
        return $this->belongsTo(pelayanan_soap_dokter::class, 'no_rawat', 'no_rawat');
    }

}
