<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\goldar;
use App\Models\kelamin;
use App\Models\pernikahan;
use App\Models\Loket;
use App\Models\Pasien;
use App\Models\Pendaftaran_rawat_jalan;
use App\Models\Pendaftaran_rawat_jalan_status;
use App\Models\penjamin;
use App\Models\poli;
use App\Models\Set_Bpjs;
use App\Models\Set_Sehat;
use App\Models\WebSetting;
use App\Models\Dokter;
use App\Http\Controllers\PcareController;
use Carbon\Carbon;
use Illuminate\Http\Request;


class MonitorController extends Controller
{
    protected $PcareController;

    public function __construct(PcareController $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    public function setingweb()
    {
        $title = "Seting Websaite";
        $setting = WebSetting::first(); // Ambil data pertama jika ada
        $set_bpjs = Set_Bpjs::all(); // Ambil data pertama jika ada
        $set_Sehat = Set_Sehat::all(); // Ambil data pertama jika ada


        return view('dashboard.webset', compact('title', 'setting', 'set_bpjs', 'set_Sehat'));
    }

    public function monitor()
    {
        $title = "Pelayanan tiket";
        $goldar = goldar::all();
        $kelamin = kelamin::all();
        $poli = poli::all();
        $pernikaha = pernikahan::all();

        return view('monitor.index', compact('title', 'poli', 'goldar', 'kelamin', 'pernikaha'));
    }

    public function monitor_bpjs(Request $request)
    {
        try {


            $data = $request->validate([
                'nikNokaInput' => 'required',
                'tanggal_kunjungan' => 'required',
                'poli_id' => 'required',
                'dokter_id' => 'required'
            ]);

            $antrian = Loket::where('poli_id', $request->poli_id)->first();


            $pasien = Pasien::where('nik', $request->nikNokaInput)
                ->orWhere('no_bpjs', $request->nikNokaInput)
                ->first();

            $penjamin = penjamin::where('nama', "BPJS")->first();
            // Ambil tanggal kunjungan dan ubah jadi format yyDDD
            $tanggal = Carbon::parse($request->tanggal_kunjungan);
            $tanggalKode = $tanggal->format('y') . str_pad($tanggal->dayOfYear, 3, '0', STR_PAD_LEFT); // Contoh: 25113

            // Angka acak 4 digit
            $angkaAcak = mt_rand(1000, 9999); // Contoh: 1234

            // Gabungkan format akhir: 1234-25113
            $no_registrasi = $angkaAcak . '-' . $tanggalKode;

            // Ambil tanggal hari ini (tanpa jam)
            $tanggalHariIni = Carbon::today();

            // Cari antrian terakhir HANYA untuk hari ini berdasarkan kode poli
            $last = Pendaftaran_rawat_jalan::where('antrian', 'like', $antrian->nama . '-%')
                ->whereDate('created_at', $tanggalHariIni)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($last) {
                // Ambil angka terakhir dan increment
                $lastNumber = (int) str_replace($antrian->nama . '-', '', $last->antrian);
                $nextNumber = $lastNumber + 1;
            } else {
                // Jika belum ada antrian hari ini, mulai dari 1
                $nextNumber = 1;
            }

            $antrianBaru = $antrian->nama . '-' . $nextNumber;


            $pendaftaran = Pendaftaran_rawat_jalan::create([
                'nomor_rm' => $pasien->no_rm,
                'pasien_id' => $pasien->id,
                'poli_id' => $request->poli_id,
                'tanggal_kujungan' => $request->tanggal_kunjungan,
                'dokter_id' => $request->dokter_id,
                'Penjamin' => $penjamin->id,
                'nomor_register' => $no_registrasi,
                'antrian' => $antrianBaru,
            ]);

            Pendaftaran_rawat_jalan_status::create([
                'nomor_rm' => $pasien->no_rm,
                'pasien_id' => $pasien->id,
                'nomor_register' => $no_registrasi,
                'tanggal_kujungan' => $request->tanggal_kunjungan,
                'register_id' => $pendaftaran->id,
                'status_panggil' => 0, // 0 = pendaftaran , 1 = perawat, 2 = dokter
                'status_pendaftaran' => 1, // 0 = batal, 1 = pendaftaran , 2 = hadir
                'Status_aplikasi' => 2, // 1 = app manual , 2 = app Onlain ,3 = bpjs
            ]);

            $poli = poli::find($request->poli_id)->first();

            // Ambil info dokter dan jadwal berdasarkan tanggal kunjungan
            $tanggalKunjungan = Carbon::parse($request->tanggal_kunjungan)->format('Y-m-d');

            $dokter = Dokter::with(['namauser', 'jadwal' => function ($query) use ($tanggalKunjungan) {
                $query->whereDate('start', $tanggalKunjungan);
            }])->find($request->dokter_id);

            $jadwal = $dokter->jadwal->first();
            $jamPraktek = $jadwal
                ? Carbon::parse($jadwal->start)->format('H:i') . '-' . Carbon::parse($jadwal->end)->format('H:i')
                : '-';

            $databpjs = [
                "nomorkartu" => $pasien->no_bpjs,
                "nik" => $pasien->nik,
                "nohp" =>  $pasien->telepon,
                "kodepoli" => $poli->kode,
                "namapoli" => $poli->nama,
                "norm" => $pasien->no_rm,
                "tanggalperiksa" => $tanggalKunjungan,
                "kodedokter" => $dokter->kode,
                "namadokter" => $dokter->namauser->name,
                "jampraktek" => $jamPraktek,
                "nomorantrean" => $antrianBaru,
                "angkaantrean" => $nextNumber,
                "keterangan" => "",
            ];

            $response = $this->PcareController->post_ws_antria_bpjs($databpjs);

            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil disimpan.',
                'noantrian' => $antrianBaru,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function monitor_nobpjs(Request $request)
    {
        try {


            $data = $request->validate([
                'nikNnamaInput' => 'required',
                'tanggal_kunjungan_no' => 'required',
                'poli_id_no' => 'required',
                'dokter_id_no' => 'required'
            ]);

            $antrian = Loket::where('poli_id', $request->poli_id_no)->first();


            $pasien = Pasien::where('nik', $request->nikNnamaInput)
                ->orWhere('nama', $request->nikNnamaInput)
                ->first();

            $penjamin = penjamin::where('nama', "UMUM")->first();
            // Ambil tanggal kunjungan dan ubah jadi format yyDDD
            $tanggal = Carbon::parse($request->tanggal_kunjungan_no);
            $tanggalKode = $tanggal->format('y') . str_pad($tanggal->dayOfYear, 3, '0', STR_PAD_LEFT); // Contoh: 25113

            // Angka acak 4 digit
            $angkaAcak = mt_rand(1000, 9999); // Contoh: 1234

            // Gabungkan format akhir: 1234-25113
            $no_registrasi = $angkaAcak . '-' . $tanggalKode;

            // Ambil tanggal hari ini (tanpa jam)
            $tanggalHariIni = Carbon::today();

            // Cari antrian terakhir HANYA untuk hari ini berdasarkan kode poli
            $last = Pendaftaran_rawat_jalan::where('antrian', 'like', $antrian->nama . '-%')
                ->whereDate('created_at', $tanggalHariIni)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($last) {
                // Ambil angka terakhir dan increment
                $lastNumber = (int) str_replace($antrian->nama . '-', '', $last->antrian);
                $nextNumber = $lastNumber + 1;
            } else {
                // Jika belum ada antrian hari ini, mulai dari 1
                $nextNumber = 1;
            }

            $antrianBaru = $antrian->nama . '-' . $nextNumber;


            $pendaftaran = Pendaftaran_rawat_jalan::create([
                'nomor_rm' => $pasien->no_rm,
                'pasien_id' => $pasien->id,
                'poli_id' => $request->poli_id_no,
                'tanggal_kujungan' => $request->tanggal_kunjungan_no,
                'dokter_id' => $request->dokter_id_no,
                'Penjamin' => $penjamin->id,
                'nomor_register' => $no_registrasi,
                'antrian' => $antrianBaru,
            ]);

            Pendaftaran_rawat_jalan_status::create([
                'nomor_rm' => $pasien->no_rm,
                'pasien_id' => $pasien->id,
                'nomor_register' => $no_registrasi,
                'tanggal_kujungan' => $request->tanggal_kunjungan_no,
                'register_id' => $pendaftaran->id,
                'status_panggil' => 0, // 0 = pendaftaran , 1 = perawat, 2 = dokter
                'status_pendaftaran' => 1, // 0 = batal, 1 = pendaftaran , 2 = hadir
                'Status_aplikasi' => 2, // 1 = app manual , 2 = app Onlain ,3 = bpjs
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil disimpan.',
                'noantrian' => $antrianBaru,

            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function loketAntrian()
    {
        $title = "Loket Antrian";
        // You can fetch active queue data here
        // For example:
        // $activeQueues = Pendaftaran_rawat_jalan::whereDate('created_at', Carbon::today())
        //     ->whereNotNull('antrian')
        //     ->orderBy('created_at', 'desc')
        //     ->get();

        return view('monitor.loket_antrian', compact('title'));
    }
}
