<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventaris_data_barang_utama extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_barang',
        'satuan_barang',
        'jenis_barang',
        'masa_pakai_barang',
        'masa_pakai_waktu_barang',
        'deskripsi_barang',
        'user_input_id',
        'user_input_name'
    ];
}
