<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dokter extends Model
{
    use HasFactory;
    protected $fillable = [
        'nik',
        'nama',
        'npwp',
        'kode',
        'tgl_masuk',
        'status_pegawaian',
        'sip',
        'exp_spri',
        'str',
        'exp_str',
        'pk',
        'exp_pk',
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
}
