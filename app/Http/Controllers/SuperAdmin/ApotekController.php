<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\apotek;
use App\Models\apotek_prebayar;
use App\Models\dokter;
use App\Models\gudang_barang;
use App\Models\gudang_barang_harga;
use App\Models\gudang_barang_stok;
use App\Models\gudang_setting_harga;
use App\Models\gudang_satuan;
use App\Models\penjamin;
use App\Models\pelayanan_soap_dokter;
use App\Models\WebSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\poli;
use Illuminate\Http\Request;


class ApotekController extends Controller
{
    public function apotek()
    {
        $title = "Apotek";
        $data_soap = pelayanan_soap_dokter::with('resep', 'pendaftaran', 'pasien')
            ->where('status_apotek', '=', "0")
            ->whereHas('resep', function ($query) {
                $query->whereNotNull('Resep_obat');
            })
            ->get();
        $dokter = dokter::with('namauser')->get();
        $poli = poli::all();
        $penjamin = penjamin::all();
        $embalase = gudang_setting_harga::value('embalase_poin');
        $stok_raw = gudang_barang_stok::selectRaw('MAX(id) as id')
            ->groupBy('kode_obat_alkes')
            ->pluck('id');

        $stok = gudang_barang_stok::whereIn('id', $stok_raw)->get();

        $obat = gudang_barang::all();
        $satuan = gudang_satuan::all();

        return view('dashboard.apotek', compact('title', 'data_soap', 'dokter', 'poli', 'penjamin', 'embalase', 'stok', 'obat', 'satuan'));
    }

