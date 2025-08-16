<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Inventaris\Stok;

use App\Http\Controllers\Controller;
use App\Models\inventaris_stok;
use App\Models\inventaris_stok_utama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Stok_Inventaris_Controller extends Controller
{
    public function stokin()
    {
        $title = "Master Stok Inventaris ";

        $subQuery = inventaris_stok::select(DB::raw('MIN(id) as id'))
            ->groupBy('kode_pembelian', 'kode_barang');

        $stok_inventaris = inventaris_stok::from('inventaris_stoks as a')
            ->joinSub($subQuery, 'b', 'a.id', '=', 'b.id')
            ->select(
                'a.id',
                'a.kode_pembelian',
                'a.kode_barang',
                'a.nama_barang',
                'a.kategori_barang',
                'a.jenis_barang',
                'a.tanggal_pembelian',
                'a.masa_akhir_penggunaan',
                'a.detail_barang',
                DB::raw('(SELECT SUM(qty_barang) FROM inventaris_stoks WHERE kode_pembelian = a.kode_pembelian AND kode_barang = a.kode_barang) as total_qty_barang')
            )
            ->get();
        return view('module.master-data-gudang.stok_inventaris', compact('title', 'stok_inventaris'));
    }

    public function stokin_data(Request $request, $id)
    {
        $title = "Detail Data Stok Inventaris";
        $kode = $request->query('kode');

        $stok = inventaris_stok::where('kode_pembelian', $id)
            ->where('kode_barang', $kode)
            ->get();

        // dd($tindakanTabel);
        return view('module.master-data-gudang.stok_inventaris_data', compact('title', 'kode', 'stok'));
    }

    public function stokin_datadelete(Request $request)
    {

        $request->validate([
            'data_inventaris_id_delete' => 'required'
        ]);

        $stok = inventaris_stok::find($request->data_inventaris_id_delete);

        if (!$stok) {
            return response()->json([
                'success' => false,
                'message' => 'Data stok inventaris tidak ditemukan!'
            ], 404);
        }

        $stok->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data stok inventaris berhasil dihapus!'
        ]);
    }

    public function stokin_dataedit(Request $request)
    {
        $request->validate([
            'kode_edit' => 'required|string',
            'nama_edit' => 'required|string',
            'lokasi_barang_edit' => 'required|string',
            'penanggung_jawab_edit' => 'required|string',
            'kondisi_barang_edit' => 'required|string',
            'no_seri_edit' => 'required|string',
        ]);

        $stok = inventaris_stok::find($request->data_id_edit);

        if (!$stok) {
            return response()->json([
                'success' => false,
                'message' => 'Data stok inventaris tidak ditemukan!'
            ], 404);
        }

        $stok->lokasi = $request->lokasi_barang_edit;
        $stok->penanggung_jawab = $request->penanggung_jawab_edit;
        $stok->kondisi = $request->kondisi_barang_edit;
        $stok->no_seri = $request->no_seri_edit;
        $stok->save();

        return response()->json([
            'success' => true,
            'message' => 'Data stok inventaris berhasil diperbarui!'
        ]);
    }

    // Stok Inventaris Utama
    public function stokin_utama()
    {
        $title = "Master Stok Inventaris Utama ";
        // $stok_inventaris = DB::table('inventaris_stoks as a')
        //     ->join(
        //         DB::raw('(SELECT MIN(id) as id FROM inventaris_stoks GROUP BY kode_pembelian, kode_barang) as b'),
        //         'a.id',
        //         '=',
        //         'b.id'
        //     )
        //     ->select(
        //         'a.id',
        //         'a.kode_pembelian',
        //         'a.kode_barang',
        //         'a.nama_barang',
        //         'a.kategori_barang',
        //         'a.jenis_barang',
        //         'a.tanggal_pembelian',
        //         'a.masa_akhir_penggunaan',
        //         'a.detail_barang'
        //     )
        //     ->get();

        $subQuery = inventaris_stok_utama::select(DB::raw('MIN(id) as id'))
            ->groupBy('kode_pembelian', 'kode_barang');

        $stok_inventaris = inventaris_stok_utama::from('inventaris_stok_utamas as a')
            ->joinSub($subQuery, 'b', 'a.id', '=', 'b.id')
            ->select(
                'a.id',
                'a.kode_pembelian',
                'a.kode_barang',
                'a.nama_barang',
                'a.kategori_barang',
                'a.jenis_barang',
                'a.tanggal_pembelian',
                'a.masa_akhir_penggunaan',
                'a.detail_barang',
                DB::raw('(SELECT SUM(qty_barang) FROM inventaris_stok_utamas WHERE kode_pembelian = a.kode_pembelian AND kode_barang = a.kode_barang) as total_qty_barang')
            )
            ->get();
        return view('module.master-data-gudang.stok_inventaris_utama', compact('title', 'stok_inventaris'));
    }

    public function stokin_data_utama(Request $request, $id)
    {
        $title = "Detail Data Stok Inventaris";
        $kode = $request->query('kode');

        $stok = inventaris_stok_utama::where('kode_pembelian', $id)
            ->where('kode_barang', $kode)
            ->get();

        // dd($tindakanTabel);
        return view('module.master-data-gudang.stok_inventaris_data_utama', compact('title', 'kode', 'stok'));
    }

    public function stokin_datadelete_utama(Request $request)
    {

        $request->validate([
            'data_inventaris_id_delete' => 'required'
        ]);

        $stok = inventaris_stok_utama::find($request->data_inventaris_id_delete);

        if (!$stok) {
            return response()->json([
                'success' => false,
                'message' => 'Data stok inventaris tidak ditemukan!'
            ], 404);
        }

        $stok->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data stok inventaris berhasil dihapus!'
        ]);
    }

    public function stokin_dataedit_utama(Request $request)
    {
        $request->validate([
            'kode_edit' => 'required|string',
            'nama_edit' => 'required|string',
            'lokasi_barang_edit' => 'required|string',
            'penanggung_jawab_edit' => 'required|string',
            'kondisi_barang_edit' => 'required|string',
            'no_seri_edit' => 'required|string',
        ]);

        $stok = inventaris_stok_utama::find($request->data_id_edit);

        if (!$stok) {
            return response()->json([
                'success' => false,
                'message' => 'Data stok inventaris tidak ditemukan!'
            ], 404);
        }

        $stok->lokasi = $request->lokasi_barang_edit;
        $stok->penanggung_jawab = $request->penanggung_jawab_edit;
        $stok->kondisi = $request->kondisi_barang_edit;
        $stok->no_seri = $request->no_seri_edit;
        $stok->save();

        return response()->json([
            'success' => true,
            'message' => 'Data stok inventaris berhasil diperbarui!'
        ]);
    }
}
