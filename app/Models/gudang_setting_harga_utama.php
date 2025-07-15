<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudang_setting_harga_utama extends Model
{
    use HasFactory;

    protected $fillable = [
        'harga_jual',
        'user_input_id',
        'user_input_name'
    ];
}
