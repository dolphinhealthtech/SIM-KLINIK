<?php

namespace App\Http\Controllers\DataMaster\inventaris;

use App\Http\Controllers\Controller;
use App\Models\inventaris_data_barang;
use App\Models\inventaris_request;
use App\Models\inventaris_request_detail;
use App\Models\inventaris_stok;
use App\Models\inventaris_utama_keluar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class InventarisUtamaController extends Controller
{
        public function inventarisutama()
    {
        $title = "Inventaris Gudang Utama";
        $request = inventaris_request::with('details')->get();
        $inventaris = inventaris_data_barang::all();

        return view('module.master-data-gudang.utama_inventaris', compact('title','request','inventaris'));
    }

    public function inventarisutamakonfirmasi(Request $request)
    {
        $request->validate([
            'detail_kode_request' => 'required|string',
            'detail_tanggal' => 'required|string',
        ]);

        try {
            $found = inventaris_request::where('kode_request', $request->input('detail_kode_request'))
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

    public function inventaris_getData($kode_barang)
    {
        try {
            $harga = inventaris_data_barang::where('kode_barang', $kode_barang)->first();

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

    public function inventarisGetDetails($kodeRequest)
    {
        $details = collect(); // Default: koleksi kosong

        if (!empty($kodeRequest)) {
            $details = inventaris_request_detail::where('kode_request', $kodeRequest)
                ->select('kode_barang', 'nama_barang', 'qty')
                ->get();
        }

        return response()->json([
            'details' => $details
        ]);
    }

    public function inventaris_prosesPermintaan(Request $request)
    {
        try {
            $itemsJson = $request->input('items_json');
            $items = json_decode($itemsJson, true);
            $kodeRequest = $request->input('kode_request');
            $tanggalRequest = $request->input('tanggal_request');
            $namaKlinik = $request->input('nama_klinik');

            $found = inventaris_request::where('kode_request', $kodeRequest)
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
                $kodeBarang = $item['kode_barang'];
                $jumlahDibutuhkan = intval($item['jumlah']);

                if ($jumlahDibutuhkan <= 0) {
                    continue;
                }

                // Ambil info jenis barang (anggap field-nya 'jenis')
                $jenisBarang = $item['jenis'];

                $stokList = inventaris_stok::where('kode_barang', $kodeBarang)
                    ->where('qty_barang', '>', 0)
                    ->orderBy('tanggal_pembelian', 'asc')
                    ->orderBy('masa_akhir_penggunaan', 'desc')
                    ->get();

                $totalTersedia = $stokList->sum('qty_barang');

                if ($totalTersedia < $jumlahDibutuhkan) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok tidak cukup untuk {$kodeBarang}. Dibutuhkan: {$jumlahDibutuhkan}, tersedia: {$totalTersedia}",
                    ], 422);
                }

                foreach ($stokList as $stok) {
                    if ($jumlahDibutuhkan <= 0) break;

                    if ($jenisBarang == 'Inventaris') {
                        // Ambil per item
                        $stok->qty_barang -= 1;
                        $stok->save();

                        inventaris_utama_keluar::create([
                            'kode_request' => $kodeRequest,
                            'nama_klinik' => $namaKlinik,
                            'tanggal_request' => $tanggalRequest,
                            'kode_barang' => $kodeBarang,
                            'nama_barang' => $stok->nama_barang,
                            'kategori_barang' => $stok->kategori_barang,
                            'jenis_barang' => $stok->jenis_barang,
                            'qty_barang' => 1,
                            'harga_barang' => $stok->harga_barang,
                            'masa_akhir_penggunaan' => $stok->masa_akhir_penggunaan,
                            'tanggal_pembelian' => $stok->tanggal_pembelian,
                            'detail_barang' => $stok->detail_barang,
                            'lokasi' => null,
                            'penanggung_jawab' => null,
                            'kondisi' => null,
                            'no_seri' => null,
                            'user_input_id' => $request->input('user_id'),
                            'user_input_name' => $request->input('user_name'),
                        ]);

                        $jumlahDibutuhkan -= 1;
                    } else {
                        // Barang habis pakai, bisa sekaligus
                        $ambil = min($stok->qty_barang, $jumlahDibutuhkan);

                        $stok->qty_barang -= $ambil;
                        $stok->save();

                        inventaris_utama_keluar::create([
                            'kode_request' => $kodeRequest,
                            'nama_klinik' => $namaKlinik,
                            'tanggal_request' => $tanggalRequest,
                            'kode_barang' => $kodeBarang,
                            'nama_barang' => $stok->nama_barang,
                            'kategori_barang' => $stok->kategori_barang,
                            'jenis_barang' => $stok->jenis_barang,
                            'qty_barang' => $ambil,
                            'harga_barang' => $stok->harga_barang,
                            'masa_akhir_penggunaan' => $stok->masa_akhir_penggunaan,
                            'tanggal_pembelian' => $stok->tanggal_pembelian,
                            'detail_barang' => $stok->detail_barang,
                            'lokasi' => null,
                            'penanggung_jawab' => null,
                            'kondisi' => null,
                            'no_seri' => null,
                            'user_input_id' => $request->input('user_id'),
                            'user_input_name' => $request->input('user_name'),
                        ]);

                        $jumlahDibutuhkan -= $ambil;
                    }
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

    public function inventaris_generatePdf($kodeRequest)
    {
        $data = inventaris_utama_keluar::where('kode_request', $kodeRequest)->get();

        $data_sendiri = inventaris_utama_keluar::where('kode_request', $kodeRequest)
        ->select('kode_request', 'nama_klinik', 'tanggal_request')
        ->first();

        $total_invoice = 0;

        foreach ($data as $item) {
            $total_invoice++;
        }

        $pdf = Pdf::loadView('pdf.faktur_pengiriman_inventaris', compact('data','data_sendiri','total_invoice'))->setPaper('a4', 'landscape');
        return $pdf->stream('faktur_pengiriman_inventaris_' . $kodeRequest . '.pdf');
    }
}
