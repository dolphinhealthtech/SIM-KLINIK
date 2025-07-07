<?php

namespace App\Http\Controllers;

use App\Exports\Nama_MakananExport;
use App\Exports\Jenis_DietExport;
use App\Exports\Htt_pemeriksaanExport;
use App\Exports\Icd10Export;
use App\Exports\Icd9Export;
use App\Exports\Laboratorium_bidangExport;
use App\Exports\PoliExport;
use App\Exports\SpesialisExport;
use App\Exports\SubspesialisExport;
use App\Exports\Perawatan_kategoriExport;
use App\Exports\Perawatan_tindakanExport;
use App\Exports\Radiologi_pemeriksaanExport;
use App\Exports\SaranaExport;
use App\Imports\Nama_MakananImport;
use App\Imports\Jenis_DietImport;
use App\Imports\Htt_pemeriksaanImport;
use App\Imports\Icd10Import;
use App\Imports\Icd9Import;
use App\Imports\Laboratorium_bidangImport;
use App\Imports\PoliImport;
use App\Imports\SpesialisImport;
use App\Imports\SubspesialisImport;
use App\Imports\Perawatan_kategoriImport;
use App\Imports\Radiologi_JenisImport;
use App\Exports\Radiologi_JenisExport;
use App\Imports\Perawatan_tindakanImport;
use App\Imports\Radiologi_pemeriksaanImport;
use App\Imports\SaranaImport;
use App\Models\alergi;
use App\Models\jenis_diet;
use App\Models\nama_makanan;
use App\Models\htt_pemeriksaan;
use App\Models\htt_sub_pemeriksaan;
use App\Models\icd10;
use App\Models\icd9;
use App\Models\laboratorium_bidang;
use App\Models\laboratorium_bidang_sub;
use App\Models\poli;
use App\Models\spesialis;
use App\Models\subspesialis;
use App\Models\perawatan_kategori;
use App\Models\perawatan_tindakan;
use App\Models\radiologi_pemeriksaan;
use App\Models\sarana;
use App\Models\radiologi_jenis;
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


        return redirect()->route('spesialis.get')->with('success', 'Data berhasil diimpor!');
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
                'message' => 'subspesialis berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'subspesialis Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan subspesialis!',
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
                 'message' => 'Pemeriksaan HTT berhasil ditambahkan!',
                 'data' => $htt_pemeriksaan
             ], 201);
         } catch (ValidationException $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Pemeriksaan HTT Sudah ada!',
                 'errors' => $e->errors()
             ], 422);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan saat menyimpan Pemeriksaan HTT!',
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
                 'message' => 'Pemeriksaan HTT tidak ditemukan!'
             ], 404);
         }

         $htt_pemeriksaan->nama_pemeriksaan = $request->nama_edit;
         $htt_pemeriksaan->save();

         return response()->json([
             'success' => true,
             'message' => 'Pemeriksaan HTT berhasil diperbarui!'
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
                'message' => 'Pemeriksaan HTT tidak ditemukan!'
            ], 404);
        }
        $htt_pemeriksaan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pemeriksaan HTT berhasil dihapus!'
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
                'message' => 'sub pemeriksaan berhasil ditambahkan!',
                'data' => $htt_sub_pemeriksaan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'sub pemeriksaan Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan sub pemeriksaan!',
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
                 'message' => 'sub pemeriksaan tidak ditemukan!'
             ], 404);
         }

         $htt_sub_pemeriksaan->htt_pemeriksaan_id = $request->htt_sub_pemeriksaan_id_edit;
         $htt_sub_pemeriksaan->nama_pemeriksaan = $request->nama_pemeriksaan_edit;
         $htt_sub_pemeriksaan->nama_subpemeriksaan = $request->nama_edit;
         $htt_sub_pemeriksaan->save();

         return response()->json([
             'success' => true,
             'message' => 'sub pemeriksaan berhasil diperbarui!'
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
                'message' => 'sub pemeriksaan tidak ditemukan!'
            ], 404);
        }
        $htt_sub_pemeriksaan->delete();

        return response()->json([
            'success' => true,
            'message' => 'sub pemeriksaan berhasil dihapus!'
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

    // jenis_diet Start
    public function jenis_diet()
    {
        $title = "Master jenis_diet";
        $jenis_diet = jenis_diet::all();
        return view('module.master-data-medis.jenis_diet', compact('title','jenis_diet'));
    }

    public function jenis_dietadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:jenis_diets,nama',
            ]);

            $jenis_diet = jenis_diet::create([
                'nama' => $request->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'jenis diet berhasil ditambahkan!',
                'data' => $jenis_diet,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'jenis diet Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan jenis diet!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function icd10()
    {
        $title = "Master ICD 10";
        $icd10 = icd10::all();
        return view('module.master-data-medis.icd10', compact('title','icd10'));
    }

    public function icd10add(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string',
                'kode' =>'string|nullable'
            ]);
            // Simpan data ke database
            $goldar = icd10::create([
                'nama_icd10' => $request->input('nama'),   // Ambil data dari request
                'kode_icd10' => $request->input('kode')
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'icd10 berhasil ditambahkan!',
                'data' => $goldar
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'icd10 Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan icd10!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function jenis_dietedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $jenis_diet = jenis_diet::find($request->jenis_dietid_edit);

        if (!$jenis_diet) {
            return response()->json([
                'success' => false,
                'message' => 'jenis diet tidak ditemukan!'
            ], 404);
        }

        $jenis_diet->nama = $request->nama_edit;
        $jenis_diet->save();

        return response()->json([
            'success' => true,
            'message' => 'jenis diet berhasil diperbarui!'
        ]);
    }

    public function jenis_dietdelete(Request $request)
    {

        $request->validate([
            'jenis_dietid_delete' => 'required'
        ]);

        $jenis_diet = jenis_diet::find($request->jenis_dietid_delete);
        if (!$jenis_diet) {
            return response()->json([
                'success' => false,
                'message' => 'jenis diet tidak ditemukan!'
            ], 404);
        }
        $jenis_diet->delete();

        return response()->json([
            'success' => true,
            'message' => 'jenis diet berhasil dihapus!'
        ]);
    }

    public function jenis_dietexport()
    {
        return Excel::download(new jenis_dietExport, 'jenis_diet.xlsx');
    }

    public function jenis_dietimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new jenis_dietImport, $request->file('file'));


        return redirect()->route('jenis_diet.get')->with('success', 'Data berhasil diimpor!');
    }
    // jenis_diet end


    // Nama_Makanan Start
    public function nama_makanan()
    {
        $title = "Master nama_makanan";
        $nama_makanan = nama_makanan::all();
        return view('module.master-data-medis.nama_makanan', compact('title','nama_makanan'));
    }

    public function nama_makananadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:nama_makanans,nama', // perhatikan nama tabel
            ]);

            $nama_makanan = nama_makanan::create([
                'nama' => $request->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'nama makanan berhasil ditambahkan!',
                'data' => $nama_makanan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'nama makanan Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {


            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan nama makanan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function icd10edit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
            'kode_edit' =>'string|nullable'
        ]);

        $icd10 = icd10::find($request->icd10id_edit);

        if (!$icd10) {
            return response()->json([
                'success' => false,
                'message' => 'icd10 tidak ditemukan!'
            ], 404);
        }

        $icd10->nama_icd10 = $request->nama_edit;
        $icd10->kode_icd10 = $request->kode_edit;
        $icd10->save();

        return response()->json([
            'success' => true,
            'message' => 'icd10 berhasil diperbarui!'
        ]);
    }
    public function icd10delete(Request $request)
    {

        $request->validate([
            'icd10id_delete' => 'required'
        ]);

        $icd10 = icd10::find($request->icd10id_delete);

        if (!$icd10) {
            return response()->json([
                'success' => false,
                'message' => 'icd10 tidak ditemukan!'
            ], 404);
        }

        $icd10->delete();

        return response()->json([
            'success' => true,
            'message' => 'icd10 berhasil dihapus!'
        ]);
    }

    public function icd10singkron(Request $request)
    {
        $response = $this->PcareController->get_diagnosis_bpjs($request->kode_icd);
        $data = json_decode($response->getContent(), true);
        try {
            // Simpan data ke database
            foreach ($data['data']['list'] as $item) {
                icd10::updateOrCreate(
                    [
                        'kode_icd10' => $item['kdDiag'],
                        'nama_icd10' => $item['nmDiag']
                    ]
                );
            }
            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'icd10 berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'icd10 Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan icd10!',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function nama_makananedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $nama_makanan = nama_makanan::find($request->nama_makananid_edit);

        if (!$nama_makanan) {
            return response()->json([
                'success' => false,
                'message' => 'nama makanan tidak ditemukan!'
            ], 404);
        }

        $nama_makanan->nama = $request->nama_edit;
        $nama_makanan->save();

        return response()->json([
            'success' => true,
            'message' => 'nama makanan berhasil diperbarui!'
        ]);
    }

    public function nama_makanandelete(Request $request)
    {

        $request->validate([
            'nama_makananid_delete' => 'required'
        ]);

        $nama_makanan = nama_makanan::find($request->nama_makananid_delete);
        if (!$nama_makanan) {
            return response()->json([
                'success' => false,
                'message' => 'nama makanan tidak ditemukan!'
            ], 404);
        }
        $nama_makanan->delete();

        return response()->json([
            'success' => true,
            'message' => 'nama makanan berhasil dihapus!'
        ]);
    }

    public function nama_makananexport()
    {
        return Excel::download(new nama_makananExport, 'nama_makanan.xlsx');
    }

    public function nama_makananimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new nama_makananImport, $request->file('file'));


        return redirect()->route('nama_makanan.get')->with('success', 'Data berhasil diimpor!');
    }
    // nama_makanan end
    public function icd10export()
    {
        return Excel::download(new Icd10Export, 'Macam-macam ICD10.xlsx');
    }

    public function icd10import(Request $request)
    {
        $request->validate([
             'file' => 'required|mimes:xlsx,xls'
         ]);

         Excel::import(new Icd10Import, $request->file('file'));
        return redirect()->route('icd10.get')->with('success', 'Data berhasil diimpor!');
    }

    public function icd9()
    {
        $title = "Master ICD 9";
        $icd9 = icd9::all();
        return view('module.master-data-medis.icd9', compact('title','icd9'));
    }

    public function icd9add(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string',
                'kode' =>'string|nullable'
            ]);
            // Simpan data ke database
            $goldar = icd9::create([
                'nama_icd9' => $request->input('nama'),   // Ambil data dari request
                'kode_icd9' => $request->input('kode')
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'icd9 berhasil ditambahkan!',
                'data' => $goldar
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'icd9 Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan icd9!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function icd9edit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
            'kode_edit' =>'string|nullable'
        ]);

        $icd9 = icd9::find($request->icd9id_edit);

        if (!$icd9) {
            return response()->json([
                'success' => false,
                'message' => 'icd9 tidak ditemukan!'
            ], 404);
        }

        $icd9->nama_icd9 = $request->nama_edit;
        $icd9->kode_icd9 = $request->kode_edit;
        $icd9->save();

        return response()->json([
            'success' => true,
            'message' => 'icd9 berhasil diperbarui!'
        ]);
    }

    public function icd9delete(Request $request)
    {

        $request->validate([
            'icd9id_delete' => 'required'
        ]);

        $icd9 = icd9::find($request->icd9id_delete);

        if (!$icd9) {
            return response()->json([
                'success' => false,
                'message' => 'icd9 tidak ditemukan!'
            ], 404);
        }

        $icd9->delete();

        return response()->json([
            'success' => true,
            'message' => 'icd9 berhasil dihapus!'
        ]);
    }

    public function icd9export()
    {
        return Excel::download(new Icd9Export, 'Macam-macam ICD9.xlsx');
    }

    public function icd9import(Request $request)
    {
        $request->validate([
             'file' => 'required|mimes:xlsx,xls'
         ]);

         Excel::import(new Icd9Import, $request->file('file'));
        return redirect()->route('icd9.get')->with('success', 'Data berhasil diimpor!');
    }

    public function sarana()
    {
        $title = "Master Data Sarana";
        $poli = sarana::all();
        return view('module.master-data-medis.sarana', compact('title','poli'));
    }

    public function saranaadd()
    {

        $response = $this->PcareController->get_sarana_bpjs();
        $data = json_decode($response->getContent(), true);
        try {
             // Validasi struktur data
            if (!isset($data['data']['list']) || !is_array($data['data']['list'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dari BPJS tidak valid atau kosong.',
                ], 400);
            }

            // Siapkan data untuk upsert
            $dataInsert = collect($data['data']['list'])->map(function ($item) {
                return [
                    'kode' => $item['kdSarana'],
                    'nama' => $item['nmSarana'],
                ];
            })->toArray();

            // Simpan sekaligus: insert baru atau update jika sudah ada berdasarkan 'kode'
            sarana::upsert($dataInsert, ['kode'], ['nama']);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'sarana berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'sarana Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan sarana!',
                'data' => $data,
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function saranadelete(Request $request)
    {

        $request->validate([
            'poliid_delete' => 'required'
        ]);

        $poli = sarana::find($request->poliid_delete);

        if (!$poli) {
            return response()->json([
                'success' => false,
                'message' => 'sarana tidak ditemukan!'
            ], 404);
        }

        $poli->delete();

        return response()->json([
            'success' => true,
            'message' => 'sarana berhasil dihapus!'
        ]);
    }

    public function saranaexport()
    {
        return Excel::download(new SaranaExport, 'Sarana.xlsx');
    }

    public function saranaimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new SaranaImport, $request->file('file'));


        return redirect()->route('sarana.get')->with('success', 'Data berhasil diimpor!');
    }

    // Radiologi_jenis Start
    public function radiologi_jenis()
    {
    $title = "Master radiologi jenis";
    $radiologi_jenis = radiologi_jenis::all();
        return view('module.master-data-medis.radiologi_jenis', compact('title','radiologi_jenis'));
    }

    public function radiologi_jenisadd(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required|string|unique:radiologi_jenis,nama', // perhatikan nama tabel
            ]);

            $radiologi_jenis = radiologi_jenis::create([
                'nama' => $request->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'radiologi jenis berhasil ditambahkan!',
                'data' => $radiologi_jenis
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'radiologi jenis Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {


            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan radiologi jenis!',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function radiologi_jenisedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
        ]);

        $radiologi_jenis = radiologi_jenis::find($request->radiologi_jenisid_edit);

        if (!$radiologi_jenis) {
            return response()->json([
                'success' => false,
                'message' => 'radiologi jenis tidak ditemukan!'
            ], 404);
        }

        $radiologi_jenis->nama = $request->nama_edit;
        $radiologi_jenis->save();

        return response()->json([
            'success' => true,
            'message' => 'radiologi jenis berhasil diperbarui!'
        ]);
    }

    public function radiologi_jenisdelete(Request $request)
    {

        $request->validate([
            'radiologi_jenisid_delete' => 'required'
        ]);

        $radiologi_jenis = radiologi_jenis::find($request->radiologi_jenisid_delete);
        if (!$radiologi_jenis) {
            return response()->json([
                'success' => false,
                'message' => 'radiologi jenis tidak ditemukan!'
            ], 404);
        }
        $radiologi_jenis->delete();

        return response()->json([
            'success' => true,
            'message' => 'radiologi jenis berhasil dihapus!'
        ]);
    }

    public function radiologi_jenisexport()
    {
        return Excel::download(new radiologi_jenisExport, 'Radiologi jenis.xlsx');
    }

    public function radiologi_jenisimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new radiologi_jenisImport, $request->file('file'));


        return redirect()->route('radiologi_jenis.get')->with('success', 'Data berhasil diimpor!');
    }


    // pemeriksaan laboratorium_bidang
    public function laboratorium_bidang()
    {
        $title = "Master Bidang Laboratorium ";
        $laboratorium_bidang = laboratorium_bidang::all();
        return view('module.master-data-medis.laboratorium_bidang', compact('title','laboratorium_bidang'));
    }

    public function laboratorium_bidangadd(Request $request)
    {
         try {
             $request->validate([
                 "nama" => 'required|string',
             ]);

             $laboratorium_bidang = laboratorium_bidang::create([
                 'nama' => $request->nama,
             ]);

             return response()->json([
                 'success' => true,
                 'message' => 'Bidang Labotorium berhasil ditambahkan!',
                 'data' => $laboratorium_bidang
             ], 201);
         } catch (ValidationException $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Bidang Labotorium Sudah ada!',
                 'errors' => $e->errors()
             ], 422);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan saat menyimpan Bidang Labotorium!',
                 'error' => $e->getMessage()
             ], 500);
         }
    }

    public function laboratorium_bidangedit(Request $request)
    {
         $request->validate([
            "nama_edit" => 'required|string',
         ]);

         $laboratorium_bidang = laboratorium_bidang::find($request->bidang_labotorium_edit);

         if (!$laboratorium_bidang) {
             return response()->json([
                 'success' => false,
                 'message' => 'Bidang Labotorium tidak ditemukan!'
             ], 404);
         }

         $laboratorium_bidang->nama = $request->nama_edit;
         $laboratorium_bidang->save();

         return response()->json([
             'success' => true,
             'message' => 'Bidang Labotorium berhasil diperbarui!'
         ]);
    }

    public function laboratorium_bidangdelete(Request $request)
    {

        $request->validate([
            'pemeriksaan_httid_delete' => 'required'
        ]);

        $laboratorium_bidang = laboratorium_bidang::find($request->pemeriksaan_httid_delete);
        if (!$laboratorium_bidang) {
            return response()->json([
                'success' => false,
                'message' => 'Bidang Labotorium tidak ditemukan!'
            ], 404);
        }
        $laboratorium_bidang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bidang Labotorium berhasil dihapus!'
        ]);
    }

    public function laboratorium_bidangexport()
    {
        return Excel::download(new Laboratorium_bidangExport, 'labotorium Bidang.xlsx');
    }

    public function laboratorium_bidangeimport(Request $request)
    {
        $request->validate([
             'file' => 'required|mimes:xlsx,xls'
         ]);

         Excel::import(new Laboratorium_bidangImport, $request->file('file'));


         return redirect()->route('laboratorium_bidang.get')->with('success', 'Data berhasil diimpor!');
    }

    public function laboratorium_bidang_sub($kode)
    {
        $title = "Master Data Sub Spesialis";
        $laboratorium_bidang_sub = laboratorium_bidang_sub::where('laboratorium_bidang_id', $kode)->get();
        $laboratorium_bidang = laboratorium_bidang::find($kode);

        // spesialis
        return view('module.master-data-medis.laboratorium_bidang_sub', compact('title','laboratorium_bidang_sub','laboratorium_bidang'));
    }

    public function laboratorium_bidang_subadd(Request $request)
    {
        try {
            $request->validate([
                "laboratorium_bidang_sub_id" => 'required',
                "nama_sub_pemeriksaan" => 'required|string',
                "nama" => 'required|string',
            ]);

            $laboratorium_bidang_sub = laboratorium_bidang_sub::create([
                'laboratorium_bidang_id' => $request->laboratorium_bidang_sub_id,
                'nama_laboratorium_bidang' => $request->nama_sub_pemeriksaan,
                'nama_sublaboratorium_bidang' => $request->nama,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bidang Laboratorium berhasil ditambahkan!',
                'data' => $laboratorium_bidang_sub
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bidang Laboratorium Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Bidang Laboratorium!',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function laboratorium_bidang_subedit(Request $request)
    {
         $request->validate([
            "laboratorium_bidang_subid_edit" => 'required',
            "laboratorium_bidang_sub_id_edit" => 'required',
            "nama_pemeriksaan_edit" => 'required|string',
            "nama_edit" => 'required|string',
         ]);

         $laboratorium_bidang_sub = laboratorium_bidang_sub::find($request->laboratorium_bidang_subid_edit);

         if (!$laboratorium_bidang_sub) {
             return response()->json([
                 'success' => false,
                 'message' => 'Bidang Laboratorium tidak ditemukan!'
             ], 404);
         }

         $laboratorium_bidang_sub->laboratorium_bidang_id = $request->laboratorium_bidang_sub_id_edit;
         $laboratorium_bidang_sub->nama_laboratorium_bidang = $request->nama_pemeriksaan_edit;
         $laboratorium_bidang_sub->nama_sublaboratorium_bidang = $request->nama_edit;
         $laboratorium_bidang_sub->save();

         return response()->json([
             'success' => true,
             'message' => 'Bidang Laboratorium berhasil diperbarui!'
         ]);
    }

    public function laboratorium_bidang_subdelete(Request $request)
    {

        $request->validate([
            'laboratorium_bidang_subid_delete' => 'required'
        ]);

        $laboratorium_bidang_sub = laboratorium_bidang_sub::find($request->laboratorium_bidang_subid_delete);
        if (!$laboratorium_bidang_sub) {
            return response()->json([
                'success' => false,
                'message' => 'Bidang Laboratorium tidak ditemukan!'
            ], 404);
        }
        $laboratorium_bidang_sub->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bidang Laboratorium berhasil dihapus!'
        ]);
    }

    // pemeriksaan laboratorium_bidang
    public function radiologi_pemeriksaan()
    {
        $title = "Master Pemeriksaan Radiologi ";
        $radiologi_pemeriksaan = radiologi_pemeriksaan::all();
        return view('module.master-data-medis.radiologi_pemeriksaan', compact('title','radiologi_pemeriksaan'));
    }

    public function radiologi_pemeriksaanadd(Request $request)
    {
         try {
             $request->validate([
                 "nama" => 'required|string',
             ]);

             $radiologi_pemeriksaan = radiologi_pemeriksaan::create([
                 'nama' => $request->nama,
             ]);

             return response()->json([
                 'success' => true,
                 'message' => 'radiologi pemeriksaan berhasil ditambahkan!',
                 'data' => $radiologi_pemeriksaan
             ], 201);
         } catch (ValidationException $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'radiologi pemeriksaan Sudah ada!',
                 'errors' => $e->errors()
             ], 422);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan saat menyimpan radiologi pemeriksaan!',
                 'error' => $e->getMessage()
             ], 500);
         }
    }

    public function radiologi_pemeriksaanedit(Request $request)
    {
         $request->validate([
            "nama_edit" => 'required|string',
         ]);

         $radiologi_pemeriksaan = radiologi_pemeriksaan::find($request->bidang_labotorium_edit);

         if (!$radiologi_pemeriksaan) {
             return response()->json([
                 'success' => false,
                 'message' => 'radiologi pemeriksaan tidak ditemukan!'
             ], 404);
         }

         $radiologi_pemeriksaan->nama = $request->nama_edit;
         $radiologi_pemeriksaan->save();

         return response()->json([
             'success' => true,
             'message' => 'radiologi pemeriksaan berhasil diperbarui!'
         ]);
    }

    public function radiologi_pemeriksaandelete(Request $request)
    {

        $request->validate([
            'pemeriksaan_httid_delete' => 'required'
        ]);

        $radiologi_pemeriksaan = radiologi_pemeriksaan::find($request->pemeriksaan_httid_delete);
        if (!$radiologi_pemeriksaan) {
            return response()->json([
                'success' => false,
                'message' => 'radiologi pemeriksaan tidak ditemukan!'
            ], 404);
        }
        $radiologi_pemeriksaan->delete();

        return response()->json([
            'success' => true,
            'message' => 'radiologi pemeriksaan berhasil dihapus!'
        ]);
    }

    public function radiologi_pemeriksaanexport()
    {
        return Excel::download(new Radiologi_pemeriksaanExport, 'Radiologi Pemeriksaan.xlsx');
    }

    public function radiologi_pemeriksaaneimport(Request $request)
    {
        $request->validate([
             'file' => 'required|mimes:xlsx,xls'
         ]);

         Excel::import(new Radiologi_pemeriksaanImport, $request->file('file'));


         return redirect()->route('radiologi_pemeriksaan.get')->with('success', 'Data berhasil diimpor!');
    }

}
