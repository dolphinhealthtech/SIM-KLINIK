<?php

namespace App\Http\Controllers\DataMaster\superadmin;

use App\Http\Controllers\Controller;
use App\Exports\Gudang_barangExport;
use App\Imports\Gudang_barangImport;
use App\Models\gudang_barang;
use App\Models\gudang_satuan;
use App\Models\gudang_kategori;
use App\Models\external_database;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Connectors\ConnectionFactory;


class DabarController extends Controller
{
    public function dabar()
    {
        $title = "Data Barang";
        $dabar = gudang_barang::all();
        $satuan = gudang_satuan::all();
        $kategori = gudang_kategori::all();
        $singkron = external_database::all();
        return view('dashboard.dabar', compact('title', 'dabar', 'satuan', 'kategori', 'singkron'));
    }

    public function dabaradd(Request $request)
    {
        try {
            $request->validate([
                'kode_barang'           => 'required|string',
                'nama_barang'           => 'required|string',
                'kfa_kode'              => 'required|string',
                'jenis_formularium'     => 'required|string',
                'nama_industri_barang'  => 'required|string',
                'jenis_obat'            => 'required|string',
                'jenis_generik'         => 'required|string',
                'satuan_kecil'          => 'required|string',
                'nilai_satuan_kecil'    => 'required|string',
                'satuan_sedang'         => 'required|string',
                'nilai_satuan_sedang'   => 'required|string',
                'satuan_besar'          => 'required|string',
                'tempat_penyimpanan'    => 'required|string',
                'barcode'               => 'required|string',
                'barang_kategori'       => 'required|string',
                'bentuk_sediaan'        => 'required|string',
            ], [
                // 👇 Custom attribute names
                'kode_barang' => 'Kode Barang',
                'nama_barang' => 'Nama Barang',
                'kfa_kode' => 'Kode KFA',
                'jenis_formularium' => 'Jenis Formularium',
                'nama_industri_barang' => 'Industri Barang',
                'jenis_obat' => 'Jenis Obat',
                'jenis_generik' => 'Jenis Generik',
                'satuan_kecil' => 'Satuan Kecil',
                'nilai_satuan_kecil' => 'Nilai Satuan Kecil',
                'satuan_sedang' => 'Satuan Sedang',
                'nilai_satuan_sedang' => 'Nilai Satuan Sedang',
                'satuan_besar' => 'Satuan Besar',
                'tempat_penyimpanan' => 'Tempat Penyimpanan',
                'barcode' => 'Barcode',
                'barang_kategori' => 'Barang Kategori',
                'bentuk_sediaan' => 'Bentuk Sediaan',
            ]);

            // Simpan data ke database
            $satuan = gudang_barang::create([
                'kode_barang' => $request->input('kode_barang'),
                'nama_barang' => $request->input('nama_barang'),
                'kfa_kode' => $request->input('kfa_kode'),
                'jenis_formularium' => $request->input('jenis_formularium'),
                'nama_industri_barang' => $request->input('nama_industri_barang'),
                'jenis_obat' => $request->input('jenis_obat'),
                'jenis_generik' => $request->input('jenis_generik'),
                'satuan_kecil' => $request->input('satuan_kecil'),
                'nilai_satuan_kecil' => $request->input('nilai_satuan_kecil'),
                'satuan_sedang' => $request->input('satuan_sedang'),
                'nilai_satuan_sedang' => $request->input('nilai_satuan_sedang'),
                'satuan_besar' => $request->input('satuan_besar'),
                'nilai_satuan_besar' => 1,
                'tempat_penyimpanan' => $request->input('tempat_penyimpanan'),
                'barcode' => $request->input('barcode'),
                'gudang_kategori' => $request->input('barang_kategori'),
                'bentuk_sediaan' => $request->input('bentuk_sediaan'),
                'user_input_id' => Auth::user()->id,
                'user_input_nama' => Auth::user()->name,
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Data Barang berhasil ditambahkan!',
                'data' => $satuan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data Barang Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Data Barang!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function dabaredit(Request $request)
    {
        $request->validate([
            'nama_barang_edit'           => 'required|string',
            'kode_kfa_edit'              => 'required|string',
            'jenis_formularium_edit'     => 'required|string',
            'industri_barang_edit'       => 'required|string',
            'jenis_obat_edit'            => 'required|string',
            'jenis_generik_edit'         => 'required|string',
            'satuan_kecil_edit'          => 'required|string',
            'nilai_satuan_kecil_edit'    => 'required|string',
            'satuan_sedang_edit'         => 'required|string',
            'nilai_satuan_sedang_edit'   => 'required|string',
            'satuan_besar_edit'          => 'required|string',
            'tempat_penyimpanan_edit'    => 'required|string',
            'barcode_edit'               => 'required|string',
            'barang_kategori_edit'       => 'required|string',
            'bentuk_sediaan_edit'        => 'required|string',
        ], [
            'nama_barang_edit'           => 'Masukan Data Nama Barang',
            'kode_kfa_edit'              => 'Kode KFA',
            'jenis_formularium_edit'     => 'Pilih Jenis Formularium',
            'industri_barang_edit'       => 'Industri Barang',
            'jenis_obat_edit'            => 'Pilih Jenis Obat',
            'jenis_generik_edit'         => 'Masukan Jenis Generik Barang',
            'satuan_kecil_edit'          => 'Pilih Satuan Kecil',
            'nilai_satuan_kecil_edit'    => 'Masukan Satuan Kecil',
            'satuan_sedang_edit'         => 'Pilih Satuan Sedang',
            'nilai_satuan_sedang_edit'   => 'Masukan Satuan Sedang',
            'satuan_besar_edit'          => 'Pilih Satuan Besar',
            'tempat_penyimpanan_edit'    => 'Masukan Tempat Penyimpanan',
            'barcode_edit'               => 'Masukan No Barcode',
            'barang_kategori_edit'       => 'Pilih Kategori Barang',
            'bentuk_sediaan_edit'        => 'Pilih Bentuk Sediaan Barang',
        ]);

        $dabar = gudang_barang::find($request->dabarid_edit);

        if (!$dabar) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan!'
            ], 404);
        }

        $dabar->nama_barang = $request->nama_barang_edit;
        $dabar->kfa_kode = $request->kode_kfa_edit;
        $dabar->jenis_formularium = $request->jenis_formularium_edit;
        $dabar->nama_industri_barang = $request->industri_barang_edit;
        $dabar->satuan_kecil = $request->satuan_kecil_edit;
        $dabar->satuan_sedang = $request->satuan_sedang_edit;
        $dabar->satuan_besar = $request->satuan_besar_edit;
        $dabar->nilai_satuan_kecil = $request->nilai_satuan_kecil_edit;
        $dabar->nilai_satuan_sedang = $request->nilai_satuan_sedang_edit;
        $dabar->tempat_penyimpanan = $request->tempat_penyimpanan_edit;
        $dabar->barcode = $request->barcode_edit;
        $dabar->gudang_kategori = $request->barang_kategori_edit;
        $dabar->jenis_obat = $request->jenis_obat_edit;
        $dabar->jenis_generik = $request->jenis_generik_edit;
        $dabar->bentuk_sediaan = $request->bentuk_sediaan_edit;
        $dabar->user_input_id = Auth::user()->id;
        $dabar->user_input_nama = Auth::user()->name;
        $dabar->save();

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil diperbarui!'
        ]);
    }

    public function dabardelete(Request $request)
    {

        $request->validate([
            'dabarid_delete' => 'required'
        ]);

        $dabar = gudang_barang::find($request->dabarid_delete);

        if (!$dabar) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan!'
            ], 404);
        }

        $dabar->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil dihapus!'
        ]);
    }

    public function dabarexport()
    {
        return Excel::download(new Gudang_barangExport, 'Data Gudang Barang.xlsx');
    }

    public function dabarimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Gudang_barangImport, $request->file('file'));


        return redirect()->route('dabar.get')->with('success', 'Data berhasil diimpor!');
    }

    // Koneksi antar database
    public function dabarsingkron($id)
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
        $data = $connection->table('gudang_barangs')->get();

        $response = response()->json($data)->getData();

        try {
            // Simpan data ke database
            foreach ($response  as $item) {
                gudang_barang::updateOrCreate(
                    [
                        'kode_barang' => $item->kode_barang,
                        'nama_barang' => $item->nama_barang,
                        'kfa_kode' => $item->kfa_kode,
                        'jenis_formularium' => $item->jenis_formularium,
                        'nama_industri_barang' => $item->nama_industri_barang,
                        'satuan_kecil' => $item->satuan_kecil,
                        'satuan_sedang' => $item->satuan_sedang,
                        'satuan_besar' => $item->satuan_besar,
                        'nilai_satuan_kecil' => $item->nilai_satuan_kecil,
                        'nilai_satuan_sedang' => $item->nilai_satuan_sedang,
                        'nilai_satuan_besar' => $item->nilai_satuan_besar,
                        'tempat_penyimpanan' => $item->tempat_penyimpanan,
                        'barcode' => $item->barcode,
                        'gudang_kategori' => $item->gudang_kategori,
                        'jenis_obat' => $item->jenis_obat,
                        'jenis_generik' => $item->jenis_generik,
                        'bentuk_sediaan' => $item->bentuk_sediaan,
                        'user_input_id' => Auth::user()->id,
                        'user_input_nama' => Auth::user()->name,
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

        //Generate Kode Barang Otomatis
        public function generateKodeDataBarang()
        {
            // Mengambil data barang terakhir dari tabel 'gudang_barang'
            $last = gudang_barang::orderBy('id', 'desc')->first();

            // Jika tidak ada data barang sebelumnya atau kode barang tidak sesuai format 'KBR-xxxx'
            if (!$last || !preg_match('/^KBR-(\d{4})$/', $last->kode_barang, $match)) {
                $nextNumber = 1;  // Mulai dengan nomor 1 jika tidak ada data atau format kode salah
            } else {
                // Jika ada data sebelumnya, ambil angka terakhir dan tambah 1
                $nextNumber = (int)$match[1] + 1;
            }

            // Membuat kode barang baru dengan format 'KBR-xxxx' (dengan padding 0 di depan)
            $kode = 'KBR-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Mengembalikan response dalam format JSON
            return response()->json([
                'success' => true,
                'kode_barang' => $kode
            ]);
        }
}
