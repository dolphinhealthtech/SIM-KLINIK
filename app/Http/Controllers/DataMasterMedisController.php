<?php

namespace App\Http\Controllers;

use App\Exports\Htt_pemeriksaanExport;
use App\Exports\PoliExport;
use App\Exports\SpesialisExport;
use App\Exports\SubspesialisExport;
use App\Exports\Perawatan_kategoriExport;
use App\Exports\Perawatan_tindakanExport;
use App\Imports\Htt_pemeriksaanImport;
use App\Imports\PoliImport;
use App\Imports\SpesialisImport;
use App\Imports\SubspesialisImport;
use App\Imports\Perawatan_kategoriImport;
use App\Imports\Perawatan_tindakanImport;
use App\Models\alergi;
use App\Models\htt_pemeriksaan;
use App\Models\htt_sub_pemeriksaan;
use App\Models\poli;
use App\Models\spesialis;
use App\Models\subspesialis;
use App\Models\perawatan_kategori;
use App\Models\perawatan_tindakan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class DataMasterMedisController extends Controller
{
    protected $PcareController;

    // Gunakan dependency injection
    public function __construct(PcareController $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    // data poli
    public function poli()
    {
        $title = "Master Data Poli";
        $poli = poli::all();
        return view('module.master-data-medis.poli', compact('title','poli'));
    }

    public function poliadd()
    {

        $response = $this->PcareController->get_poli_fktp_bpjs();
        $data = json_decode($response->getContent(), true);
        try {
            // Simpan data ke database
            foreach ($data['data'] as $item) {
                Poli::updateOrCreate(
                    [
                        'kode' => $item['kode_poli'],
                        'nama' => $item['nama_poli'],
                        'jenis' => $item['jenis_poli']
                    ]
                );
            }


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Poli berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Poli Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Poli!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function polidelete(Request $request)
    {

        $request->validate([
            'poliid_delete' => 'required'
        ]);

        $poli = poli::find($request->poliid_delete);

        if (!$poli) {
            return response()->json([
                'success' => false,
                'message' => 'Poli tidak ditemukan!'
            ], 404);
        }

        $poli->delete();

        return response()->json([
            'success' => true,
            'message' => 'Poli berhasil dihapus!'
        ]);
    }

    public function poliexport()
    {
        return Excel::download(new PoliExport, 'Poli.xlsx');
    }

    public function poliimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new PoliImport, $request->file('file'));


        return redirect()->route('poli.get')->with('success', 'Data berhasil diimpor!');
    }


    // data poli
    public function spesialis()
    {
        $title = "Master Data Spesialis";
        $spesialis = spesialis::all();
        // spesialis
        return view('module.master-data-medis.spesialis', compact('title','spesialis'));
    }

    public function spesialisadd()
    {

        $response = $this->PcareController->get_spesialis_bpjs();
        $data = json_decode($response->getContent(), true);
        try {
            // Simpan data ke database
            foreach ($data['data']['list'] as $item) {
                spesialis::updateOrCreate(
                    [
                        'kode' => $item['kdSpesialis'],
                        'nama' => $item['nmSpesialis']
                    ]
                );
            }


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Spesialis berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Spesialis Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Spesialis!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function spesialisdelete(Request $request)
    {

        $request->validate([
            'spesialisid_delete' => 'required'
        ]);

        $spesialis = spesialis::find($request->spesialisid_delete);

        if (!$spesialis) {
            return response()->json([
                'success' => false,
                'message' => 'Spesialis tidak ditemukan!'
            ], 404);
        }

        $spesialis->delete();

        return response()->json([
            'success' => true,
            'message' => 'Spesialis berhasil dihapus!'
        ]);
    }

    public function spesialisexport()
    {
        return Excel::download(new SpesialisExport, 'Spesilais.xlsx');
    }

    public function spesialisimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new SpesialisImport, $request->file('file'));


        return redirect()->route('speislais.get')->with('success', 'Data berhasil diimpor!');
    }

     // data poli
    public function subspesialis($kode)
    {
        $title = "Master Data Sub Spesialis";
        $subspesialis = subspesialis::where('kode_spesialis', $kode)->get();
        // spesialis
        return view('module.master-data-medis.subspesialis', compact('title','subspesialis','kode'));
    }

    public function subspesialisadd($kode)
    {

        $response = $this->PcareController->get_sub_spesialis_bpjs($kode);
        $data = json_decode($response->getContent(), true);
        try {
            // Simpan data ke database
            foreach ($data['data']['list'] as $item) {
                subspesialis::updateOrCreate(
                    [
                        'nama' => $item['nmSubSpesialis'],
                        'kode' => $item['kdSubSpesialis'],
                        'kode_rujukan' => $item['kdPoliRujuk'],
                        'kode_spesialis' => $kode
                    ]
                );
            }


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Spesialis berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Spesialis Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Spesialis!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function subspesialisdelete(Request $request)
    {

        $request->validate([
            'subspesialisid_delete' => 'required'
        ]);

        $subspesialis = subspesialis::find($request->subspesialisid_delete);

        if (!$subspesialis) {
            return response()->json([
                'success' => false,
                'message' => 'subspesialis tidak ditemukan!'
            ], 404);
        }

        $subspesialis->delete();

        return response()->json([
            'success' => true,
            'message' => 'subspesialis berhasil dihapus!'
        ]);
    }

    // Kategori Perawatan

    public function kategori_perawatan()
    {
         $title = "Master Kategori Perawatan";
         $kategori_perawatan = perawatan_kategori::all();
         return view('module.master-data-medis.kategori_perawatan', compact('title','kategori_perawatan'));
    }

    public function kategori_perawatanadd(Request $request)
    {
         try {
             $request->validate([
                 "nama" => 'required|string|unique:perawatan_kategoris,nama',
             ]);

             $kategori_perawatan = perawatan_kategori::create([
                 'nama' => $request->nama,
             ]);

             return response()->json([
                 'success' => true,
                 'message' => 'Kategori perawatan berhasil ditambahkan!',
                 'data' => $kategori_perawatan
             ], 201);
         } catch (ValidationException $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Kategori perawatan Sudah ada!',
                 'errors' => $e->errors()
             ], 422);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan saat menyimpan kategori perawatan!',
                 'error' => $e->getMessage()
             ], 500);
         }
    }

    public function kategori_perawatanedit(Request $request)
    {
         $request->validate([
             'nama_edit' => 'required|string',
         ]);

         $kategori_perawatan = perawatan_kategori::find($request->kategori_perawatanid_edit);

         if (!$kategori_perawatan) {
             return response()->json([
                 'success' => false,
                 'message' => 'Kategori perawatan tidak ditemukan!'
             ], 404);
         }

         $kategori_perawatan->nama = $request->nama_edit;
         $kategori_perawatan->save();

         return response()->json([
             'success' => true,
             'message' => 'Kategori perawatan berhasil diperbarui!'
         ]);
    }

    public function kategori_perawatandelete(Request $request)
    {

        $request->validate([
            'kategori_perawatanid_delete' => 'required'
        ]);

        $kategori_perawatan = perawatan_kategori::find($request->kategori_perawatanid_delete);
        if (!$kategori_perawatan) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori perawatan tidak ditemukan!'
            ], 404);
        }
        $kategori_perawatan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori perawatan berhasil dihapus!'
        ]);
    }

    public function kategori_perawatanexport()
    {
         return Excel::download(new Perawatan_kategoriExport, 'Kategori Perawatan.xlsx');
    }

    public function kategori_perawatanimport(Request $request)
    {
        $request->validate([
             'file' => 'required|mimes:xlsx,xls'
         ]);

         Excel::import(new Perawatan_kategoriImport, $request->file('file'));


         return redirect()->route('kategori_perawatan.get')->with('success', 'Data berhasil diimpor!');
    }

    // End Kategori Perawatan

    // Kategori Perawatan

    public function perawatan_tindakan()
    {
         $title = "Master Kategori Perawatan";
         $perawatan_tindakan = perawatan_tindakan::with('perawatan_kategori')->get();
         $kategori = perawatan_kategori::all();
         return view('module.master-data-medis.perawatan_tindakan', compact('title','perawatan_tindakan','kategori'));
    }

    public function perawatan_tindakanadd(Request $request)
    {
         try {
             $request->validate([
                 "kode" => 'required|string',
                 "nama" => 'required|string',
                 "kategori" => 'required|string',
                 "tarif_dokter" => 'required|string',
                 "tarif_perawat" => 'required|string',
                 "tarif_total" => 'required|string',
             ]);

             $perawatan_tindakan = perawatan_tindakan::create([
                 'kode' => $request->kode,
                 'nama' => $request->nama,
                 'perawatan_kategori_id' => $request->kategori,
                 'tarif_dokter' => $request->tarif_dokter,
                 'tarif_perawat' => $request->tarif_perawat,
                 'tarif_total' => $request->tarif_total,
             ]);

             return response()->json([
                 'success' => true,
                 'message' => 'Perawatan tindakan berhasil ditambahkan!',
                 'data' => $perawatan_tindakan
             ], 201);
         } catch (ValidationException $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Perawatan tindakan Sudah ada!',
                 'errors' => $e->errors()
             ], 422);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan saat menyimpan perawatan tindakan!',
                 'error' => $e->getMessage()
             ], 500);
         }
    }

    public function perawatan_tindakanedit(Request $request)
    {
         $request->validate([
            "nama_edit" => 'required|string',
            "kategori_edit" => 'required|string',
            "tarif_dokter_edit" => 'required|string',
            "tarif_perawat_edit" => 'required|string',
            "tarif_total_edit" => 'required|string',
         ]);

         $perawatan_tindakan = perawatan_tindakan::find($request->perawatan_tindakanid_edit);

         if (!$perawatan_tindakan) {
             return response()->json([
                 'success' => false,
                 'message' => 'Perawatan tindakan tidak ditemukan!'
             ], 404);
         }

         $perawatan_tindakan->nama = $request->nama_edit;
         $perawatan_tindakan->perawatan_kategori_id = $request->kategori_edit;
         $perawatan_tindakan->tarif_dokter = $request->tarif_dokter_edit;
         $perawatan_tindakan->tarif_perawat = $request->tarif_perawat_edit;
         $perawatan_tindakan->tarif_total = $request->tarif_total_edit;
         $perawatan_tindakan->save();

         return response()->json([
             'success' => true,
             'message' => 'Perawatan tindakan berhasil diperbarui!'
         ]);
    }

    public function perawatan_tindakandelete(Request $request)
    {

        $request->validate([
            'perawatan_tindakanid_delete' => 'required'
        ]);

        $perawatan_tindakan = perawatan_tindakan::find($request->perawatan_tindakanid_delete);
        if (!$perawatan_tindakan) {
            return response()->json([
                'success' => false,
                'message' => 'Perawatan tindakan tidak ditemukan!'
            ], 404);
        }
        $perawatan_tindakan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Perawatan tindakan berhasil dihapus!'
        ]);
    }

    public function perawatan_tindakanexport()
    {
         return Excel::download(new Perawatan_tindakanExport, 'Macam-macam perawatan tindakan.xlsx');
    }

    public function perawatan_tindakanimport(Request $request)
    {
        $request->validate([
             'file' => 'required|mimes:xlsx,xls'
         ]);

         Excel::import(new Perawatan_tindakanImport, $request->file('file'));


         return redirect()->route('perawatan_tindakan.get')->with('success', 'Data berhasil diimpor!');
    }

        // API Get Kode Kategori Perawatan

        public function getLastKode()
        {
            $last = perawatan_tindakan::orderBy('id', 'desc')->first();

            return response()->json([
                'kode' => $last ? $last->kode : null
            ]);
        }

        //End

    // End Kategori Perawatan

    // pemeriksaan htt
    public function htt_pemeriksaan()
    {
        $title = "Master Pemeriksaan HTT";
        $pemeriksaan_htt = htt_pemeriksaan::all();
        return view('module.master-data-medis.htt_pemeriksaan', compact('title','pemeriksaan_htt'));
    }

    public function htt_pemeriksaanadd(Request $request)
    {
         try {
             $request->validate([
                 "nama" => 'required|string',
             ]);

             $htt_pemeriksaan = htt_pemeriksaan::create([
                 'nama_pemeriksaan' => $request->nama,
             ]);

             return response()->json([
                 'success' => true,
                 'message' => 'Perawatan tindakan berhasil ditambahkan!',
                 'data' => $htt_pemeriksaan
             ], 201);
         } catch (ValidationException $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Perawatan tindakan Sudah ada!',
                 'errors' => $e->errors()
             ], 422);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan saat menyimpan perawatan tindakan!',
                 'error' => $e->getMessage()
             ], 500);
         }
    }

    public function htt_pemeriksaanedit(Request $request)
    {
         $request->validate([
            "nama_edit" => 'required|string',
         ]);

         $htt_pemeriksaan = htt_pemeriksaan::find($request->htt_pemeriksaanid_edit);

         if (!$htt_pemeriksaan) {
             return response()->json([
                 'success' => false,
                 'message' => 'Perawatan tindakan tidak ditemukan!'
             ], 404);
         }

         $htt_pemeriksaan->nama_pemeriksaan = $request->nama_edit;
         $htt_pemeriksaan->save();

         return response()->json([
             'success' => true,
             'message' => 'Perawatan tindakan berhasil diperbarui!'
         ]);
    }

    public function htt_pemeriksaandelete(Request $request)
    {

        $request->validate([
            'pemeriksaan_httid_delete' => 'required'
        ]);

        $htt_pemeriksaan = htt_pemeriksaan::find($request->pemeriksaan_httid_delete);
        if (!$htt_pemeriksaan) {
            return response()->json([
                'success' => false,
                'message' => 'Perawatan tindakan tidak ditemukan!'
            ], 404);
        }
        $htt_pemeriksaan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Perawatan tindakan berhasil dihapus!'
        ]);
    }

    public function htt_pemeriksaanexport()
    {
        return Excel::download(new Htt_pemeriksaanExport, 'Macam-macam Pemeriksaan HTT.xlsx');
    }

    public function htt_pemeriksaaneimport(Request $request)
    {
        $request->validate([
             'file' => 'required|mimes:xlsx,xls'
         ]);

         Excel::import(new Htt_pemeriksaanImport, $request->file('file'));


         return redirect()->route('htt_pemeriksaan.get')->with('success', 'Data berhasil diimpor!');
    }

    public function htt_sub_pemeriksaan($kode)
    {
        $title = "Master Data Sub Spesialis";
        $htt_sub_pemeriksaan = htt_sub_pemeriksaan::where('htt_pemeriksaan_id', $kode)->get();
        $htt_pemeriksaan = htt_pemeriksaan::find($kode);

        // spesialis
        return view('module.master-data-medis.htt_sub_pemeriksaan', compact('title','htt_sub_pemeriksaan','htt_pemeriksaan'));
    }

    public function htt_sub_pemeriksaanadd(Request $request)
    {
        try {
            $request->validate([
                "htt_sub_pemeriksaan_id" => 'required',
                "nama_sub_pemeriksaan" => 'required|string',
                "nama" => 'required|string',
            ]);

            $htt_sub_pemeriksaan = htt_sub_pemeriksaan::create([
                'htt_pemeriksaan_id' => $request->htt_sub_pemeriksaan_id,
                'nama_pemeriksaan' => $request->nama_sub_pemeriksaan,
                'nama_subpemeriksaan' => $request->nama,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Perawatan tindakan berhasil ditambahkan!',
                'data' => $htt_sub_pemeriksaan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Perawatan tindakan Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan perawatan tindakan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function htt_sub_pemeriksaanedit(Request $request)
    {
         $request->validate([
            "htt_sub_pemeriksaanid_edit" => 'required',
            "htt_sub_pemeriksaan_id_edit" => 'required',
            "nama_pemeriksaan_edit" => 'required|string',
            "nama_edit" => 'required|string',
         ]);

         $htt_sub_pemeriksaan = htt_sub_pemeriksaan::find($request->htt_sub_pemeriksaanid_edit);

         if (!$htt_sub_pemeriksaan) {
             return response()->json([
                 'success' => false,
                 'message' => 'Perawatan tindakan tidak ditemukan!'
             ], 404);
         }

         $htt_sub_pemeriksaan->htt_pemeriksaan_id = $request->htt_sub_pemeriksaan_id_edit;
         $htt_sub_pemeriksaan->nama_pemeriksaan = $request->nama_pemeriksaan_edit;
         $htt_sub_pemeriksaan->nama_subpemeriksaan = $request->nama_edit;
         $htt_sub_pemeriksaan->save();

         return response()->json([
             'success' => true,
             'message' => 'Perawatan tindakan berhasil diperbarui!'
         ]);
    }

    public function htt_sub_pemeriksaandelete(Request $request)
    {

        $request->validate([
            'htt_sub_pemeriksaanid_delete' => 'required'
        ]);

        $htt_sub_pemeriksaan = htt_sub_pemeriksaan::find($request->htt_sub_pemeriksaanid_delete);
        if (!$htt_sub_pemeriksaan) {
            return response()->json([
                'success' => false,
                'message' => 'Perawatan tindakan tidak ditemukan!'
            ], 404);
        }
        $htt_sub_pemeriksaan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Perawatan tindakan berhasil dihapus!'
        ]);
    }

    // data poli
    public function alergi()
    {
        $title = "Master Data Alergi";
        $alergi = alergi::all();
        return view('module.master-data-medis.alergi', compact('title','alergi'));
    }

    public function alergiadd(Request $request)
    {

        $response = $this->PcareController->get_alergi_bpjs($request->jenis_alergi);
        $data = json_decode($response->getContent(), true);
        try {
            // Simpan data ke database
            foreach ($data['data']['list'] as $item) {
                if ($item['kdAlergi'] != '00') {
                    alergi::updateOrCreate(
                        [
                            'kode_jenis_alergi' => $request->jenis_alergi,
                            'kode_alergi' => $item['kdAlergi'],
                            'nama_jenis_alergi' => $item['nmAlergi']
                        ]
                    );
                }
            }


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Alergi berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Alergi Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Alergi!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function alergidelete(Request $request)
    {

        $request->validate([
            'alergiid_delete' => 'required'
        ]);

        $alergi = alergi::find($request->alergiid_delete);

        if (!$alergi) {
            return response()->json([
                'success' => false,
                'message' => 'alergi tidak ditemukan!'
            ], 404);
        }

        $alergi->delete();

        return response()->json([
            'success' => true,
            'message' => 'alergi berhasil dihapus!'
        ]);
    }
}
