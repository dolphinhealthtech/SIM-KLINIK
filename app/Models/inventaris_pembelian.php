<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventaris_pembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'tanggal_pembelian',
        'total_harga',
        'petugas_penerima',
        'user_input_id',
        'user_input_name'
    ];
}
