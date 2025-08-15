<?php

namespace App\Http\Controllers\Module\Pelayanan;

use App\Http\Controllers\Controller;
use App\Models\pelayanan;
use App\Models\pelayanan_soap_perawat;
use App\Models\gcs_eye;
use App\Models\gcs_verbal;
use App\Models\gcs_motorik;
use App\Models\gcs_kesadaran;
use App\Models\htt_pemeriksaan;
use App\Http\Controllers\Brijing_Intergrasi\Pcare_Controller;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;


class Pelayanan_Perawat_Controller extends Controller
{
    protected $PcareController;

    public function __construct(Pcare_Controller $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    public function pelayana()
    {
        $title = "Pelayanan";
        $pelayanan = Pelayanan::with([
            'poli',
            'dokter.namauser',
            'pasien',
            'pendaftaran.status',
            'pelayanan_soap'
        ])
            ->whereDate('created_at', Carbon::today()) // Filter hanya hari ini
            ->get();


        foreach ($pelayanan as $item) {
            $status = $item->pendaftaran->status->status_panggil ?? 0;

            $soap = pelayanan_soap_perawat::where('no_rawat', $item->nomor_register)->first();

            if ($status == 0) {
                $item->tindakan_button = 'panggil';
            } elseif ($status == 1 && !$soap) {
                $item->tindakan_button = 'soap';
            } elseif ($status == 1 && $soap) {
                $item->tindakan_button = 'edit';
            } elseif ($status == 2) {
                $item->tindakan_button = 'Complete';
            } else {
                $item->tindakan_button = 'Complete';
            }
        }
        return view('module.pelayanan.pelayanan-perawat.index', compact('title', 'pelayanan'));
    }

    public function sopelayanan($norawat)
    {
        $nomor_rawat = base64_decode($norawat);
        $title = "Pelayanan";
        $pelayanan = pelayanan::with('poli', 'dokter.namauser', 'pasien.kelamin', 'pendaftaran.penjamin')->where('nomor_register', $nomor_rawat)->first();

        $tgl_lahir = Carbon::createFromFormat('Y-m-d', $pelayanan->pasien->tanggal_lahir);
        $diff = $tgl_lahir->diff(Carbon::now());

        $umurTahun = $diff->y;
        $umurBulan = $diff->m;
        $umurHari = $diff->d;

        $umur = '';
        if ($umurTahun > 0) {
            $umur .= $umurTahun . ' Tahun ';
        }
        if ($umurBulan > 0 || $umurTahun > 0) {
            $umur .= $umurBulan . ' Bulan ';
        }
        $umur .= $umurHari . ' Hari';

        $gsc_eye = gcs_eye::all();
        $gcs_verbal = gcs_verbal::all();
        $gcs_motorik = gcs_motorik::all();
        $gcs_kesadaran = gcs_kesadaran::all();

        $htt_pemeriksaan = htt_pemeriksaan::all();
        return view('module.pelayanan.pelayanan-perawat.so-perawat', compact('title', 'pelayanan', 'umur', 'gsc_eye', 'gcs_verbal', 'gcs_motorik', 'gcs_kesadaran', 'htt_pemeriksaan'));
    }

    public function sopelayananedit($norawat)
    {
        $nomor_rawat = base64_decode($norawat);
        $title = "Pelayanan";
        $pelayanan = pelayanan::with('poli', 'dokter.namauser', 'pasien.kelamin', 'pendaftaran.penjamin')->where('nomor_register', $nomor_rawat)->first();
        $pelayanan_soap = pelayanan_soap_perawat::where('no_rawat', $nomor_rawat)->first();

        $tgl_lahir = Carbon::createFromFormat('Y-m-d', $pelayanan->pasien->tanggal_lahir);
        $diff = $tgl_lahir->diff(Carbon::now());

        $umurTahun = $diff->y;
        $umurBulan = $diff->m;
        $umurHari = $diff->d;

        $umur = '';
        if ($umurTahun > 0) {
            $umur .= $umurTahun . ' Tahun ';
        }
        if ($umurBulan > 0 || $umurTahun > 0) {
            $umur .= $umurBulan . ' Bulan ';
        }
        $umur .= $umurHari . ' Hari';

        $gsc_eye = gcs_eye::all();
        $gcs_verbal = gcs_verbal::all();
        $gcs_motorik = gcs_motorik::all();
        $gcs_kesadaran = gcs_kesadaran::all();

        $htt_pemeriksaan = htt_pemeriksaan::all();
        return view('module.pelayanan.pelayanan-perawat.so-perawat_edit', compact('title', 'pelayanan', 'pelayanan_soap', 'umur', 'gsc_eye', 'gcs_verbal', 'gcs_motorik', 'gcs_kesadaran', 'htt_pemeriksaan'));
    }

    public function sopelayanandd(Request $request)
    {
        $request->validate([
            'nomor_rm' => 'required|string|max:20',
            'nama' => 'required|string|max:255',
            'no_rawat' => 'required|string|max:50',
            'sex' => 'required',
            'penjamin' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'umur' => 'required|string|max:50',
            'tableData' => 'required|json', // Pastikan tableData berformat JSON
            'sistol' => 'nullable|string|max:10',
            'distol' => 'nullable|string|max:10',
            'tensi' => 'nullable|string|max:10',
            'suhu' => 'nullable|string|max:10',
            'nadi' => 'nullable|string|max:10',
            'rr' => 'nullable|string|max:10',
            'tinggi' => 'nullable|string|max:10',
            'berat' => 'nullable|string|max:10',
            'spo2' => 'nullable|string|max:10',
            'lingkar_perut' => 'nullable|string|max:10',
            'nilai_bmi' => 'nullable|string|max:10',
            'status_bmi' => 'nullable|string|max:50',
            'jenis_alergi' => 'nullable|string|max:2',
            'alergi' => 'nullable|string|max:2',
            'eye' => 'nullable|integer',
            'verbal' => 'nullable|integer',
            'motorik' => 'nullable|integer',
            'summernote' => 'nullable|string',
        ]);

        try {
            // Simpan data ke database
            $pemeriksaan = pelayanan_soap_perawat::updateOrCreate([
                'nomor_rm' => $request->nomor_rm,
                'nama' => $request->nama,
                'no_rawat' => $request->no_rawat,
                'sex' => $request->sex,
                'penjamin' => $request->penjamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'umur' => $request->umur,
                'tableData' => $request->tableData,
                'sistol' => $request->sistol,
                'distol' => $request->distol,
                'tensi' => $request->tensi,
                'suhu' => $request->suhu,
                'nadi' => $request->nadi,
                'rr' => $request->rr,
                'tinggi' => $request->tinggi,
                'berat' => $request->berat,
                'spo2' => $request->spo2,
                'lingkar_perut' => $request->lingkar_perut,
                'nilai_bmi' => $request->nilai_bmi,
                'status_bmi' => $request->status_bmi,
                'jenis_alergi' => $request->jenis_alergi,
                'alergi' => $request->alergi,
                'eye' => $request->eye,
                'verbal' => $request->verbal,
                'motorik' => $request->motorik,
                'summernote' => $request->summernote,
                'user_input_id' => Auth::user()->id,
                'user_input_name' => Auth::user()->name,
            ]);


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'pelayanan soap perawat berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'pelayanan soap perawat Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pelayanan soap perawat!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sopelayananupdate(Request $request)
    {
        try {
            // Simpan data ke database
            $pemeriksaan = pelayanan_soap_perawat::where('no_rawat', $request->no_rawat)->update([
                'nomor_rm' => $request->nomor_rm,
                'nama' => $request->nama,
                'no_rawat' => $request->no_rawat,
                'sex' => $request->sex,
                'penjamin' => $request->penjamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'umur' => $request->umur,
                'tableData' => json_encode($request->tableData),
                'sistol' => $request->sistol,
                'distol' => $request->distol,
                'tensi' => $request->tensi,
                'suhu' => $request->suhu,
                'nadi' => $request->nadi,
                'rr' => $request->rr,
                'tinggi' => $request->tinggi,
                'berat' => $request->berat,
                'spo2' => $request->spo2,
                'lingkar_perut' => $request->lingkar_perut,
                'nilai_bmi' => $request->nilai_bmi,
                'status_bmi' => $request->status_bmi,
                'jenis_alergi' => $request->jenis_alergi,
                'alergi' => $request->alergi,
                'eye' => $request->eye,
                'verbal' => $request->verbal,
                'motorik' => $request->motorik,
                'summernote' => $request->summernote,
                'user_input_id' => Auth::user()->id,
                'user_input_name' => Auth::user()->name,
            ]);


            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'pelayanan soap perawat berhasil ditambahkan!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'pelayanan soap perawat Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pelayanan soap perawat!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
