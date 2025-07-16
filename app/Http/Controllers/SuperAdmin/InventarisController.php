<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Exports\Inventaris_data_barangExport;
use App\Exports\Inventaris_data_barangExport_utama;
use App\Imports\Inventaris_data_barangImport;
use App\Imports\Inventaris_data_barangImport_utama;
use App\Models\inventaris_data_barang;
use App\Models\inventaris_satuan;
use App\Models\inventaris_kategori;
use App\Models\external_database;
use App\Models\inventaris_pembelian;
use App\Models\inventaris_pembelian_detail;
use App\Models\inventaris_stok;
use App\Models\inventaris_data_barang_utama;
use App\Models\inventaris_pembelian_utama;
use App\Models\inventaris_pembelian_detail_utama;
use App\Models\inventaris_stok_utama;
use App\Models\WebSetting;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Http\Request;


class InventarisController extends Controller
{
    public function inventaris()
    {
        $title = "Data Inventaris";
        $inventaris = inventaris_data_barang::all();
        $satuan = inventaris_satuan::all();
        $kategori = inventaris_kategori::all();
        $singkron = external_database::all();
        return view('dashboard.data_inventaris', compact('title', 'inventaris', 'satuan', 'kategori', 'singkron'));
    }

    public function inventarisadd(Request $request)
    {
        try {
            $request->validate([
                'kode_barang'   => 'required|string',
                'nama_barang'   => 'required|string',
                'kategori_barang'   => 'required|string',
                'satuan_barang' => 'required|string',
                'jenis_barang' => 'required|string',
                'masa_pakai_barang' => 'required|string',
                'masa_pakai_waktu_barang'   => 'required|string',
                'deskripsi_barang'  => 'required|string',
            ], [
                // 👇 Custom attribute names
                'kode_barang'   => 'Kode Barang',
                'nama_barang'   => 'Nama Barang',
                'kategori_barang'   => 'Kategori Barang',
                'satuan_barang' => 'Satuan Barang',
                'jenis_barang' => 'Jenis Barang',
                'masa_pakai_barang' => 'Masa Pakai Barang',
                'masa_pakai_waktu_barang'   => 'Pilihan Masa Pakai Barang',
                'deskripsi_barang'  => 'Deskripsi Barang',
            ]);

            // Simpan data ke database
            $inventaris_data = inventaris_data_barang::create([
                'kode_barang' => $request->input('kode_barang'),
                'nama_barang' => $request->input('nama_barang'),
                'kategori_barang' => $request->input('kategori_barang'),
                'satuan_barang' => $request->input('satuan_barang'),
                'jenis_barang' => $request->input('jenis_barang'),
                'masa_pakai_barang' => $request->input('masa_pakai_barang'),
                'masa_pakai_waktu_barang' => $request->input('masa_pakai_waktu_barang'),
                'deskripsi_barang' => $request->input('deskripsi_barang'),
                'user_input_id' => Auth::user()->id,
                'user_input_name' => Auth::user()->name,
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Inventaris berhasil ditambahkan!',
                'data' => $inventaris_data
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventaris Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Inventaris!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function inventarisedit(Request $request)
    {
        $request->validate([
            'kode_barang_edit'   => 'required|string',
            'nama_barang_edit'   => 'required|string',
            'kategori_barang_edit'   => 'required|string',
            'satuan_barang_edit' => 'required|string',
            'masa_pakai_barang_edit' => 'required|string',
            'masa_pakai_waktu_barang_edit'   => 'required|string',
            'deskripsi_barang_edit'  => 'required|string',
        ], [
            // 👇 Custom attribute names
            'kode_barang_edit'   => 'Kode Barang',
            'nama_barang_edit'   => 'Nama Barang',
            'kategori_barang_edit'   => 'Kategori Barang',
            'satuan_barang_edit' => 'Satuan Barang',
            'masa_pakai_barang_edit' => 'Masa Pakai Barang',
            'masa_pakai_waktu_barang_edit'   => 'Pilihan Masa Pakai Barang',
            'deskripsi_barang_edit'  => 'Deskripsi Barang',
        ]);

        $inventaris = inventaris_data_barang::find($request->inventarisid_edit);

        if (!$inventaris) {
            return response()->json([
                'success' => false,
                'message' => 'Inventaris tidak ditemukan!'
            ], 404);
        }

        $inventaris->kode_barang = $request->kode_barang_edit;
        $inventaris->nama_barang = $request->nama_barang_edit;
        $inventaris->kategori_barang = $request->kategori_barang_edit;
        $inventaris->satuan_barang = $request->satuan_barang_edit;
        $inventaris->masa_pakai_barang = $request->masa_pakai_barang_edit;
        $inventaris->masa_pakai_waktu_barang = $request->masa_pakai_waktu_barang_edit;
        $inventaris->deskripsi_barang = $request->deskripsi_barang_edit;
        $inventaris->user_input_id = Auth::user()->id;
        $inventaris->user_input_name = Auth::user()->name;
        $inventaris->save();

        return response()->json([
            'success' => true,
            'message' => 'Inventaris berhasil diperbarui!'
        ]);
    }

    public function inventarisdelete(Request $request)
    {

        $request->validate([
            'inventarisid_delete' => 'required'
        ]);

        $inventaris = inventaris_data_barang::find($request->inventarisid_delete);

        if (!$inventaris) {
            return response()->json([
                'success' => false,
                'message' => 'Inventaris tidak ditemukan!'
            ], 404);
        }

        $inventaris->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inventaris berhasil dihapus!'
        ]);
    }

    public function inventarisexport()
    {
        return Excel::download(new Inventaris_data_barangExport, 'Inventaris Data Barang.xlsx');
    }

    public function inventarisimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Inventaris_data_barangImport, $request->file('file'));


        return redirect()->route('inventaris.get')->with('success', 'Data berhasil di import!');
    }

