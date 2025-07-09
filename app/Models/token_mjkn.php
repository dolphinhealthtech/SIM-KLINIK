<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class token_mjkn extends Model
{
    use HasFactory;
    protected $fillable = [
        'token',
        'expired'
    ];
}
