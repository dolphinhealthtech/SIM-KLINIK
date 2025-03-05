<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Set_Bpjs extends Model
{
    use HasFactory;
    protected $fillable = [
        'CONSID',
        'USERNAME',
        'PASSWORD',
        'SCREET_KEY',
        'USER_KEY',
        'APP_CODE',
        'BASE_URL',
        'SERVICE',
        'SERVICE_ANTREAN',
        'KPFK',
    ];
}
