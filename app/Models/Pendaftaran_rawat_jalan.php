<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran_rawat_jalan extends Model
{

    use HasFactory;
    protected $fillable = ['nomor_rm','pasien_id','nomor_register','tanggal_kujungan','poli_id','dokter_id','Penjamin'];
}
