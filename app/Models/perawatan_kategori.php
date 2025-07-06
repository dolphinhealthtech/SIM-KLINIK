<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class perawatan_kategori extends Model
{
    use HasFactory;
    protected $fillable = ['nama'];

    public function perawatan_tindakan() {
        return $this->hasMany(perawatan_tindakan::class);
    }
}