    public function apotekadd(Request $request)
    {
        try {
            $validated = $request->validate([
                'no_rawat' => 'nullable|string',
                'no_rm' => 'required|string',
                'nama' => 'required|string',
                'alamat' => 'nullable|string',
                'resep' => 'required|string',
                'faktur_apotek' => 'required|string|unique:apoteks,kode_faktur',
                'dokter' => 'nullable|string',
                'poli' => 'nullable|string',
                'penjamin' => 'required|string',
                'nilai_embis_input' => 'nullable|string',
                'sub_total_hidden' => 'required|string',
                'embalase_total_hidden' => 'nullable|string',
                'total_hidden' => 'required|string',
                'note_apotek' => 'nullable|string',
                'tabel_apotek_harga_hidden' => 'required|string',
            ]);

            $existing = apotek::where('kode_faktur', $validated['faktur_apotek'])->first();
            if ($existing) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaksi sudah pernah dilakukan. Tidak boleh menginput ulang.',
                ], 409);
            }

            $apotek = apotek::create([
                'kode_faktur' => $validated['faktur_apotek'],
                'no_rm' => $validated['no_rm'],
                'no_rawat' => $validated['no_rawat'],
                'nama' => $validated['nama'],
                'alamat' => $validated['alamat'] ?? null,
                'tanggal' => now()->format('Y-m-d'),
                'jenis_resep' => $validated['resep'],
                'jenis_rawat' => 'RAWAT JALAN',
                'poli' => $validated['poli'],
                'dokter' => $validated['dokter'],
                'penjamin' => $validated['penjamin'],
                'embalase_poin' => $validated['nilai_embis_input'] ?? 0,
                'sub_total' => $validated['sub_total_hidden'] ?? 0,
                'embis_total' => $validated['embalase_total_hidden'] ?? 0,
                'total' => $validated['total_hidden'] ?? 0,
                'note_apotek' => $validated['note_apotek'] ?? null,
                'status_kasir' => 0,
                'user_input_id' => Auth::user()->id,
                'user_input_name' => Auth::user()->name,
            ]);

            // Decode JSON
            $tabel_apotek_harga = json_decode($validated['tabel_apotek_harga_hidden'], true);

            foreach ($tabel_apotek_harga as $detail) {
                apotek_prebayar::create([
                    'kode_faktur' => $validated['faktur_apotek'],
                    'no_rm' => $validated['no_rm'],
                    'nama' => $validated['nama'],
                    'tanggal' => now()->format('Y-m-d'),
                    'nama_obat_alkes' => $detail['nama'],
                    'kode_obat_alkes' => $detail['kode'],
                    'harga' => $detail['harga'],
                    'qty' => $detail['qty'],
                    'total' => $detail['total'],
                    'user_input_id' => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                ]);

                $qtyToDeduct = $detail['qty']; // Jumlah yang akan dikurangi dari stok
                $kodeObat = $detail['kode'];
                $today = now()->startOfDay()->toDateString();

                $stokList = gudang_barang_stok::where('kode_obat_alkes', $kodeObat)
                    ->where('qty', '>', 0)
                    ->whereDate('expired', '>=', $today)
                    ->orderBy('expired', 'asc')
                    ->get();

                foreach ($stokList as $stok) {
                    if ($qtyToDeduct <= 0) break;

                    $availableQty = $stok->qty;
                    $deductQty = min($availableQty, $qtyToDeduct);

                    // Kurangi stok
                    $stok->qty -= $deductQty;
                    $stok->save();

                    $qtyToDeduct -= $deductQty;
                }
            }

            $updated = pelayanan_soap_dokter::where('no_rawat', $validated['no_rawat'])
                ->update(['status_apotek' => '1']);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan Silahkan verifikasi di kasir Sebelum di ambil obatnya',
                'data' => $apotek,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getKodeObat(Request $request)
    {
        $nama = $request->input('nama');
        $penjamin = $request->input('penjamin');

        // Cari berdasarkan nama_obat_alkes
        $data = DB::table('gudang_barang_hargas')
            ->where('nama_obat_alkes', $nama)
            ->first();

        $query = DB::table('gudang_barang_hargas')
            ->where('nama_obat_alkes', $nama);

        if ($penjamin === 'BPJS') {
            // Ambil nilai max dari harga_jual_1
            $harga_jual = $query->max('harga_jual_1');
        } elseif ($penjamin === 'ASURANSI') {
            // Ambil nilai max dari harga_jual_2
            $harga_jual = $query->max('harga_jual_2');
        } elseif ($penjamin === 'UMUM') {
            // Ambil nilai max dari harga_jual_3
            $harga_jual = $query->max('harga_jual_3');
        } else {
            // Default ambil max harga_jual_3
            $harga_jual = $query->max('harga_jual_3');
        }

        // $harga_jual sekarang adalah nilai tertinggi sesuai penjamin

        return response()->json([
            'kode' => $data->kode_obat_alkes ?? null,
            'harga' => $harga_jual ?? null
        ]);
    }

    public function hargaBebas(Request $request)
    {
        $kode = $request->kode;
        $penjamin = strtoupper($request->penjamin); // pastikan huruf besar

        switch ($penjamin) {
            case 'BPJS':
                $harga = gudang_barang_harga::where('kode_obat_alkes', $kode)->max('harga_jual_1');
                break;
            case 'ASURANSI':
                $harga = gudang_barang_harga::where('kode_obat_alkes', $kode)->max('harga_jual_2');
                break;
            default: // UMUM atau lainnya
                $harga = gudang_barang_harga::where('kode_obat_alkes', $kode)->max('harga_jual_3');
                break;
        }

        return response()->json(['harga' => $harga]);
    }

    public function getKodeFaktur(Request $request)
    {

        try {
            // Ambil kode faktur terakhir
            $last = apotek::orderBy('id', 'desc')->first();

            $lastNumber = 1;

            if ($last && preg_match('/(\d+)$/', $last->kode_faktur, $matches)) {
                $lastNumber = (int)$matches[1] + 1;
            }

            // Buat kode faktur baru
            $datePart = date('Ymd');
            $numberPart = str_pad($lastNumber, 5, '0', STR_PAD_LEFT);
            $kodeFaktur = "RSP-$datePart-$numberPart";

            return response()->json([
                'kode' => $kodeFaktur
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal generate kode faktur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //BELI BEBAS
    public function getBeliBebas()
    {
        $last = apotek::where('no_rm', 'like', 'BBS-%')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 1;

        if ($last && preg_match('/BBS-(\d+)/', $last->no_rm, $matches)) {
            $lastNumber = (int)$matches[1] + 1;
        }

        $noRm = 'BBS-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

        return response()->json(['no_rm' => $noRm]);
    }

    public function getKodeFakturBeliBebas()
    {
        $datePart = date('Ymd');

        // Ambil angka terakhir dari semua kode_faktur yang punya format -nnnnn di akhir
        $last = apotek::where('kode_faktur', 'regexp', '-[0-9]{5}$')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 1;
        if ($last && preg_match('/-(\d{5})$/', $last->kode_faktur, $matches)) {
            $lastNumber = (int)$matches[1] + 1;
        }

        $numberPart = str_pad($lastNumber, 5, '0', STR_PAD_LEFT);

        $kodeFaktur = "BBS-$datePart-$numberPart";

        return response()->json(['kode_faktur' => $kodeFaktur]);
    }

    //Print PDF

    public function resep_dokter(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $note = $request->input('note');
        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = Pdf::loadView('pdf.resepApotek_dokter', compact('data', 'note', 'namaKlinik', 'alamatKlinik'))
            ->setPaper('a6', 'potrait');

        $filename = 'kasir_detail_lunas_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function resep_revisi(Request $request)
    {
        $resepList = json_decode($request->input('resep_data'), true);
        $note = $request->input('note');
        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = Pdf::loadView('pdf.resepApotek_revisi', [
            'resepList' => $resepList,
            'note' => $note,
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik
        ])->setPaper('a6', 'portrait');

        $filename = 'resep_obat_revisi_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }
}
