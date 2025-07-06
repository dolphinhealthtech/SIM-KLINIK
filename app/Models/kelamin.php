<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kelamin extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'kode',
    ];
}
