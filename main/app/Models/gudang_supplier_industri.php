<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudang_supplier_industri extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'nama_pic',
        'telepon_pic'
    ];
}
