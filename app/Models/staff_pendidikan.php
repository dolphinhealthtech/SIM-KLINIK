<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class staff_pendidikan extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_verifikasi_id',
        'kode',
        'nama_sekolah',
        'tahun_lulus',
        'ijasah',
    ];

    public function verifikasi()
    {
        return $this->belongsTo(staff_verifikasi::class, 'staff_verifikasi_id');
    }
}
