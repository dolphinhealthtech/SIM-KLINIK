<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudang_barang_harga extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_obat_alkes',
        'nama_obat_alkes',
        'harga_dasar',
        'harga_jual_1',
        'harga_jual_2',
        'harga_jual_3',
        'diskon',
        'ppn',
        'tanggal_obat_masuk',
        'user_input_id',
        'user_input_name',
    ];
}
