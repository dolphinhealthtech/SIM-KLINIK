<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pelayanan_soap_perawat extends Model
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
        'files',
        'user_input_id',
        'user_input_name',
    ];

    protected $casts = [
        'tableData' => 'array', // Mengonversi kolom JSON menjadi array
    ];

    // pelayanan_soap_perawat.php

    public function alergi_keterangan()
    {
        return $this->belongsTo(alergi::class, 'alergi', 'kode_alergi');
    }

    public function gcs_eye()
    {
        return $this->belongsTo(gcs_eye::class, 'eye');
    }

    public function gcs_verbal()
    {
        return $this->belongsTo(gcs_verbal::class, 'verbal');
    }

    public function gcs_motorik()
    {
        return $this->belongsTo(gcs_motorik::class, 'motorik');
    }

    public function gcs_kesadaran()
    {
        return $this->belongsTo(gcs_kesadaran::class, 'gcs_total', 'skor');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran_rawat_jalan::class, 'no_rawat', 'nomor_register');
    }
}
