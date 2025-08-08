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
        $today = Carbon::today();
        $now = Carbon::now();

        // ================================
        // 1. Data Pasien & Dokter Aktif
        // ================================
        $datapasien = pasien::count();

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
        return view('dashboard.index', compact(
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


}
