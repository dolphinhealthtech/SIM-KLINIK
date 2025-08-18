<?php

namespace App\Http\Controllers\Module\Pendaftaran;

use App\Http\Controllers\Controller;
use App\Models\dokter;
use App\Models\loket;
use App\Models\pasien;
use App\Models\Pendaftaran_rawat_jalan;
use App\Models\Pendaftaran_rawat_jalan_status;
use App\Models\penjamin;
use App\Models\poli;
use App\Models\pelayanan;
use App\Http\Controllers\Brijing_Intergrasi\Pcare_Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Pendaftaran_Controller extends Controller
{
    protected $PcareController;

    public function __construct(Pcare_Controller $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    public function pendaftaran()
    {
        $title = "Pasien";
        $pasiens = Pasien::all();
        $poli = poli::all();

        $today = Carbon::today(); // atau now()->startOfDay()

        $pendaftaran = Pendaftaran_rawat_jalan::with('status', 'poli', 'dokter.namauser', 'pasien', 'penjamin')
            ->whereHas('status', function ($query) {
                $query->whereIn('status_pendaftaran', ['1', '2']);
            })
            ->whereDate('tanggal_kujungan', '=', $today)
            ->withCount('apotek')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->is_apotek = $item->apotek_count > 0 ? 1 : 0;
                unset($item->apotek_count);
                return $item;
            });



        $pasienallold = Pendaftaran_rawat_jalan::whereDate('tanggal_kujungan', '=', $today)
            ->whereHas('status', function ($query) {
                $query->where('status_pendaftaran', '!=', 0);
            })
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
            ->whereHas('status', function ($query) {
                $query->where('status_pendaftaran', '!=', 0);
            })
            ->select('poli_id', 'dokter_id', DB::raw('count(*) as jumlah'))
            ->groupBy('poli_id', 'dokter_id')
            ->with(['poli', 'dokter'])
            ->get();

        $jumlahDokter = $rekapPerPoliDokter->count(); // Banyaknya dokter unik
        $totalPasien = $rekapPerPoliDokter->sum('jumlah'); // Total pasien dari semua dokter

        $rekapPerDokter = Pendaftaran_rawat_jalan::with(['dokter.namauser', 'poli', 'status'])
            ->whereDate('tanggal_kujungan', $today) // filter kunjungan hari ini
            ->whereHas('dokter.jadwal', function ($query) use ($today) {
                $query->whereDate('start', '=', $today);
            })
            ->whereHas('status', function ($query) {
                $query->whereIn('status_panggil', [0, 1, 2, 3]);
            })

            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('dokter_id')
            ->map(function ($group) {
                $jumlahMenunggu = $group->filter(function ($item) {
                    return $item->status && in_array($item->status->status_panggil, [0, 1]) && $item->status->status_pendaftaran == 2;
                })->count();

                $jumlahDilayani = $group->filter(function ($item) {
                    return $item->status && $item->status->status_panggil == 3;
                })->count();

                // Cari nomor antrian untuk status 2 atau 3
                $pasienAktif = $group->filter(function ($item) {
                    return $item->status && in_array($item->status->status_panggil, [2]);
                })->sortBy('antrian')->first();

                $noAntrian = $pasienAktif ? $pasienAktif->antrian : '-';

                $latest = $group->first();

                // Tentukan status_periksa
                $statusPeriksa = '-';
                if ($latest && $latest->status) {
                    if ($group->contains(function ($item) {
                        return $item->status && in_array($item->status->status_panggil, [0, 1]) && $item->status->status_pendaftaran == 2;
                    })) {
                        $statusPeriksa = 1; //menungu
                    } elseif ($group->contains(function ($item) {
                        return $item->status && $item->status->status_panggil == 2;
                    })) {
                        $statusPeriksa = 2; //periksa
                    } else {
                        $statusPeriksa = 3; //kosong
                    }
                }

                return (object) [
                    'dokter'         => $latest->dokter,
                    'poli'           => $latest->poli,
                    'menunggu'       => $jumlahMenunggu,
                    'dilayani'       => $jumlahDilayani,
                    'no_antrian'     => $noAntrian,
                    'status_periksa' => $statusPeriksa
                ];
            });

        return view('module.pendaftaran.index', compact('title', 'rekapPerDokter', 'jumlahDokter', 'totalPasien', 'rekapPerPoliDokter', 'pendaftaran', 'pasiens', 'penjamin', 'poli', 'pasienallnewnow', 'pasienallold'));
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
            if (!$pasien) {
                return response()->json(['error' => 'Pasien tidak ditemukan'], 404);
            }
            $tanggalKunjungan = Carbon::parse($request->tanggal_kunjungan)->toDateString();

            // ✅ Cek kalau penjamin adalah BPJS
            $penjamin = Penjamin::find($request->penjamin_id);
            if ($penjamin && strtoupper($penjamin->nama) === "BPJS") {
                $sudahDaftar = Pendaftaran_rawat_jalan::where('pasien_id', $pasien->id)
                    ->where('Penjamin', $penjamin->id)
                    ->whereDate('tanggal_kujungan', $tanggalKunjungan)
                    ->exists();

                if ($sudahDaftar) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pasien sudah terdaftar dengan BPJS pada tanggal tersebut.'
                    ], 409);
                }
            }

            $tanggal = Carbon::parse($request->tanggal_kunjungan);
            $tanggalKode = $tanggal->format('y') . str_pad($tanggal->dayOfYear, 3, '0', STR_PAD_LEFT);
            $angkaAcak = mt_rand(1000, 9999);
            $no_registrasi = $angkaAcak . '-' . $tanggalKode;

            $antrian = Loket::where('poli_id', $request->poli_id)->first();
            if (!$antrian) {
                return response()->json(['error' => 'Loket tidak ditemukan untuk poli ini'], 404);
            }

            $today = Carbon::today();
            $last = Pendaftaran_rawat_jalan::where('antrian', 'like', $antrian->nama . '-%')
                ->whereDate('created_at', $today)
                ->orderBy('created_at', 'desc')
                ->first();
            $nextNumber = $last ? ((int) str_replace($antrian->nama . '-', '', $last->antrian)) + 1 : 1;
            $antrianBaru = $antrian->nama . '-' . $nextNumber;

            // Simpan ke database
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
                'status_panggil' => 0,
                'status_pendaftaran' => 1,
                'Status_aplikasi' => 1,
            ]);

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
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan pada server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function pendaftaranbatalpcare(Request $request)
    {
        try {

            $pendaftaran = Pendaftaran_rawat_jalan_status::find($request->batalid_delete);

            // Pastikan data ditemukan
            if (!$pendaftaran) {
                return redirect()->back()->with('error', 'Pendaftaran tidak ditemukan.');
            }


            $datapendaftaran = Pendaftaran_rawat_jalan::where('nomor_register', $pendaftaran->nomor_register)
                ->first();

            $penjamin = penjamin::find($datapendaftaran->Penjamin);
            if ($penjamin->nama == 'BPJS') {

                $poli = poli::find($datapendaftaran->poli_id)->first();

                $databpjs = [
                    "tanggalperiksa" => Carbon::parse($pendaftaran->tanggal_kunjungan)->format('d-m-Y'),
                    "kodepoli" => $poli->kode,
                    "nomorkartu" => $datapendaftaran->pasien->no_bpjs,
                    "nourut" => $datapendaftaran->no_urut,
                ];

                $this->PcareController->delete_pendaftaran($databpjs);
            }


            $pemeriksaan = pelayanan::where('nomor_register', $pendaftaran->nomor_register)
                ->where('tanggal_kujungan', $pendaftaran->tanggal_kujungan)
                ->where('pasien_id', $pendaftaran->pasien_id)
                ->first();

            if ($pemeriksaan) {
                $pemeriksaan->pendaftaran?->delete();
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


            $pemeriksaan = Pendaftaran_rawat_jalan::where('nomor_register', $pendaftaran->nomor_register)
                ->where('tanggal_kujungan', $pendaftaran->tanggal_kujungan)
                ->where('pasien_id', $pendaftaran->pasien_id)
                ->first();

            if ($pemeriksaan) {
                // Hapus status terkait
                $pemeriksaan->status()?->delete();

                // Hapus pendaftaran
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

                $poli = poli::where('id', $datapendaftaran->poli_id)->first();


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

                if ((int)$pendaftaran->Status_aplikasi === 2) {
                    $this->PcareController->update_ws_antria_bpjs($databpjs);
                }

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

                try {
                    $response = $this->PcareController->post_pendaftaran_bpjs($pendaftaranpcare);

                    if (in_array((int)$response->getStatusCode(), [200, 201])) {
                        $data = json_decode($response->getContent(), true);

                        if (isset($data['data']['message'])) {
                            $no_urut = $data['data']['message'];

                            $pendaftaran_nourut = Pendaftaran_rawat_jalan::where('nomor_register', $pendaftaran->nomor_register)
                                ->first();

                            Log::info('Data pendaftaran_nourut dan no_urut', [
                                'pendaftaran_nourut' => $pendaftaran_nourut,
                                'no_urut' => $no_urut
                            ]);

                            if ($pendaftaran_nourut) {
                                $pendaftaran_nourut->update([
                                    'no_urut' => $no_urut
                                ]);
                                // Update status_pendaftaran jika sukses
                                $pendaftaran->update([
                                    'status_pendaftaran' => 2
                                ]);
                            } else {
                                Log::warning('Data pendaftaran tidak ditemukan untuk update no_urut', [
                                    'nomor_register' => $pendaftaran->nomor_register,
                                    'tanggal_kujungan' => $pendaftaran->tanggal_kunjungan
                                ]);
                            }
                        } else {
                            Log::warning('Pcare response tidak memiliki message.', $data);
                            return response()->json([
                                'success' => false,
                                'message' => 'Pendaftaran gagal: response dari BPJS tidak valid.'
                            ], 500);
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Gagal mendaftarkan ke BPJS. Status: ' . $response->getStatusCode()
                        ], 500);
                    }
                } catch (\Exception $e) {
                    Log::error('Gagal post_pendaftaran_bpjs: ' . $e->getMessage());
                }
            } else {
                // Perbarui status_pendaftaran menjadi 0 (batal)
                $pendaftaran->status_pendaftaran = 2;
                $pendaftaran->save();
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
            // Ambil data pendaftaran sesuai nomor_register
            $datapendaftaran = Pendaftaran_rawat_jalan::where('nomor_register', $request->rubahdokter_id)->first();

            if (!$datapendaftaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pelayanan tidak ditemukan.'
                ], 404);
            }


            // Update dokter
            $datapendaftaran->dokter_id = $request->dokter_id_update;
            $datapendaftaran->save();

            return response()->json([
                'success' => true,
                'message' => 'Data dokter berhasil diupdate.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Catch error lain jika terjadi
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
