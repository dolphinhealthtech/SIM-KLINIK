<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subspesialis extends Model
{
    use HasFactory;
    protected $fillable = ['nama','kode','kode_rujukan','kode_spesialis'];
}
