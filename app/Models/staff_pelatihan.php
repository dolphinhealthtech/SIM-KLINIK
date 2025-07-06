<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class staff_pelatihan extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_verifikasi_id',
        'nama',
        'penyelenggara',
        'tahun',
        'sertifikat',
    ];

    public function verifikasi()
    {
        return $this->belongsTo(staff_verifikasi::class, 'staff_verifikasi_id');
    }
}
