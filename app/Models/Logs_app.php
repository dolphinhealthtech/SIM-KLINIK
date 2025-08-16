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
        'response_body',   // baru ditambahkan
        'is_api',
        'method',
        'time',
        'payload',
        'execution_ms',    // baru ditambahkan
    ];
}