    // // Koneksi antar database
    public function inventarissingkron($id)
    {
        $externalDb = external_database::findOrFail($id);

        $config = [
            'driver' => 'mysql',
            'host' => $externalDb->host,
            'database' => $externalDb->database,
            'username' => $externalDb->username,
            'password' => $externalDb->password,
            'port' => $externalDb->port ?? 3306,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];

        $factory = app(ConnectionFactory::class);
        $connection = $factory->make($config, $externalDb->name);

        // Gunakan koneksi ini untuk query
        $data = $connection->table('inventaris_data_barangs')->get();

        $response = response()->json($data)->getData();

        try {
            // Simpan data ke database
            foreach ($response  as $item) {
                inventaris_data_barang::updateOrCreate(
                    [
                        'kode_barang' => $item->kode_barang,
                        'nama_barang' => $item->nama_barang,
                        'kategori_barang' => $item->kategori_barang,
                        'satuan_barang' => $item->satuan_barang,
                        'masa_pakai_barang' => $item->masa_pakai_barang,
                        'masa_pakai_waktu_barang' => $item->masa_pakai_waktu_barang,
                        'deskripsi_barang' => $item->deskripsi_barang,
                        'user_input_id' => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                    ]
                );
            }

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Inventaris berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventaris Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Inventaris!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //Generate Kode Barang Otomatis
    public function generateKodeInventaris()
    {
        // Mengecek status gudang utama dari tabel websetting
        $isGudangUtama = WebSetting::first()?->is_gudangutama_active;

        // Mengambil data barang terakhir dari tabel yang sesuai
        if ($isGudangUtama) {
            $last = inventaris_data_barang_utama::orderBy('id', 'desc')->first();
        } else {
            $last = inventaris_data_barang::orderBy('id', 'desc')->first();
        }

        // Jika tidak ada data barang sebelumnya atau kode barang tidak sesuai format 'KBI-xxxx'
        if (!$last || !preg_match('/^KBI-(\d{4})$/', $last->kode_barang, $match)) {
            $nextNumber = 1;  // Mulai dengan nomor 1 jika tidak ada data atau format kode salah
        } else {
            // Jika ada data sebelumnya, ambil angka terakhir dan tambah 1
            $nextNumber = (int)$match[1] + 1;
        }

        // Membuat kode barang baru dengan format 'KBI-xxxx' (dengan padding 0 di depan)
        $kode = 'KBI-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Mengembalikan response dalam format JSON
        return response()->json([
            'success' => true,
            'kode_barang' => $kode
        ]);
    }

    // Inventaris Pembelian
    public function inventaris_pembelian()
    {
        $title = "Inventaris Pembelian";
        $inventaris = inventaris_data_barang::all();
        $user = User::all();

        return view('dashboard.inventaris_pembelian', compact('title', 'inventaris', 'user'));
    }

    public function inventaris_pembelianadd(Request $request)
    {
        try {
            $request->validate([
                'data_hidden' => 'required|string',
                'kode_pembelian_inventaris' => 'required|string',
                'total_keseluruhan_input' => 'nullable|string',
                'penerima_barang' => 'nullable|string',
            ]);

            // Simpan data ke database (1)
            $pembelian = inventaris_pembelian::create([
                'kode' => $request->input('kode_pembelian_inventaris'),
                'tanggal_pembelian' => now()->format('Y-m-d'),
                'total_harga' => $request->input('total_keseluruhan_input'),
                'petugas_penerima' => $request->input('penerima_barang'),
                'user_input_id' => Auth::user()->id,
                'user_input_name' => Auth::user()->name,
            ]);

            // Simpan detail pembelian
            $dataDetail = json_decode($request->data_hidden, true);

            foreach ($dataDetail as $detail) {
                inventaris_pembelian_detail::create([
                    'kode' => $request->input('kode_pembelian_inventaris'),
                    'kode_barang' => $detail['kode_barang'],
                    'nama_barang' => $detail['nama_barang'],
                    'kategori_barang' => $detail['kategori_barang'],
                    'jenis_barang' => $detail['jenis_barang'],
                    'qty_barang' => $detail['qty_pembelian'],
                    'harga_barang' => $detail['harga_satuan'],
                    'lokasi' => $detail['lokasi_barang'],
                    'kondisi' => $detail['kondisi_barang'],
                    'masa_akhir_penggunaan' => $detail['masa_akhir_penggunaan'],
                    'tanggal_pembelian' => $detail['tanggal_pembelian'],
                    'detail_barang' => $detail['detail_barang'],
                    'user_input_id' => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                ]);

                // === Simpan ke stok sesuai jenis barang ===
                if ($detail['jenis_barang'] === 'Inventaris') {
                    for ($i = 0; $i < intval($detail['qty_pembelian']); $i++) {
                        inventaris_stok::create([
                            'kode_pembelian' => $request->input('kode_pembelian_inventaris'),
                            'kode_barang' => $detail['kode_barang'],
                            'nama_barang' => $detail['nama_barang'],
                            'kategori_barang' => $detail['kategori_barang'],
                            'jenis_barang' => $detail['jenis_barang'],
                            'qty_barang' => '1',
                            'harga_barang' => $detail['harga_satuan'],
                            'masa_akhir_penggunaan' => $detail['masa_akhir_penggunaan'],
                            'tanggal_pembelian' => $detail['tanggal_pembelian'],
                            'detail_barang' => $detail['detail_barang'],
                            'lokasi' => $detail['lokasi_barang'],
                            'penanggung_jawab' => null,
                            'kondisi' => $detail['kondisi_barang'],
                            'no_seri' => null,
                            'user_input_id' => Auth::id(),
                            'user_input_name' => Auth::user()->name,
                        ]);
                    }
                } else {
                    inventaris_stok::create([
                        'kode_pembelian' => $request->input('kode_pembelian_inventaris'),
                        'kode_barang' => $detail['kode_barang'],
                        'nama_barang' => $detail['nama_barang'],
                        'kategori_barang' => $detail['kategori_barang'],
                        'jenis_barang' => $detail['jenis_barang'],
                        'qty_barang' => $detail['qty_pembelian'],
                        'harga_barang' => $detail['harga_satuan'],
                        'masa_akhir_penggunaan' => $detail['masa_akhir_penggunaan'],
                        'tanggal_pembelian' => $detail['tanggal_pembelian'],
                        'detail_barang' => $detail['detail_barang'],
                        'lokasi' => $detail['lokasi_barang'],
                        'penanggung_jawab' => null,
                        'kondisi' => $detail['kondisi_barang'],
                        'no_seri' => null,
                        'user_input_id' => Auth::id(),
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

    public function generatePembelianInventaris()
    {
        $today = date('Ymd'); // Tanggal hari ini dalam format YYYYMMDD

        // Mengecek status gudang utama dari tabel websetting
        $isGudangUtama = WebSetting::first()?->is_gudangutama_active;

        // Mengambil data barang terakhir dari tabel yang sesuai
        if ($isGudangUtama) {
            $last = inventaris_pembelian_utama::orderBy('id', 'desc')->first();
        } else {
            $last = inventaris_pembelian::orderBy('id', 'desc')->first();
        }

        if (!$last) {
            $nextNumber = 1;
        } else {
            // Ambil nomor urut terakhir dari kode
            preg_match('/FIP-\d{8}-(\d{5})$/', $last->kode, $match);
            $nextNumber = isset($match[1]) ? ((int)$match[1] + 1) : 1;
        }

        $kode = 'FIP-' . $today . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return response()->json([
            'success' => true,
            'kode' => $kode
        ]);
    }

    // Inventaris Utama
    public function inventaris_utama()
    {
        $title = "Data Inventaris";
        $inventaris = inventaris_data_barang_utama::all();
        $satuan = inventaris_satuan::all();
        $kategori = inventaris_kategori::all();
        $singkron = external_database::all();
        return view('dashboard.data_inventaris_utama', compact('title', 'inventaris', 'satuan', 'kategori', 'singkron'));
    }

    public function inventarisadd_utama(Request $request)
    {
        try {
            $request->validate([
                'kode_barang'   => 'required|string',
                'nama_barang'   => 'required|string',
                'kategori_barang'   => 'required|string',
                'satuan_barang' => 'required|string',
                'jenis_barang' => 'required|string',
                'masa_pakai_barang' => 'required|string',
                'masa_pakai_waktu_barang'   => 'required|string',
                'deskripsi_barang'  => 'required|string',
            ], [
                // 👇 Custom attribute names
                'kode_barang'   => 'Kode Barang',
                'nama_barang'   => 'Nama Barang',
                'kategori_barang'   => 'Kategori Barang',
                'satuan_barang' => 'Satuan Barang',
                'jenis_barang' => 'Jenis Barang',
                'masa_pakai_barang' => 'Masa Pakai Barang',
                'masa_pakai_waktu_barang'   => 'Pilihan Masa Pakai Barang',
                'deskripsi_barang'  => 'Deskripsi Barang',
            ]);

            // Simpan data ke database
            $inventaris_data = inventaris_data_barang_utama::create([
                'kode_barang' => $request->input('kode_barang'),
                'nama_barang' => $request->input('nama_barang'),
                'kategori_barang' => $request->input('kategori_barang'),
                'satuan_barang' => $request->input('satuan_barang'),
                'jenis_barang' => $request->input('jenis_barang'),
                'masa_pakai_barang' => $request->input('masa_pakai_barang'),
                'masa_pakai_waktu_barang' => $request->input('masa_pakai_waktu_barang'),
                'deskripsi_barang' => $request->input('deskripsi_barang'),
                'user_input_id' => Auth::user()->id,
                'user_input_name' => Auth::user()->name,
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Inventaris berhasil ditambahkan!',
                'data' => $inventaris_data
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventaris Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Inventaris!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function inventarisedit_utama(Request $request)
    {
        $request->validate([
            'kode_barang_edit'   => 'required|string',
            'nama_barang_edit'   => 'required|string',
            'kategori_barang_edit'   => 'required|string',
            'satuan_barang_edit' => 'required|string',
            'masa_pakai_barang_edit' => 'required|string',
            'masa_pakai_waktu_barang_edit'   => 'required|string',
            'deskripsi_barang_edit'  => 'required|string',
        ], [
            // 👇 Custom attribute names
            'kode_barang_edit'   => 'Kode Barang',
            'nama_barang_edit'   => 'Nama Barang',
            'kategori_barang_edit'   => 'Kategori Barang',
            'satuan_barang_edit' => 'Satuan Barang',
            'masa_pakai_barang_edit' => 'Masa Pakai Barang',
            'masa_pakai_waktu_barang_edit'   => 'Pilihan Masa Pakai Barang',
            'deskripsi_barang_edit'  => 'Deskripsi Barang',
        ]);

        $inventaris = inventaris_data_barang_utama::find($request->inventarisid_edit);

        if (!$inventaris) {
            return response()->json([
                'success' => false,
                'message' => 'Inventaris tidak ditemukan!'
            ], 404);
        }

        $inventaris->kode_barang = $request->kode_barang_edit;
        $inventaris->nama_barang = $request->nama_barang_edit;
        $inventaris->kategori_barang = $request->kategori_barang_edit;
        $inventaris->satuan_barang = $request->satuan_barang_edit;
        $inventaris->masa_pakai_barang = $request->masa_pakai_barang_edit;
        $inventaris->masa_pakai_waktu_barang = $request->masa_pakai_waktu_barang_edit;
        $inventaris->deskripsi_barang = $request->deskripsi_barang_edit;
        $inventaris->user_input_id = Auth::user()->id;
        $inventaris->user_input_name = Auth::user()->name;
        $inventaris->save();

        return response()->json([
            'success' => true,
            'message' => 'Inventaris berhasil diperbarui!'
        ]);
    }

    public function inventarisdelete_utama(Request $request)
    {

        $request->validate([
            'inventarisid_delete' => 'required'
        ]);

        $inventaris = inventaris_data_barang_utama::find($request->inventarisid_delete);

        if (!$inventaris) {
            return response()->json([
                'success' => false,
                'message' => 'Inventaris tidak ditemukan!'
            ], 404);
        }

        $inventaris->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inventaris berhasil dihapus!'
        ]);
    }

    public function inventarisexport_utama()
    {
        return Excel::download(new Inventaris_data_barangExport_utama, 'Inventaris Data Barang.xlsx');
    }

    public function inventarisimport_utama(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Inventaris_data_barangImport_utama, $request->file('file'));


        return redirect()->route('inventaris_utama.get')->with('success', 'Data berhasil di import!');
    }

    // Inventaris Pembelian Utama
    public function inventaris_pembelian_utama()
    {
        $title = "Inventaris Pembelian";
        $inventaris = inventaris_data_barang_utama::all();
        $user = User::all();

        return view('dashboard.inventaris_pembelian_utama', compact('title', 'inventaris', 'user'));
    }

    public function inventaris_pembelianadd_utama(Request $request)
    {
        try {
            $request->validate([
                'data_hidden' => 'required|string',
                'kode_pembelian_inventaris' => 'required|string',
                'total_keseluruhan_input' => 'nullable|string',
                'penerima_barang' => 'nullable|string',
            ]);

            // Simpan data ke database (1)
            $pembelian = inventaris_pembelian_utama::create([
                'kode' => $request->input('kode_pembelian_inventaris'),
                'tanggal_pembelian' => now()->format('Y-m-d'),
                'total_harga' => $request->input('total_keseluruhan_input'),
                'petugas_penerima' => $request->input('penerima_barang'),
                'user_input_id' => Auth::user()->id,
                'user_input_name' => Auth::user()->name,
            ]);

            // Simpan detail pembelian
            $dataDetail = json_decode($request->data_hidden, true);

            foreach ($dataDetail as $detail) {
                inventaris_pembelian_detail_utama::create([
                    'kode' => $request->input('kode_pembelian_inventaris'),
                    'kode_barang' => $detail['kode_barang'],
                    'nama_barang' => $detail['nama_barang'],
                    'kategori_barang' => $detail['kategori_barang'],
                    'jenis_barang' => $detail['jenis_barang'],
                    'qty_barang' => $detail['qty_pembelian'],
                    'harga_barang' => $detail['harga_satuan'],
                    'lokasi' => $detail['lokasi_barang'],
                    'kondisi' => $detail['kondisi_barang'],
                    'masa_akhir_penggunaan' => $detail['masa_akhir_penggunaan'],
                    'tanggal_pembelian' => $detail['tanggal_pembelian'],
                    'detail_barang' => $detail['detail_barang'],
                    'user_input_id' => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                ]);

                // === Simpan ke stok sesuai jenis barang ===
                if ($detail['jenis_barang'] === 'Inventaris') {
                    for ($i = 0; $i < intval($detail['qty_pembelian']); $i++) {
                        inventaris_stok_utama::create([
                            'kode_pembelian' => $request->input('kode_pembelian_inventaris'),
                            'kode_barang' => $detail['kode_barang'],
                            'nama_barang' => $detail['nama_barang'],
                            'kategori_barang' => $detail['kategori_barang'],
                            'jenis_barang' => $detail['jenis_barang'],
                            'qty_barang' => '1',
                            'harga_barang' => $detail['harga_satuan'],
                            'masa_akhir_penggunaan' => $detail['masa_akhir_penggunaan'],
                            'tanggal_pembelian' => $detail['tanggal_pembelian'],
                            'detail_barang' => $detail['detail_barang'],
                            'lokasi' => $detail['lokasi_barang'],
                            'penanggung_jawab' => null,
                            'kondisi' => $detail['kondisi_barang'],
                            'no_seri' => null,
                            'user_input_id' => Auth::id(),
                            'user_input_name' => Auth::user()->name,
                        ]);
                    }
                } else {
                    inventaris_stok_utama::create([
                        'kode_pembelian' => $request->input('kode_pembelian_inventaris'),
                        'kode_barang' => $detail['kode_barang'],
                        'nama_barang' => $detail['nama_barang'],
                        'kategori_barang' => $detail['kategori_barang'],
                        'jenis_barang' => $detail['jenis_barang'],
                        'qty_barang' => $detail['qty_pembelian'],
                        'harga_barang' => $detail['harga_satuan'],
                        'masa_akhir_penggunaan' => $detail['masa_akhir_penggunaan'],
                        'tanggal_pembelian' => $detail['tanggal_pembelian'],
                        'detail_barang' => $detail['detail_barang'],
                        'lokasi' => $detail['lokasi_barang'],
                        'penanggung_jawab' => null,
                        'kondisi' => $detail['kondisi_barang'],
                        'no_seri' => null,
                        'user_input_id' => Auth::id(),
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
}
