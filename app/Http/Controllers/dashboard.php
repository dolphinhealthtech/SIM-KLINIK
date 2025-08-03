<?php

namespace App\Http\Controllers;

use App\Models\dokter;
use App\Models\dokter_jadwal;
use App\Models\pasien;
use App\Models\Pendaftaran_rawat_jalan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class dashboard extends Controller
{
    /**
     * Display the dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $datapasien = pasien::count();
        $datadokter = dokter::where('verifikasi', 2)->count();
        $datakunjungan = Pendaftaran_rawat_jalan::whereDate('tanggal_kujungan', Carbon::today())->count();

        $now = Carbon::now(); // waktu saat ini
        $today = Carbon::today(); // hanya tanggal hari ini

        // Ambil dokter yang sedang aktif sekarang
        $dokterHariIni = dokter_jadwal::whereDate('start', $today)
            ->where('start', '<=', $now)
            ->where('end', '>=', $now)
            ->with('dokter')
            ->get()
            ->filter(fn($item) => $item->dokter->verifikasi == 2)
            ->map(function ($item) {
                return (object)[
                    'nama' => $item->dokter->namauser->name,
                    'spesialisasi' => $item->dokter->namapoli->nama,
                    'start' => Carbon::parse($item->start)->format('H:i'),
                    'end' => Carbon::parse($item->end)->format('H:i')
                ];
            });

        $datadokter = $dokterHariIni->count();

        return view('dashboard.index', compact('datapasien', 'datadokter', 'datakunjungan', 'dokterHariIni'));
    }


    public function kunjunganHarian()
    {
        $start = Carbon::today()->subDays(6); // 6 hari ke belakang
        $end = Carbon::today()->endOfDay(); // hari ini

        // Ambil data kunjungan per hari
        $kunjungan = Pendaftaran_rawat_jalan::selectRaw('DATE(tanggal_kujungan) as tanggal, COUNT(*) as jumlah')
            ->whereBetween('tanggal_kujungan', [$start, $end])
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

    public function getPendapatanHariIni()
    {
        $pendapatanHariIni = DB::table('kasirs')
            ->whereDate('created_at', Carbon::today())
            ->sum('total');

        return response()->json([
            'pendapatan' => $pendapatanHariIni
        ]);
    }

    public function getPendapatanBulanan()
    {
        $data = DB::table('kasirs')
            ->selectRaw('MONTH(created_at) as bulan, SUM(total) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Siapkan array 12 bulan
        $pendapatan = [];
        for ($i = 1; $i <= 12; $i++) {
            $pendapatan[$i] = 0;
        }

        foreach ($data as $row) {
            $pendapatan[(int)$row->bulan] = (float)$row->total;
        }

        // Buat labels dan data
        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $totals = array_values($pendapatan);

        return response()->json([
            'labels' => $bulanLabels,
            'totals' => $totals
        ]);
    }


    public function getPendapatanDetail()
    {
        try {
            $today = now()->toDateString();

            // Ambil total jasa dari tabel kasir_tindakan_lunas
            $totalJasa = DB::table('kasir_tindakan_lunas')
                ->whereDate('created_at', $today)
                ->sum('total');

            // Ambil total obat dari tabel kasir_apotek_lunas
            $totalObat = DB::table('kasir_apotek_lunas')
                ->whereDate('created_at', $today)
                ->sum('total');

            // Ambil data administrasi dan materai mentah
            $dataKasirs = DB::table('kasirs')
                ->whereDate('created_at', $today)
                ->get(['administrasi', 'materai']);

            $totalAdministrasi = 0;
            $totalMaterai = 0;

            foreach ($dataKasirs as $kasir) {
                // Konversi format Indonesia (misalnya 120.000) ke format standar (120000)
                $administrasi = str_replace('.', '', $kasir->administrasi ?? '0');
                $materai = str_replace('.', '', $kasir->materai ?? '0');

                $totalAdministrasi += floatval($administrasi);
                $totalMaterai += floatval($materai);
            }

            // Gabungkan administrasi dan materai ke total jasa
            $totalJasaGabungan = ($totalJasa ?? 0) + $totalAdministrasi + $totalMaterai;

            return response()->json([
                'status' => 'success',
                'jasa' => $totalJasaGabungan,
                'obat' => $totalObat ?? 0,
                'total' => $totalJasaGabungan + ($totalObat ?? 0),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data pendapatan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
