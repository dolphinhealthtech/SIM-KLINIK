<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class loket extends Model
{
    use HasFactory;
    protected $fillable = ['nama', 'poli_id'];

    public function poli()
    {
        return $this->belongsTo(poli::class, 'poli_id');
    }
}
