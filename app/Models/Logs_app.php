<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logs_app extends Model
{
    protected $fillable = [
    'user_id',
    'username',
    'activity',
    'ip_address',
    'browser',
    'os',
    'device',
    'response_status',
    'is_api',
    'method',
    'time',
    'payload',
    ];
}
