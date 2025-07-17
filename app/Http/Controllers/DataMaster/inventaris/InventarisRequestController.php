<?php

namespace App\Http\Controllers\DataMaster\inventaris;

use App\Http\Controllers\Controller;
use App\Models\external_database;
use App\Models\inventaris_data_barang;
use App\Models\inventaris_stok;
use App\Models\inventaris_utama_keluar;
use App\Models\inventaris_request_detail;
use App\Models\inventaris_request;
use App\Models\WebSetting;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class InventarisRequestController extends Controller
{
    public function inventarisrequest()
    {
        $title = "Request Data Inventaris";
        $inventaris = inventaris_data_barang::all();
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
                $request = $connection->table('inventaris_requests')
                    ->where('kode_klinik', $kodeKlinik)
                    ->get();

                $data_kirim = $connection->table('inventaris_utama_keluars')
                    ->select('kode_request', 'tanggal_request', 'nama_klinik')
                    ->where('nama_klinik', $namaKlinik)
                    ->groupBy('kode_request', 'tanggal_request', 'nama_klinik')
                    ->get();
            } else {
                $request = collect(); // kosong jika tidak ada koneksi eksternal
                $data_kirim = collect(); // kosong jika tidak ada koneksi eksternal
            }
        } elseif (WebSetting::first()->is_gudangutama_active == 1) {
            $request = inventaris_request::where('kode_klinik', $kodeKlinik)
                ->get();
            $data_kirim = inventaris_utama_keluar::select('kode_request', 'tanggal_request', 'nama_klinik')
                ->where('nama_klinik', $namaKlinik)
                ->groupBy('kode_request', 'tanggal_request', 'nama_klinik')
                ->get();
        }

        return view('module.master-data-gudang.request_inventaris', compact('title', 'inventaris', 'request', 'singkron', 'data_kirim'));
    }

    public function inventarisrequestadd(Request $request)
    {
        try {
            $request->validate([
                'data_tabel_request'  => 'required|string',
                'kode_request'        => 'required|string',
                'external_database'   => 'required|string',
            ], [
                'data_tabel_request'  => 'Tidak Input Request',
                'kode_request'        => 'Kode Request',
                'external_database'   => 'Koneksi Database',
            ]);

            $kodeKlinik = WebSetting::first()->kode_klinik;
            $namaKlinik = WebSetting::first()->nama;

            if (WebSetting::first()->is_gudangutama_active == 0) {
                $externalDbId = $request->input('external_database');
                $externalDb = external_database::findOrFail($externalDbId);

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

                $connection->table('inventaris_requests')->insert([
                    'kode_request'   => $kodeRequest,
                    'kode_klinik'    => $kodeKlinik,
                    'nama_klinik'    => $namaKlinik,
                    'status'         => 0,
                    'tanggal_input'  => now()->toDateString(),
                    'user_input_id'  => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                $dataDetail = json_decode($request->data_tabel_request, true);

                foreach ($dataDetail as $detail) {
                    $connection->table('inventaris_request_details')->insert([
                        'kode_request'   => $kodeRequest,
                        'kode_barang'    => $detail['kode_barang'],
                        'nama_barang'    => $detail['nama_barang'],
                        'qty'            => $detail['jumlah'],
                        'user_input_id'  => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }
            } elseif (WebSetting::first()->is_gudangutama_active == 1) {
                $kodeRequest = $request->kode_request;

                $connection = inventaris_request::create([
                    'kode_request'    => $kodeRequest,
                    'kode_klinik'     => $kodeKlinik,
                    'nama_klinik'     => $namaKlinik,
                    'status'          => 0,
                    'tanggal_input'   => now()->toDateString(),
                    'user_input_id'   => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                ]);

                $dataDetail = json_decode($request->data_tabel_request, true);

                foreach ($dataDetail as $detail) {
                    inventaris_request_detail::create([
                        'kode_request'    => $kodeRequest,
                        'kode_barang'     => $detail['kode_barang'],
                        'nama_barang'     => $detail['nama_barang'],
                        'qty'             => $detail['jumlah'],
                        'user_input_id'   => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Request data inventaris berhasil dilakukan!',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Request data inventaris sudah ada!',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan request data inventaris!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function inventaris_request_getLastKode()
    {
        $webSetting = WebSetting::first();
        $kodeKlinik = $webSetting->kode_klinik;
        $tanggal    = now()->format('Ymd'); // Format YYYYMMDD

        $kodeBaru = null;
        $last = null;

        if ($webSetting->is_gudangutama_active == 0) {
            // Ambil dari database eksternal
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

                $last = $connection->table('inventaris_requests')
                    ->where('kode_klinik', $kodeKlinik)
                    ->where('kode_request', 'like', "{$kodeKlinik}-%-%")
                    ->orderByDesc('kode_request')
                    ->first();
            }
        } elseif ($webSetting->is_gudangutama_active == 1) {
            $last = inventaris_request::where('kode_klinik', $kodeKlinik)
                ->where('kode_request', 'like', "{$kodeKlinik}-%-%")
                ->orderByDesc('kode_request')
                ->first();
        }

        // Tentukan nomor urut
        $lastNumber = $last ? (int) substr($last->kode_request, -5) : 0;
        $nextNumber = $lastNumber + 1;
        $kodeBaru   = $kodeKlinik . '-' . $tanggal . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return response()->json([
            'kode_request' => $kodeBaru
        ]);
    }

    public function inventaris_getDetails($kodeRequest)
    {
        if (WebSetting::first()->is_gudangutama_active == 0) {
            // Ambil koneksi external (contoh id=1)
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
                $details = $connection->table('inventaris_request_details')
                    ->where('kode_request', $kodeRequest)
                    ->select('kode_barang', 'nama_barang', 'qty')
                    ->get();
            } else {
                $details = collect(); // kosong jika tidak ada koneksi eksternal
            }
        } elseif (WebSetting::first()->is_gudangutama_active == 1) {
            $details = inventaris_request_detail::where('kode_request', $kodeRequest)
                ->select('kode_barang', 'nama_barang', 'qty')
                ->get();
        }

        return response()->json([
            'details' => $details
        ]);
    }

    public function inventaris_detailsAprroval($kodeRequest)
    {
        if (WebSetting::first()->is_gudangutama_active == 0) {
            // Ambil koneksi external (contoh id=1)
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
                $details = $connection->table('inventaris_utama_keluars')
                    ->where('kode_request', $kodeRequest)
                    ->get();
            } else {
                $details = collect(); // kosong jika tidak ada koneksi eksternal
            }
        } elseif (WebSetting::first()->is_gudangutama_active == 1) {
            $details = inventaris_utama_keluar::where('kode_request', $kodeRequest)
                ->get();
        }

        return response()->json([
            'details' => $details
        ]);
    }

    public function inventaris_terimaData(Request $request, $id)
    {
        try {
            if (WebSetting::first()->is_gudangutama_active == 0) {
                // Ambil koneksi eksternal (misalnya id = 1)
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
                $data = $connection->table('inventaris_utama_keluars')->where('id', $id)->first();

                if (!$data) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data tidak ditemukan.'
                    ], 404);
                }

                // Simpan ke tabel tujuan
                inventaris_stok::create([
                    'kode_pembelian' => $data->kode_request,
                    'kode_barang' => $data->kode_barang,
                    'nama_barang' => $data->nama_barang,
                    'kategori_barang' => $data->kategori_barang,
                    'jenis_barang' => $data->jenis_barang,
                    'qty_barang' => $data->qty_barang,
                    'harga_barang' => $data->harga_barang,
                    'masa_akhir_penggunaan' => $data->masa_akhir_penggunaan,
                    'tanggal_pembelian' => $data->tanggal_pembelian,
                    'detail_barang' => $data->detail_barang,
                    'lokasi' => null,
                    'penanggung_jawab' => null,
                    'kondisi' => null,
                    'no_seri' => null,
                    'user_input_id' => $request->input('user_id'),
                    'user_input_name' => $request->input('user_name'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $connection->table('inventaris_utama_keluars')->where('id', $id)->delete();
            } elseif (WebSetting::first()->is_gudangutama_active == 1) {
                // Ambil data berdasarkan ID
                $data = inventaris_utama_keluar::where('id', $id)->first();

                if (!$data) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data tidak ditemukan.'
                    ], 404);
                }

                // Simpan ke tabel tujuan
                inventaris_stok::create([
                    'kode_pembelian' => $data->kode_request,
                    'kode_barang' => $data->kode_barang,
                    'nama_barang' => $data->nama_barang,
                    'kategori_barang' => $data->kategori_barang,
                    'jenis_barang' => $data->jenis_barang,
                    'qty_barang' => $data->qty_barang,
                    'harga_barang' => $data->harga_barang,
                    'masa_akhir_penggunaan' => $data->masa_akhir_penggunaan,
                    'tanggal_pembelian' => $data->tanggal_pembelian,
                    'detail_barang' => $data->detail_barang,
                    'lokasi' => null,
                    'penanggung_jawab' => null,
                    'kondisi' => null,
                    'no_seri' => null,
                    'user_input_id' => $request->input('user_id'),
                    'user_input_name' => $request->input('user_name'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                inventaris_utama_keluar::where('id', $id)->delete();
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

    public function inventaris_tolakData(Request $request, $id)
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

                $data = $connection->table('inventaris_utama_keluars')->where('id', $id)->first();

                if (!$data) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data tidak ditemukan.'
                    ], 404);
                }

                // Tambahkan kembali qty ke gudang_barang
                $connection->table('inventaris_stok_utamas')->insert([
                    'kode_pembelian' => $data->kode_request,
                    'kode_barang' => $data->kode_barang,
                    'nama_barang' => $data->nama_barang,
                    'kategori_barang' => $data->kategori_barang,
                    'jenis_barang' => $data->jenis_barang,
                    'qty_barang' => $data->qty_barang,
                    'harga_barang' => $data->harga_barang,
                    'masa_akhir_penggunaan' => $data->masa_akhir_penggunaan,
                    'tanggal_pembelian' => $data->tanggal_pembelian,
                    'detail_barang' => $data->detail_barang,
                    'lokasi' => null,
                    'penanggung_jawab' => null,
                    'kondisi' => null,
                    'no_seri' => null,
                    'user_input_id' => $request->input('user_id'),
                    'user_input_name' => $request->input('user_name'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Hapus data dari keluar
                $connection->table('inventaris_utama_keluars')->where('id', $id)->delete();
            } elseif (WebSetting::first()->is_gudangutama_active == 1) {
                $data = inventaris_utama_keluar::where('id', $id)->first();

                if (!$data) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data tidak ditemukan.'
                    ], 404);
                }

                // Tambahkan kembali qty ke gudang_barang
                inventaris_stok_utama::create([
                    'kode_pembelian' => $data->kode_request,
                    'kode_barang' => $data->kode_barang,
                    'nama_barang' => $data->nama_barang,
                    'kategori_barang' => $data->kategori_barang,
                    'jenis_barang' => $data->jenis_barang,
                    'qty_barang' => $data->qty_barang,
                    'harga_barang' => $data->harga_barang,
                    'masa_akhir_penggunaan' => $data->masa_akhir_penggunaan,
                    'tanggal_pembelian' => $data->tanggal_pembelian,
                    'detail_barang' => $data->detail_barang,
                    'lokasi' => null,
                    'penanggung_jawab' => null,
                    'kondisi' => null,
                    'no_seri' => null,
                    'user_input_id' => $request->input('user_id'),
                    'user_input_name' => $request->input('user_name'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Hapus data dari keluar
                inventaris_utama_keluar::where('id', $id)->delete();
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
