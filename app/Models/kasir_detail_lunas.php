<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kasir_detail_lunas extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_faktur',
        'no_rawat',
        'no_rm',
        'nama',
        'nama_obat_tindakan',
        'harga_obat_tindakan',
        'qty_pelaksana',
        'total',
        'tanggal',
        'user_input_id',
        'user_input_name',
    ];
}
