<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Loket;
use App\Models\Pasien;
use App\Models\Pendaftaran_rawat_jalan;
use App\Models\Pendaftaran_rawat_jalan_status;
use App\Models\penjamin;
use App\Models\poli;
use App\Models\pelayanan;
use App\Http\Controllers\PcareController;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class PendaftaranController extends Controller
{
    protected $PcareController;

    public function __construct(PcareController $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    public function pendaftaran()
    {
        $title = "Pasien";
        $pasiens = pasien::all();
        $poli = poli::all();

        $today = Carbon::today(); // atau now()->startOfDay()

        $pendaftaran = Pendaftaran_rawat_jalan::with('status', 'poli', 'dokter.namauser', 'pasien', 'penjamin')
            ->whereHas('status', function ($query) {
                $query->whereIn('status_pendaftaran', ['1', '2']);
            })
            ->whereDate('tanggal_kujungan', '=', $today)
            ->whereDoesntHave('apotek') // Filter: yang belum ada di tabel apotek
            ->get();


        $pasienallold = Pendaftaran_rawat_jalan::whereDate('tanggal_kujungan', '=', $today)
            ->count();
        $pasienallnewnow = Pendaftaran_rawat_jalan::with('status')
            ->whereHas('status', function ($query) {
                $query->whereIn('status_pendaftaran', ['3']);
            })
            ->count();

        $penjamin = penjamin::all();

        $rekapPerPoliDokter = Pendaftaran_rawat_jalan::whereDate('tanggal_kujungan', $today)
            ->whereHas('dokter.jadwal', function ($query) use ($today) {
                $query->whereDate('start', '=', $today);
            })
            ->select('poli_id', 'dokter_id', DB::raw('count(*) as jumlah'))
            ->groupBy('poli_id', 'dokter_id')
            ->with(['poli', 'dokter'])
            ->get();

        $jumlahDokter = $rekapPerPoliDokter->count(); // Banyaknya dokter unik
        $totalPasien = $rekapPerPoliDokter->sum('jumlah'); // Total pasien dari semua dokter

        // dd($rekapPerPoliDokter,$jumlahDokter,$totalPasien);

        return view('module.pendaftaran.daftar', compact('title', 'jumlahDokter', 'totalPasien', 'rekapPerPoliDokter', 'pendaftaran', 'pasiens', 'penjamin', 'poli', 'pasienallnewnow', 'pasienallold'));
    }

    public function getByPoli($id, Request $request)
    {
        $datetime = $request->input('datetime'); // ex: 2025-04-16 00:30:00

        $dokter = Dokter::where('poli', $id)
            ->whereHas('jadwal', function ($query) use ($datetime) {
                $query->where('start', '<=', $datetime)
                    ->where('end', '>=', $datetime);
            })
            ->with('namauser', 'namapoli', 'namastatuspegawai')
            ->get();

        return response()->json($dokter);
    }

    public function pendaftaranadd(Request $request)
    {
        try {

            $data = $request->validate([
                'pasien' => 'required',
                'poli_id' => 'required',
                'tanggal_kunjungan' => 'required',
                'dokter_id' => 'required',
                'penjamin_id' => 'required',
            ]);

            $pasien = Pasien::find($request->pasien);
            // Ambil tanggal kunjungan dan ubah jadi format yyDDD
            $tanggal = Carbon::parse($request->tanggal_kunjungan);
            $tanggalKode = $tanggal->format('y') . str_pad($tanggal->dayOfYear, 3, '0', STR_PAD_LEFT); // Contoh: 25113

            // Angka acak 4 digit
            $angkaAcak = mt_rand(1000, 9999); // Contoh: 1234

            // Gabungkan format akhir: 1234-25113
            $no_registrasi = $angkaAcak . '-' . $tanggalKode;

            $antrian = Loket::where('poli_id', $request->poli_id)->first();

            // Cari antrian terakhir berdasarkan kode poli
            $last = Pendaftaran_rawat_jalan::where('antrian', 'like', $antrian->nama . '-%')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($last) {
                // Ambil angka terakhir dan increment
                $lastNumber = (int) str_replace($antrian->nama . '-', '', $last->antrian);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $antrianBaru = $antrian->nama . '-' . $nextNumber;


            $pendaftaran = Pendaftaran_rawat_jalan::create([
                'nomor_rm' => $pasien->no_rm,
                'pasien_id' => $request->pasien,
                'poli_id' => $request->poli_id,
                'tanggal_kujungan' => $request->tanggal_kunjungan,
                'dokter_id' => $request->dokter_id,
                'Penjamin' => $request->penjamin_id,
                'nomor_register' => $no_registrasi,
                'antrian' => $antrianBaru,
            ]);

            Pendaftaran_rawat_jalan_status::create([
                'nomor_rm' => $pasien->no_rm,
                'pasien_id' => $request->pasien,
                'nomor_register' => $no_registrasi,
                'tanggal_kujungan' => $request->tanggal_kunjungan,
                'register_id' => $pendaftaran->id,
                'status_panggil' => 0, // 0 = pendaftaran , 1 = perawat, 2 = dokter ,3 = selesai
                'status_pendaftaran' => 1, // 0 = batal, 1 = pendaftaran , 2 = hadir
                'Status_aplikasi' => 1, // 1 = app manual , 2 = app Onlain ,3 = bpjs
            ]);

            $penjamin = penjamin::find($request->penjamin_id);
            if ($penjamin->nama == 'BPJS') {

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

                $this->PcareController->post_ws_antria_bpjs($databpjs);
            }
            return response()->json([
                'success' => true,
                'message' => 'Pasien berhasil didaftarkan.',
                'noantrian' => $antrianBaru,
                'data' => $data
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }


    public function pendaftaranbatal(Request $request)
    {
        try {

            $pendaftaran = Pendaftaran_rawat_jalan_status::find($request->batalid_delete);

            // Pastikan data ditemukan
            if (!$pendaftaran) {
                return redirect()->back()->with('error', 'Pendaftaran tidak ditemukan.');
            }


            $datapendaftaran = Pendaftaran_rawat_jalan::where('nomor_register', $pendaftaran->nomor_register)
                ->where('tanggal_kujungan', $pendaftaran->tanggal_kujungan)
                ->first();

            $penjamin = penjamin::find($datapendaftaran->Penjamin);
            if ($penjamin->nama == 'BPJS') {

                $poli = poli::find($datapendaftaran->poli_id)->first();

                $databpjs = [
                    "tanggalperiksa" => Carbon::parse($pendaftaran->tanggal_kunjungan)->format('Y-m-d'),
                    "kodepoli" => $poli->kode,
                    "nomorkartu" => $datapendaftaran->pasien->no_bpjs,
                    "alasan" => $request->alasanpembatalan,
                ];

                $this->PcareController->delete_ws_antria_bpjs($databpjs);
            }


            // Perbarui status_pendaftaran menjadi 0 (batal)
            $pendaftaran->status_pendaftaran = 0;
            $pendaftaran->save();

            $pemeriksaan = pelayanan::where('nomor_register', $pendaftaran->nomor_register)
                ->where('tanggal_kujungan', $pendaftaran->tanggal_kujungan)
                ->where('pasien_id', $pendaftaran->pasien_id)
                ->first();

            if ($pemeriksaan) {
                $pemeriksaan->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil disimpan.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function pendaftaranupdokter(Request $request)
    {
        try {

            $pendaftaran = Pendaftaran_rawat_jalan::find($request->rubahdokter_id);

            // Pastikan data ditemukan
            if (!$pendaftaran) {
                return redirect()->back()->with('error', 'Pendaftaran tidak ditemukan.');
            }

            // Perbarui status_pendaftaran menjadi 0 (batal)
            $pendaftaran->dokter_id = $request->dokter_id_update;
            $pendaftaran->save();

            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil disimpan.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function pendaftaranhadir(Request $request)
    {
        try {

            $pendaftaran = Pendaftaran_rawat_jalan_status::find($request->hadirid_delete);

            // Pastikan data ditemukan
            if (!$pendaftaran) {
                return redirect()->back()->with('error', 'Pendaftaran tidak ditemukan.');
            }

            $datapendaftaran = Pendaftaran_rawat_jalan::where('nomor_register', $pendaftaran->nomor_register)
                ->where('tanggal_kujungan', $pendaftaran->tanggal_kujungan)
                ->first();


            pelayanan::updateOrCreate([
                'nomor_rm' => $datapendaftaran->nomor_rm,
                'pasien_id' => $datapendaftaran->pasien_id,
                'nomor_register' => $datapendaftaran->nomor_register,
                'tanggal_kujungan' => $datapendaftaran->tanggal_kujungan,
                'poli_id' => $datapendaftaran->poli_id,
                'dokter_id' => $datapendaftaran->dokter_id,
            ]);

            $penjamin = penjamin::find($datapendaftaran->Penjamin);

            if ($penjamin->nama == 'BPJS') {

                $poli = poli::find($datapendaftaran->poli_id)->first();

                date_default_timezone_set('UTC');
                $Timestamp = strval(time() - strtotime('1970-01-01 00:00:00'));
                $newTimestamp = $Timestamp * 1000;

                $databpjs = [
                    "tanggalperiksa" => Carbon::parse($pendaftaran->tanggal_kunjungan, 'Asia/Jakarta')->format('Y-m-d'),
                    "kodepoli" => $poli->kode,
                    "nomorkartu" => $datapendaftaran->pasien->no_bpjs,
                    "status" => 1,
                    "waktu" => $newTimestamp,
                ];

                $this->PcareController->update_ws_antria_bpjs($databpjs);

                $pendaftaranpcare = [
                    "kdProviderPeserta" => $datapendaftaran->pasien->kodeprovide,
                    "tglDaftar" => Carbon::parse($pendaftaran->tanggal_kunjungan, 'Asia/Jakarta')->format('d-m-Y'),
                    "noKartu" => $datapendaftaran->pasien->no_bpjs,
                    "kdPoli" => $poli->kode,
                    "keluhan" => null,
                    "kunjSakit" => true,
                    "sistole" => 0,
                    "diastole" => 0,
                    "beratBadan" => 0,
                    "tinggiBadan" => 0,
                    "respRate" => 0,
                    "lingkarPerut" => 0,
                    "heartRate" => 0,
                    "rujukBalik" => 0,
                    "kdTkp" => "10",
                ];

                $nourut = $this->PcareController->post_pendaftaran_bpjs($pendaftaranpcare);
                $data = json_decode($nourut->getContent(), true);
                $pendaftaran_nourut = Pendaftaran_rawat_jalan::where('nomor_register', $pendaftaran->nomor_register)
                    ->where('tanggal_kujungan', $pendaftaran->tanggal_kujungan)
                    ->first();
                if ($pendaftaran_nourut) {
                    $pendaftaran_nourut->no_urut = $data['data']['message'];
                    $pendaftaran_nourut->save();
                }
            }
            // Perbarui status_pendaftaran menjadi 0 (batal)
            $pendaftaran->status_pendaftaran = 2;
            $pendaftaran->save();

            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil disimpan.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }
}
