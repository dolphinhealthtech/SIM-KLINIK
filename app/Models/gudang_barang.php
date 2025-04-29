<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudang_barang extends Model
{
    use HasFactory;

    protected $table = 'gudang_barangs';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'jenis_formularium',
        'kfa_kode',
        'nama_industri_barang',
        'satuan_kecil',
        'satuan_sedang',
        'satuan_besar',
        'nilai_satuan_kecil',
        'nilai_satuan_sedang',
        'nilai_satuan_besar',
        'tempat_penyimpanan',
        'barcode',
        'gudang_kategori',
        'jenis_obat',
        'jenis_generik',
        'bentuk_sediaan',
        'user_input_id',
        'user_input_nama',
    ];

}
