<?php

namespace App\Http\Controllers\Module\Pelayanan;

use App\Http\Controllers\Controller;
use App\Models\alergi;
use App\Models\pelayanan;
use App\Models\pelayanan_soap_perawat;
use App\Models\gcs_eye;
use App\Models\gcs_verbal;
use App\Models\gcs_motorik;
use App\Models\gcs_kesadaran;
use App\Models\htt_pemeriksaan;
use App\Models\htt_sub_pemeriksaan;
use App\Models\pelayanan_soap_dokter;
use App\Models\pelayanan_soap_dokter_icd;
use App\Models\pelayanan_soap_dokter_obat;
use App\Models\pelayanan_soap_dokter_diet;
use App\Models\pelayanan_soap_dokter_tindakan;
use App\Models\laboratorium_bidang_sub;
use App\Models\kode_surat;
use App\Models\odontogram;
use App\Models\odontogram_details;
use App\Models\pelayanan_rujukan;
use App\Models\subspesialis;
use App\Models\WebSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class Pelayanan_Api_Controller extends Controller
{
    public function odontogramadd(Request $request)
    {
        $data = $request->all(); // Data berupa array JSON dari JS

        foreach ($data as $item) {
            odontogram::updateOrCreate(
                [
                    'nomor_rm'    => $item['nomor_rm'],
                    'nama'  => $item['nama'],
                    'no_rawat'  => $item['no_rawat'],
                    'sex'  => $item['sex'],
                    'penjamin'  => $item['penjamin'],
                    'tanggal_lahir'  => $item['tanggal_lahir'],
                    'tooth_number'  => $item['tooth_number']
                ],
                [
                    'condition' => $item['condition'],
                    'note'      => $item['note']
                ]
            );
        }

        return response()->json(['message' => 'Data kondisi gigi berhasil disimpan atau diperbarui.']);
    }
     public function odontogramload(Request $request)
    {
        $request->validate([
            'nomor_rm'    => 'required|string',
            'no_rawat'  => 'required|string',
        ]);

        $conditions = odontogram::where('nomor_rm', $request->nomor_rm)
            ->where('no_rawat', $request->no_rawat)
            ->get();

        return response()->json($conditions);
    }

    public function odontogramdetailsadd(Request $request)
    {
        $validated = $request->validate([
            'nomor_rm' => 'required|string',
            'no_rawat' => 'required|string',
            'nama' => 'required|string',
            'sex' => 'nullable|string',
            'penjamin' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'Decayed' => 'nullable|string',
            'Missing' => 'nullable|string',
            'Filled' => 'nullable|string',
            'Oclusi' => 'nullable|string',
            'Palatinus' => 'nullable|string',
            'Mandibularis' => 'nullable|string',
            'Platum' => 'nullable|string',
            'Diastema' => 'nullable|string',
            'Anomali' => 'nullable|string',
        ]);

        // Simpan atau update data
        odontogram_details::updateOrCreate(
            [
                'nomor_rm' => $validated['nomor_rm'],
                'no_rawat' => $validated['no_rawat']
            ],
            $validated
        );

        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    public function odontogramdetailsload(Request $request)
    {
        $request->validate([
            'nomor_rm' => 'required|string',
            'no_rawat' => 'required|string',
        ]);

        $data = odontogram_details::where('nomor_rm', $request->nomor_rm)
                        ->where('no_rawat', $request->no_rawat)
                        ->first();

        return response()->json($data);
    }
    public function cetakSuratRujukan($no_rawat)
    {
        $pelayanan = pelayanan::with(['poli', 'dokter.namauser', 'pasien.kelamin', 'pendaftaran.penjamin'])
            ->where('nomor_register', $no_rawat)
            ->first();
        $websetting = WebSetting::find(1);
        $rujukan = pelayanan_rujukan::where('no_rawat', $no_rawat)->first(); // hanya satu baris data
        $data_rujukan = json_decode($rujukan->rujukan_lanjut, true); // decode kolom rujukan_lanjut yang isinya JSON

        $kdSubSpesialis = data_get($data_rujukan, 'subSpesialis.kdSubSpesialis1');

        $kepada = subspesialis::where('kode', $kdSubSpesialis)->first();

        if (!$pelayanan) {
            abort(404, 'Data tidak ditemukan');
        }
        $diagnosa = pelayanan_soap_dokter_icd::where('no_rawat', $no_rawat)->first();
        $data = [
            'nomor_registrasi' => $pelayanan->nomor_register ?? '-',
            'fktp'             => $websetting->nama ?? '-',
            'nama_pasien'      => $pelayanan->pasien->nama ?? '-',
            'nomor_rm'         => $pelayanan->nomor_rm ?? '-',
            'tanggal_lahir'    => $pelayanan->pasien->tanggal_lahir ?? '-',
            'jenis_kelamin'    => $pelayanan->pasien->kelamin->nama ?? '-',
            'penjamin'         => $pelayanan->pendaftaran->penjamin->nama ?? '-',
            'dokter_pengirim'  => $pelayanan->dokter->namauser->name ?? '-',
            'diagnosa'         => $diagnosa ?? '-',
            'tanggal_rujukan'  => $data_rujukan['tglEstRujuk'],
            'keterangan'       => 'Rujukan untuk pemeriksaan lebih lanjut',
            'no_rujukan'       => $pelayanan->kunjungan ?? '-',
            'no_bpjs'          => $pelayanan->pasien->no_bpjs ?? '-',
            'subspesialis'     => $kepada->nama,
            'lokasi'           => $data_rujukan['kdppk'] ?? null,
        ];
        $pdf = Pdf::loadView('pdf.rujukan', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('rujukan-'.$pelayanan->nomor_rm.'.pdf');
    }

    public function getSubSpesialis($kode)
    {
        $subSpesialis = subspesialis::where('kode_spesialis', $kode)->get();
        return response()->json($subSpesialis);
    }


    public function sopelayananpanggil($norawat)
    {
        $nomor_rawat = base64_decode($norawat);

        $pelayanan = Pelayanan::with('pendaftaran.status')->where('nomor_register', $nomor_rawat)->first();

        if ($pelayanan && $pelayanan->pendaftaran && $pelayanan->pendaftaran->status) {
            $pelayanan->pendaftaran->status->status_panggil = 1;
            $pelayanan->pendaftaran->status->save();

            return response()->json([
                'success' => true,
                'message' => 'Status panggil berhasil diperbarui.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data status tidak ditemukan.'
            ], 404);
        }


        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan atau belum memiliki status.'
        ], 404);
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
        return view('module.pelayanan.so-perawat_edit', compact('title', 'pelayanan', 'pelayanan_soap', 'umur', 'gsc_eye', 'gcs_verbal', 'gcs_motorik', 'gcs_kesadaran', 'htt_pemeriksaan'));
    }

    public function getSubPemeriksaan($id)
    {
        $sub = htt_sub_pemeriksaan::where('htt_pemeriksaan_id', $id)->get();
        return response()->json($sub);
    }

    public function getByJenis($kode)
    {
        $data = alergi::where('kode_jenis_alergi', $kode)->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function soappelayanandata($nomor_rawat)
    {
        $data = pelayanan_soap_perawat::where('no_rawat', $nomor_rawat)->first();
        return response()->json($data);
    }

    public function soappelayanandataedit($nomor_rawat)
    {
        $data = pelayanan_soap_dokter::where('no_rawat', $nomor_rawat)->first();
        return response()->json($data);
    }

    public function soappelayanandataicd($nomor_rawat)
    {
        $data = pelayanan_soap_dokter_icd::where('no_rawat', $nomor_rawat)->get();
        return response()->json($data);
    }
    public function soappelayanandatadiet($nomor_rawat)
    {
        $data = pelayanan_soap_dokter_diet::where('no_rawat', $nomor_rawat)->get();
        return response()->json($data);
    }
    public function soappelayanandataobat($nomor_rawat)
    {
        $data = pelayanan_soap_dokter_obat::where('no_rawat', $nomor_rawat)->get();
        return response()->json($data);
    }
    public function soappelayanandatatindakan($nomor_rawat)
    {
        $data = pelayanan_soap_dokter_tindakan::where('no_rawat', $nomor_rawat)->get();
        return response()->json($data);
    }

    public function print(Request $request)
    {
        $resepList = json_decode($request->input('resep_data'), true);
        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = Pdf::loadView('pdf.resep', [
            'resepList' => $resepList,
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik,
        ])->setPaper('a6', 'portrait');

        return $pdf->download('resep-obat.pdf'); // akan dibuka lewat blob di JS
    }

    public function permintaanSakitPrint(Request $request)
    {
        $diagnosis_utama = $request->diagnosis_utama;
        $diagnosis_penyerta_1 = $request->diagnosis_penyerta_1;
        $diagnosis_penyerta_2 = $request->diagnosis_penyerta_2;
        $diagnosis_penyerta_3 = $request->diagnosis_penyerta_3;
        $komplikasi_1 = $request->komplikasi_1;
        $komplikasi_2 = $request->komplikasi_2;
        $komplikasi_3 = $request->komplikasi_3;
        $lama_istirahat = $request->lama_istirahat;
        $terhitung_mulai = $request->terhitung_mulai;
        $nama_pasien = $request->nama_pasien;
        $dokter_pengirim = $request->dokter_pengirim;
        $jenis_kelamin = $request->jenis_kelamin;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $umur = $request->umur;
        $now = Carbon::now();

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = PDF::loadView('pdf.permintaan_sakit', [
            'diagnosis_utama' => $diagnosis_utama,
            'diagnosis_penyerta_1' => $diagnosis_penyerta_1,
            'diagnosis_penyerta_2' => $diagnosis_penyerta_2,
            'diagnosis_penyerta_3' => $diagnosis_penyerta_3,
            'komplikasi_1' => $komplikasi_1,
            'komplikasi_2' => $komplikasi_2,
            'komplikasi_3' => $komplikasi_3,
            'lama_istirahat' => $lama_istirahat,
            'terhitung_mulai' => $terhitung_mulai,
            'nama_pasien' => $nama_pasien,
            'dokter_pengirim' => $dokter_pengirim,
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'umur' => $umur,
            'now' => $now,
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik,
        ])->setPaper('a6', 'portrait');

        $filename = 'permintaan_sakit_' . $nama_pasien . '.pdf';

        return $pdf->stream($filename);
    }

    public function permintaanSehatPrint(Request $request)
    {
        $tgl_periksa = $request->tgl_periksa_sehat;
        $sistole = $request->sistole;
        $diastole = $request->diastole;
        $suhu = $request->suhu;
        $berat = $request->berat;
        $respiratory_rate = $request->respiratory_rate;
        $nadi = $request->nadi;
        $tinggi = $request->tinggi;
        $buta_warna_status = $request->buta_warna_status;
        $nama_pasien = $request->nama_pasien;
        $dokter_pengirim = $request->dokter_pengirim;
        $jenis_kelamin = $request->jenis_kelamin;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $umur = $request->umur;
        $now = Carbon::now();

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = PDF::loadView('pdf.permintaan_sehat', [
            'tgl_periksa' => $tgl_periksa,
            'sistole' => $sistole,
            'diastole' => $diastole,
            'suhu' => $suhu,
            'berat' => $berat,
            'respiratory_rate' => $respiratory_rate,
            'nadi' => $nadi,
            'tinggi' => $tinggi,
            'buta_warna_status' => $buta_warna_status,
            'nama_pasien' => $nama_pasien,
            'dokter_pengirim' => $dokter_pengirim,
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'umur' => $umur,
            'now' => $now,
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik,
        ])->setPaper('a6', 'portrait');

        $filename = 'permintaan_sehat_' . $nama_pasien . '.pdf';

        return $pdf->stream($filename);
    }

    public function permintaanKematianPrint(Request $request)
    {
        $tgl_periksa = $request->tgl_periksa_kematian;
        $dokter_kematian = $request->dokter_kematian;
        $penandatangan = $request->penandatangan;
        $tanggal_meninggal = $request->tanggal_meninggal;
        $jam_meninggal = $request->jam_meninggal;
        $ref_tgl_jam = $request->ref_tgl_jam;
        $penyebab_kematian = $request->penyebab_kematian;
        $penyebab_lainnya = $request->penyebab_lainnya;
        $nama_pasien = $request->nama_pasien;
        $dokter_pengirim = $request->dokter_pengirim;
        $jenis_kelamin = $request->jenis_kelamin;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $umur = $request->umur;
        $now = Carbon::now();

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = PDF::loadView('pdf.permintaan_kematian', [
            'tgl_periksa' => $tgl_periksa,
            'dokter_kematian' => $dokter_kematian,
            'penandatangan' => $penandatangan,
            'tanggal_meninggal' => $tanggal_meninggal,
            'jam_meninggal' => $jam_meninggal,
            'ref_tgl_jam' => $ref_tgl_jam,
            'penyebab_kematian' => $penyebab_kematian,
            'penyebab_lainnya' => $penyebab_lainnya,
            'nama_pasien' => $nama_pasien,
            'dokter_pengirim' => $dokter_pengirim,
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'umur' => $umur,
            'now' => $now,
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik,
        ])->setPaper('a6', 'portrait');

        $filename = 'permintaan_kematian_' . $nama_pasien . '.pdf';

        return $pdf->stream($filename);
    }

    public function getSubBidangLab($id)
    {
        if ($id === 'all') {
            $data = laboratorium_bidang_sub::all();
        } else {
            $data = laboratorium_bidang_sub::where('laboratorium_bidang_id', $id)->get();
        }

        return response()->json($data);
    }

    public function laboratoriumPrint(Request $request)
    {
        $labData = json_decode($request->lab_table_hidden, true);
        $diagnosa = $request->diagnosa_laboratorium;
        $tanggal = $request->tanggal_periksa_laboratorium;
        $catatan = $request->catatan_dokter_laboratorium;
        $nama_pasien = $request->nama_pasien;
        $dokter_pengirim = $request->dokter_pengirim;
        $poli = $request->poli;
        $jenis_kelamin = $request->jenis_kelamin;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $penjamin = $request->penjamin;

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = PDF::loadView('pdf.permintaan_laboratorium', [
            'labData' => $labData,
            'diagnosa' => $diagnosa,
            'tanggal' => $tanggal,
            'catatan' => $catatan,
            'nama_pasien' => $nama_pasien,
            'dokter_pengirim' => $dokter_pengirim,
            'poli' => $poli,
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'penjamin' => $penjamin,
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik,
        ])->setPaper('a6', 'portrait');

        $filename = 'permintaan_laboratorium_' . $nama_pasien . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function radiologiPrint(Request $request)
    {
        $radData = json_decode($request->rad_table_hidden, true);
        $diagnosa = $request->diagnosa_radiologi;
        $tanggal = $request->tanggal_periksa_radiologi;
        $catatan = $request->catatan_dokter_radiologi;
        $nama_pasien = $request->nama_pasien;
        $dokter_pengirim = $request->dokter_pengirim;
        $poli = $request->poli;
        $jenis_kelamin = $request->jenis_kelamin;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $penjamin = $request->penjamin;

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        $pdf = PDF::loadView('pdf.permintaan_radiologi', [
            'radData' => $radData,
            'diagnosa' => $diagnosa,
            'tanggal' => $tanggal,
            'catatan' => $catatan,
            'nama_pasien' => $nama_pasien,
            'dokter_pengirim' => $dokter_pengirim,
            'poli' => $poli,
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'penjamin' => $penjamin,
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik,
        ])->setPaper('a6', 'portrait');

        $filename = 'permintaan_radiologi_' . $nama_pasien . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }

    public function skdPrint(Request $request)
    {
        $tgl_pemeriksaan = $request->tgl_pemeriksaan_skd;
        $kode_surat = $request->kode_surat_skd;
        $tgl_awal = $request->tgl_awal_skd;
        $tgl_akhir = $request->tgl_akhir_skd;
        $diagnosa = $request->diagnosa_skd;
        $nama_pasien = $request->nama_pasien;
        $dokter_pengirim = $request->dokter_pengirim;
        $jenis_kelamin = $request->jenis_kelamin;
        $tanggal_lahir = $request->tanggal_lahir;
        $alamat = $request->alamat;
        $umur = $request->umur;
        $no_bpjs = $request->no_bpjs;
        $now = Carbon::now();

        $tgl_awal_proses = Carbon::parse($request->tgl_awal_skd);
        $tgl_akhir_proses = Carbon::parse($request->tgl_akhir_skd);

        $jumlah_hari = $tgl_awal_proses->diffInDays($tgl_akhir_proses) + 1;

        $namaKlinik = WebSetting::value('nama');
        $alamatKlinik = WebSetting::value('alamat');

        kode_surat::create([
            'kode_surat_skd' => $kode_surat,
            'user_input_id' => Auth::user()->id,
            'user_input_name' => Auth::user()->name,
        ]);

        $pdf = PDF::loadView('pdf.permintaan_skd', [
            'tgl_pemeriksaan' => $tgl_pemeriksaan,
            'kode_surat' => $kode_surat,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'diagnosa' => $diagnosa,
            'nama_pasien' => $nama_pasien,
            'dokter_pengirim' => $dokter_pengirim,
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'umur' => $umur,
            'no_bpjs' => $no_bpjs,
            'now' => $now,
            'jumlah_hari' => $jumlah_hari,
            'namaKlinik' => $namaKlinik,
            'alamatKlinik' => $alamatKlinik,
        ])->setPaper('a6', 'portrait');

        $filename = 'surat_keterangan_dokter_' . $nama_pasien . '.pdf';

        return $pdf->stream($filename); // tampilkan langsung di tab baru
    }
}
