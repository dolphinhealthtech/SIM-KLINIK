<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class external_database extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'host',
        'database',
        'username',
        'password',
        'port',
        'active'
    ];

}
