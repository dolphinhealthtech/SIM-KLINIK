<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventaris_utama_keluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_request',
        'nama_klinik',
        'tanggal_request',
        'kode_barang',
        'nama_barang',
        'kategori_barang',
        'jenis_barang',
        'qty_barang',
        'harga_barang',
        'masa_akhir_penggunaan',
        'tanggal_pembelian',
        'detail_barang',
        'lokasi',
        'penanggung_jawab',
        'kondisi',
        'no_seri',
        'user_input_id',
        'user_input_name'
    ];
}
