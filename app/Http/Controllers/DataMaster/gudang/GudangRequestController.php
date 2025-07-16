<?php

namespace App\Http\Controllers\DataMaster\gudang;

use App\Http\Controllers\Controller;
use App\Models\external_database;
use App\Models\gudang_barang;
use App\Models\gudang_barang_harga;
use App\Models\gudang_barang_stok;
use App\Models\gudang_setting_harga;
use App\Models\WebSetting;
use App\Models\gudang_klinik_request;
use App\Models\gudang_utama_keluar;
use Carbon\Carbon;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class GudangRequestController extends Controller
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

        return view('module.master-data-gudang.request', compact('title', 'dabar','request','singkron','data_kirim','stok'));
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

        //API
        public function getDetails($kodeRequest)
        {

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

                    // Query detail request berdasarkan kode_request
                    $details = $connection->table('gudang_klinik_request_details')
                        ->where('kode_request', $kodeRequest)
                        ->select('kode_obat_alkes', 'nama_obat_alkes', 'qty')
                        ->get();

                } else {
                    $details = collect(); // kosong jika tidak ada koneksi eksternal
                }

            } elseif (WebSetting::first()->is_gudangutama_active == 1) {
                $details = gudang_klinik_request_details::where('kode_request', $kodeRequest)
                            ->select('kode_obat_alkes', 'nama_obat_alkes', 'qty')
                            ->get();
            }



            return response()->json([
                'details' => $details
            ]);
        }

        //API details approval

        public function detailsAprroval($kodeRequest)
        {

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

                    // Query detail request berdasarkan kode_request
                    $details = $connection->table('gudang_utama_keluars')
                        ->where('kode_request', $kodeRequest)
                        ->get();

                } else {
                    $details = collect(); // kosong jika tidak ada koneksi eksternal
                }
            } elseif (WebSetting::first()->is_gudangutama_active == 1) {
                $details = gudang_utama_keluar::where('kode_request', $kodeRequest)
                            ->get();
            }

            return response()->json([
                'details' => $details
            ]);
        }

        // API Get Kode Supplier Industri

        public function request_getLastKode()
        {
            $webSetting = WebSetting::first(); // Hindari pemanggilan berkali-kali
            $kodeKlinik = $webSetting->kode_klinik;
            $tanggal    = now()->format('Ymd'); // Format YYYYMMDD

            $kodeBaru = null;
            $last     = null;

            if ($webSetting->is_gudangutama_active == 0) {
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

                    $factory     = app(ConnectionFactory::class);
                    $connection  = $factory->make($config, $connExternal->name);

                    $last = $connection->table('gudang_klinik_requests')
                        ->where('kode_klinik', $kodeKlinik)
                        ->where('kode_request', 'like', "{$kodeKlinik}-%-%")
                        ->orderByDesc('kode_request')
                        ->first();
                }
            } elseif ($webSetting->is_gudangutama_active == 1) {
                $last = gudang_klinik_request::where('kode_klinik', $kodeKlinik)
                    ->where('kode_request', 'like', "{$kodeKlinik}-%-%")
                    ->orderByDesc('kode_request')
                    ->first();
            }

            // Buat kode baru jika $last ditemukan
            $lastNumber = $last ? (int) substr($last->kode_request, -5) : 0;
            $nextNumber = $lastNumber + 1;
            $kodeBaru   = $kodeKlinik . '-' . $tanggal . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            return response()->json([
                'kode_request' => $kodeBaru
            ]);
        }


        // API Terima Data
        public function terimaData(Request $request, $id)
        {
            try {
                if (WebSetting::first()->is_gudangutama_active == 0) {
                    $connExternal = external_database::where('active', 1)->first();

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

                    // Ambil data berdasarkan ID
                    $data = $connection->table('gudang_utama_keluars')->where('id', $id)->first();

                    if (!$data) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Data tidak ditemukan.'
                        ], 404);
                    }

                    // Simpan ke tabel tujuan (misal: gudang_klinik_masuk)
                    gudang_barang_stok::create([
                        'kode_obat_alkes' => $data->kode_obat_alkes,
                        'nama_obat_alkes' => $data->nama_obat_alkes,
                        'qty' => $data->qty,
                        'tanggal_terima_obat' => Carbon::now()->toDateString(),
                        'expired' => $data->expired,
                        'user_input_id' => $request->input('user_id'),
                        'user_input_name' => $request->input('user_name'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Ambil margin setting
                    $setting = gudang_setting_harga::first(); // atau where('active', 1)->first()

                    // Hitung harga jual
                    $harga_dasar = $data->harga_dasar;

                    $harga_jual_1 = $harga_dasar + ($harga_dasar * ($setting->harga_jual_1 / 100));
                    $harga_jual_2 = $harga_dasar + ($harga_dasar * ($setting->harga_jual_2 / 100));
                    $harga_jual_3 = $harga_dasar + ($harga_dasar * ($setting->harga_jual_3 / 100));

                    gudang_barang_harga::create([
                        'kode_obat_alkes' => $data->kode_obat_alkes,
                        'nama_obat_alkes' => $data->nama_obat_alkes,
                        'harga_dasar' => $data->harga_dasar,
                        'harga_jual_1' => $harga_jual_1,
                        'harga_jual_2' => $harga_jual_2,
                        'harga_jual_3' => $harga_jual_3,
                        'diskon' => 0,
                        'ppn' => 0,
                        'tanggal_obat_masuk' => Carbon::now()->toDateString(),
                        'user_input_id' => $request->input('user_id'),
                        'user_input_name' => $request->input('user_name'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Hapus dari gudang_utama_keluars (opsional)
                    $connection->table('gudang_utama_keluars')->where('id', $id)->delete();

                } elseif (WebSetting::first()->is_gudangutama_active == 1) {
                    // Ambil data berdasarkan ID
                    $data = gudang_utama_keluar::where('id', $id)->first();

                    if (!$data) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Data tidak ditemukan.'
                        ], 404);
                    }

                    // Simpan ke tabel tujuan (misal: gudang_klinik_masuk)
                    gudang_barang_stok::create([
                        'kode_obat_alkes' => $data->kode_obat_alkes,
                        'nama_obat_alkes' => $data->nama_obat_alkes,
                        'qty' => $data->qty,
                        'tanggal_terima_obat' => Carbon::now()->toDateString(),
                        'expired' => $data->expired,
                        'user_input_id' => $request->input('user_id'),
                        'user_input_name' => $request->input('user_name'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Ambil margin setting
                    $setting = gudang_setting_harga::first(); // atau where('active', 1)->first()

                    // Hitung harga jual
                    $harga_dasar = $data->harga_dasar;

                    $harga_jual_1 = $harga_dasar + ($harga_dasar * ($setting->harga_jual_1 / 100));
                    $harga_jual_2 = $harga_dasar + ($harga_dasar * ($setting->harga_jual_2 / 100));
                    $harga_jual_3 = $harga_dasar + ($harga_dasar * ($setting->harga_jual_3 / 100));

                    gudang_barang_harga::create([
                        'kode_obat_alkes' => $data->kode_obat_alkes,
                        'nama_obat_alkes' => $data->nama_obat_alkes,
                        'harga_dasar' => $data->harga_dasar,
                        'harga_jual_1' => $harga_jual_1,
                        'harga_jual_2' => $harga_jual_2,
                        'harga_jual_3' => $harga_jual_3,
                        'diskon' => 0,
                        'ppn' => 0,
                        'tanggal_obat_masuk' => Carbon::now()->toDateString(),
                        'user_input_id' => $request->input('user_id'),
                        'user_input_name' => $request->input('user_name'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Hapus dari gudang_utama_keluars (opsional)
                    gudang_utama_keluar::where('id', $id)->delete();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil diterima dan dipindahkan.'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
        }

        // API Tolak Data
        public function tolakData(Request $request, $id)
        {
            try {
                if (WebSetting::first()->is_gudangutama_active == 0) {
                    $connExternal = external_database::where('active', 1)->first();

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

                    $data = $connection->table('gudang_utama_keluars')->where('id', $id)->first();

                    if (!$data) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Data tidak ditemukan.'
                        ], 404);
                    }

                    // Tambahkan kembali qty ke gudang_barang
                    $connection->table('gudang_barang_stok_utamas')->insert([
                        'kode_obat_alkes' => $data->kode_obat_alkes,
                        'nama_obat_alkes' => $data->nama_obat_alkes,
                        'qty' => $data->qty,
                        'tanggal_terima_obat' => $data->tanggal_terima_obat,
                        'expired' => $data->expired,
                        'user_input_id' => $request->input('user_id'),
                        'user_input_name' => $request->input('user_name'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Hapus data dari keluar
                    $connection->table('gudang_utama_keluars')->where('id', $id)->delete();

                } elseif (WebSetting::first()->is_gudangutama_active == 1) {
                    $data = gudang_utama_keluar::where('id', $id)->first();

                    if (!$data) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Data tidak ditemukan.'
                        ], 404);
                    }

                    // Tambahkan kembali qty ke gudang_barang
                    gudang_barang_stok_utama::create([
                        'kode_obat_alkes' => $data->kode_obat_alkes,
                        'nama_obat_alkes' => $data->nama_obat_alkes,
                        'qty' => $data->qty,
                        'tanggal_terima_obat' => $data->tanggal_terima_obat,
                        'expired' => $data->expired,
                        'user_input_id' => $request->input('user_id'),
                        'user_input_name' => $request->input('user_name'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Hapus data dari keluar
                    gudang_utama_keluar::where('id', $id)->delete();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Stok berhasil dikembalikan ke gudang.'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
        }
}
