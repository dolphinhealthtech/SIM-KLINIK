<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\gudang_barang;
use App\Models\gudang_barang_harga;
use App\Models\gudang_barang_stok;
use App\Models\gudang_setting_harga;
use App\Models\gudang_supplier_industri;
use App\Models\pembelian;
use App\Models\pembelian_details;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

// Gudang Utama
use App\Models\gudang_barang_utama;
use App\Models\gudang_barang_harga_utama;
use App\Models\gudang_barang_stok_utama;
use App\Models\gudang_setting_harga_utama;
use App\Models\pembelian_detail_utama;
use App\Models\pembelian_utama;
use App\Models\WebSetting;
use Illuminate\Support\Facades\Log;

class PembelianController extends Controller
{
        public function pembelian()
    {
        $title = "Pembelian";
        $supplier = gudang_supplier_industri::all();
        $gudang = WebSetting::first()->is_gudangutama_active;
        if ($gudang == 1) {
            $dabar = gudang_barang_utama::all();
        } else {
            $dabar = gudang_barang::all();
        }
        $user = User::all();
        if ($gudang == 1) {
            $settingHarga = gudang_setting_harga_utama::first();
        } else {
            $settingHarga = gudang_setting_harga::first();
        }

        return view('dashboard.pembelian', compact('title','supplier','dabar','user','settingHarga','gudang'));
    }

