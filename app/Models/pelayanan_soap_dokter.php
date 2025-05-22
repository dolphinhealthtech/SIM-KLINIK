<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pelayanan_soap_dokter extends Model
{
    use HasFactory;
    protected $fillable = [
        'nomor_rm',
        'nama',
        'no_rawat',
        'sex',
        'penjamin',
        'tanggal_lahir',
        'umur',
        'tableData',
        'sistol',
        'distol',
        'tensi',
        'suhu',
        'nadi',
        'rr',
        'tinggi',
        'berat',
        'spo2',
        'lingkar_perut',
        'nilai_bmi',
        'status_bmi',
        'jenis_alergi',
        'alergi',
        'eye',
        'verbal',
        'motorik',
        'summernote',
        'summernote2',
        'summernote5',
        'summernote4',
        'files',
        'status_apotek',
    ];
    protected $casts = [
        'tableData' => 'array', // Mengonversi kolom JSON menjadi array
    ];

    // Model pelayanan_soap_dokter

    public function resep()
    {
        return $this->hasOne(Pelayanan_soap_dokter_obat::class, 'no_rawat','no_rawat');
    }
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran_rawat_jalan::class, 'no_rawat', 'nomor_register');
    }

    public function pasien()
    {
        return $this->belongsTo(pasien::class, 'nomor_rm','no_rm');
    }
}
