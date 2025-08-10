<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'nama',
        'alamat',
        'profile_image',
        'kode_klinik',
        'is_bpjs_active',
        'is_satusehat_active',
        'is_gudangutama_active',
    ];
}
