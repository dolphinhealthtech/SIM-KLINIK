<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class icd9 extends Model
{
    use HasFactory;

    protected $fillable = ['kode_icd9', 'nama_icd9'];
}
