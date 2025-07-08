<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\gudang_penyesuaian_keluar;
use App\Models\gudang_penyesuaian_masuk;
use App\Models\gudang_stok_opname;
use App\Models\pasien_antrian;
use App\Models\Pendaftaran_rawat_jalan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class PendataanController extends Controller
{
        public function pendataan_antrian()
    {
        $title = "Data Antrian";

        $data = pasien_antrian::with('pasien')->get();

        return view('module.pendataan.antrian', compact('title','data'));
    }

    public function print_antrian(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $total_invoice = count($data);

        $pdf = Pdf::loadView('pdf.data_antrian', compact('data', 'tanggal_awal', 'tanggal_akhir', 'total_invoice'))
                ->setPaper('a4', 'landscape');

        $filename = 'laporan_antrian_' . $tanggal_awal . '_' . $tanggal_akhir . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function pendataan_pendaftaran()
    {
        $title = "Data Pendaftaran";

        $data = Pendaftaran_rawat_jalan::with('poli','dokter.namauser','pasien','penjamin')->get();

        return view('module.pendataan.pendaftaran', compact('title','data'));
    }

    public function print_pendaftaran(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');
        $dokter = $request->input('dokter');

        $total_invoice = count($data);

        $pdf = Pdf::loadView('pdf.data_pendaftaran', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli', 'dokter', 'total_invoice'))
                ->setPaper('a4', 'landscape');

        $filename = 'laporan_pendaftaran_' . $tanggal_awal . '_' . $tanggal_akhir . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function pendataan_dokter()
    {
        $title = "Data Pemeriksaan Dokter";

        $data = Pendaftaran_rawat_jalan::with('poli', 'dokter.namauser', 'pasien', 'penjamin', 'soap_dokter')
                ->whereHas('soap_dokter')
                ->get();

        return view('module.pendataan.dokter', compact('title','data'));
    }

    public function print_dokter(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');
        $dokter = $request->input('dokter');

        $total_invoice = count($data);

        $pdf = Pdf::loadView('pdf.data_pelayanan_dokter', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli', 'dokter', 'total_invoice'))
                ->setPaper('a4', 'landscape');

        $filename = 'laporan_pelayanan_dokter_' . $tanggal_awal . '_' . $tanggal_akhir . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function pendataan_perawat()
    {
        $title = "Data Pemeriksaan Perawat";

        $data = Pendaftaran_rawat_jalan::with('poli', 'dokter.namauser', 'pasien', 'penjamin', 'soap_perawat')
                ->whereHas('soap_perawat')
                ->get();

        return view('module.pendataan.perawat', compact('title','data'));
    }

    public function print_perawat(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');
        $dokter = $request->input('dokter');

        $total_invoice = count($data);

        $pdf = Pdf::loadView('pdf.data_pelayanan_perawat', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli', 'dokter', 'total_invoice'))
                ->setPaper('a4', 'landscape');

        $filename = 'laporan_pelayanan_perawat_' . $tanggal_awal . '_' . $tanggal_akhir . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function laporan_stok_penyesuaian()
    {
        $title = "Laporan Selisih Mutasi & Penyesuaian";

        // Gabungkan data masuk dan keluar menjadi satu collection
        $data_masuk = gudang_penyesuaian_masuk::all()->map(function ($item) {
            $item->tipe = 'MASUK';
            return $item;
        });

        $data_keluar = gudang_penyesuaian_keluar::all()->map(function ($item) {
            $item->tipe = 'KELUAR';
            return $item;
        });

        // Gabung semua data
        $data = $data_masuk->concat($data_keluar);

        // dd($data);
        return view('module.pendataan.stok_penyesuaian', compact('title', 'data'));
    }

    public function print_stok_penyesuaian(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $obat = $request->input('obat');
        $jenis = $request->input('jenis');

        $total_invoice = count($data);

        $pdf = Pdf::loadView('pdf.data_mutasi_penyesuaian', compact('data', 'tanggal_awal', 'tanggal_akhir', 'obat', 'jenis', 'total_invoice'))
                ->setPaper('a4', 'landscape');

        $filename = 'laporan_selisih_mutasi_penyesuaian_' . $tanggal_awal . '_' . $tanggal_akhir . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function stok_opname()
    {
        $title = "Laporan Stok Opname";

        $data = gudang_stok_opname::all();

        // dd($data);
        return view('module.pendataan.stok_opname', compact('title', 'data'));
    }


    public function print_stok_opname(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $obat = $request->input('obat');

        $total_invoice = count($data);

        $pdf = Pdf::loadView('pdf.data_stok_opname', compact('data', 'tanggal_awal', 'tanggal_akhir', 'obat', 'total_invoice'))
                ->setPaper('a4', 'landscape');

        $filename = 'laporan_stok_opname_' . $tanggal_awal . '_' . $tanggal_akhir . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }
}
