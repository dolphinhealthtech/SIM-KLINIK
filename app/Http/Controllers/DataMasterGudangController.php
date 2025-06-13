<?php

namespace App\Http\Controllers;

use App\Exports\Gudang_satuanExport;
use App\Exports\Gudang_kategoriExport;
use App\Exports\Gudang_supplier_industriExport;
use App\Exports\Inventaris_kategoriExport;
use App\Imports\Gudang_satuanImport;
use App\Imports\Gudang_kategoriImport;
use App\Imports\Gudang_supplier_industriImport;
use App\Imports\Inventaris_kategoriImport;
use App\Models\gudang_satuan;
use App\Models\gudang_kategori;
use App\Models\gudang_supplier_industri;
use App\Models\gudang_barang;
use App\Models\gudang_barang_harga;
use App\Models\gudang_barang_stok;
use App\Models\gudang_setting_harga;
use App\Models\external_database;
use App\Models\gudang_klinik_request;
use App\Models\gudang_klinik_request_details;
use App\Models\gudang_utama_keluar;
use App\Models\inventaris_kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Connectors\ConnectionFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class DataMasterGudangController extends Controller
{
    // Jenis Satuan

    public function satuan()
    {
        $title = "Master Jenis Satuan";
        $satuan = gudang_satuan::all();
        return view('module.master-data-gudang.satuan', compact('title','satuan'));
    }

    public function satuanadd(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string'
            ]);
            // Simpan data ke database
            $satuan = gudang_satuan::create([
                'nama' => $request->input('nama')
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Jenis satuan berhasil ditambahkan!',
                'data' => $satuan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis satuan Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Jenis satuan!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function satuanedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string'
        ]);

        $satuan = gudang_satuan::find($request->satuanid_edit);

        if (!$satuan) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis satuan tidak ditemukan!'
            ], 404);
        }

        $satuan->nama = $request->nama_edit;
        $satuan->save();

        return response()->json([
            'success' => true,
            'message' => 'Jenis satuan berhasil diperbarui!'
        ]);
    }

    public function satuandelete(Request $request)
    {

        $request->validate([
            'satuanid_delete' => 'required'
        ]);

        $satuan = gudang_satuan::find($request->satuanid_delete);

        if (!$satuan) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis satuan tidak ditemukan!'
            ], 404);
        }

        $satuan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis satuan berhasil dihapus!'
        ]);
    }

    public function satuanexport()
    {
        return Excel::download(new Gudang_satuanExport, 'Jenis Satuan.xlsx');
    }

    public function satuanimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Gudang_satuanImport, $request->file('file'));


        return redirect()->route('satuan.get')->with('success', 'Data berhasil diimpor!');
    }

    // Jenis satuan end

    // Jenis Kategori

    public function kategori()
    {
        $title = "Master Jenis Kategori";
        $kategori = gudang_kategori::all();
        return view('module.master-data-gudang.kategori', compact('title','kategori'));
    }

    public function kategoriadd(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string'
            ]);
            // Simpan data ke database
            $kategori = gudang_kategori::create([
                'nama' => $request->input('nama')
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Jenis kategori berhasil ditambahkan!',
                'data' => $kategori
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

    public function kategoriedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string'
        ]);

        $kategori = gudang_kategori::find($request->kategoriid_edit);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis kategori tidak ditemukan!'
            ], 404);
        }

        $kategori->nama = $request->nama_edit;
        $kategori->save();

        return response()->json([
            'success' => true,
            'message' => 'Jenis kategori berhasil diperbarui!'
        ]);
    }

    public function kategoridelete(Request $request)
    {

        $request->validate([
            'kategoriid_delete' => 'required'
        ]);

        $kategori = gudang_kategori::find($request->kategoriid_delete);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis kategori tidak ditemukan!'
            ], 404);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis kategori berhasil dihapus!'
        ]);
    }

    public function kategoriexport()
    {
        return Excel::download(new Gudang_kategoriExport, 'Jenis Kategori.xlsx');
    }

    public function kategoriimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Gudang_kategoriImport, $request->file('file'));


        return redirect()->route('kategori.get')->with('success', 'Data berhasil diimpor!');
    }

    // Jenis Kategori end

    // Supplier Industri

    public function supplier()
    {
        $title = "Master Supplier Industri";
        $supplier = gudang_supplier_industri::all();
        return view('module.master-data-gudang.supplier', compact('title','supplier'));
    }

    public function supplieradd(Request $request)
    {
        try {
            $request->validate([
                'kode' => 'required|string',
                'nama' => 'required|string',
                'nama_pic' => 'required|string',
                'telepon_pic' => 'required|string'
            ]);
            // Simpan data ke database
            $supplier = gudang_supplier_industri::create([
                'kode' => $request->input('kode'),
                'nama' => $request->input('nama'),
                'nama_pic' => $request->input('nama_pic'),
                'telepon_pic' => $request->input('telepon_pic'),
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Supplier Industri berhasil ditambahkan!',
                'data' => $supplier
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier Industri sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Supplier Industri!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function supplieredit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
            'nama_pic_edit' => 'required|string',
            'telepon_pic_edit' => 'required|string'
        ]);

        $supplier = gudang_supplier_industri::find($request->supplierid_edit);

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier Industri tidak ditemukan!'
            ], 404);
        }

        $supplier->nama = $request->nama_edit;
        $supplier->nama_pic = $request->nama_pic_edit;
        $supplier->telepon_pic = $request->telepon_pic_edit;
        $supplier->save();

        return response()->json([
            'success' => true,
            'message' => 'Supplier Industri berhasil diperbarui!'
        ]);
    }

    public function supplierdelete(Request $request)
    {

        $request->validate([
            'supplierid_delete' => 'required'
        ]);

        $supplier = gudang_supplier_industri::find($request->supplierid_delete);

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier Industri tidak ditemukan!'
            ], 404);
        }

        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier Industri berhasil dihapus!'
        ]);
    }

    public function supplierexport()
    {
        return Excel::download(new Gudang_supplier_industriExport, 'Supplier Industri.xlsx');
    }

    public function supplierimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Gudang_supplier_industriImport, $request->file('file'));


        return redirect()->route('supplier.get')->with('success', 'Data berhasil diimpor!');
    }

        // API Get Kode Supplier Industri

        public function getLastKode()
        {
            $last = gudang_supplier_industri::orderBy('id', 'desc')->first();

            return response()->json([
                'kode' => $last ? $last->kode : null
            ]);
        }

    // Supplier Industri end

    // Setting Harga

    public function setharga()
    {
        $title = "Master Setting Harga Jual";
        $setharga = gudang_setting_harga::first();
        Carbon::setLocale('id');
        $lastUpdated = $setharga ? Carbon::parse($setharga->updated_at)->diffForHumans() : 'belum ada update';
        $singkron = external_database::all();

        return view('module.master-data-gudang.setting_harga_jual', compact('title','setharga','lastUpdated','singkron'));
    }

    public function sethargaadd(Request $request)
    {
        try {
            $request->validate([
                'harga_jual_1'  => 'required|string',
                'harga_jual_2'  => 'required|string',
                'harga_jual_3'  => 'required|string',
                'embalase_poin' => 'required|string',
            ], [
                'harga_jual_1'  => 'Harga Jual 1',
                'harga_jual_2'  => 'Harga Jual 2',
                'harga_jual_3'  => 'Harga Jual 3',
                'embalase_poin' => 'Embalase Poin',
            ]);

            // Bersihkan prefix atau simbol dari input agar hanya angka saja
            $harga_jual_1  = preg_replace('/[^\d]/', '', $request->input('harga_jual_1'));
            $harga_jual_2  = preg_replace('/[^\d]/', '', $request->input('harga_jual_2'));
            $harga_jual_3  = preg_replace('/[^\d]/', '', $request->input('harga_jual_3'));
            $embalase_poin = preg_replace('/[^\d]/', '', $request->input('embalase_poin'));

            // Simpan data ke database
            $setharga = gudang_setting_harga::first();

            if ($setharga) {
                $setharga->update([
                    'harga_jual_1' => $harga_jual_1,
                    'harga_jual_2' => $harga_jual_2,
                    'harga_jual_3' => $harga_jual_3,
                    'embalase_poin' => $embalase_poin,
                    'user_input_id' => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                ]);
            } else {
                $setharga = gudang_setting_harga::create([
                    'harga_jual_1' => $harga_jual_1,
                    'harga_jual_2' => $harga_jual_2,
                    'harga_jual_3' => $harga_jual_3,
                    'embalase_poin' => $embalase_poin,
                    'user_input_id' => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                ]);
            }
            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Setting harga jual berhasil ditambahkan!',
                'data' => $setharga
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Setting harga jual sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan setting harga jual!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    // Koneksi antar database
    public function sethargasingkron($id)
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
        $data = $connection->table('gudang_setting_hargas')->get();

        $response = response()->json($data)->getData();

        try {
            // Simpan data ke database
            foreach ($response  as $item) {
                gudang_setting_harga::updateOrCreate(
                    [
                        'harga_jual_1' => $item->harga_jual_1,
                        'harga_jual_2' => $item->harga_jual_2,
                        'harga_jual_3' => $item->harga_jual_3,
                        'embalase_poin' => $item->embalase_poin,
                        'user_input_id' => Auth::user()->id,
                        'user_input_name' => Auth::user()->name,
                    ]
                );
            }


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Data barang berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Data barang!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // End Setting Harga

    // Harga Jual Barang

    public function hargajual()
    {
        $title = "Master Harga Jual Obat / Alkes";
        $harga_jual = gudang_barang_harga::all();

        return view('module.master-data-gudang.harga_jual', compact('title','harga_jual'));
    }

    //End Harga Jual Barang

    // Stok Barang (Obat / Alkes)

    public function stokobatalkes()
    {
        $title = "Master Stok Obat / Alkes";
        $stok = gudang_barang_stok::all();

        return view('module.master-data-gudang.stok', compact('title','stok'));
    }

    // End Stok Barang (Obat / Alkes)

    // Jenis Kategori Inventaris

    public function katin()
    {
        $title = "Master Jenis Kategori Inventaris";
        $katin = inventaris_kategori::all();
        return view('module.master-data-gudang.kategori_inventaris', compact('title','katin'));
    }

    public function katinadd(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string'
            ]);
            // Simpan data ke database
            $kategori = inventaris_kategori::create([
                'nama' => $request->input('nama')
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Jenis kategori berhasil ditambahkan!',
                'data' => $kategori
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

    public function katinedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string'
        ]);

        $kategori = inventaris_kategori::find($request->katinid_edit);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis kategori tidak ditemukan!'
            ], 404);
        }

        $kategori->nama = $request->nama_edit;
        $kategori->save();

        return response()->json([
            'success' => true,
            'message' => 'Jenis kategori berhasil diperbarui!'
        ]);
    }

    public function katindelete(Request $request)
    {

        $request->validate([
            'katinid_delete' => 'required'
        ]);

        $kategori = inventaris_kategori::find($request->katinid_delete);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis kategori tidak ditemukan!'
            ], 404);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis kategori berhasil dihapus!'
        ]);
    }

    public function katinexport()
    {
        return Excel::download(new Inventaris_kategoriExport, 'Jenis Kategori Inventaris.xlsx');
    }

    public function katinimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Inventaris_kategoriImport, $request->file('file'));


        return redirect()->route('katin.get')->with('success', 'Data berhasil diimpor!');
    }

    // Jenis Kategori Inventaris end

    // Gudang Klinik Request (OMEGA)

    public function gudangrequest()
    {
        $title = "Request Stok Obat Alkes";
        $dabar = gudang_barang::all();
        $stok = gudang_barang_stok::all();
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
            $request = $connection->table('gudang_klinik_requests')
                                ->where('kode_klinik', 'BLRJ')
                                ->get();

            $data_kirim = $connection->table('gudang_utama_keluars')
                                ->select('kode_request', 'tanggal_request', 'nama_klinik')
                                ->where('nama_klinik', 'Klinik Balaraja')
                                ->groupBy('kode_request', 'tanggal_request', 'nama_klinik')
                                ->get();

        } else {
            $request = collect(); // kosong jika tidak ada koneksi eksternal
            $data_kirim = collect(); // kosong jika tidak ada koneksi eksternal
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
                $details = $connection->table('gudang_klinik_request_details')
                    ->where('kode_request', $kodeRequest)
                    ->select('kode_obat_alkes', 'nama_obat_alkes', 'qty')
                    ->get();

            } else {
                $details = collect(); // kosong jika tidak ada koneksi eksternal
            }

            return response()->json([
                'details' => $details
            ]);
        }

        //API details approval

        public function detailsAprroval($kodeRequest)
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
                $details = $connection->table('gudang_utama_keluars')
                    ->where('kode_request', $kodeRequest)
                    ->get();

            } else {
                $details = collect(); // kosong jika tidak ada koneksi eksternal
            }

            return response()->json([
                'details' => $details
            ]);
        }

        // API Get Kode Supplier Industri

        public function request_getLastKode()
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
                $last = $connection->table('gudang_klinik_requests')
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

        // API Terima Data
        public function terimaData(Request $request, $id)
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

                $data = $connection->table('gudang_utama_keluars')->where('id', $id)->first();

                if (!$data) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data tidak ditemukan.'
                    ], 404);
                }

                // Tambahkan kembali qty ke gudang_barang
                $connection->table('gudang_barang_stoks')->insert([
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


    // END Gudang Klinik Request (OMEGA)

    // Gudang Utama (OMEGA)

    public function gudangutama()
    {
        $title = "Dashboard Gudang Utama";
        $request = gudang_klinik_request::with('details')->get();
        $dabar = gudang_barang::all();

        return view('module.master-data-gudang.utama', compact('title','request','dabar'));
    }

    public function gudangutamakonfirmasi(Request $request)
    {
        $request->validate([
            'detail_kode_request' => 'required|string',
            'detail_tanggal' => 'required|string',
        ]);

        try {
            $found = gudang_klinik_request::where('kode_request', $request->input('detail_kode_request'))
                ->where('tanggal_input', $request->input('detail_tanggal'))
                ->first();

            if (!$found) {
                // Data tidak ditemukan, return error
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid atau tidak ditemukan!',
                ], 404);
            }

            // Update status
            $found->update([
                'status' => 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dikonfirmasi',
                'data' => $found,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat konfirmasi data!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getHargaDasar($kode_obat)
    {
        try {
            $tanggalHariIni = Carbon::today();
            $tanggal3BulanLalu = $tanggalHariIni->copy()->subMonths(3);

            $harga = gudang_barang_harga::where('kode_obat_alkes', $kode_obat)
                ->whereBetween('tanggal_obat_masuk', [$tanggal3BulanLalu, $tanggalHariIni])
                ->max('harga_jual_1');

            return response()->json([
                'success' => true,
                'harga_dasar' => $harga
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function prosesPermintaan(Request $request)
    {
        try {
            $itemsJson = $request->input('items_json');
            $items = json_decode($itemsJson, true);
            $kodeRequest = $request->input('kode_request');
            $tanggalRequest = $request->input('tanggal_request');
            $namaKlinik = $request->input('nama_klinik');

            foreach ($items as $item) {
                $kodeObat = $item['kode_obat'];
                $jumlahDibutuhkan = intval($item['jumlah']);

                $hargaDasarRaw = $item['harga_dasar'];
                $hargaDasar = intval(str_replace(['Rp', '.', ' '], '', $hargaDasarRaw));

                // Skip jika jumlah kosong/tidak valid
                if ($jumlahDibutuhkan <= 0) {
                    continue;
                }

                $stokList = gudang_barang_stok::where('kode_obat_alkes', $kodeObat)
                            ->where('qty', '>', 0)
                            ->orderBy('tanggal_terima_obat', 'asc')
                            ->get();

                $totalTersedia = $stokList->sum('qty');
                if ($totalTersedia < $jumlahDibutuhkan) {
                    // Validasi gagal jika stok tidak mencukupi
                    return response()->json([
                        'success' => false,
                        'message' => "Stok tidak cukup untuk kode obat {$kodeObat}. Dibutuhkan: {$jumlahDibutuhkan}, tersedia: {$totalTersedia}",
                    ], 422);
                }

                foreach ($stokList as $stok) {
                    if ($jumlahDibutuhkan <= 0) break;

                    $ambil = min($stok->qty, $jumlahDibutuhkan);

                    $stok->qty -= $ambil;
                    $stok->save();

                    $jumlahDibutuhkan -= $ambil;

                    gudang_utama_keluar::create([
                        'kode_request' => $kodeRequest,
                        'nama_klinik' => $namaKlinik,
                        'tanggal_request' => $tanggalRequest,
                        'kode_obat_alkes' => $kodeObat,
                        'nama_obat_alkes' => $stok->nama_obat_alkes,
                        'harga_dasar' => $hargaDasar,
                        'qty' => $ambil,
                        'tanggal_terima_obat' => $stok->tanggal_terima_obat,
                        'expired' => $stok->expired,
                        'user_input_id' => $request->input('user_id'),
                        'user_input_name' => $request->input('user_name'),
                    ]);
                }
            }
            // Return jika berhasil
            return response()->json([
                'success' => true,
                'message' => 'Permintaan berhasil diproses!',
                'data' => $kodeRequest,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses permintaan!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function generatePdf($kodeRequest)
    {
        $data = gudang_utama_keluar::where('kode_request', $kodeRequest)->get();

        $data_sendiri = gudang_utama_keluar::where('kode_request', $kodeRequest)
        ->select('kode_request', 'nama_klinik', 'tanggal_request')
        ->first();

        $total_invoice = 0;

        foreach ($data as $item) {
            $total_invoice++;
        }

        $pdf = Pdf::loadView('pdf.faktur_pengiriman', compact('data','data_sendiri','total_invoice'))->setPaper('a4', 'landscape');
        return $pdf->stream('faktur_pengiriman_' . $kodeRequest . '.pdf');
    }

    public function laporan_gudang_utama()
    {
        $title = "Kasir Apotek Lunas";

        $data = gudang_klinik_request::with('details')
                ->whereHas('details')
                ->get();

        return view('module.master-data-gudang.laporan_gudang_utama', compact('title','data'));
    }

    public function print_gudang_utama(Request $request)
    {
        $data = json_decode($request->input('data'), true); // penting! decode data JSON
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $klinik = $request->input('klinik');

        $total_invoice = 0;

        foreach ($data as $item) {
            if (isset($item['is_detail']) && $item['is_detail'] == false) {
                $total_invoice++;
            }
        }

        $obatQtySummary = []; // array penampung

        foreach ($data as $item) {
            $nama_obat = $item['nama_obat_alkes'] ?? '-';
            $qty = (int) $item['qty'] ?? 0;

            if (!isset($obatQtySummary[$nama_obat])) {
                $obatQtySummary[$nama_obat] = 0;
            }

            $obatQtySummary[$nama_obat] += $qty;
        }

        $pdf = Pdf::loadView('pdf.data_laporan_gudang_utama', compact('data', 'tanggal_awal', 'tanggal_akhir', 'klinik','total_invoice','obatQtySummary'))
                ->setPaper('a4', 'landscape');

        $filename = 'laporan_gudang_utama_' . $tanggal_awal . '_' . $tanggal_akhir . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }


    // END Gudang Utama (OMEGA)
}




