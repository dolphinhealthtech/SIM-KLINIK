<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laboratorium_bidang_sub extends Model
{
    use HasFactory;
    protected $fillable = [
        'laboratorium_bidang_id',
        'nama_laboratorium_bidang',
        'nama_sublaboratorium_bidang',
    ];

    public function induk()
    {
        return $this->belongsTo(laboratorium_bidang::class, 'laboratorium_bidang_id');
    }
}
