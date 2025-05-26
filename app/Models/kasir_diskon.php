<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kasir_diskon extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_faktur',
        'no_rawat',
        'no_rm',
        'nama',
        'nama_diskon',
        'harga_diskon',
        'qty',
        'total',
        'tanggal',
        'user_input_id',
        'user_input_name',
    ];

    public function kasir()
    {
        return $this->belongsTo(kasir::class, 'kode_faktur', 'kode_faktur');
    }
}