    public function pembelianadd(Request $request)
    {
        try {
            $request->validate([
                'data_json_tabel' => 'required|string',
                'nomor_faktur' => 'required|string',
                'supplier_select' => 'nullable|string',
                'supplier_input' => 'nullable|string',
                'no_po_sp' => 'required|string',
                'no_faktur_supplier' => 'required|string',
                'tanggal_terima_barang' => 'required|string',
                'tanggal_faktur' => 'required|string',
                'tanggal_jatuh_tempo' => 'required|string',
                'pajak_ppn' => 'required|string',
                'metode_hna' => 'required|string',
                'sub_total_keseluruhan_input' => 'required|string',
                'diskon_total_keseluruhan_input' => 'required|string',
                'ppn_total_keseluruhan_input' => 'required|string',
                'total_keseluruhan_input' => 'required|string',
                'materai' => 'required|string',
                'koreksi' => 'required|string',
                'penerima_barang' => 'required|string',
            ], [
                // Custom attribute names
                'data_json_tabel' => 'Data JSON Tabel',
                'nomor_faktur' => 'Nomor Faktur',
                'supplier_select' => 'Supplier Select',
                'supplier_input' => 'Supplier Input',
                'no_po_sp' => 'Nomor PO/SP',
                'no_faktur_supplier' => 'Nomor Faktur Supplier',
                'tanggal_terima_barang' => 'Tanggal Terima Barang',
                'tanggal_faktur' => 'Tanggal Faktur',
                'tanggal_jatuh_tempo' => 'Tanggal Jatuh Tempo',
                'pajak_ppn' => 'Pajak PPN',
                'metode_hna' => 'Metode HNA',
                'sub_total_keseluruhan_input' => 'Sub Total Keseluruhan',
                'diskon_total_keseluruhan_input' => 'Diskon Total Keseluruhan',
                'ppn_total_keseluruhan_input' => 'PPN Total Keseluruhan',
                'total_keseluruhan_input' => 'Total Keseluruhan',
                'materai' => 'Materai',
                'koreksi' => 'Koreksi',
                'penerima_barang' => 'Penerima Barang',
            ]);

            // Konsep: Pembelian hanya boleh dilakukan oleh gudang utama.
            // Gudang klinik tidak bisa melakukan pembelian langsung ke supplier.
            if (WebSetting::first()->is_gudangutama_active == 1) {
                $pembelian = pembelian_utama::create([
                    'nomor_faktur' => $request->input('nomor_faktur'),
                    'supplier' => $request->input('supplier_select') ?: $request->input('supplier_input'),
                    'no_po_sp' => $request->input('no_po_sp'),
                    'no_faktur_supplier' => $request->input('no_faktur_supplier'),
                    'tanggal_terima_barang' => $request->input('tanggal_terima_barang'),
                    'tanggal_faktur' => $request->input('tanggal_faktur'),
                    'tanggal_jatuh_tempo' => $request->input('tanggal_jatuh_tempo'),
                    'pajak_ppn' => $request->input('pajak_ppn'),
                    'metode_hna' => $request->input('metode_hna'),
                    'sub_total' => $request->input('sub_total_keseluruhan_input'),
                    'total_diskon' => $request->input('diskon_total_keseluruhan_input'),
                    'ppn_total' => $request->input('ppn_total_keseluruhan_input'),
                    'materai' => $request->input('materai'),
                    'koreksi' => $request->input('koreksi'),
                    'total' => $request->input('total_keseluruhan_input'),
                    'penerima_barang' => $request->input('penerima_barang'),
                    'user_input_id' => Auth::user()->id,
                    'user_input_nama' => Auth::user()->name,
                ]);
            } else {
                $pembelian = pembelian::create([
                    'nomor_faktur' => $request->input('nomor_faktur'),
                    'supplier' => $request->input('supplier_select') ?: $request->input('supplier_input'),
                    'no_po_sp' => $request->input('no_po_sp'),
                    'no_faktur_supplier' => $request->input('no_faktur_supplier'),
                    'tanggal_terima_barang' => $request->input('tanggal_terima_barang'),
                    'tanggal_faktur' => $request->input('tanggal_faktur'),
                    'tanggal_jatuh_tempo' => $request->input('tanggal_jatuh_tempo'),
                    'pajak_ppn' => $request->input('pajak_ppn'),
                    'metode_hna' => $request->input('metode_hna'),
                    'sub_total' => $request->input('sub_total_keseluruhan_input'),
                    'total_diskon' => $request->input('diskon_total_keseluruhan_input'),
                    'ppn_total' => $request->input('ppn_total_keseluruhan_input'),
                    'materai' => $request->input('materai'),
                    'koreksi' => $request->input('koreksi'),
                    'total' => $request->input('total_keseluruhan_input'),
                    'penerima_barang' => $request->input('penerima_barang'),
                    'user_input_id' => Auth::user()->id,
                    'user_input_nama' => Auth::user()->name,
                ]);
            }

            // Simpan detail pembelian
            $dataDetail = json_decode($request->data_json_tabel, true);

            foreach ($dataDetail as $detail) {
                // Ambil metode
                $metode = $request->metode_hna;

                // Ambil dan konversi subtotal ke float
                $subTotalRaw = $detail['hargaSatuan'];
                $subTotal = (float) str_replace(['Rp', '.', ' '], '', $subTotalRaw);

                // Ambil dan konversi diskon
                $diskon = $detail['disc'];
                $diskonPersen = 0;
                $diskonRupiah = 0;

                if (strpos($diskon, '%') !== false) {
                    // Jika diskon dalam persen (misal: "10%")
                    $diskonPersen = (float) str_replace('%', '', $diskon);
                } else {
                    // Jika diskon dalam rupiah
                    $diskonRupiah = (float) str_replace(['Rp', '.', ' '], '', $diskon);
                }

                // Ambil dan konversi PPN ke float
                $ppn = (float) str_replace('%', '', $request->pajak_ppn);

                $PPNbarang = 0;
                $Diskonbarang = 0;
                $hargaDiskon_4 = 0;
                $hargaDasar = $subTotal;

                if ($metode == '1') {
                    // Metode 1: Hanya subtotal
                    $hargaDasar = $subTotal;
                } elseif ($metode == '2') {
                    // Metode 2: Subtotal + PPN
                    $PPNbarang = $subTotal * ($ppn / 100);
                    $hargaDasar = $subTotal + $PPNbarang;
                } elseif ($metode == '3') {
                    // Metode 3: Subtotal - Diskon
                    if ($diskonPersen > 0) {
                        $Diskonbarang = $subTotal * ($diskonPersen / 100);
                        $hargaDasar = $subTotal - $Diskonbarang;
                    } else {
                        $Diskonbarang = $diskonRupiah;
                        $hargaDasar = $subTotal - $Diskonbarang;
                    }
                } elseif ($metode == '4') {
                    // Metode 4: Subtotal + PPN - Diskon
                    if ($diskonPersen > 0) {
                        $hargaDiskon_4 = $subTotal * ($diskonPersen / 100);
                        $Diskonbarang = $hargaDiskon_4;
                    } else {
                        $hargaDiskon_4 = $diskonRupiah;
                        $Diskonbarang = $hargaDiskon_4;
                    }

                    $hargaSetelahDiskon = $subTotal - $hargaDiskon_4;
                    $PPNbarang = $hargaSetelahDiskon * ($ppn / 100);
                    $hargaDasar = $hargaSetelahDiskon + $PPNbarang;
                }
                if (WebSetting::first()->is_gudangutama_active == 1) {
                    $setting = gudang_setting_harga_utama::first(); // atau where('some_column', ...)
                    $hargaJual1 = $hargaDasar * (1 + ($setting->harga_jual_1 / 100));
                    $hargaJual2 = $hargaDasar * (1 + ($setting->harga_jual_2 / 100));
                    $hargaJual3 = $hargaDasar * (1 + ($setting->harga_jual_3 / 100));
                } else {
                    $setting = gudang_setting_harga::first(); // atau where('some_column', ...)
                    $hargaJual1 = $hargaDasar * (1 + ($setting->harga_jual_1 / 100));
                    $hargaJual2 = $hargaDasar * (1 + ($setting->harga_jual_2 / 100));
                    $hargaJual3 = $hargaDasar * (1 + ($setting->harga_jual_3 / 100));
                }



                // Contoh: switch mode gudang utama/klinik (bisa diganti dari request/session sesuai kebutuhan)

                // Simpan ke gudang (switch model sesuai mode)
                if (WebSetting::first()->is_gudangutama_active == 1) {
                    gudang_barang_harga_utama::create([
                        'kode_obat_alkes' => $detail['kodeBarang'],
                        'nama_obat_alkes' => $detail['nama'],
                        'harga_dasar' => $hargaDasar,
                        'harga_jual_1' => $hargaJual1,
                        'harga_jual_2' => $hargaJual2,
                        'harga_jual_3' => $hargaJual3,
                        'diskon' => $Diskonbarang,
                        'ppn' => $PPNbarang,
                        'tanggal_obat_masuk' => $request->input('tanggal_terima_barang'),
                        'user_input_id' => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                    ]);
                } else {
                    gudang_barang_harga::create([
                        'kode_obat_alkes' => $detail['kodeBarang'],
                        'nama_obat_alkes' => $detail['nama'],
                        'harga_dasar' => $hargaDasar,
                        'harga_jual_1' => $hargaJual1,
                        'harga_jual_2' => $hargaJual2,
                        'harga_jual_3' => $hargaJual3,
                        'diskon' => $Diskonbarang,
                        'ppn' => $PPNbarang,
                        'tanggal_obat_masuk' => $request->input('tanggal_terima_barang'),
                        'user_input_id' => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                    ]);
                }

                // Simpan ke detail pembelian

                if (WebSetting::first()->is_gudangutama_active == 1) {
                    pembelian_detail_utama::create([
                        'nomor_faktur' => $request->input('nomor_faktur'),
                        'nama_obat_alkes' => $detail['nama'],
                        'kode_obat_alkes' => $detail['kodeBarang'],
                        'qty' => $detail['qty'],
                        'harga_satuan' => $detail['hargaSatuan'],
                        'diskon' => $detail['disc'],
                        'exp' => $detail['exp'],
                        'batch' => $detail['batch'],
                        'sub_total' => $detail['subTotal'],
                    ]);
                } else {
                    pembelian_details::create([
                        'nomor_faktur' => $request->input('nomor_faktur'),
                        'nama_obat_alkes' => $detail['nama'],
                        'kode_obat_alkes' => $detail['kodeBarang'],
                        'qty' => $detail['qty'],
                        'harga_satuan' => $detail['hargaSatuan'],
                        'diskon' => $detail['disc'],
                        'exp' => $detail['exp'],
                        'batch' => $detail['batch'],
                        'sub_total' => $detail['subTotal'],
                    ]);
                }

                // Simpan ke stok (switch model sesuai mode)
                if (WebSetting::first()->is_gudangutama_active == 1) {
                    gudang_barang_stok_utama::create([
                        'kode_obat_alkes' => $detail['kodeBarang'],
                        'nama_obat_alkes' => $detail['nama'],
                        'qty' => $detail['qty'],
                        'tanggal_terima_obat' => $request->input('tanggal_terima_barang'),
                        'expired' => $detail['exp'],
                        'user_input_id' => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                    ]);
                } else {
                    gudang_barang_stok::create([
                        'kode_obat_alkes' => $detail['kodeBarang'],
                        'nama_obat_alkes' => $detail['nama'],
                        'qty' => $detail['qty'],
                        'tanggal_terima_obat' => $request->input('tanggal_terima_barang'),
                        'expired' => $detail['exp'],
                        'user_input_id' => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                    ]);
                }
            }


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Data pembelian berhasil ditambahkan!',
                'data' => $pembelian
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pembelian Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Data pembelian!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //GENERATE NO FAKTUR
        public function generateFakturPembelian()
        {
            try {
                // Ambil tanggal hari ini dalam format Ymd (tanpa tanda -)
                $today = date('Ymd'); // Format menjadi YYYYMMDD

                // Cari nomor faktur terakhir untuk tanggal yang sama
                if (WebSetting::first()->is_gudangutama_active == 1) {
                    $lastPembelian = pembelian_utama::whereDate('created_at', '=', date('Y-m-d'))
                        ->latest('nomor_faktur')
                        ->first();
                } else {
                    $lastPembelian = pembelian::whereDate('created_at', '=', date('Y-m-d'))
                        ->latest('nomor_faktur')
                        ->first();
                }

                // Format dasar nomor faktur 'INV-YYYYMMDD-'
                $prefix = 'INV-' . $today . '-';

                // Jika ada nomor faktur terakhir, ambil angka di akhir nomor faktur dan tambahkan 1
                if ($lastPembelian) {
                    preg_match('/(\d+)$/', $lastPembelian->nomor_faktur, $matches);
                    $nextNumber = isset($matches[0]) ? (int) $matches[0] + 1 : 1;
                } else {
                    // Jika tidak ada nomor faktur sebelumnya, mulai dari 1
                    $nextNumber = 1;
                }

                // Format nomor faktur dengan padding 5 digit
                $nextNomorFaktur = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

                return response()->json([
                    'success' => true,
                    'kode_faktur' => $nextNomorFaktur
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghasilkan nomor faktur.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

    //CETAK PDF
        public function cetakPembelianPdf($nomor_faktur)
        {
            //pembelian sama details dibuat utama
            // Ambil data pembelian
            if (WebSetting::first()->is_gudangutama_active == 1) {
                $pembelian = pembelian_utama::where('nomor_faktur', $nomor_faktur)->first();
                $details = pembelian_detail_utama::where('nomor_faktur', $nomor_faktur)->get();
            } else {
                $pembelian = pembelian::where('nomor_faktur', $nomor_faktur)->first();
                $details = pembelian_details::where('nomor_faktur', $nomor_faktur)->get();
            }

            // Pastikan data numerik dikonversi dengan benar
            foreach ($details as $detail) {
                // Bersihkan format mata uang jika ada
                $detail->harga_satuan = is_numeric($detail->harga_satuan) ? $detail->harga_satuan :
                    floatval(str_replace(['Rp', '.', ','], ['', '', '.'], $detail->harga_satuan));

                // Hitung diskon
                $diskonValue = 0;
                if (strpos($detail->diskon, '%') !== false) {
                    // Jika diskon dalam persen
                    $diskonPersen = floatval(str_replace('%', '', $detail->diskon));
                    $diskonValue = ($detail->harga_satuan * $detail->qty) * ($diskonPersen / 100);
                } elseif (strpos($detail->diskon, 'Rp') !== false) {
                    // Jika diskon dalam rupiah
                    $diskonValue = floatval(str_replace(['Rp', '.', ','], ['', '', '.'], $detail->diskon));
                }

                // Hitung subtotal setelah diskon
                $detail->sub_total = ($detail->harga_satuan * $detail->qty) - $diskonValue;
            }

            // Tampilkan PDF
            $pdf = PDF::loadView('pdf.pembelian', compact('pembelian', 'details'));
            return $pdf->stream('pembelian-'.$nomor_faktur.'.pdf');
        }
}
