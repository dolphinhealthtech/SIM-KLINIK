<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class alergi extends Model
{
    use HasFactory;
    protected $fillable = ['kode_alergi', 'kode_jenis_alergi', 'nama_jenis_alergi'];
}
