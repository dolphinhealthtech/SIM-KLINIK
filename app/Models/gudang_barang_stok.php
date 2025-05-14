<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudang_barang_stok extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_obat_alkes',
        'nama_obat_alkes',
        'qty',
        'tanggal_terima_obat',
        'expired',
        'user_input_id',
        'user_input_name',
    ];
}
