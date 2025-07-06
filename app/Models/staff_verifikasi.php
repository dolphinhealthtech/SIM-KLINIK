<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class staff_verifikasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_id',
        'nama_bank',
        'norek',
        'cabang_bank',
    ];

    public function pendidikan()
    {
        return $this->hasMany(staff_pendidikan::class);
    }

    public function pelatihan()
    {
        return $this->hasMany(staff_pelatihan::class);
    }

    public function staff()
    {
        return $this->belongsTo(staff::class);
    }
}
