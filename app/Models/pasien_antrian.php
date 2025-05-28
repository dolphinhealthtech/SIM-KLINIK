<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pasien_antrian extends Model
{
        use HasFactory;
    
    protected $fillable = [
        'pasien_id',
        'nomor_antrian',
        'status_panggil',
    ];

    
}
