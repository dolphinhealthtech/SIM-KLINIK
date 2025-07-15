<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pembelian_utama extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'nomor_faktur',
        'supplier',
        'no_po_sp',
        'no_faktur_supplier',
        'tanggal_terima_barang',
        'tanggal_faktur',
        'tanggal_jatuh_tempo',
        'pajak_ppn',
        'metode_hna',
        'sub_total',
        'total_diskon',
        'ppn_total',
        'total',
        'materai',
        'koreksi',
        'penerima_barang',
        'user_input_id',
        'user_input_nama'
    ];

    public function details()
    {
        return $this->hasMany(pembelian_details::class, 'nomor_faktur', 'nomor_faktur');
    }
}
