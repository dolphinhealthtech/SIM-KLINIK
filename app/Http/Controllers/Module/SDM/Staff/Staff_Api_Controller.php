<?php

namespace App\Http\Controllers\Module\SDM\Staff;

use App\Http\Controllers\Controller;
use App\Models\agama;
use App\Models\bahasa;
use App\Models\bangsa;
use App\Models\bank;
use App\Models\goldar;
use App\Models\kelamin;
use App\Models\pendidikan;
use App\Models\pernikahan;
use App\Models\poli;
use App\Models\provinsi;
use App\Models\suku;
use App\Models\staff;
use App\Models\staff_pendidikan;
use App\Models\staff_pelatihan;
use App\Models\staff_verifikasi;
use App\Models\User;
use App\Models\posker;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class Staff_Api_Controller extends Controller
{
    public function getStaff($id)
    {
        // Ambil data dokter
        $dokter = staff::findOrFail($id);

        // Ambil data pendidikan dari SD hingga ke tingkat terakhir yang dimiliki dokter
        $pendidikan = DB::table('pendidikans')
            ->where('urutan', '<=', function ($query) use ($dokter) {
                $query->select('urutan')
                    ->from('pendidikans')
                    ->where('kode', $dokter->pendidikan);
            })
            ->orderBy('urutan')
            ->get();

        // Return ke view modal atau response JSON
        return response()->json([
            'staff' => $dokter,
            'pendidikans' => $pendidikan
        ]);
    }

    public function getStaffEdit($id)
    {
        $dokter = staff::with([
            'namauser',
            'namastatuspegawai',
            'verifikasi.pendidikan',
            'verifikasi.pelatihan'
        ])->findOrFail($id);


        // Return ke view modal atau response JSON
        return response()->json([
            'dokter' => $dokter
        ]);
    }
}
