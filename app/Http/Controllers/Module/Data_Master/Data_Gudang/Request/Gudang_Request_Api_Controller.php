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

class Gudang_Request_Api_Controller extends Controller
{
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

                gudang_penyesuaian_masuk::create([
                    'kode_obat' => $data->kode_obat_alkes,
                    'nama_obat' => $data->nama_obat_alkes,
                    'qty_sebelum' => '0',
                    'qty_mutasi' => $data->qty,
                    'qty_sesudah' => '0',
                    'jenis_penyesuaian' => 'PERMINTAAN OBAT',
                    'alasan' => "Permintaan pada tanggal {$data->tanggal_request}",
                    'tanggal' => now()->toDateString(),
                    'jam' => now()->toTimeString(),
                    'harga' => $data->harga_dasar,
                    'expired' => $data->expired,
                    'user_input_id' => $request->input('user_id'),
                    'user_input_name' => $request->input('user_name'),
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

                gudang_penyesuaian_masuk::create([
                    'kode_obat' => $data->kode_obat_alkes,
                    'nama_obat' => $data->nama_obat_alkes,
                    'qty_sebelum' => '0',
                    'qty_mutasi' => $data->qty,
                    'qty_sesudah' => '0',
                    'jenis_penyesuaian' => 'PERMINTAAN OBAT',
                    'alasan' => "Permintaan pada tanggal {$data->tanggal_request}",
                    'tanggal' => now()->toDateString(),
                    'jam' => now()->toTimeString(),
                    'harga' => $data->harga_dasar,
                    'expired' => $data->expired,
                    'user_input_id' => $request->input('user_id'),
                    'user_input_name' => $request->input('user_name'),
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

                $connection->table('gudang_penyesuaian_masuk_utamas')->insert([
                    'kode_obat' => $data->kode_obat_alkes,
                    'nama_obat' => $data->nama_obat_alkes,
                    'qty_sebelum' => '0',
                    'qty_mutasi' => $data->qty,
                    'qty_sesudah' => '0',
                    'jenis_penyesuaian' => 'BARANG DITOLAK',
                    'alasan' => "Kesalahan Pengiriman Barang Pada {$data->nama_klinik}",
                    'tanggal' => now()->toDateString(),
                    'jam' => now()->toTimeString(),
                    'harga' => $data->harga_dasar,
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

                gudang_penyesuaian_masuk_utama::create([
                    'kode_obat' => $data->kode_obat_alkes,
                    'nama_obat' => $data->nama_obat_alkes,
                    'qty_sebelum' => '0',
                    'qty_mutasi' => $data->qty,
                    'qty_sesudah' => '0',
                    'jenis_penyesuaian' => 'BARANG DITOLAK',
                    'alasan' => "Kesalahan Pengiriman Barang Pada {$data->nama_klinik}",
                    'tanggal' => now()->toDateString(),
                    'jam' => now()->toTimeString(),
                    'harga' => $data->harga_dasar,
                    'expired' => $data->expired,
                    'user_input_id' => $request->input('user_id'),
                    'user_input_name' => $request->input('user_name'),
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
