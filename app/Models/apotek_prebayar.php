<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class apotek_prebayar extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_faktur',
        'no_rm',
        'nama',
        'tanggal',
        'nama_obat_alkes',
        'kode_obat_alkes',
        'harga',
        'qty',
        'total',
        'user_input_id',
        'user_input_name',
    ];

    public function apotek()
    {
        return $this->belongsTo(apotek::class, 'kode_faktur', 'kode_faktur');
    }

}
