<?php

namespace App\Http\Controllers;

use App\Exports\Gudang_satuanExport;
use App\Exports\Gudang_kategoriExport;
use App\Exports\Gudang_supplier_industriExport;
use App\Imports\Gudang_satuanImport;
use App\Imports\Gudang_kategoriImport;
use App\Imports\Gudang_supplier_industriImport;
use App\Models\gudang_satuan;
use App\Models\gudang_kategori;
use App\Models\gudang_supplier_industri;
use App\Models\gudang_setting_harga;
use App\Models\external_database;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Connectors\ConnectionFactory;

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

    // Supplier Industri

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
                'harga_jual_4'  => 'required|string',
                'harga_jual_5'  => 'required|string',
                'embalase_poin' => 'required|string',
            ], [
                'harga_jual_1'  => 'Harga Jual 1',
                'harga_jual_2'  => 'Harga Jual 2',
                'harga_jual_3'  => 'Harga Jual 3',
                'harga_jual_4'  => 'Harga Jual 4',
                'harga_jual_5'  => 'Harga Jual 5',
                'embalase_poin' => 'Embalase Poin',
            ]);

            // Bersihkan prefix atau simbol dari input agar hanya angka saja
            $harga_jual_1  = preg_replace('/[^\d]/', '', $request->input('harga_jual_1'));
            $harga_jual_2  = preg_replace('/[^\d]/', '', $request->input('harga_jual_2'));
            $harga_jual_3  = preg_replace('/[^\d]/', '', $request->input('harga_jual_3'));
            $harga_jual_4  = preg_replace('/[^\d]/', '', $request->input('harga_jual_4'));
            $harga_jual_5  = preg_replace('/[^\d]/', '', $request->input('harga_jual_5'));
            $embalase_poin = preg_replace('/[^\d]/', '', $request->input('embalase_poin'));

            // Simpan data ke database
            $setharga = gudang_setting_harga::first();

            if ($setharga) {
                $setharga->update([
                    'harga_jual_1' => $harga_jual_1,
                    'harga_jual_2' => $harga_jual_2,
                    'harga_jual_3' => $harga_jual_3,
                    'harga_jual_4' => $harga_jual_4,
                    'harga_jual_5' => $harga_jual_5,
                    'embalase_poin' => $embalase_poin,
                    'user_input_id' => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                ]);
            } else {
                $setharga = gudang_setting_harga::create([
                    'harga_jual_1' => $harga_jual_1,
                    'harga_jual_2' => $harga_jual_2,
                    'harga_jual_3' => $harga_jual_3,
                    'harga_jual_4' => $harga_jual_4,
                    'harga_jual_5' => $harga_jual_5,
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
                        'harga_jual_4' => $item->harga_jual_4,
                        'harga_jual_5' => $item->harga_jual_5,
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
}
