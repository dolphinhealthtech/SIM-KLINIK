<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kasir_tindakan_lunas extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_faktur',
        'no_rawat',
        'no_rm',
        'nama',
        'nama_tindakan',
        'harga_tindakan',
        'pelaksana',
        'total',
        'tanggal',
        'user_input_id',
        'user_input_name',
    ];
}
