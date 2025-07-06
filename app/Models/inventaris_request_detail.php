<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventaris_request_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_request',
        'kode_barang',
        'nama_barang',
        'qty',
        'user_input_id',
        'user_input_name',
    ];
}
