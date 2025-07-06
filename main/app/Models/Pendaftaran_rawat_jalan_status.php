<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran_rawat_jalan_status extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'nomor_rm',
        'pasien_id',
        'nomor_register',
        'tanggal_kujungan',
        'register_id',
        'status_panggil',
        'status_pendaftaran',
        'Status_aplikasi'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran_rawat_jalan::class, 'id');
    }
}
