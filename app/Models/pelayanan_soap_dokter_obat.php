<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pelayanan_soap_dokter_obat extends Model
{
    use HasFactory;
    protected $fillable = [
        'nomor_rm',
        'nama',
        'no_rawat',
        'sex',
        'penjamin',
        'tanggal_lahir',
        'Resep_obat',
    ];

    protected $casts = [
        'Resep_obat' => 'array', // agar Laravel otomatis decode JSON ke array
    ];

    public function pelayanan()
    {
        return $this->belongsTo(pelayanan_soap_dokter::class,'no_rawat','no_rawat');
    }

}
