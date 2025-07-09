<?php

namespace App\Http\Controllers\DataMaster\gudang;

use App\Http\Controllers\Controller;
use App\Models\gudang_barang_stok;
use App\Models\gudang_barang;
use App\Models\gudang_barang_harga;
use App\Models\gudang_penyesuaian_masuk;
use App\Models\gudang_penyesuaian_keluar;
use App\Models\gudang_stok_opname;
use App\Models\apotek_prebayar;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\pembelian_details;



class StokBarangController extends Controller
{
    // Stok Barang (Obat / Alkes)

    public function stokobatalkes()
    {
        $title = "Master Stok Obat / Alkes";
        $stok = gudang_barang_stok::all();

        return view('module.master-data-gudang.stok', compact('title','stok'));
    }

    // Penyesuaian / Stok Opname

    public function stok_penyesuaian()
    {
        $title = "Master Stok Penyesuaian / Stok Opname";
        $stok = gudang_barang_stok::all();
        $obat = gudang_barang::all();

        return view('module.master-data-gudang.stok_penyesuaian_opname', compact('title','stok','obat'));
    }

    public function stok_penyesuaianadd(Request $request)
    {
        try {
            $request->validate([
                'kode_obat' => 'required',
                'aktifitas_penyesuaian' => 'required',
                'harga_penyesuaian' => 'required',
                'obat_penyesuaian' => 'required',
                'keterangan_qty_penyesuaian' => 'nullable',
                'qty_penyesuaian' => 'required',
                'alasan_penyesuaian' => 'required',
                'expired_penyesuaian' => 'required',
            ]);

            // Total semua qty untuk kode obat yang sama (tanpa mempedulikan expired)
            $stokSebelumnyaQty = gudang_barang_stok::where('kode_obat_alkes', $request->kode_obat)->sum('qty');

            $tipeHarga = $request->harga_penyesuaian; // 'harga_jual_1', 'harga_jual_2', atau 'harga_jual_3'

            // Validasi agar aman
            if (!in_array($tipeHarga, ['harga_jual_1', 'harga_jual_2', 'harga_jual_3'])) {
                return response()->json(['error' => 'Tipe harga tidak valid'], 400);
            }

            // Ambil harga tertinggi dari field sesuai tipe yang dipilih
            $hargaTertinggi = gudang_barang_harga::where('kode_obat_alkes', $request->kode_obat)
                ->max($tipeHarga);

            if ($request->aktifitas_penyesuaian === 'stok_opname') {
                $selisih = $request->qty_penyesuaian - $stokSebelumnyaQty;

                $stokKeluarApotek = apotek_prebayar::where('kode_obat_alkes', $request->kode_obat)->sum('qty');

                $selisih_kedua = $selisih - $stokKeluarApotek;

                if ($selisih > 0) {
                    // Tambahkan stok baru sesuai yang diinput user
                    gudang_barang_stok::create([
                        'kode_obat_alkes' => $request->kode_obat,
                        'nama_obat_alkes' => $request->obat_penyesuaian,
                        'qty' => $selisih,
                        'expired' => $request->expired_penyesuaian,
                        'tanggal_terima_obat' => now()->toDateString(),
                        'user_input_id' => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                    ]);

                    // Simpan riwayat penyesuaian
                    gudang_penyesuaian_masuk::create([
                        'kode_obat' => $request->kode_obat,
                        'nama_obat' => $request->obat_penyesuaian,
                        'qty_sebelum' => $stokSebelumnyaQty,
                        'qty_mutasi' => $selisih,
                        'qty_sesudah' => $request->qty_penyesuaian,
                        'jenis_penyesuaian' => 'STOK OPNAME',
                        'alasan' => $request->alasan_penyesuaian,
                        'tanggal' => now()->toDateString(),
                        'jam' => now()->toTimeString(),
                        'harga' => $hargaTertinggi,
                        'expired' => $request->expired_penyesuaian,
                        'user_input_id' => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                    ]);
                } elseif ($selisih < 0) {
                    $selisihPengurangan = abs($selisih);
                    $qtyRiwayat = $selisihPengurangan; // Simpan nilai asli untuk histori

                    // Ambil stok-stok berdasarkan expired tercepat & tanggal terima obat tertua
                    $stokList = gudang_barang_stok::where('kode_obat_alkes', $request->kode_obat)
                        ->orderBy('expired', 'asc')
                        ->orderBy('tanggal_terima_obat', 'asc')
                        ->get();

                    foreach ($stokList as $stok) {
                        if ($selisihPengurangan <= 0) break;

                        if ($stok->qty <= $selisihPengurangan) {
                            $selisihPengurangan -= $stok->qty;
                            $stok->qty = 0;
                        } else {
                            $stok->qty -= $selisihPengurangan;
                            $selisihPengurangan = 0;
                        }
                        $stok->save();
                    }

                    // Simpan riwayat penyesuaian (setelah stok benar-benar dikurangi)
                    gudang_penyesuaian_keluar::create([
                        'kode_obat' => $request->kode_obat,
                        'nama_obat' => $request->obat_penyesuaian,
                        'qty_sebelum' => $stokSebelumnyaQty,
                        'qty_mutasi' => $qtyRiwayat,
                        'qty_sesudah' => $request->qty_penyesuaian,
                        'jenis_penyesuaian' => 'STOK OPNAME',
                        'alasan' => $request->alasan_penyesuaian,
                        'tanggal' => now()->toDateString(),
                        'jam' => now()->toTimeString(),
                        'harga' => $hargaTertinggi,
                        'expired' => $request->expired_penyesuaian,
                        'user_input_id' => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                    ]);
                }

                gudang_stok_opname::create([
                    'kode_obat' => $request->kode_obat,
                    'nama_obat' => $request->obat_penyesuaian,
                    'expired' => $request->expired_penyesuaian,
                    'qty' => $request->qty_penyesuaian,
                    'alasan' => $request->alasan_penyesuaian,
                    'harga' => $hargaTertinggi,
                    'tanggal' => now()->toDateString(),
                    'jam' => now()->toTimeString(),
                    'user_input_id' => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                ]);

            } elseif ($request->aktifitas_penyesuaian === 'koreksi_manual') {
                if ($request->keterangan_qty_penyesuaian === 'tambahkan') {
                    // Tambahkan stok baru sesuai yang diinput user
                    gudang_barang_stok::create([
                        'kode_obat_alkes' => $request->kode_obat,
                        'nama_obat_alkes' => $request->obat_penyesuaian,
                        'qty' => $request->qty_penyesuaian,
                        'expired' => $request->expired_penyesuaian,
                        'tanggal_terima_obat' => now()->toDateString(),
                        'user_input_id' => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                    ]);

                    $qty_sesudah_koreksi = $stokSebelumnyaQty + $request->qty_penyesuaian;

                    // Simpan riwayat penyesuaian
                    gudang_penyesuaian_masuk::create([
                        'kode_obat' => $request->kode_obat,
                        'nama_obat' => $request->obat_penyesuaian,
                        'qty_sebelum' => $stokSebelumnyaQty,
                        'qty_mutasi' => $request->qty_penyesuaian,
                        'qty_sesudah' => $qty_sesudah_koreksi,
                        'jenis_penyesuaian' => 'PENYESUAIAN MASUK',
                        'alasan' => $request->alasan_penyesuaian,
                        'tanggal' => now()->toDateString(),
                        'jam' => now()->toTimeString(),
                        'harga' => $hargaTertinggi,
                        'expired' => $request->expired_penyesuaian,
                        'user_input_id' => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                    ]);

                } elseif ($request->keterangan_qty_penyesuaian === 'kurangi') {
                    $stok_pengurangan = $request->qty_penyesuaian;

                    // Ambil stok-stok berdasarkan expired tercepat & tanggal terima obat tertua
                    $stokList_2 = gudang_barang_stok::where('kode_obat_alkes', $request->kode_obat)
                        ->orderBy('expired', 'asc')
                        ->orderBy('tanggal_terima_obat', 'asc')
                        ->get();

                    foreach ($stokList_2 as $stok) {
                        if ($stok_pengurangan <= 0) break;

                        if ($stok->qty <= $stok_pengurangan) {
                            $stok_pengurangan -= $stok->qty;
                            $stok->qty = 0;
                        } else {
                            $stok->qty -= $stok_pengurangan;
                            $stok_pengurangan = 0;
                        }
                        $stok->save();
                    }

                    $qty_sesudah_koreksi = $stokSebelumnyaQty - $request->qty_penyesuaian;

                    // Simpan riwayat penyesuaian
                    gudang_penyesuaian_keluar::create([
                        'kode_obat' => $request->kode_obat,
                        'nama_obat' => $request->obat_penyesuaian,
                        'qty_sebelum' => $stokSebelumnyaQty,
                        'qty_mutasi' => $request->qty_penyesuaian,
                        'qty_sesudah' => $qty_sesudah_koreksi,
                        'jenis_penyesuaian' => 'PENYESUAIAN KELUAR',
                        'alasan' => $request->alasan_penyesuaian,
                        'tanggal' => now()->toDateString(),
                        'jam' => now()->toTimeString(),
                        'harga' => $hargaTertinggi,
                        'expired' => $request->expired_penyesuaian,
                        'user_input_id' => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                    ]);
                }
            }

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Penyesuaian Obat / Alkes Berhasil Dilakukan!',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis kategori sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Jenis kategori!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function kartu_stok()
    {
        $title = "Kartu Stok";

        $data = gudang_barang::all();

        return view('module.master-data-gudang.kartu_stok', compact('title', 'data'));
    }

    public function getKartuStokMasuk(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $kodeObat = $request->kode_obat;
        $start = Carbon::parse($tanggalAwal)->startOfDay();
        $end = Carbon::parse($tanggalAkhir)->endOfDay();

        try {
            $satuan = gudang_barang::where('kode_barang', $kodeObat)->value('satuan_kecil');

            $stok = gudang_barang_stok::where('kode_obat_alkes', $kodeObat)->sum('qty');

            $dataPrebayar = pembelian_details::when($kodeObat !== 'all', fn($q) => $q->where('kode_obat_alkes', $kodeObat))
                ->whereBetween('created_at', [$start, $end])
                ->with('pembelian')
                ->get()
                ->map(function ($item) {
                    return [
                        'tanggal' => 'Saldo Masuk ' . $item->created_at->format('Y-m-d'),
                        'qty' => $item->qty,
                        'harga' => $item->harga_satuan,
                        'keterangan' => 'Pembelian (Faktur: ' . $item->nomor_faktur . ')',
                        'expired' => $item->batch . ' / ' . $item->exp,
                        'user' => $item->pembelian->user_input_nama,
                    ];
                })->toArray();

            $dataPenyesuaian = gudang_penyesuaian_masuk::when($kodeObat !== 'all', fn($q) => $q->where('kode_obat', $kodeObat))
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->get()
                ->map(function ($item) {
                    return [
                        'tanggal' => $item->tanggal . ' / ' . $item->jam,
                        'qty' => $item->qty_mutasi,
                        'harga' => $item->harga,
                        'keterangan' => 'Penyesuaian (' . $item->jenis_penyesuaian . ') - ' . $item->alasan,
                        'expired' => $item->expired,
                        'user' => $item->user_input_name,
                    ];
                })->toArray();

            // Gabungkan data
            $dataGabung = array_merge($dataPrebayar, $dataPenyesuaian);

            return response()->json([
                'data' => $dataGabung,
                'satuan_kecil' => $satuan,
                'stok' => $stok,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function getKartuStokKeluar(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $kodeObat = $request->kode_obat;

        try {
            $satuan = gudang_barang::where('kode_barang', $kodeObat)->value('satuan_kecil');

            $dataPenjualan = apotek_prebayar::when($kodeObat !== 'all', fn($q) => $q->where('kode_obat_alkes', $kodeObat))
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->get()
                ->map(function ($item) {
                    return [
                        'tanggal' => 'Saldo Keluar ' . $item->tanggal,
                        'qty' => $item->qty,
                        'harga' => $item->harga,
                        'keterangan' => 'Penjualan (Faktur: ' . $item->kode_faktur . ')',
                        'user' => $item->user_input_name,
                    ];
                })->toArray();

            $dataPenyesuaian = gudang_penyesuaian_keluar::when($kodeObat !== 'all', fn($q) => $q->where('kode_obat', $kodeObat))
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->get()
                ->map(function ($item) {
                    return [
                        'tanggal' => $item->tanggal . ' / ' . $item->jam,
                        'qty' => $item->qty_mutasi,
                        'harga' => '0',
                        'keterangan' => 'Penyesuaian (' . $item->jenis_penyesuaian . ') - ' . $item->alasan,
                        'user' => $item->user_input_name,
                    ];
                })->toArray();

            // Gabungkan data
            $dataGabung = array_merge($dataPenjualan, $dataPenyesuaian);

            return response()->json([
                'data' => $dataGabung,
                'satuan_kecil' => $satuan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
