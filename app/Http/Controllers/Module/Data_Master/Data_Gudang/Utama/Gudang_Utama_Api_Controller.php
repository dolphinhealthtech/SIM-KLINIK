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


class Gudang_Utama_Api_Controller extends Controller
{
    public function getHargaDasar($kode_obat)
    {
        try {
            $tanggalHariIni = Carbon::today();
            $tanggal3BulanLalu = $tanggalHariIni->copy()->subMonths(3);

            $harga = gudang_barang_harga_utama::where('kode_obat_alkes', $kode_obat)
                ->whereBetween('tanggal_obat_masuk', [$tanggal3BulanLalu, $tanggalHariIni])
                ->max('harga_dasar');

            return response()->json([
                'success' => true,
                'harga_dasar' => $harga
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function utamaGetDetails($kodeRequest)
    {
        $details = collect(); // Default: koleksi kosong

        if (!empty($kodeRequest)) {
            $details = gudang_klinik_request_details::where('kode_request', $kodeRequest)
                ->select('kode_obat_alkes', 'nama_obat_alkes', 'qty')
                ->get();
        }

        return response()->json([
            'details' => $details
        ]);
    }

    public function prosesPermintaan(Request $request)
    {
        try {
            $itemsJson = $request->input('items_json');
            $items = json_decode($itemsJson, true);
            $kodeRequest = $request->input('kode_request');
            $tanggalRequest = $request->input('tanggal_request');
            $namaKlinik = $request->input('nama_klinik');

            $found = gudang_klinik_request::where('kode_request', $kodeRequest)
                ->where('tanggal_input', $tanggalRequest)
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
                'status' => 2,
            ]);

            foreach ($items as $item) {
                $kodeObat = $item['kode_obat'];
                $jumlahDibutuhkan = intval($item['jumlah']);

                $hargaDasarRaw = $item['harga_dasar'];
                $hargaDasar = intval(str_replace(['Rp', '.', ' '], '', $hargaDasarRaw));

                // Skip jika jumlah kosong/tidak valid
                if ($jumlahDibutuhkan <= 0) {
                    continue;
                }

                $stokList = gudang_barang_stok_utama::where('kode_obat_alkes', $kodeObat)
                            ->where('qty', '>', 0)
                            ->orderBy('tanggal_terima_obat', 'asc')
                            ->get();

                $totalTersedia = $stokList->sum('qty');
                if ($totalTersedia < $jumlahDibutuhkan) {
                    // Validasi gagal jika stok tidak mencukupi
                    return response()->json([
                        'success' => false,
                        'message' => "Stok tidak cukup untuk kode obat {$kodeObat}. Dibutuhkan: {$jumlahDibutuhkan}, tersedia: {$totalTersedia}",
                    ], 422);
                }

                foreach ($stokList as $stok) {
                    if ($jumlahDibutuhkan <= 0) break;

                    $ambil = min($stok->qty, $jumlahDibutuhkan);

                    $stok->qty -= $ambil;
                    $stok->save();

                    $jumlahDibutuhkan -= $ambil;

                    gudang_barang_keluar_utama::create([
                        'kode_request' => $kodeRequest,
                        'nama_klinik' => $namaKlinik,
                        'tanggal_request' => $tanggalRequest,
                        'kode_obat_alkes' => $kodeObat,
                        'nama_obat_alkes' => $stok->nama_obat_alkes,
                        'harga_dasar' => $hargaDasar,
                        'qty' => $ambil,
                        'tanggal_terima_obat' => $stok->tanggal_terima_obat,
                        'expired' => $stok->expired,
                        'user_input_id' => $request->input('user_id'),
                        'user_input_name' => $request->input('user_name'),
                    ]);

                    gudang_utama_keluar::create([
                        'kode_request' => $kodeRequest,
                        'nama_klinik' => $namaKlinik,
                        'tanggal_request' => $tanggalRequest,
                        'kode_obat_alkes' => $kodeObat,
                        'nama_obat_alkes' => $stok->nama_obat_alkes,
                        'harga_dasar' => $hargaDasar,
                        'qty' => $ambil,
                        'tanggal_terima_obat' => $stok->tanggal_terima_obat,
                        'expired' => $stok->expired,
                        'user_input_id' => $request->input('user_id'),
                        'user_input_name' => $request->input('user_name'),
                    ]);
                }
            }
            // Return jika berhasil
            return response()->json([
                'success' => true,
                'message' => 'Permintaan berhasil diproses!',
                'data' => $kodeRequest,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses permintaan!',
                'error' => $e->getMessage(),
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
}
