<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gcs_verbal extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'skor',
    ];
}
