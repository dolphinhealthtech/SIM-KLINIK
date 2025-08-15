<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Gudang\Utama;

use App\Http\Controllers\Controller;
use App\Models\gudang_barang;
use App\Models\gudang_barang_harga;
use App\Models\gudang_barang_stok;
use App\Models\gudang_klinik_request;
use App\Models\gudang_klinik_request_details;
use App\Models\gudang_utama_keluar;
use App\Models\gudang_barang_utama;
use App\Models\gudang_barang_harga_utama;
use App\Models\gudang_barang_stok_utama;
use App\Models\gudang_barang_keluar_utama;
use App\Models\WebSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;


class Gudang_Utama_Controller extends Controller
{
    public function gudangutama()
    {
        $title = "Dashboard Gudang Utama";
        $request = gudang_klinik_request::with('details')->get();
        $dabar = gudang_barang_utama::all();

        return view('module.master-data-gudang.utama', compact('title','request','dabar'));
    }

    public function gudangutamakonfirmasi(Request $request)
    {
        $request->validate([
            'detail_kode_request' => 'required|string',
            'detail_tanggal' => 'required|string',
        ]);

        try {
            $found = gudang_klinik_request::where('kode_request', $request->input('detail_kode_request'))
                ->where('tanggal_input', $request->input('detail_tanggal'))
                ->first();

            if (!$found) {
                // Data tidak ditemukan, return error
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid atau tidak ditemukan!',
                ], 404);
            }

            // Update status
            $found->update([
                'status' => 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dikonfirmasi',
                'data' => $found,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat konfirmasi data!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generatePdf($kodeRequest)
    {
        $data = gudang_utama_keluar::where('kode_request', $kodeRequest)->get();

        $data_sendiri = gudang_utama_keluar::where('kode_request', $kodeRequest)
        ->select('kode_request', 'nama_klinik', 'tanggal_request')
        ->first();

        $total_invoice = 0;

        foreach ($data as $item) {
            $total_invoice++;
        }

        $pdf = Pdf::loadView('pdf.faktur_pengiriman', compact('data','data_sendiri','total_invoice'))->setPaper('a4', 'landscape');
        return $pdf->stream('faktur_pengiriman_' . $kodeRequest . '.pdf');
    }


    public function laporan_gudang_utama()
    {
        $title = "Kasir Apotek Lunas";

        $data = gudang_klinik_request::with('details')
                ->whereHas('details')
                ->get();

        return view('module.master-data-gudang.laporan_gudang_utama', compact('title','data'));
    }

    public function print_gudang_utama(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $klinik = $request->input('klinik');

        $total_invoice = 0;

        foreach ($data as $item) {
            if (isset($item['is_detail']) && $item['is_detail'] == false) {
                $total_invoice++;
            }
        }

        $obatQtySummary = []; // array penampung

        foreach ($data as $item) {
            $nama_obat = $item['nama_obat_alkes'] ?? '-';
            $qty = (int) $item['qty'] ?? 0;

            if (!isset($obatQtySummary[$nama_obat])) {
                $obatQtySummary[$nama_obat] = 0;
            }

            $obatQtySummary[$nama_obat] += $qty;
        }

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = Pdf::loadView('pdf.data_laporan_gudang_utama', compact('data', 'tanggal_awal', 'tanggal_akhir', 'klinik','total_invoice','obatQtySummary', 'namaKlinik', 'alamatKlinik'))
                ->setPaper('a4', 'landscape');

        $filename = 'laporan_gudang_utama_' . $tanggal_awal . '_' . $tanggal_akhir . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }
}
