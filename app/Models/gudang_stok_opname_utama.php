<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudang_stok_opname_utama extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'expired',
        'qty',
        'alasan',
        'harga',
        'tanggal',
        'jam',
        'user_input_id',
        'user_input_name',
    ];
}
