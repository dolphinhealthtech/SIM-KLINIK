<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudang_klinik_request extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_request',
        'kode_klinik',
        'nama_klinik',
        'status',
        'tanggal_input',
        'user_input_id',
        'user_input_name',
    ];

    public function details()
    {
        return $this->hasMany(gudang_klinik_request_details::class, 'kode_request', 'kode_request');
    }
}
