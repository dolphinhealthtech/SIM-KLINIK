<?php

namespace App\Http\Controllers\DataMaster\inventaris;

use App\Http\Controllers\Controller;
use App\Models\external_database;
use App\Models\inventaris_data_barang;
use App\Models\inventaris_stok;
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
        $singkron = external_database::all();

        // Ambil koneksi external (contoh id=1)
        $connExternal = external_database::find(1);

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
                                ->where('kode_klinik', 'BLRJ')
                                ->get();

            $data_kirim = $connection->table('inventaris_utama_keluars')
                                ->select('kode_request', 'tanggal_request', 'nama_klinik')
                                ->where('nama_klinik', 'Klinik Balaraja')
                                ->groupBy('kode_request', 'tanggal_request', 'nama_klinik')
                                ->get();

        } else {
            $request = collect(); // kosong jika tidak ada koneksi eksternal
            $data_kirim = collect(); // kosong jika tidak ada koneksi eksternal
        }

        return view('module.master-data-gudang.request_inventaris', compact('title', 'inventaris','request','singkron','data_kirim'));
    }

    public function inventarisrequestadd(Request $request)
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
            $kodeKlinik = explode('-', $kodeRequest)[0];

            // Mapping kode klinik ke nama
            $namaKlinik = match($kodeKlinik) {
                'KRNJ' => 'Klinik Kronjo',
                'BLRJ' => 'Klinik Balaraja',
                'KRSK' => 'Klinik Kresek',
                'RJEG' => 'Klinik Rajeg',
                'CITR' => 'Klinik Citra',
                'TGRS' => 'Klinik Tiga Raksa',
                'CTRY' => 'Klinik Citra Raya',
                'JAYA' => 'Klinik Jaya',
                default => 'Klinik Tidak Dikenal'
            };

            $connection->table('inventaris_requests')->insert([
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
                $connection->table('inventaris_request_details')->insert([
                    'kode_request'    => $request->input('kode_request'),
                    'kode_barang' => $detail['kode_barang'],
                    'nama_barang' => $detail['nama_barang'],
                    'qty'             => $detail['jumlah'],
                    'user_input_id'   => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Request data inventaris berhasil dilakukan!',
                'data' => $connection
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Request data inventaris sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan request data inventaris!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function inventaris_request_getLastKode()
    {
        $kodeKlinik = 'BLRJ'; // Bisa juga ambil dari request jika dynamic
        $tanggal = now()->format('Ymd'); // Format YYYYMMDD

        $connExternal = external_database::find(1); // Ambil koneksi eksternal

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

            // Ambil kode terakhir yang sesuai tanggal & klinik
            $last = $connection->table('inventaris_requests')
                ->where('kode_klinik', $kodeKlinik)
                ->where('kode_request', 'like', "{$kodeKlinik}-{$tanggal}-%")
                ->orderByDesc('kode_request')
                ->first();

            // Tentukan nomor urut
            if ($last) {
                $lastNumber = (int) substr($last->kode_request, -5); // Ambil 5 digit terakhir
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $kodeBaru = $kodeKlinik . '-' . $tanggal . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            return response()->json([
                'kode_request' => $kodeBaru
            ]);
        }

        return response()->json([
            'kode_request' => null
        ]);
    }

    public function inventaris_getDetails($kodeRequest)
    {
        // Ambil koneksi external (contoh id=1)
        $connExternal = external_database::find(1);

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

        return response()->json([
            'details' => $details
        ]);
    }

    public function inventaris_detailsAprroval($kodeRequest)
    {
        // Ambil koneksi external (contoh id=1)
        $connExternal = external_database::find(1);

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

        return response()->json([
            'details' => $details
        ]);
    }

    public function inventaris_terimaData(Request $request, $id)
    {
        try {
            // Ambil koneksi eksternal (misalnya id = 1)
            $connExternal = external_database::find(1);

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
            $connExternal = external_database::find(1);

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
            $connection->table('inventaris_stoks')->insert([
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
