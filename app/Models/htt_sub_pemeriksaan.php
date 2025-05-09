<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class htt_sub_pemeriksaan extends Model
{
    use HasFactory;

    protected $fillable = ['htt_pemeriksaan_id','nama_pemeriksaan','nama_subpemeriksaan'];
}
