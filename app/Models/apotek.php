<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class apotek extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_faktur',
        'no_rm',
        'no_rawat',
        'nama',
        'alamat',
        'tanggal',
        'jenis_resep',
        'jenis_rawat',
        'poli',
        'dokter',
        'penjamin',
        'embalase_poin',
        'sub_total',
        'embis_total',
        'total',
        'note_apotek',
        'user_input_id',
        'user_input_name',
    ];
}
