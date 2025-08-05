<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\apotek;
use App\Models\apotek_prebayar;
use App\Models\bank;
use App\Models\asuransi;
use App\Models\kasir;
use App\Models\kasir_apotek_lunas;
use App\Models\kasir_detail_lunas;
use App\Models\kasir_diskon;
use App\Models\kasir_tindakan_lunas;
use App\Models\pelayanan_soap_dokter_tindakan;
use App\Models\penjamin;
use App\Models\perawatan_kategori;
use App\Models\Pendaftaran_rawat_jalan;
use App\Models\WebSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class KasirController extends Controller
{
    public function kasir()
    {
        $title = "Kasir";

        $apotek = apotek::with([
            'detail_obat',
            'detail_tindakan' => function ($query) {
                $query->whereNotNull('Jenis_tindakan')
                    ->whereNotNull('jenis_pelaksana')
                    ->whereNotNull('harga');
            }
        ])->where('status_kasir', 0)->get();

        $tanggal = Carbon::now()->format('Ymd');

        $tindakan = pelayanan_soap_dokter_tindakan::where('status_kasir', 0)
            ->whereHas('cek_resep', function ($query) {
                $query->whereNull('resep_obat');
            })
            ->with('data_soap')
            ->get();

        $latestFaktur = kasir::where('kode_faktur', 'LIKE', "TND-{$tanggal}-%")
            ->orderBy('kode_faktur', 'desc')
            ->first();

        $lastNumber = 0;
        if ($latestFaktur) {
            $lastNumber = (int) substr($latestFaktur->kode_faktur, -4);
        }

        $kodeFakturMap = [];

        foreach ($tindakan as $item) {
            if (isset($kodeFakturMap[$item->no_rawat])) {
                $item->kode_faktur = $kodeFakturMap[$item->no_rawat];
            } else {
                $existing = kasir::where('no_rawat', $item->no_rawat)->first();
                if ($existing) {
                    $kodeFakturMap[$item->no_rawat] = $existing->kode_faktur;
                    $item->kode_faktur = $existing->kode_faktur;
                } else {
                    $lastNumber++;
                    $newNumber = str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
                    $newKodeFaktur = "TND-{$tanggal}-{$newNumber}";
                    $kodeFakturMap[$item->no_rawat] = $newKodeFaktur;
                    $item->kode_faktur = $newKodeFaktur;
                }
            }
        }

        // Filter supaya unique berdasarkan no_rawat (hanya 1 data tiap no_rawat)
        $tindakan = $tindakan->unique('no_rawat')->values();

        return view('dashboard.kasir', compact('title', 'apotek', 'tindakan'));
    }

    public function kasirPembayaran(Request $request, $kode_faktur)
    {
        $title = "Detail Pembayaran Kasir";
        $no_rawat = $request->query('no_rawat');
        $apotek = apotek::with('data_soap', 'detail_obat')->where('kode_faktur', $kode_faktur)->first();
        $tindakan = pelayanan_soap_dokter_tindakan::with('data_soap')->where('no_rawat', $no_rawat)->first();

        $apotekTabel = apotek_prebayar::where('kode_faktur', $kode_faktur)->get();
        $tindakanTabel = pelayanan_soap_dokter_tindakan::with('data_soap')->where('no_rawat', $no_rawat)->whereNotNull('jenis_pelaksana')->get();

        $penjamin = penjamin::all();
        $tindakanTambahan = perawatan_kategori::with('perawatan_tindakan')->get();

        $bank = bank::all();
        $asuransi = asuransi::all();

        // dd($tindakanTabel);
        return view('dashboard.kasir_pembayaran', compact('title', 'no_rawat', 'kode_faktur', 'apotek', 'apotekTabel', 'tindakan', 'tindakanTabel', 'penjamin', 'tindakanTambahan', 'bank', 'asuransi'));
    }

    public function kasiradd(Request $request)
    {
        try {
            $validated = $request->validate([
                'data_hidden' => 'nullable|string',
                'kode_faktur_hidden' => 'required|string',
                'no_rawat_hidden' => 'nullable|string',
                'no_rm' => 'required|string',
                'nama' => 'required|string',
                'sex' => 'nullable|string',
                'usia' => 'nullable|string',
                'alamat' => 'nullable|string',
                'poli' => 'required|string',
                'dokter' => 'nullable|string',
                'jenis_perawatan' => 'required|string',
                'penjamin' => 'required|string',
                'sub_total' => 'required|string',
                'potongan_harga' => 'nullable|string',
                'administrasi' => 'nullable|string',
                'materai' => 'nullable|string',
                'total' => 'required|string',
                'tagihan' => 'required|string',
                'kurang_dibayar' => 'required|string',
                'payment_method_1' => 'required|string',
                'payment_nominal_1' => 'required|string',
                'payment_type_1' => 'nullable|string',
                'payment_ref_1' => 'nullable|string',
                'payment_method_2' => 'nullable|string',
                'payment_nominal_2' => 'nullable|string',
                'payment_type_2' => 'nullable|string',
                'payment_ref_2' => 'nullable|string',
                'payment_method_3' => 'nullable|string',
                'payment_nominal_3' => 'nullable|string',
                'payment_type_3' => 'nullable|string',
                'payment_ref_3' => 'nullable|string',
            ], [
                'kode_faktur_hidden'  => 'Kode Faktur',
                'no_faktur_hidden'    => 'No Rawat',
                'no_rm'               => 'No RM',
                'nama'                => 'Nama Pasien',
                'sex'                 => 'Jenis Kelamin',
                'usia'                => 'Usia',
                'alamat'              => 'Alamat',
                'poli'                => 'Poli',
                'dokter'              => 'Dokter',
                'jenis_perawatan'     => 'Jenis Perawatan',
                'penjamin'            => 'Penjamin',
                'sub_total'           => 'Subtotal',
                'potongan_harga'      => 'Potongan Harga',
                'administrasi'        => 'Administrasi',
                'materai'             => 'Materai',
                'total'               => 'Total',
                'tagihan'             => 'Tagihan',
                'kurang_dibayar'      => 'Kurang Dibayar',
                'payment_method_1'    => 'Metode Pembayaran 1',
                'payment_nominal_1'   => 'Nominal Pembayaran 1',
                'payment_type_1'      => 'Tipe Pembayaran 1',
                'payment_ref_1'       => 'Referensi Pembayaran 1',
                'payment_method_2'    => 'Metode Pembayaran 2',
                'payment_nominal_2'   => 'Nominal Pembayaran 2',
                'payment_type_2'      => 'Tipe Pembayaran 2',
                'payment_ref_2'       => 'Referensi Pembayaran 2',
                'payment_method_3'    => 'Metode Pembayaran 3',
                'payment_nominal_3'   => 'Nominal Pembayaran 3',
                'payment_type_3'      => 'Tipe Pembayaran 3',
                'payment_ref_3'       => 'Referensi Pembayaran 3',
            ]);

            $kasir = kasir::create([
                'kode_faktur'       => $validated['kode_faktur_hidden'],
                'no_rawat'          => $validated['no_rawat_hidden'] ?? null,
                'no_rm'             => $validated['no_rm'],
                'nama'              => $validated['nama'],
                'sex'               => $validated['sex'] ?? null,
                'usia'              => $validated['usia'] ?? null,
                'alamat'            => $validated['alamat'] ?? null,
                'poli'              => $validated['poli'],
                'dokter'            => $validated['dokter'] ?? null,
                'jenis_perawatan'   => $validated['jenis_perawatan'],
                'penjamin'          => $validated['penjamin'],
                'tanggal'           => now()->format('Y-m-d'),
                'sub_total'         => $validated['sub_total'],
                'potongan_harga'    => $validated['potongan_harga'] ?? '0',
                'administrasi'      => $validated['administrasi'] ?? '0',
                'materai'           => $validated['materai'] ?? '0',
                'total'             => $validated['total'],
                'tagihan'           => $validated['tagihan'],
                'kembalian'         => $validated['kurang_dibayar'], // atau hitung: bayar - tagihan?

                'payment_method_1'  => $validated['payment_method_1'],
                'payment_nominal_1' => $validated['payment_nominal_1'],
                'payment_type_1'    => $validated['payment_type_1'] ?? null,
                'payment_ref_1'     => $validated['payment_ref_1'] ?? null,

                'payment_method_2'  => $validated['payment_method_2'] ?? null,
                'payment_nominal_2' => $validated['payment_nominal_2'] ?? null,
                'payment_type_2'    => $validated['payment_type_2'] ?? null,
                'payment_ref_2'     => $validated['payment_ref_2'] ?? null,

                'payment_method_3'  => $validated['payment_method_3'] ?? null,
                'payment_nominal_3' => $validated['payment_nominal_3'] ?? null,
                'payment_type_3'    => $validated['payment_type_3'] ?? null,
                'payment_ref_3'     => $validated['payment_ref_3'] ?? null,

                'user_input_id'     => Auth::user()->id,
                'user_input_name'   => Auth::user()->name,
            ]);

            // Simpan detail pembelian
            $dataDetail = json_decode($request->data_hidden, true);

            if (!empty($dataDetail['tindakan'])) {
                foreach ($dataDetail['tindakan'] as $t) {
                    kasir_detail_lunas::create([
                        'kode_faktur'          => $request->kode_faktur_hidden,
                        'no_rawat'             => $request->no_rawat_hidden ?? null,
                        'no_rm'                => $request->no_rm,
                        'nama'                 => $request->nama,
                        'nama_obat_tindakan'   => $t['jenis_tindakan'],
                        'harga_obat_tindakan'  => $t['harga'],
                        'qty_pelaksana'        => $t['jenis_pelaksana'],
                        'total'                => $t['total'],
                        'tanggal'              => $t['tanggal'],
                        'user_input_id'        => Auth::user()->id,
                        'user_input_name'      => Auth::user()->name,
                    ]);

                    kasir_tindakan_lunas::create([
                        'kode_faktur'          => $request->kode_faktur_hidden,
                        'no_rawat'             => $request->no_rawat_hidden ?? null,
                        'no_rm'                => $request->no_rm,
                        'nama'                 => $request->nama,
                        'nama_tindakan'        => $t['jenis_tindakan'],
                        'harga_tindakan'       => $t['harga'],
                        'pelaksana'            => $t['jenis_pelaksana'],
                        'total'                => $t['total'],
                        'tanggal'              => $t['tanggal'],
                        'user_input_id'        => Auth::user()->id,
                        'user_input_name'      => Auth::user()->name,
                    ]);
                }
            }

            if (!empty($dataDetail['apotek'])) {
                foreach ($dataDetail['apotek'] as $a) {
                    kasir_detail_lunas::create([
                        'kode_faktur'          => $request->kode_faktur_hidden,
                        'no_rawat'             => $request->no_rawat_hidden ?? null,
                        'no_rm'                => $request->no_rm,
                        'nama'                 => $request->nama,
                        'nama_obat_tindakan'   => $a['nama_obat_alkes'],
                        'harga_obat_tindakan'  => $a['harga'],
                        'qty_pelaksana'        => $a['qty'],
                        'total'                => $a['total'],
                        'tanggal'              => $a['tanggal'],
                        'user_input_id'        => Auth::user()->id,
                        'user_input_name'      => Auth::user()->name,
                    ]);

                    kasir_apotek_lunas::create([
                        'kode_faktur'          => $request->kode_faktur_hidden,
                        'no_rawat'             => $request->no_rawat_hidden ?? null,
                        'no_rm'                => $request->no_rm,
                        'nama'                 => $request->nama,
                        'nama_obat_alkes'      => $a['nama_obat_alkes'],
                        'harga_obat_alkes'     => $a['harga'],
                        'qty'                  => $a['qty'],
                        'total'                => $a['total'],
                        'tanggal'              => $a['tanggal'],
                        'user_input_id'        => Auth::user()->id,
                        'user_input_name'      => Auth::user()->name,
                    ]);
                }
            }

            if (!empty($dataDetail['diskon'])) {
                foreach ($dataDetail['diskon'] as $d) {
                    kasir_detail_lunas::create([
                        'kode_faktur'          => $request->kode_faktur_hidden,
                        'no_rawat'             => $request->no_rawat_hidden ?? null,
                        'no_rm'                => $request->no_rm,
                        'nama'                 => $request->nama,
                        'nama_obat_tindakan'   => $d['nama'],
                        'harga_obat_tindakan'  => abs($d['harga']),
                        'qty_pelaksana'        => $d['jenis'],
                        'total'                => abs($d['nilai']),
                        'tanggal'              => $d['tanggal'],
                        'user_input_id'        => Auth::user()->id,
                        'user_input_name'      => Auth::user()->name,
                    ]);

                    kasir_diskon::create([
                        'kode_faktur'          => $request->kode_faktur_hidden,
                        'no_rawat'             => $request->no_rawat_hidden ?? null,
                        'no_rm'                => $request->no_rm,
                        'nama'                 => $request->nama,
                        'nama_diskon'          => $d['nama'],
                        'harga_diskon'         => abs($d['harga']),
                        'qty'                  => $d['jenis'],
                        'total'                => abs($d['nilai']),
                        'tanggal'              => $d['tanggal'],
                        'user_input_id'        => Auth::user()->id,
                        'user_input_name'      => Auth::user()->name,
                    ]);
                }
            }

            $updateApotek = apotek::where('kode_faktur', $request->kode_faktur_hidden)->first();

            if ($updateApotek) {
                $updateApotek->status_kasir = 1;
                $updateApotek->save();
            }

            $updateTindakan = pelayanan_soap_dokter_tindakan::where('no_rawat', $request->no_rawat_hidden)->get();

            if ($updateTindakan->isNotEmpty()) {
                foreach ($updateTindakan as $item) {
                    $item->status_kasir = 1;
                    $item->save();
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran kasir berhasil dilakukan.',
                'data' => $kasir,
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

    public function previewData(Request $request)
    {
        $noRawat = $request->input('no_rawat');

        $data = DB::table('pelayanan_soap_dokter_tindakans')
            ->where('no_rawat', $noRawat)
            ->get(['jenis_tindakan', 'jenis_pelaksana', 'harga']);

        return response()->json($data);
    }

    public function generatePdf($kode_faktur)
    {
        $kasir = kasir::with('detail_lunas')->where('kode_faktur', $kode_faktur)->firstOrFail();

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = Pdf::loadView('pdf.kasir_bil', compact('kasir', 'namaKlinik', 'alamatKlinik'))->setPaper('a5', 'landscape');
        return $pdf->stream('kasir_' . $kode_faktur . '.pdf');
    }

    // Data Lunas Kasir
    public function datakasir_lunas()
    {
        $title = "Kasir Lunas";

        $header = kasir::all();

        return view('dashboard.datakasir_lunas', compact('title', 'header'));
    }

    // Contoh format rupiah tanpa desimal
    private function formatRupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }

    public function datakasir_lunas_print(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');

        $total_invoice = count($data);

        $cash = 0;
        $debit = 0;
        $credit = 0;
        $transfer = 0;

        foreach ($data as $item) {
            for ($i = 1; $i <= 3; $i++) {
                $methodKey = "payment_method_$i";
                $nominalKey = "payment_nominal_$i";

                if (!empty($item[$methodKey]) && !empty($item[$nominalKey])) {
                    $method = strtolower($item[$methodKey]);
                    // Hilangkan 'Rp ', titik dan spasi dari nominal sebelum konversi
                    $nominalStr = str_replace(['Rp', '.', ' '], '', $item[$nominalKey]);

                    // Cek apakah setelah dibersihkan adalah angka
                    if ($nominalStr) {
                        $nominal = $nominalStr;

                        switch ($method) {
                            case 'cash':
                                $cash += $nominal;
                                break;
                            case 'debit':
                                $debit += $nominal;
                                break;
                            case 'credit':
                                $credit += $nominal;
                                break;
                            case 'transfer':
                                $transfer += $nominal;
                                break;
                        }
                    }
                }
            }
        }

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        // Contoh penggunaan:
        $cashFormatted = $this->formatRupiah($cash);
        $debitFormatted = $this->formatRupiah($debit);
        $creditFormatted = $this->formatRupiah($credit);
        $transferFormatted = $this->formatRupiah($transfer);

        $pendapatan = $cash + $debit + $credit + $transfer;
        $pendapatanFormatted = $this->formatRupiah($pendapatan);

        $pdf = Pdf::loadView('pdf.data_lunas_kasir', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli', 'total_invoice', 'cashFormatted', 'debitFormatted', 'creditFormatted', 'transferFormatted', 'pendapatanFormatted', 'namaKlinik', 'alamatKlinik'))
            ->setPaper('a4', 'landscape');

        $filename = 'kasir_lunas_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    // Data Lunas Detail
    public function datakasir_detail()
    {
        $title = "Kasir Detail Lunas";

        $header = kasir::with('detail_lunas')->get();

        return view('dashboard.datakasir_detail_lunas', compact('title', 'header'));
    }

    public function datakasir_detail_print(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');

        $total_invoice = 0;

        foreach ($data as $item) {
            if (isset($item['is_detail']) && $item['is_detail'] == false) {
                $total_invoice++;
            }
        }

        $cash = 0;
        $debit = 0;
        $credit = 0;
        $transfer = 0;

        foreach ($data as $item) {
            for ($i = 1; $i <= 3; $i++) {
                $methodKey = "payment_method_$i";
                $nominalKey = "payment_nominal_$i";

                if (!empty($item[$methodKey]) && !empty($item[$nominalKey])) {
                    $method = strtolower($item[$methodKey]);
                    // Hilangkan 'Rp ', titik dan spasi dari nominal sebelum konversi
                    $nominalStr = str_replace(['Rp', '.', ' '], '', $item[$nominalKey]);

                    // Cek apakah setelah dibersihkan adalah angka
                    if ($nominalStr) {
                        $nominal = $nominalStr;

                        switch ($method) {
                            case 'cash':
                                $cash += $nominal;
                                break;
                            case 'debit':
                                $debit += $nominal;
                                break;
                            case 'credit':
                                $credit += $nominal;
                                break;
                            case 'transfer':
                                $transfer += $nominal;
                                break;
                        }
                    }
                }
            }
        }

        // Contoh penggunaan:
        $cashFormatted = $this->formatRupiah($cash);
        $debitFormatted = $this->formatRupiah($debit);
        $creditFormatted = $this->formatRupiah($credit);
        $transferFormatted = $this->formatRupiah($transfer);

        $pendapatan = $cash + $debit + $credit + $transfer;
        $pendapatanFormatted = $this->formatRupiah($pendapatan);

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = Pdf::loadView('pdf.data_lunas_kasir_detail', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli', 'total_invoice', 'cashFormatted', 'debitFormatted', 'creditFormatted', 'transferFormatted', 'pendapatanFormatted', 'namaKlinik', 'alamatKlinik'))
            ->setPaper('a4', 'landscape');

        $filename = 'kasir_detail_lunas_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    // Data Lunas Apotek
    public function datakasir_apotek()
    {
        $title = "Kasir Apotek Lunas";

        $header = kasir::has('apotek_lunas')->with('apotek_lunas')->get();

        $obatList = collect($header)->flatMap(function ($item) {
            return collect($item['apotek_lunas'])->pluck('nama_obat_alkes');
        })->unique()->sort()->values();

        return view('dashboard.datakasir_apotek_lunas', compact('title', 'header', 'obatList'));
    }

    public function datakasir_apotek_print(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');

        $total_invoice = 0;

        foreach ($data as $item) {
            if (isset($item['is_detail']) && $item['is_detail'] == false) {
                $total_invoice++;
            }
        }

        $pendapatan = 0;

        foreach ($data as $item) {
            $pendapatan += $item['total_sementara'];
        }

        $pendapatanFormatted = $this->formatRupiah($pendapatan);

        $obatQtySummary = []; // array penampung

        foreach ($data as $item) {
            $nama_obat = $item['nama_obat_tindakan'] ?? '-';
            $qty = (int) $item['qty_pelaksana'] ?? 0;

            if (!isset($obatQtySummary[$nama_obat])) {
                $obatQtySummary[$nama_obat] = 0;
            }

            $obatQtySummary[$nama_obat] += $qty;
        }

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = Pdf::loadView('pdf.data_lunas_kasir_apotek', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli', 'total_invoice', 'pendapatanFormatted', 'obatQtySummary', 'namaKlinik', 'alamatKlinik'))
            ->setPaper('a4', 'landscape');

        $filename = 'kasir_apotek_lunas_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    // Data Lunas Tindakan
    public function datakasir_tindakan()
    {
        $title = "Kasir Tindakan Lunas";

        $header = kasir::has('tindakan_lunas')->with('tindakan_lunas')->get();

        $tindakanList = collect($header)->flatMap(function ($item) {
            return collect($item['tindakan_lunas'])->pluck('nama_tindakan');
        })->unique()->sort()->values();

        return view('dashboard.datakasir_tindakan_lunas', compact('title', 'header', 'tindakanList'));
    }

    public function datakasir_tindakan_print(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');

        $total_invoice = 0;

        foreach ($data as $item) {
            if (isset($item['is_detail']) && $item['is_detail'] == false) {
                $total_invoice++;
            }
        }

        $pendapatan = 0;

        foreach ($data as $item) {
            $pendapatan += $item['total_sementara'];
        }

        $pendapatanFormatted = $this->formatRupiah($pendapatan);

        $tindakanQtySummary = []; // array penampung

        $tindakanQtySummary = [];

        foreach ($data as $item) {
            $namaTindakan = $item['nama_obat_tindakan'] ?? '-';

            if (!isset($tindakanQtySummary[$namaTindakan])) {
                $tindakanQtySummary[$namaTindakan] = 0;
            }

            $tindakanQtySummary[$namaTindakan] += 1; // Hitung jumlah kemunculan
        }

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = Pdf::loadView('pdf.data_lunas_kasir_tindakan', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli', 'total_invoice', 'pendapatanFormatted', 'tindakanQtySummary', 'namaKlinik', 'alamatKlinik'))
            ->setPaper('a4', 'landscape');

        $filename = 'kasir_tindakan_lunas_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    // Data Diskon
    public function datakasir_diskon()
    {
        $title = "Kasir Diskon";

        $header = kasir::has('diskon')->with('diskon')->get();

        return view('dashboard.datakasir_diskon', compact('title', 'header'));
    }

    public function datakasir_diskon_print(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $poli = $request->input('poli');

        $total_invoice = 0;

        foreach ($data as $item) {
            if (isset($item['is_detail']) && $item['is_detail'] == false) {
                $total_invoice++;
            }
        }

        $pendapatan = 0;

        foreach ($data as $item) {
            $pendapatan += $item['total_sementara'];
        }

        // Contoh format rupiah tanpa desimal
        function formatRupiah($angka)
        {
            return 'Rp ' . number_format($angka, 0, ',', '.');
        }

        $pendapatanFormatted = formatRupiah($pendapatan);

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = Pdf::loadView('pdf.data_lunas_kasir_diskon', compact('data', 'tanggal_awal', 'tanggal_akhir', 'poli', 'total_invoice', 'pendapatanFormatted', 'namaKlinik', 'alamatKlinik'))
            ->setPaper('a4', 'landscape');

        $filename = 'kasir_diskon_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function getKode1($no_rawat, $method)
    {
        $pendaftaran = Pendaftaran_rawat_jalan::where('nomor_register', $no_rawat)
            ->with('pasien')
            ->first();

        if (!$pendaftaran || !$pendaftaran->pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Data pasien tidak ditemukan.'
            ]);
        }

        $pasien = $pendaftaran->pasien;

        if ($method === 'penjaminan_bpjs') {
            if ($pasien->no_bpjs) {
                return response()->json([
                    'success' => true,
                    'no_bpjs' => $pasien->no_bpjs
                ]);
            }
        } else {
            // Cek apakah nama penjamin_2_nama cocok
            if (
                isset($pasien->penjamin_2_nama, $pasien->penjamin_2_no) &&
                $pasien->penjamin_2_nama === $method
            ) {
                return response()->json([
                    'success' => true,
                    'no_bpjs' => $pasien->penjamin_2_no
                ]);
            }

            // Cek apakah nama penjamin_3_nama cocok
            if (
                isset($pasien->penjamin_3_nama, $pasien->penjamin_3_no) &&
                $pasien->penjamin_3_nama === $method
            ) {
                return response()->json([
                    'success' => true,
                    'no_bpjs' => $pasien->penjamin_3_no
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Data penjamin tidak ditemukan.'
        ]);
    }

    public function getKode2($no_rawat, $method)
    {
        $pendaftaran = Pendaftaran_rawat_jalan::where('nomor_register', $no_rawat)
            ->with('pasien')
            ->first();

        if (!$pendaftaran || !$pendaftaran->pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Data pasien tidak ditemukan.'
            ]);
        }

        $pasien = $pendaftaran->pasien;

        if (
            isset($pasien->penjamin_2_nama, $pasien->penjamin_2_no) &&
            $pasien->penjamin_2_nama === $method
        ) {
            return response()->json([
                'success' => true,
                'no_bpjs' => $pasien->penjamin_2_no
            ]);
        }

        // Cek apakah nama penjamin_3_nama cocok
        if (
            isset($pasien->penjamin_3_nama, $pasien->penjamin_3_no) &&
            $pasien->penjamin_3_nama === $method
        ) {
            return response()->json([
                'success' => true,
                'no_bpjs' => $pasien->penjamin_3_no
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data penjamin tidak ditemukan.'
        ]);
    }

    public function getKode3($no_rawat, $method)
    {
        $pendaftaran = Pendaftaran_rawat_jalan::where('nomor_register', $no_rawat)
            ->with('pasien')
            ->first();

        if (!$pendaftaran || !$pendaftaran->pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Data pasien tidak ditemukan.'
            ]);
        }

        $pasien = $pendaftaran->pasien;

        if (
            isset($pasien->penjamin_2_nama, $pasien->penjamin_2_no) &&
            $pasien->penjamin_2_nama === $method
        ) {
            return response()->json([
                'success' => true,
                'no_bpjs' => $pasien->penjamin_2_no
            ]);
        }

        // Cek apakah nama penjamin_3_nama cocok
        if (
            isset($pasien->penjamin_3_nama, $pasien->penjamin_3_no) &&
            $pasien->penjamin_3_nama === $method
        ) {
            return response()->json([
                'success' => true,
                'no_bpjs' => $pasien->penjamin_3_no
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data penjamin tidak ditemukan.'
        ]);
    }

    public function getKodePenjamin($no_rawat, $method)
    {
        $pendaftaran = Pendaftaran_rawat_jalan::where('nomor_register', $no_rawat)
            ->with('pasien')
            ->first();

        if (!$pendaftaran || !$pendaftaran->pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Data pasien tidak ditemukan.'
            ]);
        }

        $pasien = $pendaftaran->pasien;

        // Penjaminan BPJS
        if ($method === 'penjaminan_bpjs' && $pasien->no_bpjs) {
            return response()->json([
                'success' => true,
                'no_bpjs' => $pasien->no_bpjs
            ]);
        }

        // Penjaminan Asuransi: cek penjamin_2 dan penjamin_3
        foreach ([2, 3] as $i) {
            $nama = $pasien->{"penjamin_{$i}_nama"} ?? null;
            $nomor = $pasien->{"penjamin_{$i}_no"} ?? null;

            if ($nama === $method && $nomor) {
                return response()->json([
                    'success' => true,
                    'no_bpjs' => $nomor
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Data penjamin tidak ditemukan.'
        ]);
    }


}
