<?php

namespace App\Http\Controllers;

use App\Models\dokter;
use App\Models\dokter_jadwal;
use App\Models\pasien;
use App\Models\Pendaftaran_rawat_jalan;
use App\Models\kasir;
use App\Models\pelayanan;
use App\Models\pelayanan_rujukan;
use App\Models\pelayanan_soap_perawat;
use App\Models\staff;
use App\Models\posker;
use App\Models\apotek;
use App\Models\apotek_prebayar;
use App\Models\gudang_barang_stok;
use App\Models\gudang_barang_stok_utama;
use App\Models\gudang_klinik_request;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class dashboard extends Controller
{
    /**
     * Display the dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            if ($user->hasAnyRole(['Administrasi'])) {
                return view('module.dashboard.administrasi.index');
            }
            if ($user->hasAnyRole(['Apoteker'])) {
                return view('module.dashboard.apoteker.index');
            }
            if ($user->hasAnyRole(['Dokter'])) {
                $dokterId = dokter::where('users', $user->id)->value('id');
                return view('module.dashboard.dokter.index', compact('dokterId'));
            }
            if ($user->hasAnyRole(['Gudang'])) {
                return view('module.dashboard.gudang.index');
            }
            if ($user->hasAnyRole(['Gudang Utama', 'Gudang_Utama', 'GudangUtama'])) {
                return view('module.dashboard.gudang-utama.index');
            }
            if ($user->hasAnyRole(['Kasir'])) {
                return view('module.dashboard.kasir.index');
            }
            if ($user->hasAnyRole(['Manajemen'])) {
                return view('module.dashboard.manajemen.index');
            }
            if ($user->hasAnyRole(['Pasien'])) {
                return view('module.dashboard.pasien.index');
            }
            if ($user->hasAnyRole(['Perawat'])) {
                return view('module.dashboard.perawat.index');
            }
            if ($user->hasAnyRole(['Personalia'])) {
                return view('module.dashboard.personalia.index');
            }
            if ($user->hasAnyRole(['Registrasi'])) {
                return view('module.dashboard.registrasi.index');
            }
        }

        $today = Carbon::today();
        $now = Carbon::now();

        $datapasien = pasien::count();

        // ================================
        // 1. Dokter Aktif
        // ================================

        // Dokter yang sedang bertugas saat ini
        $dokterHariIni = dokter_jadwal::whereDate('start', $today)
            ->where('start', '<=', $now)
            ->where('end', '>=', $now)
            ->with('dokter')
            ->get()
            ->filter(fn($item) => $item->dokter && $item->dokter->verifikasi == 2)
            ->map(function ($item) {
                return (object)[
                    'nama' => $item->dokter->namauser->name,
                    'spesialisasi' => $item->dokter->namapoli->nama,
                    'start' => Carbon::parse($item->start)->format('H:i'),
                    'end' => Carbon::parse($item->end)->format('H:i')
                ];
            });

        $datadokter = $dokterHariIni->isEmpty() ? 0 : $dokterHariIni->count();

        $datakunjungan = Pendaftaran_rawat_jalan::whereDate('tanggal_kujungan', $today)
            ->whereHas('status', function ($query) {
                $query->where('status_pendaftaran', '!=', 0);
            })
            ->count();

        // ================================
        // 2. Pendapatan Harian
        // ================================
        $pendapatanHariIni = DB::table('kasirs')
            ->whereDate('created_at', $today)
            ->sum('total');

        // ================================
        // 3. Pendapatan Detail (Jasa & Obat)
        // ================================
        $totalJasa = DB::table('kasir_tindakan_lunas')
            ->whereDate('created_at', $today)
            ->sum('total');

        $totalObat = DB::table('kasir_apotek_lunas')
            ->whereDate('created_at', $today)
            ->sum('total');

        $dataKasirs = DB::table('kasirs')
            ->whereDate('created_at', $today)
            ->get(['administrasi', 'materai']);

        $totalAdministrasi = 0;
        $totalMaterai = 0;

        foreach ($dataKasirs as $kasir) {
            $administrasi = str_replace('.', '', $kasir->administrasi ?? '0');
            $materai = str_replace('.', '', $kasir->materai ?? '0');

            $totalAdministrasi += floatval($administrasi);
            $totalMaterai += floatval($materai);
        }

        $totalJasaGabungan = ($totalJasa ?? 0) + $totalAdministrasi + $totalMaterai;
        $totalPendapatan = $totalJasaGabungan + ($totalObat ?? 0);

        $persenJasa = $totalPendapatan > 0 ? round(($totalJasaGabungan / $totalPendapatan) * 100) : 0;
        $persenObat = $totalPendapatan > 0 ? round(($totalObat / $totalPendapatan) * 100) : 0;

        $kategoriAktif = 0;
        if ($totalJasaGabungan > 0) $kategoriAktif++;
        if ($totalObat > 0) $kategoriAktif++;

        // ================================
        // 4. Pendapatan Bulanan
        // ================================
        $dataBulanan = DB::table('kasirs')
            ->selectRaw('MONTH(created_at) as bulan, SUM(total) as total')
            ->whereYear('created_at', $now->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        $pendapatanBulanan = array_fill(1, 12, 0);
        foreach ($dataBulanan as $row) {
            $pendapatanBulanan[(int)$row->bulan] = (float)$row->total;
        }

        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $bulananTotals = array_values($pendapatanBulanan);

        // ================================
        // 5. Kirim Semua ke View
        // ================================
        return view('module.dashboard.super-admin.index', compact(
            'datapasien',
            'datadokter',
            'datakunjungan',
            'dokterHariIni',
            'pendapatanHariIni',
            'totalJasaGabungan',
            'totalObat',
            'totalPendapatan',
            'persenJasa',
            'persenObat',
            'kategoriAktif',
            'bulanLabels',
            'bulananTotals'
        ));
    }



    public function kunjunganHarian()
    {
        $start = Carbon::today()->subDays(6); // 6 hari ke belakang
        $end = Carbon::today()->endOfDay(); // hari ini

        // Ambil data kunjungan per hari
        $kunjungan = Pendaftaran_rawat_jalan::selectRaw('DATE(tanggal_kujungan) as tanggal, COUNT(*) as jumlah')
            ->whereBetween('tanggal_kujungan', [$start, $end])
            ->whereHas('status', function ($query) {
                $query->where('status_pendaftaran', '!=', 0);
            })
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal'); // hasilnya seperti ['2025-06-11' => {jumlah: 12}, ...]

        // Siapkan label dan data per hari
        $labels = [];
        $data = [];

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $labels[] = $date->format('l'); // e.g. "Senin", "Selasa"
            $data[] = $kunjungan->get($date->toDateString())->jumlah ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }

    public function kunjunganPerPoli()
    {
        $data = Pendaftaran_rawat_jalan::whereHas('status', function ($query) {
            $query->where('status_pendaftaran', '!=', 0);
        })
            ->whereMonth('tanggal_kujungan', Carbon::now()->month)
            ->whereYear('tanggal_kujungan', Carbon::now()->year)
            ->with('poli')
            ->get()
            ->groupBy('poli.nama')
            ->map(function ($group) {
                return [
                    'nama' => $group->first()->poli->nama,
                    'jumlah' => $group->count(),
                ];
            })
            ->sortByDesc('jumlah')
            ->values();


        return response()->json([
            'labels' => $data->pluck('nama'),
            'data' => $data->pluck('jumlah'),
        ]);
    }

    // ================================
    // ADMINISTRASI DASHBOARD ENDPOINTS
    // ================================
    public function ringkasanAdministrasi()
    {
        $tanggalHariIni = Carbon::today();
        $waktuSekarang = Carbon::now();

        $jumlahHariIni = Pendaftaran_rawat_jalan::whereDate('tanggal_kujungan', $tanggalHariIni)
            ->whereHas('status', function ($query) {
                $query->where('status_pendaftaran', '!=', 0);
            })
            ->count();

        $jumlahBulanIni = Pendaftaran_rawat_jalan::whereMonth('tanggal_kujungan', $waktuSekarang->month)
            ->whereYear('tanggal_kujungan', $waktuSekarang->year)
            ->whereHas('status', function ($query) {
                $query->where('status_pendaftaran', '!=', 0);
            })
            ->count();

        return response()->json([
            'hari_ini' => $jumlahHariIni,
            'bulan_ini' => $jumlahBulanIni,
        ]);
    }

    public function jadwalKunjunganHariIni()
    {
        $tanggalHariIni = Carbon::today();

        $jadwal = Pendaftaran_rawat_jalan::with(['pasien', 'poli', 'dokter.namauser'])
            ->whereDate('tanggal_kujungan', $tanggalHariIni)
            ->whereHas('status', function ($query) {
                $query->where('status_pendaftaran', '!=', 0);
            })
            ->orderBy('no_urut')
            ->get()
            ->map(function ($row) {
                $namaDokter = null;
                if ($row->dokter) {
                    if (method_exists($row->dokter, 'namauser') && $row->dokter->relationLoaded('namauser') && $row->dokter->namauser) {
                        $namaDokter = $row->dokter->namauser->name;
                    } elseif (property_exists($row->dokter, 'nama')) {
                        $namaDokter = $row->dokter->nama;
                    }
                }

                return [
                    'waktu' => $row->tanggal_kujungan ? Carbon::parse($row->tanggal_kujungan)->format('H:i') : null,
                    'pasien' => $row->pasien->nama ?? null,
                    'poli' => $row->poli->nama ?? null,
                    'dokter' => $namaDokter,
                ];
            });

        return response()->json(['data' => $jadwal]);
    }

    public function statusPembayaranHariIni()
    {
        $tanggalHariIni = Carbon::today();

        $noRawatHariIni = Pendaftaran_rawat_jalan::whereDate('tanggal_kujungan', $tanggalHariIni)
            ->whereHas('status', function ($query) {
                $query->where('status_pendaftaran', '!=', 0);
            })
            ->pluck('nomor_register');

        $noRawatLunas = kasir::whereDate('created_at', $tanggalHariIni)
            ->whereIn('no_rawat', $noRawatHariIni)
            ->distinct()
            ->pluck('no_rawat');

        $jumlahLunas = $noRawatLunas->count();
        $jumlahTotal = $noRawatHariIni->count();
        $jumlahBelumLunas = max($jumlahTotal - $jumlahLunas, 0);

        return response()->json([
            'lunas' => $jumlahLunas,
            'belum_lunas' => $jumlahBelumLunas,
            'total' => $jumlahTotal,
        ]);
    }

    public function dataBelumLengkap()
    {
        $tanggalHariIni = Carbon::today();

        $items = Pendaftaran_rawat_jalan::with('pasien')
            ->whereDate('tanggal_kujungan', $tanggalHariIni)
            ->whereHas('status', function ($query) {
                $query->where('status_pendaftaran', '!=', 0);
            })
            ->get()
            ->filter(function ($row) {
                $p = $row->pasien;
                if (!$p) return false;
                $nikKosong = empty($p->nik);
                $alamatKosong = empty($p->alamat);
                return $nikKosong || $alamatKosong;
            })
            ->map(function ($row) {
                return [
                    'nama' => $row->pasien->nama ?? 'Tidak diketahui',
                    'nik' => $row->pasien->nik ?? null,
                    'alamat' => $row->pasien->alamat ?? null,
                ];
            })
            ->values();

        return response()->json([
            'count' => $items->count(),
            'data' => $items,
        ]);
    }

    // ================================
    // DOKTER DASHBOARD ENDPOINTS
    // ================================
    public function jadwalDokterHariIni()
    {
        $user = Auth::user();
        $dokterId = dokter::where('users', $user->id)->value('id');
        if (!$dokterId) {
            return response()->json(['data' => []]);
        }

        $tanggalHariIni = Carbon::today();
        $waktuSekarang = Carbon::now();

        $jadwal = dokter_jadwal::where('dokter_id', $dokterId)
            ->whereDate('start', $tanggalHariIni)
            ->where('end', '>=', $waktuSekarang)
            ->orderBy('start')
            ->get()
            ->map(function ($row) {
                return [
                    'mulai' => Carbon::parse($row->start)->format('H:i'),
                    'selesai' => Carbon::parse($row->end)->format('H:i'),
                ];
            });

        return response()->json(['data' => $jadwal]);
    }

    public function antrianDokterHariIni()
    {
        $user = Auth::user();
        $dokterId = dokter::where('users', $user->id)->value('id');
        if (!$dokterId) {
            return response()->json(['data' => []]);
        }

        $daftar = pelayanan::with(['pasien', 'poli', 'pendaftaran.status'])
            ->where('dokter_id', $dokterId)
            ->whereDate('created_at', Carbon::today())
            ->get()
            ->filter(function ($row) {
                $status = $row->pendaftaran->status->status_panggil ?? 0;
                return $status !== 3; // belum complete
            })
            ->map(function ($row) {
                $status = $row->pendaftaran->status->status_panggil ?? 0;
                $statusLabel = match ($status) {
                    1 => 'Dipanggil',
                    2 => 'Dalam Pemeriksaan',
                    3 => 'Selesai',
                    default => 'Menunggu',
                };
                return [
                    'no_rawat' => $row->nomor_register,
                    'pasien' => $row->pasien->nama ?? '-',
                    'poli' => $row->poli->nama ?? '-',
                    'status' => $statusLabel,
                ];
            })
            ->values();

        return response()->json(['data' => $daftar]);
    }

    public function rmeTerbaruDokter()
    {
        $user = Auth::user();
        $dokterId = dokter::where('users', $user->id)->value('id');
        if (!$dokterId) {
            return response()->json(['data' => []]);
        }

        $rows = DB::table('pelayanan_soap_dokters as sd')
            ->join('pelayanans as p', 'p.nomor_register', '=', 'sd.no_rawat')
            ->leftJoin('pasiens as ps', 'ps.no_rm', '=', 'sd.nomor_rm')
            ->where('p.dokter_id', $dokterId)
            ->orderByDesc('sd.created_at')
            ->limit(10)
            ->get(['sd.no_rawat', 'sd.nomor_rm', 'ps.nama as pasien', 'sd.created_at'])
            ->map(function ($row) {
                return [
                    'no_rawat' => $row->no_rawat,
                    'no_rm' => $row->nomor_rm,
                    'pasien' => $row->pasien,
                    'tanggal' => Carbon::parse($row->created_at)->format('d-m-Y H:i'),
                ];
            });

        return response()->json(['data' => $rows]);
    }

    public function rujukanTerbaruDokter()
    {
        $user = Auth::user();
        $dokterId = dokter::where('users', $user->id)->value('id');
        if (!$dokterId) {
            return response()->json(['data' => []]);
        }

        $rows = pelayanan_rujukan::query()
            ->join('pelayanans as p', 'p.nomor_register', '=', 'pelayanan_rujukans.no_rawat')
            ->leftJoin('pasiens as ps', 'ps.no_rm', '=', 'pelayanan_rujukans.nomor_rm')
            ->where('p.dokter_id', $dokterId)
            ->orderByDesc('pelayanan_rujukans.created_at')
            ->limit(10)
            ->get([
                'pelayanan_rujukans.no_rawat',
                'pelayanan_rujukans.nomor_rm',
                'ps.nama as pasien',
                'pelayanan_rujukans.tanggal_rujukan',
                'pelayanan_rujukans.tujuan_rujukan',
            ])
            ->map(function ($row) {
                return [
                    'no_rawat' => $row->no_rawat,
                    'no_rm' => $row->nomor_rm,
                    'pasien' => $row->pasien,
                    'tanggal' => $row->tanggal_rujukan ? Carbon::parse($row->tanggal_rujukan)->format('d-m-Y') : '-',
                    'tujuan' => $row->tujuan_rujukan,
                ];
            });

        return response()->json(['data' => $rows]);
    }

    public function ringkasanDokter()
    {
        $user = Auth::user();
        $dokterId = dokter::where('users', $user->id)->value('id');
        if (!$dokterId) {
            return response()->json([
                'pasien_hari_ini' => 0,
                'total_30_hari' => 0,
                'labels' => [],
                'data' => [],
                'rujukan_hari_ini' => 0,
                'rme_hari_ini' => 0,
                'antrian_menunggu' => 0,
            ]);
        }

        $hariIni = Carbon::today();
        $mulai = Carbon::today()->subDays(29);

        $pasienHariIni = pelayanan::where('dokter_id', $dokterId)
            ->whereDate('created_at', $hariIni)
            ->whereHas('pendaftaran.status', function ($q) {
                $q->where('status_pendaftaran', '!=', 0);
            })
            ->count();

        $ringkasan = DB::table('pelayanans')
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah')
            ->where('dokter_id', $dokterId)
            ->whereBetween('created_at', [$mulai->copy()->startOfDay(), $hariIni->copy()->endOfDay()])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $labels = [];
        $data = [];
        $cursor = $mulai->copy();
        while ($cursor->lte($hariIni)) {
            $tgl = $cursor->toDateString();
            $labels[] = $cursor->format('d M');
            $data[] = (int) ($ringkasan[$tgl]->jumlah ?? 0);
            $cursor->addDay();
        }

        $total30 = array_sum($data);

        $rujukanHariIni = pelayanan_rujukan::join('pelayanans as p', 'p.nomor_register', '=', 'pelayanan_rujukans.no_rawat')
            ->where('p.dokter_id', $dokterId)
            ->whereDate('pelayanan_rujukans.created_at', $hariIni)
            ->count();

        $rmeHariIni = DB::table('pelayanan_soap_dokters as sd')
            ->join('pelayanans as p', 'p.nomor_register', '=', 'sd.no_rawat')
            ->where('p.dokter_id', $dokterId)
            ->whereDate('sd.created_at', $hariIni)
            ->count();

        $statusCounts = pelayanan::with('pendaftaran.status')
            ->where('dokter_id', $dokterId)
            ->whereDate('created_at', $hariIni)
            ->get()
            ->reduce(function ($acc, $row) {
                $status = (int) ($row->pendaftaran->status->status_panggil ?? 0);
                // 0: Menunggu, 1: Dipanggil, 2: Dalam Pemeriksaan, 3: Selesai
                if ($status === 0) $acc['menunggu']++;
                elseif ($status === 1) $acc['dipanggil']++;
                elseif ($status === 2) $acc['pemeriksaan']++;
                elseif ($status === 3) $acc['selesai']++;
                else $acc['menunggu']++;
                return $acc;
            }, ['menunggu' => 0, 'dipanggil' => 0, 'pemeriksaan' => 0, 'selesai' => 0]);

        return response()->json([
            'pasien_hari_ini' => $pasienHariIni,
            'total_30_hari' => $total30,
            'labels' => $labels,
            'data' => $data,
            'rujukan_hari_ini' => $rujukanHariIni,
            'rme_hari_ini' => $rmeHariIni,
            'antrian_menunggu' => $statusCounts['menunggu'],
            'status_menunggu' => $statusCounts['menunggu'],
            'status_dipanggil' => $statusCounts['dipanggil'],
            'status_pemeriksaan' => $statusCounts['pemeriksaan'],
            'status_selesai' => $statusCounts['selesai'],
        ]);
    }

    // ==================================
    // KASIR DASHBOARD ENDPOINTS
    // ==================================
    public function ringkasanKasir()
    {
        $hariIni = Carbon::today();

        $totalPendapatan = DB::table('kasirs')
            ->whereDate('created_at', $hariIni)
            ->sum('total');

        $jumlahTransaksi = DB::table('kasirs')
            ->whereDate('created_at', $hariIni)
            ->count();

        $dataKasirs = DB::table('kasirs')
            ->whereDate('created_at', $hariIni)
            ->get(['administrasi', 'materai']);

        $totalAdministrasi = 0;
        $totalMaterai = 0;
        foreach ($dataKasirs as $kasir) {
            $administrasi = str_replace('.', '', $kasir->administrasi ?? '0');
            $materai = str_replace('.', '', $kasir->materai ?? '0');
            $totalAdministrasi += floatval($administrasi);
            $totalMaterai += floatval($materai);
        }

        $totalJasa = DB::table('kasir_tindakan_lunas')
            ->whereDate('created_at', $hariIni)
            ->sum('total');

        $totalObat = DB::table('kasir_apotek_lunas')
            ->whereDate('created_at', $hariIni)
            ->sum('total');

        $totalJasaGabungan = ($totalJasa ?? 0) + $totalAdministrasi + $totalMaterai;

        $rataRata = $jumlahTransaksi > 0 ? round($totalPendapatan / $jumlahTransaksi) : 0;

        return response()->json([
            'pendapatan_hari_ini' => (float) $totalPendapatan,
            'transaksi_hari_ini' => (int) $jumlahTransaksi,
            'rata_rata' => (float) $rataRata,
            'jasa_hari_ini' => (float) $totalJasaGabungan,
            'obat_hari_ini' => (float) ($totalObat ?? 0),
        ]);
    }

    public function komposisiPendapatanHariIni()
    {
        $hariIni = Carbon::today();

        $dataKasirs = DB::table('kasirs')
            ->whereDate('created_at', $hariIni)
            ->get(['administrasi', 'materai']);

        $totalAdministrasi = 0;
        $totalMaterai = 0;
        foreach ($dataKasirs as $kasir) {
            $administrasi = str_replace('.', '', $kasir->administrasi ?? '0');
            $materai = str_replace('.', '', $kasir->materai ?? '0');
            $totalAdministrasi += floatval($administrasi);
            $totalMaterai += floatval($materai);
        }

        $totalJasa = DB::table('kasir_tindakan_lunas')
            ->whereDate('created_at', $hariIni)
            ->sum('total');

        $totalObat = DB::table('kasir_apotek_lunas')
            ->whereDate('created_at', $hariIni)
            ->sum('total');

        $jasaGabungan = ($totalJasa ?? 0) + $totalAdministrasi + $totalMaterai;
        $total = $jasaGabungan + ($totalObat ?? 0);

        $persenJasa = $total > 0 ? round(($jasaGabungan / $total) * 100) : 0;
        $persenObat = $total > 0 ? round((($totalObat ?? 0) / $total) * 100) : 0;

        return response()->json([
            'jasa' => (float) $jasaGabungan,
            'obat' => (float) ($totalObat ?? 0),
            'total' => (float) $total,
            'persen_jasa' => $persenJasa,
            'persen_obat' => $persenObat,
        ]);
    }

    public function pendapatanBulananKasir()
    {
        $now = Carbon::now();

        $dataBulanan = DB::table('kasirs')
            ->selectRaw('MONTH(created_at) as bulan, SUM(total) as total')
            ->whereYear('created_at', $now->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        $pendapatanBulanan = array_fill(1, 12, 0);
        foreach ($dataBulanan as $row) {
            $pendapatanBulanan[(int)$row->bulan] = (float)$row->total;
        }

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $data = array_values($pendapatanBulanan);

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    public function transaksiTerbaruKasir()
    {
        $hariIni = Carbon::today();

        $rows = DB::table('kasirs as k')
            ->leftJoin('pelayanans as p', 'p.nomor_register', '=', 'k.no_rawat')
            ->leftJoin('pasiens as ps', 'ps.no_rm', '=', 'p.nomor_rm')
            ->whereDate('k.created_at', $hariIni)
            ->orderByDesc('k.created_at')
            ->limit(10)
            ->get([
                'k.no_rawat',
                'ps.nama as pasien',
                'k.total',
                'k.created_at',
            ])
            ->map(function ($row) {
                return [
                    'no_rawat' => $row->no_rawat,
                    'pasien' => $row->pasien ?? '-',
                    'total' => (float) ($row->total ?? 0),
                    'waktu' => $row->created_at ? Carbon::parse($row->created_at)->format('H:i') : '-',
                ];
            });

        return response()->json(['data' => $rows]);
    }

    // ==================================
    // PERAWAT DASHBOARD ENDPOINTS
    // ==================================
    public function ringkasanPerawat()
    {
        $hariIni = Carbon::today();

        $statusCounts = pelayanan::with('pendaftaran.status')
            ->whereDate('created_at', $hariIni)
            ->get()
            ->reduce(function ($acc, $row) {
                $status = (int) ($row->pendaftaran->status->status_panggil ?? 0);
                if ($status === 0) $acc['menunggu']++;
                elseif ($status === 1) $acc['dipanggil']++;
                elseif ($status === 2) $acc['pemeriksaan']++;
                elseif ($status === 3) $acc['selesai']++;
                else $acc['menunggu']++;
                return $acc;
            }, ['menunggu' => 0, 'dipanggil' => 0, 'pemeriksaan' => 0, 'selesai' => 0]);

        $totalAntrian = array_sum($statusCounts);

        return response()->json([
            'antrian_total' => $totalAntrian,
            'status_menunggu' => $statusCounts['menunggu'],
            'status_dipanggil' => $statusCounts['dipanggil'],
            'status_pemeriksaan' => $statusCounts['pemeriksaan'],
            'status_selesai' => $statusCounts['selesai'],
        ]);
    }

    public function antrianPerawatHariIni()
    {
        $rows = pelayanan::with(['pasien', 'poli', 'dokter.namauser', 'pendaftaran.status'])
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at')
            ->get()
            ->map(function ($row) {
                $status = (int) ($row->pendaftaran->status->status_panggil ?? 0);
                $statusLabel = match ($status) {
                    1 => 'Dipanggil',
                    2 => 'Dalam Pemeriksaan',
                    3 => 'Selesai',
                    default => 'Menunggu',
                };
                $namaDokter = null;
                if ($row->dokter) {
                    if (method_exists($row->dokter, 'namauser') && $row->dokter->relationLoaded('namauser') && $row->dokter->namauser) {
                        $namaDokter = $row->dokter->namauser->name;
                    } elseif (property_exists($row->dokter, 'nama')) {
                        $namaDokter = $row->dokter->nama;
                    }
                }
                return [
                    'no_rawat' => $row->nomor_register,
                    'pasien' => $row->pasien->nama ?? '-',
                    'poli' => $row->poli->nama ?? '-',
                    'dokter' => $namaDokter,
                    'status' => $statusLabel,
                ];
            })
            ->values();

        return response()->json(['data' => $rows]);
    }

    public function soapPerawatTerbaru()
    {
        $rows = DB::table('pelayanan_soap_perawats as sp')
            ->join('pelayanans as p', 'p.nomor_register', '=', 'sp.no_rawat')
            ->leftJoin('pasiens as ps', 'ps.no_rm', '=', 'sp.nomor_rm')
            ->orderByDesc('sp.created_at')
            ->limit(10)
            ->get([
                'sp.no_rawat', 'sp.nomor_rm', 'ps.nama as pasien', 'sp.created_at', 'sp.user_input_name'
            ])
            ->map(function ($row) {
                return [
                    'no_rawat' => $row->no_rawat,
                    'no_rm' => $row->nomor_rm,
                    'pasien' => $row->pasien,
                    'perawat' => $row->user_input_name,
                    'waktu' => $row->created_at ? Carbon::parse($row->created_at)->format('d-m-Y H:i') : '-',
                ];
            });

        return response()->json(['data' => $rows]);
    }

    // ==================================
    // PERSONALIA DASHBOARD ENDPOINTS
    // ==================================
    public function ringkasanPersonalia()
    {
        $now = Carbon::now();
        $totalStaff = DB::table('staff')->count();
        $staffBaruBulanIni = DB::table('staff')
            ->whereMonth('tgl_masuk', $now->month)
            ->whereYear('tgl_masuk', $now->year)
            ->count();
        $belumVerif = DB::table('staff')->where('verifikasi', 1)->count();
        $sudahVerif = DB::table('staff')->where('verifikasi', 2)->count();

        return response()->json([
            'total_staff' => $totalStaff,
            'staff_bulan_ini' => $staffBaruBulanIni,
            'belum_verifikasi' => $belumVerif,
            'sudah_verifikasi' => $sudahVerif,
        ]);
    }

    public function komposisiStatusPegawai()
    {
        $rows = DB::table('staff as s')
            ->leftJoin('poskers as p', 'p.kode', '=', 's.status_pegawaian')
            ->selectRaw('COALESCE(p.nama, s.status_pegawaian) as status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->orderByDesc('jumlah')
            ->get();

        return response()->json([
            'labels' => $rows->pluck('status'),
            'data' => $rows->pluck('jumlah'),
        ]);
    }

    public function rekrutBulananStaff()
    {
        $now = Carbon::now();
        $rows = DB::table('staff')
            ->selectRaw('MONTH(tgl_masuk) as bulan, COUNT(*) as jumlah')
            ->whereYear('tgl_masuk', $now->year)
            ->groupBy(DB::raw('MONTH(tgl_masuk)'))
            ->get();

        $bulanan = array_fill(1, 12, 0);
        foreach ($rows as $row) {
            $bulanan[(int) $row->bulan] = (int) $row->jumlah;
        }
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $data = array_values($bulanan);

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    public function staffTerbaru()
    {
        $rows = DB::table('staff as s')
            ->leftJoin('users as u', 'u.id', '=', 's.users')
            ->leftJoin('poskers as p', 'p.kode', '=', 's.status_pegawaian')
            ->orderByDesc('s.created_at')
            ->limit(10)
            ->get([
                'u.name as nama', 's.nik', 'p.nama as status', 's.tgl_masuk', 's.created_at'
            ])
            ->map(function ($row) {
                return [
                    'nama' => $row->nama ?? '-',
                    'nik' => $row->nik ?? '-',
                    'status' => $row->status ?? '-',
                    'tgl_masuk' => $row->tgl_masuk ? Carbon::parse($row->tgl_masuk)->format('d-m-Y') : '-',
                    'dibuat' => $row->created_at ? Carbon::parse($row->created_at)->format('d-m-Y H:i') : '-',
                ];
            });

        return response()->json(['data' => $rows]);
    }

    // ==================================
    // APOTEKER DASHBOARD ENDPOINTS
    // ==================================
    public function ringkasanApoteker()
    {
        $hariIni = Carbon::today();

        $resepMenunggu = DB::table('pelayanan_soap_dokters')
            ->where('status_apotek', '0')
            ->count();

        $penjualanHariIni = DB::table('apoteks')
            ->whereDate('created_at', $hariIni)
            ->sum('total');

        $transaksiHariIni = DB::table('apoteks')
            ->whereDate('created_at', $hariIni)
            ->count();

        return response()->json([
            'resep_menunggu' => (int) $resepMenunggu,
            'penjualan_hari_ini' => (float) $penjualanHariIni,
            'transaksi_hari_ini' => (int) $transaksiHariIni,
        ]);
    }

    public function penjualanBulananApoteker()
    {
        $now = Carbon::now();
        $rows = DB::table('apoteks')
            ->selectRaw('MONTH(created_at) as bulan, SUM(total) as total')
            ->whereYear('created_at', $now->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        $bulanan = array_fill(1, 12, 0.0);
        foreach ($rows as $row) {
            $bulanan[(int) $row->bulan] = (float) $row->total;
        }
        $labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $data = array_values($bulanan);
        return response()->json(['labels' => $labels, 'data' => $data]);
    }

    public function topObatHariIni()
    {
        $hariIni = Carbon::today()->toDateString();
        $rows = DB::table('apotek_prebayars')
            ->whereDate('tanggal', $hariIni)
            ->select('nama_obat_alkes', DB::raw('SUM(qty) as jumlah'))
            ->groupBy('nama_obat_alkes')
            ->orderByDesc('jumlah')
            ->limit(10)
            ->get();
        return response()->json(['labels' => $rows->pluck('nama_obat_alkes'), 'data' => $rows->pluck('jumlah')]);
    }

    public function resepMenungguApotek()
    {
        $rows = DB::table('pelayanan_soap_dokters as sd')
            ->leftJoin('pasiens as ps', 'ps.no_rm', '=', 'sd.nomor_rm')
            ->where('sd.status_apotek', '0')
            ->orderBy('sd.created_at')
            ->limit(10)
            ->get(['sd.no_rawat', 'sd.nomor_rm', 'ps.nama as pasien', 'sd.created_at'])
            ->map(function ($row) {
                return [
                    'no_rawat' => $row->no_rawat,
                    'no_rm' => $row->nomor_rm,
                    'pasien' => $row->pasien,
                    'waktu' => $row->created_at ? Carbon::parse($row->created_at)->format('H:i') : '-',
                ];
            });
        return response()->json(['data' => $rows]);
    }

    // ==================================
    // GUDANG DASHBOARD ENDPOINTS
    // ==================================
    public function ringkasanGudang()
    {
        $totalItem = DB::table('gudang_barangs')->count();
        $totalStokKlinik = DB::table('gudang_barang_stoks')->sum('qty');
        $totalStokUtama = DB::table('gudang_barang_stok_utamas')->sum('qty');
        $permintaanPending = DB::table('gudang_klinik_requests')->where('status', 0)->count();

        return response()->json([
            'total_item' => (int) $totalItem,
            'stok_klinik' => (int) $totalStokKlinik,
            'stok_utama' => (int) $totalStokUtama,
            'permintaan_pending' => (int) $permintaanPending,
        ]);
    }

    public function pergerakanGudangHariIni()
    {
        $hariIni = Carbon::today()->toDateString();

        $masuk = DB::table('pembelian_details')
            ->whereDate('created_at', $hariIni)
            ->sum('qty');

        $keluar = DB::table('apotek_prebayars')
            ->whereDate('tanggal', $hariIni)
            ->sum('qty');

        $penyesuaianMasuk = DB::table('gudang_penyesuaian_masuks')
            ->whereDate('tanggal', $hariIni)
            ->sum('qty_mutasi');

        $penyesuaianKeluar = DB::table('gudang_penyesuaian_keluars')
            ->whereDate('tanggal', $hariIni)
            ->sum('qty_mutasi');

        return response()->json([
            'masuk' => (int) $masuk,
            'keluar' => (int) $keluar,
            'penyesuaian_masuk' => (int) $penyesuaianMasuk,
            'penyesuaian_keluar' => (int) $penyesuaianKeluar,
        ]);
    }

    public function gudangLowStockTop()
    {
        $rows = DB::table('gudang_barang_stoks')
            ->select('nama_obat_alkes', 'qty')
            ->orderBy('qty', 'asc')
            ->limit(10)
            ->get();
        return response()->json(['data' => $rows]);
    }

    public function gudangPermintaanTerbaru()
    {
        $rows = DB::table('gudang_klinik_requests')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get(['kode_request', 'tanggal_input', 'nama_klinik', 'status'])
            ->map(function ($row) {
                $statusLabel = match ((int)$row->status) {
                    0 => 'Request',
                    1 => 'Dikonfirmasi',
                    2 => 'Diproses',
                    default => 'Tidak diketahui',
                };
                return [
                    'kode_request' => $row->kode_request,
                    'tanggal' => $row->tanggal_input ? Carbon::parse($row->tanggal_input)->format('d-m-Y') : '-',
                    'klinik' => $row->nama_klinik,
                    'status' => $statusLabel,
                ];
            });
        return response()->json(['data' => $rows]);
    }

    // ==================================
    // GUDANG UTAMA DASHBOARD ENDPOINTS
    // ==================================
    public function ringkasanGudangUtama()
    {
        $totalItemUtama = DB::table('gudang_barang_utamas')->count();
        $totalStokUtama = DB::table('gudang_barang_stok_utamas')->sum('qty');
        $permintaanKlinikHariIni = DB::table('gudang_klinik_requests')
            ->whereDate('tanggal_input', Carbon::today())
            ->count();

        return response()->json([
            'total_item' => (int) $totalItemUtama,
            'stok_utama' => (int) $totalStokUtama,
            'request_hari_ini' => (int) $permintaanKlinikHariIni,
        ]);
    }

    public function pergerakanGudangUtamaHariIni()
    {
        $hariIni = Carbon::today()->toDateString();

        $masuk = DB::table('pembelian_detail_utamas')
            ->whereDate('created_at', $hariIni)
            ->sum('qty');

        $keluar = DB::table('gudang_barang_keluar_utamas')
            ->whereDate('tanggal_request', $hariIni)
            ->sum('qty');

        $penyesuaianMasuk = DB::table('gudang_penyesuaian_masuk_utamas')
            ->whereDate('tanggal', $hariIni)
            ->sum('qty_mutasi');

        $penyesuaianKeluar = DB::table('gudang_penyesuaian_keluar_utamas')
            ->whereDate('tanggal', $hariIni)
            ->sum('qty_mutasi');

        return response()->json([
            'masuk' => (int) $masuk,
            'keluar' => (int) $keluar,
            'penyesuaian_masuk' => (int) $penyesuaianMasuk,
            'penyesuaian_keluar' => (int) $penyesuaianKeluar,
        ]);
    }

    public function gudangUtamaLowStockTop()
    {
        $rows = DB::table('gudang_barang_stok_utamas')
            ->select('nama_obat_alkes', 'qty')
            ->orderBy('qty', 'asc')
            ->limit(10)
            ->get();
        return response()->json(['data' => $rows]);
    }

    public function gudangUtamaPengirimanTerbaru()
    {
        $rows = DB::table('gudang_utama_keluars')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get(['kode_request', 'tanggal_request', 'nama_klinik', 'kode_obat_alkes', 'qty'])
            ->map(function ($row) {
                return [
                    'kode_request' => $row->kode_request,
                    'tanggal' => $row->tanggal_request ? Carbon::parse($row->tanggal_request)->format('d-m-Y') : '-',
                    'klinik' => $row->nama_klinik,
                    'kode_obat' => $row->kode_obat_alkes,
                    'qty' => (int) $row->qty,
                ];
            });
        return response()->json(['data' => $rows]);
    }

    // ==================================
    // PASIEN DASHBOARD ENDPOINTS
    // ==================================
    public function ringkasanPasien()
    {
        $total = DB::table('pasiens')->count();
        $baruBulanIni = DB::table('pasiens')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $bpjs = DB::table('pasiens')->whereNotNull('no_bpjs')->where('no_bpjs', '!=', '')->count();
        $nonBpjs = max($total - $bpjs, 0);

        return response()->json([
            'total' => (int) $total,
            'baru_bulan_ini' => (int) $baruBulanIni,
            'bpjs' => (int) $bpjs,
            'non_bpjs' => (int) $nonBpjs,
        ]);
    }

    public function distribusiKelaminPasien()
    {
        $rows = DB::table('pasiens as ps')
            ->leftJoin('kelamins as k', 'k.kode', '=', 'ps.seks')
            ->selectRaw('COALESCE(k.nama, ps.seks) as kelamin, COUNT(*) as jumlah')
            ->groupBy('kelamin')
            ->get();
        return response()->json(['labels' => $rows->pluck('kelamin'), 'data' => $rows->pluck('jumlah')]);
    }

    public function pasienBaruBulanan()
    {
        $now = Carbon::now();
        $rows = DB::table('pasiens')
            ->selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->whereYear('created_at', $now->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
        $bulanan = array_fill(1, 12, 0);
        foreach ($rows as $row) $bulanan[(int)$row->bulan] = (int)$row->jumlah;
        $labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        return response()->json(['labels' => $labels, 'data' => array_values($bulanan)]);
    }

    public function pasienTerbaru()
    {
        $rows = DB::table('pasiens')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get(['no_rm','nik','nama','seks','created_at'])
            ->map(function ($row) {
                return [
                    'no_rm' => $row->no_rm,
                    'nik' => $row->nik,
                    'nama' => $row->nama,
                    'kelamin' => $row->seks,
                    'tanggal' => $row->created_at ? Carbon::parse($row->created_at)->format('d-m-Y H:i') : '-',
                ];
            });
        return response()->json(['data' => $rows]);
    }

    // ==================================
    // MANAJEMEN DASHBOARD ENDPOINTS
    // ==================================
    public function ringkasanManajemen()
    {
        $hariIni = Carbon::today();
        $pendapatanHariIni = DB::table('kasirs')->whereDate('created_at', $hariIni)->sum('total');
        $kunjunganHariIni = Pendaftaran_rawat_jalan::whereDate('tanggal_kujungan', $hariIni)
            ->whereHas('status', fn($q) => $q->where('status_pendaftaran','!=',0))
            ->count();
        $pasienBaruHariIni = DB::table('pasiens')->whereDate('created_at', $hariIni)->count();
        $resepMenunggu = DB::table('pelayanan_soap_dokters')->where('status_apotek','0')->count();

        return response()->json([
            'pendapatan_hari_ini' => (float) $pendapatanHariIni,
            'kunjungan_hari_ini' => (int) $kunjunganHariIni,
            'pasien_baru_hari_ini' => (int) $pasienBaruHariIni,
            'resep_menunggu' => (int) $resepMenunggu,
        ]);
    }

    public function pendapatanBulananManajemen()
    {
        $now = Carbon::now();
        $rows = DB::table('kasirs')
            ->selectRaw('MONTH(created_at) as bulan, SUM(total) as total')
            ->whereYear('created_at', $now->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
        $bulanan = array_fill(1, 12, 0.0);
        foreach ($rows as $row) $bulanan[(int)$row->bulan] = (float) $row->total;
        $labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        return response()->json(['labels' => $labels, 'data' => array_values($bulanan)]);
    }

    public function topDokter30Hari()
    {
        $mulai = Carbon::today()->subDays(29)->startOfDay();
        $akhir = Carbon::today()->endOfDay();
        $rows = DB::table('pelayanans as p')
            ->join('dokters as d', 'd.id', '=', 'p.dokter_id')
            ->join('users as u', 'u.id', '=', 'd.users')
            ->whereBetween('p.created_at', [$mulai, $akhir])
            ->groupBy('u.name')
            ->select('u.name as dokter', DB::raw('COUNT(*) as jumlah'))
            ->orderByDesc('jumlah')
            ->limit(10)
            ->get();
        return response()->json(['labels' => $rows->pluck('dokter'), 'data' => $rows->pluck('jumlah')]);
    }
    // ==================================
    // REGISTRASI DASHBOARD ENDPOINTS
    // ==================================
    public function ringkasanRegistrasi()
    {
        $hariIni = Carbon::today();
        $waktuSekarang = Carbon::now();

        $jumlahHariIni = Pendaftaran_rawat_jalan::whereDate('tanggal_kujungan', $hariIni)
            ->whereHas('status', function ($query) {
                $query->where('status_pendaftaran', '!=', 0);
            })
            ->count();

        $jumlahBulanIni = Pendaftaran_rawat_jalan::whereMonth('tanggal_kujungan', $waktuSekarang->month)
            ->whereYear('tanggal_kujungan', $waktuSekarang->year)
            ->whereHas('status', function ($query) {
                $query->where('status_pendaftaran', '!=', 0);
            })
            ->count();

        $statusCounts = Pendaftaran_rawat_jalan::with('status')
            ->whereDate('tanggal_kujungan', $hariIni)
            ->get()
            ->reduce(function ($acc, $row) {
                $status = (int) ($row->status->status_panggil ?? 0);
                if ($status === 0) $acc['menunggu']++;
                elseif ($status === 1) $acc['dipanggil']++;
                elseif ($status === 2) $acc['pemeriksaan']++;
                return $acc;
            }, ['menunggu' => 0, 'dipanggil' => 0, 'pemeriksaan' => 0]);

        return response()->json([
            'hari_ini' => $jumlahHariIni,
            'bulan_ini' => $jumlahBulanIni,
            'antrian_menunggu' => $statusCounts['menunggu'],
            'antrian_dipanggil' => $statusCounts['dipanggil'],
            'antrian_pemeriksaan' => $statusCounts['pemeriksaan'],
        ]);
    }

    public function registrasiTerbaru()
    {
        $hariIni = Carbon::today();

        $rows = Pendaftaran_rawat_jalan::with(['pasien', 'poli'])
            ->whereDate('created_at', $hariIni)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                return [
                    'no_rawat' => $row->nomor_register ?? '-',
                    'pasien' => $row->pasien->nama ?? '-',
                    'poli' => $row->poli->nama ?? '-',
                    'waktu' => $row->created_at ? Carbon::parse($row->created_at)->format('H:i') : '-',
                ];
            });

        return response()->json(['data' => $rows]);
    }
}
