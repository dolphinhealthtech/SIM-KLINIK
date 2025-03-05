<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role_redirect extends Model
{
    use HasFactory;
    protected $table = 'role_redirects';
    protected $fillable = [
        'role_id',
        'redirect_route',
    ];
}
