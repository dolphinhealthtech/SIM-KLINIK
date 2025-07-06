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
        'status_kasir',
        'user_input_id',
        'user_input_name',
    ];

    public function detail_obat()
    {
        return $this->hasMany(apotek_prebayar::class, 'kode_faktur', 'kode_faktur');
    }

    public function detail_tindakan()
    {
        return $this->hasMany(pelayanan_soap_dokter_tindakan::class, 'no_rawat', 'no_rawat');
    }

    public function data_soap()
    {
        return $this->belongsTo(pelayanan_soap_dokter::class, 'no_rawat','no_rawat');
    }
}
