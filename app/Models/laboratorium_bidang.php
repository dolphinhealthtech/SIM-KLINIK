<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laboratorium_bidang extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
    ];

    public function sub()
    {
        return $this->hasMany(laboratorium_bidang_sub::class, 'laboratorium_bidang_id');
    }
}
