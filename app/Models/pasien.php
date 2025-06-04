<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pasien extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_rm',
        'nik',
        'nama',
        'kode_ihs',
        'tempat_lahir',
        'tanggal_lahir',
        'no_bpjs',
        'tgl_exp_bpjs',
        'kelas_bpjs',
        'jenis_Kartu_bpjs',
        'provide',
        'kodeprovide',
        'hubungan_keluarga',
        'alamat',
        'rt',
        'rw',
        'kode_pos',
        'kewarganegaraan',
        'seks',
        'agama',
        'pendidikan',
        'goldar',
        'pernikahan',
        'pekerjaan',
        'telepon',
        'provinsi_kode',
        'kabupaten_kode',
        'kecamatan_kode',
        'desa_kode',
        'suku',
        'bahasa',
        'bangsa',
        'verifikasi',
        'users',
        'user_id_input',
        'user_name_input',
    ];

    public function kelamin()
    {
        return $this->belongsTo(kelamin::class, 'seks','kode');
    }

    public function soap_dokter()
    {
        return $this->hasOne(pelayanan_soap_dokter::class, 'nomor_rm', 'no_rm');
    }

    public function antrian()
    {
        return $this->hasOne(pasien_antrian::class, 'pasien_id', 'id');
    }
}
