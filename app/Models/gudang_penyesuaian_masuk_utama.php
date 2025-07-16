<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudang_penyesuaian_masuk_utama extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'qty_sebelum',
        'qty_mutasi',
        'qty_sesudah',
        'jenis_penyesuaian',
        'alasan',
        'tanggal',
        'jam',
        'harga',
        'expired',
        'user_input_id',
        'user_input_name',
    ];
}
