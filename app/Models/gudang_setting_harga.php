<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudang_setting_harga extends Model
{
    use HasFactory;

    protected $fillable = [
        'harga_jual_1',
        'harga_jual_2',
        'harga_jual_3',
        'embalase_poin',
        'user_input_id',
        'user_input_name'
    ];
}
