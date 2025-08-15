<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Gudang\Request;

use App\Http\Controllers\Controller;
use App\Models\external_database;
use App\Models\gudang_barang;
use App\Models\gudang_barang_harga;
use App\Models\gudang_barang_stok;
use App\Models\gudang_setting_harga;
use App\Models\WebSetting;
use App\Models\gudang_klinik_request;
use App\Models\gudang_utama_keluar;
use App\Models\gudang_penyesuaian_masuk_utama;
use App\Models\gudang_penyesuaian_masuk;
use App\Models\gudang_barang_stok_utama;
use App\Models\gudang_klinik_request_details;
use Carbon\Carbon;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Gudang_Request_Controller extends Controller
{
    public function gudangrequest()
    {
        $title = "Request Stok Obat Alkes";
        $dabar = gudang_barang::all();
        $stok = gudang_barang_stok::all();
        $singkron = external_database::where('active', 1)->get();

        $kodeKlinik = WebSetting::first()->kode_klinik;
        $namaKlinik = WebSetting::first()->nama;

        if (WebSetting::first()->is_gudangutama_active == 0) {
            $connExternal = external_database::where('active', 1)->first();

            if ($connExternal) {
                $config = [
                    'driver'    => 'mysql',
                    'host'      => $connExternal->host,
                    'database'  => $connExternal->database,
                    'username'  => $connExternal->username,
                    'password'  => $connExternal->password,
                    'port'      => $connExternal->port ?? 3306,
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ];

                $factory = app(ConnectionFactory::class);
                $connection = $factory->make($config, $connExternal->name);

                // Gunakan koneksi ini untuk query
                $request = $connection->table('gudang_klinik_requests')
                    ->where('kode_klinik', $kodeKlinik)
                    ->get();

                $data_kirim = $connection->table('gudang_utama_keluars')
                    ->select('kode_request', 'tanggal_request', 'nama_klinik')
                    ->where('nama_klinik', $namaKlinik)
                    ->groupBy('kode_request', 'tanggal_request', 'nama_klinik')
                    ->get();
            } else {
                $request = collect(); // kosong jika tidak ada koneksi eksternal
                $data_kirim = collect(); // kosong jika tidak ada koneksi eksternal
            }
        } elseif (WebSetting::first()->is_gudangutama_active == 1) {
            $request = gudang_klinik_request::where('kode_klinik', $kodeKlinik)
                ->get();
            $data_kirim = gudang_utama_keluar::select('kode_request', 'tanggal_request', 'nama_klinik')
                ->where('nama_klinik', $namaKlinik)
                ->groupBy('kode_request', 'tanggal_request', 'nama_klinik')
                ->get();
        }

        return view('module.master-data-gudang.request', compact('title', 'dabar', 'request', 'singkron', 'data_kirim', 'stok'));
    }

    public function gudangrequestadd(Request $request)
    {
        try {
            $request->validate([
                'data_tabel_request'  => 'required|string',
                'kode_request'  => 'required|string',
                'external_database'  => 'required|string',
            ], [
                'data_tabel_request'  => 'Tidak Input Request',
                'kode_request'  => 'Kode Request',
                'external_database'  => 'Koneksi Database',
            ]);

            $kodeKlinik = WebSetting::first()->kode_klinik;
            $namaKlinik = WebSetting::first()->nama;

            if (WebSetting::first()->is_gudangutama_active == 0) {
                // Ambil ID dari input request
                $externalDbId = $request->input('external_database');

                // Ambil data dari tabel external_database
                $externalDb = external_database::findOrFail($externalDbId);

                // Buat config koneksi
                $config = [
                    'driver'    => 'mysql',
                    'host'      => $externalDb->host,
                    'database'  => $externalDb->database,
                    'username'  => $externalDb->username,
                    'password'  => $externalDb->password,
                    'port'      => $externalDb->port ?? 3306,
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ];

                $factory = app(ConnectionFactory::class);
                $connection = $factory->make($config, $externalDb->name);

                $kodeRequest = $request->kode_request;

                $connection->table('gudang_klinik_requests')->insert([
                    'kode_request' => $kodeRequest,
                    'kode_klinik' => $kodeKlinik,
                    'nama_klinik' => $namaKlinik,
                    'status' => 0,
                    'tanggal_input' => now()->toDateString(),
                    'user_input_id' => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                // Simpan detail pembelian
                $dataDetail = json_decode($request->data_tabel_request, true);

                foreach ($dataDetail as $detail) {
                    $connection->table('gudang_klinik_request_details')->insert([
                        'kode_request'    => $request->input('kode_request'),
                        'kode_obat_alkes' => $detail['kode_barang'],
                        'nama_obat_alkes' => $detail['nama_obat'],
                        'qty'             => $detail['jumlah'],
                        'user_input_id'   => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }
            } elseif (WebSetting::first()->is_gudangutama_active == 1) {
                $kodeRequest = $request->kode_request;

                $connection = gudang_klinik_request::create([
                    'kode_request'     => $kodeRequest,
                    'kode_klinik'      => $kodeKlinik,
                    'nama_klinik'      => $namaKlinik,
                    'status'           => 0,
                    'tanggal_input'    => now()->toDateString(),
                    'user_input_id'    => Auth::user()->id,
                    'user_input_name'  => Auth::user()->name,
                ]);

                $dataDetail = json_decode($request->data_tabel_request, true);

                foreach ($dataDetail as $detail) {
                    gudang_klinik_request_details::create([
                        'kode_request'     => $kodeRequest,
                        'kode_obat_alkes'  => $detail['kode_barang'],
                        'nama_obat_alkes'  => $detail['nama_obat'],
                        'qty'              => $detail['jumlah'],
                        'user_input_id'    => Auth::user()->id,
                        'user_input_name'  => Auth::user()->name,
                    ]);
                }
            }

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Request obat / alkes berhasil dilakukan!',
                'data' => $connection
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Request obat / alkes sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan request obat / alkes!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
