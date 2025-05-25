<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kasir extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_faktur',
        'no_rawat',
        'no_rm',
        'nama',
        'sex',
        'usia',
        'alamat',
        'poli',
        'dokter',
        'jenis_perawatan',
        'penjamin',
        'tanggal',
        'sub_total',
        'potongan_harga',
        'administrasi',
        'materai',
        'total',
        'tagihan',
        'kembalian',
        'payment_method_1',
        'payment_nominal_1',
        'payment_type_1',
        'payment_ref_1',
        'payment_method_2',
        'payment_nominal_2',
        'payment_type_2',
        'payment_ref_2',
        'payment_method_3',
        'payment_nominal_3',
        'payment_type_3',
        'payment_ref_3',
        'user_input_id',
        'user_input_name',
    ];
}
