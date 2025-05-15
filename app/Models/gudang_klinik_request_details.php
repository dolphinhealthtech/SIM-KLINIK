<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudang_klinik_request_details extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_request',
        'kode_obat_alkes',
        'nama_obat_alkes',
        'qty',
        'user_input_id',
        'user_input_name',
    ];
}
