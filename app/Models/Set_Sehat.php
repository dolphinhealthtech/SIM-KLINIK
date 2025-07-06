<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Set_Sehat extends Model
{
    use HasFactory;
    protected $fillable = [
        'org_id',
        'client_id',
        'client_secret',
        'SCREET_KEY',
        'SATUSEHAT_BASE_URL',
    ];
}
