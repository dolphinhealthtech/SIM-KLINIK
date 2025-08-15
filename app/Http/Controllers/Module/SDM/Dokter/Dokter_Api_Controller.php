<?php

namespace App\Http\Controllers\Module\SDM\Dokter;

use App\Http\Controllers\Controller;
use App\Models\dokter;
use App\Models\dokter_jadwal;
use App\Http\Controllers\Brijing_Intergrasi\Pcare_Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;


class Dokter_Api_Controller extends Controller
{
    protected $PcareController;

    public function __construct(Pcare_Controller $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    public function getDokter($id)
    {
        // Ambil data dokter
        $dokter = Dokter::findOrFail($id);

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
            'dokter' => $dokter,
            'pendidikans' => $pendidikan
        ]);
    }

    public function getDokterEdit($id)
    {
        $dokter = dokter::with([
            'namauser',
            'namapoli',
            'namastatuspegawai',
            'verifikasi.pendidikan',
            'verifikasi.spesialis',
            'verifikasi.pelatihan'
        ])->findOrFail($id);


        // Return ke view modal atau response JSON
        return response()->json([
            'dokter' => $dokter
        ]);
    }



    public function dokterjadwaljson($id)
    {
        return dokter_jadwal::where('dokter_id', $id)->get(['id', 'title', 'start', 'end']);
    }



    public function jadwal_dokter($id)
    {
        $dokter = Dokter::findOrFail($id);
        $kodepoli = $dokter->namapoli->kode;
        $tanggal = date('Y-m-d');

        $listDokter = $this->PcareController->get_jadwal_dokter_bpjs($kodepoli, $tanggal)->getData(true); // Ambil data sebagai array

        if (!$listDokter) {
            Log::error("Tidak ada data dokter dari BPJS untuk poli {$kodepoli} tanggal {$tanggal}");
            return;
        }


        foreach ($listDokter['data'] as $dokterData) {
            $kodeDokter = $dokterData['kodedokter'];
            $jamPraktek = $dokterData['jampraktek']; // contoh: "08:00-23:00"
            $kapasitas = $dokterData['kapasitas'];

            $dokter = dokter::where('kode', $kodeDokter)->first();

            if (!$dokter) {
                Log::warning("Dokter dengan kodedokter {$kodeDokter} tidak ditemukan.");
                continue;
            }

            if (strpos($jamPraktek, '-') !== false) {
                list($jamMulai, $jamSelesai) = explode('-', $jamPraktek);

                $jamMulaiFull = $tanggal . ' ' . $jamMulai . ':00';
                $jamSelesaiFull = $tanggal . ' ' . $jamSelesai . ':00';

                // Shift malam: jika jam selesai lebih kecil dari jam mulai, tambahkan 1 hari
                if ($jamSelesai < $jamMulai) {
                    $jamSelesaiFull = date('Y-m-d H:i:s', strtotime($jamSelesaiFull . ' +1 day'));
                }

                // Simpan ke tabel dokter_jadwal
                dokter_jadwal::updateOrCreate(
                    [
                        'dokter_id' => $dokter->id,
                        'title' => "Jadwal Masuk",
                        'start' => $jamMulaiFull,
                        'end' => $jamSelesaiFull
                    ]
                );
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diperbarui!'
        ], 201);
    }
}
