<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dokter_jadwal extends Model
{
    use HasFactory;
    protected $fillable = [
        'dokter_id', 'title', 'start', 'end'
    ];
}
