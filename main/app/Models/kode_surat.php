<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kode_surat extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_surat_skd',
        'user_input_id',
        'user_input_name',
    ];
}
