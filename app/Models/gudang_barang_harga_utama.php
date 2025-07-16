<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class gudang_barang_harga_utama extends Model
{
    protected $fillable = [
        'kode_obat_alkes',
        'nama_obat_alkes',
        'harga_dasar',
        'harga_jual_1',
        'diskon',
        'ppn',
        'tanggal_obat_masuk',
        'user_input_id',
        'user_input_name',
    ];
}
