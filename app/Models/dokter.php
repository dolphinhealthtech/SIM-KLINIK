<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dokter extends Model
{
    use HasFactory;
    protected $fillable = [
        'nik',
        'npwp',
        'poli',
        'kode',
        'kode_satu',
        'tgl_masuk',
        'status_pegawaian',
        'sip',
        'exp_spri',
        'str',
        'exp_str',
        'tempat_lahir',
        'tanggal_lahir',
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


    public function namauser()
    {
        return $this->belongsTo(User::class, 'users');
    }
    public function namapoli()
    {
        return $this->belongsTo(poli::class, 'poli');
    }
    public function namastatuspegawai()
    {
        return $this->belongsTo(posker::class, 'status_pegawaian');
    }

    public function verifikasi()
    {
        return $this->hasOne(dokter_verifikasi::class);
    }

}
