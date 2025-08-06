@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1>Transaksi Apotek / Farmasi</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cariPasienModal">
                        <i class="fas fa-search"></i> Cari Pasien
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Step 1: Form Pasien -->
            <div id="step1" class="card">
                <div class="card-header bg-light">
                    <h3 class="card-title">Data Pasien</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>No. Rawat</label>
                        </div>
                        <div class="col-md-1 text-center">:</div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_rawat" name="no_rawat">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>No. RM</label>
                        </div>
                        <div class="col-md-1 text-center">:</div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_rm" name="no_rm">
                        </div>
                        <div class="col-md-5">

                            <button class="btn btn-info mr-2" onclick="belibebas()">Auto</button>

                            <!-- Icon i dengan tooltip -->
                            <span
                                class="ml-1"
                                data-toggle="tooltip"
                                title="Opsi Auto untuk penjualan obat langsung"
                                style="cursor: pointer; color: #17a2b8;"
                            >
                                <i class="fas fa-info-circle"></i>
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>Nama</label>
                        </div>
                        <div class="col-md-1 text-center">:</div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="nama" name="nama">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>Alamat</label>
                        </div>
                        <div class="col-md-1 text-center">:</div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="alamat" name="alamat">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>Resep</label>
                        </div>
                        <div class="col-md-1 text-center">:</div>
                        <div class="col-md-2">
                            <select class="form-control" id="resep" name="resep">
                                <option value="" disabled selected>-- Pilih --</option>
                                <option value="RESEP">RESEP</option>
                                <option value="BELI BEBAS">BELI BEBAS</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" id="faktur_apotek" name="faktur_apotek" readonly>
                        </div>
                        <div class="col-md-1">
                            <label>Dokter</label>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" id="dokter" name="dokter">
                                <option value="" disabled selected>Pilih Dokter</option>
                                @foreach ($dokter as $dokterData)
                                    <option value="{{ $dokterData->namauser->name }}">{{ $dokterData->namauser->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>Rawat</label>
                        </div>
                        <div class="col-md-1 text-center">:</div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100">RAWAT JALAN</button>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control select2bs4" id="poli" name="poli" style="width: 100%;">
                                <option value="" disabled selected>Pilih Poli</option>
                                <option value="APS">APS</option>
                                @foreach ($poli as $poliData)
                                    <option value="{{ $poliData->nama }}">{{ $poliData->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label>Penjamin</label>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" id="penjamin" name="penjamin">
                                <option value="" disabled selected>-- Pilih --</option>
                                @foreach ($penjamin as $penjaminData)
                                    <option value="{{ $penjaminData->nama }}">{{ $penjaminData->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-primary" onclick="showStep2()">
                        <i class="fas fa-arrow-right"></i> Lanjut
                    </button>
                </div>
            </div>

            <!-- Step 2: Transaksi Obat -->
            <div id="step2" style="display: none;">
                <div class="row">
                    <!-- Panel Kiri - Form Resep -->
                    <div class="col-md-8 pr-2">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h3 class="card-title">Form Resep</h3>
                            </div>
                            <div class="card-body p-3">
                                <input type="hidden" id="tabel_apotek_harga_hidden" name="tabel_apotek_harga_hidden">
                                <div style="border: 2px solid black; padding: 10px; width: 100%; max-width: auto; min-height: 300px;">
                                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                        <table class="table" id="tabel_apotek_harga" style="border: none;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Item</th>
                                                    <th>Kode Item</th>
                                                    <th>Harga</th>
                                                    <th>Kuantitas</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- DATA TERISI OTOMATIS NANTI --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="p-3 bg-light border mt-3">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="row mb-3">
                                                <div class="col-md-1">
                                                    <button class="btn btn-info btn-sm rounded-circle">R:/</button>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Barang :</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <select class="form-control" id="barang_tambahan" name="barang_tambahan">
                                                        <option value="" disabled selected>Pilih Obat / Alkes</option>
                                                        @foreach ($stok as $stokData)
                                                            <option value="{{ $stokData->nama_obat_alkes }}" data-kode="{{ $stokData->kode_obat_alkes}}">
                                                                {{ $stokData->nama_obat_alkes }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-2">
                                                    <label>Qty :</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="number" class="form-control" id="qty_tambahan" name="qty_tambahan">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-2">
                                                    <label>Harga :</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" id="harga_tambahan" name="harga_tambahan">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-9">
                                                    <button class="btn btn-info mr-2" onclick="tambahanData()">Tambah</button>
                                                    <button class="btn btn-info" onclick="hapusBarisTerpilih()">Hapus</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="form-group row align-items-center">
                                                <div class="col-md-3">
                                                    <label>Barang</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <label>:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="nilai_embis_input" name="nilai_embis_input" value="0" placeholder="Masukan nilai embis poin">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">(Poin)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row align-items-center">
                                                <div class="col-md-3">
                                                    <label>Sub Total</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <label>:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <label id="sub_total">Rp 0</label>

                                                    <input type="hidden" id="sub_total_hidden" name="sub_total_hidden">
                                                </div>
                                            </div>

                                            <div class="form-group row align-items-center">
                                                <div class="col-md-3">
                                                    <label class="text-danger">Embis</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="text-danger">:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="text-danger" id="embalase_total">Rp 0</label>

                                                    <input type="hidden" id="embalase_total_hidden" name="embalase_total_hidden">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 95%; border-top: 2px solid black; position: relative; margin-top: 20px;">
                                                    <span style="position: absolute; right: -10px; top: -15px; font-weight: bold;">+</span>
                                                </div>
                                            </div>


                                            <div class="form-group row align-items-center">
                                                <div class="col-md-3">
                                                    <label>Total</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <label>:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <label id="total">Rp 0</label>

                                                    <input type="hidden" id="total_hidden" name="total_hidden">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel Kanan - Informasi Resep -->
                    <div class="col-md-4 pl-2">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h3 class="card-title">Informasi Resep</h3>
                            </div>
                            <div class="card-body p-3">
                                <input type="hidden" id="hidden_resep_input" name="hidden_resep_input">
                                {{-- <div class="border border-dark" style="height: 242px; background-color: #fff;"></div> --}}
                                <div style="border: 2px solid black; padding: 10px; width: 100%; max-width: auto; min-height: 300px;">
                                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                        <table class="table" id="tabel_resep_sementara" style="border: none;">
                                            <tbody>
                                                {{-- DATA TERISI OTOMATIS NANTI --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="p-3 bg-light border mt-3" style="min-height: 300px;">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label>No R:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="no_r" name="no_r" placeholder="Masukkan ID resep">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label>Jumlah:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="jumlah_r" name="jumlah_r" placeholder="Masukan kuantitas r">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label>Note:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="note_apotek" name="note_apotek">
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <button id="loadPerR" class="btn btn-info btn-block btn-sm">
                                                <i class="fas fa-file-download"></i> Load Per R/
                                            </button>
                                        </div>
                                        <div class="col-md-4">
                                            <button id="loadFull" class="btn btn-info btn-block btn-sm">
                                                <i class="fas fa-file-download"></i> Load R/ Full
                                            </button>
                                        </div>
                                        <div class="col-md-4">
                                            <button id="printResepDokter" class="btn btn-danger btn-block btn-sm">
                                                <i class="fas fa-download"></i> Download
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <button class="btn btn-secondary btn-block btn-sm" data-toggle="modal" data-target="#modalResep">
                                                <i class="fas fa-download"></i> Revisi resep obat
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-secondary mr-2" onclick="showStep1()">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-success" onclick="postData()">
                            <i class="fas fa-check"></i> Selesai
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Cari Pasien -->
<div class="modal fade" id="cariPasienModal" tabindex="-1" role="dialog" aria-labelledby="cariPasienModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cariPasienModalLabel">Cari Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Isi modal akan ditambahkan nanti -->
                {{-- <p class="text-center">Fitur pencarian pasien akan ditambahkan nanti.</p> --}}
                <table id="agamatabel" class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">No RM</th>
                            <th class="text-center">No Rawat</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">J. Kelamin</th>
                            <th class="text-center">Penjamin</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_soap as $index => $data_soap)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $data_soap->nomor_rm }}</td>
                                <td class="text-center">{{ $data_soap->no_rawat }}</td>
                                <td class="text-center">{{ $data_soap->nama }}</td>
                                <td class="text-center">{{ $data_soap->sex }}</td>
                                <td class="text-center">{{ $data_soap->penjamin }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success" onclick="cariResepObat(this)"
                                        data-no_rawat="{{ $data_soap->no_rawat }}"
                                        data-no_rm="{{ $data_soap->nomor_rm }}"
                                        data-nama="{{ $data_soap->nama }}"
                                        data-alamat="{{ $data_soap->pasien->alamat }}"
                                        data-dokter="{{ $data_soap->pendaftaran->dokter->namauser->name }}"
                                        data-poli="{{ $data_soap->pendaftaran->poli->nama }}"
                                        data-penjamin="{{ $data_soap->penjamin }}"
                                        data-resep_obat='@json($data_soap->resep->Resep_obat)'
                                    >Pilih</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Pilih Pasien</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalResep" tabindex="-1" role="dialog" aria-labelledby="modalResepLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalResepLabel">Input Resep</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> <!-- Ganti ke btn-close jika pakai Bootstrap 5 -->
                </button>
            </div>
            <div class="modal-body">
                <!-- Input R:/ -->
                <div class="form-row align-items-center mb-3">
                    <div class="col-12">
                        <label for="r-text">R:/</label>
                        <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" id="btn-r-action" class="btn btn-info">R:/</button>
                        </div>
                        <input type="text" id="r-text" class="form-control" placeholder="Kosong = R:/, isi = R:/ + teks">
                        </div>
                    </div>
                </div>

                <!-- Input Obat & Dosis -->
                <div class="form-row align-items-end mb-3">
                    <div class="col-md-4">
                        <label for="nama-obat">Nama Obat</label>
                        <select class="form-control" id="nama-obat">
                            <option value="" disabled selected>-- Pilih Obat --</option>
                            @foreach ($obat as $obatdata)
                                <option value="{{ $obatdata->nama_barang }}" data-satuan="{{ $obatdata->satuan_kecil }}">{{ $obatdata->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Dosis</label>
                        <div class="form-row">
                            <div class="col">
                                <input type="text" id="dosis1" class="form-control" placeholder="Contoh: 500">
                            </div>
                            <div class="col">
                                <input type="text" id="dosis2" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="instruksi">Instruksi</label>
                        <select class="form-control" id="instruksi">
                            <option value="" selected disabled>-- Pilih Instruksi --</option>
                            <option value="CITO">CITO</option>
                            <option value="ITER">ITER</option>
                            <option value="Equal qs">Equal qs</option>
                            <option value="m.f pulv da in caps">m.f pulv da in caps</option>
                            <option value="s.u.e">s.u.e</option>
                            <option value="m.f pulv dtd no X">m.f pulv dtd no X</option>
                            <option value="m.f pulv dtd no XV">m.f pulv dtd no XV</option>
                            <option value="s.q.d.d.c">s.q.d.d.c</option>
                            <option value="haust">haust</option>
                            <option value="s.i.m.m">s.i.m.m</option>
                        </select>
                    </div>
                </div>

                <!-- Input Signa -->
                <div class="form-row align-items-end mb-3">
                    <div class="col-md-4">
                        <label>Signa</label>
                        <div class="form-row align-items-center">
                            <div class="col">
                                <input type="text" id="signa-jumlah1" class="form-control" placeholder="Contoh: 1">
                            </div>
                            <div class="col-auto">
                                <strong>x</strong>
                            </div>
                            <div class="col">
                                <input type="text" id="signa-jumlah2" class="form-control" placeholder="Contoh: 3">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div style="visibility: hidden;"><label for="signa-satuan1">Signa Satuan 1</label></div>
                        <select class="form-control" id="dosis3">
                            <option value="" disabled selected>-- Pilih Satuan --</option>
                            @foreach ($satuan as $satuandata)
                                <option value="{{ $satuandata->nama }}">{{ $satuandata->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div style="visibility: hidden;"><label for="signa-satuan2">Signa Satuan 2</label></div>
                        <select class="form-control" id="signa-satuan2">
                            <option value="" disabled selected>-- Pilih Satuan --</option>
                            <option value="SEBELUM MAKAN">SEBELUM MAKAN</option>
                            <option value="SESUDAH MAKAN">SESUDAH MAKAN</option>
                            <option value="SEBELUM/SESUDAH MAKAN">SEBELUM/SESUDAH MAKAN</option>
                            <option value="JIKA MUAL-MUAL">JIKA MUAL-MUAL</option>
                            <option value="JIKA BUANG AIR BESAR">JIKA BUANG AIR BESAR</option>
                            <option value="JIKA MERASA NYERI">JIKA MERASA NYERI</option>
                            <option value="DIMINUM SETELAH SUAPAN PERTAMA">DIMINUM SETELAH SUAPAN PERTAMA</option>
                        </select>
                    </div>
                </div>

                <!-- Tombol Tambah -->
                <div class="form-group mb-3">
                    <div class="col-md-12 mb-2">
                        <label for="">Note</label>
                        <input type="text" class="form-control" id="note_revisi" name="note_revisi">
                    </div>
                    <div class="col-md-12">
                        <button type="button" id="btn-add-obat" class="btn btn-primary">Tambah Obat ke Resep</button>
                    </div>
                </div>

                <!-- Tampilan Resep -->
                <div class="form-group">
                    <label for="summernote-resep">Resep:</label>
                    <div id="summernote-resep" name="summernote-resep" style="border:1px solid #ccc; min-height:200px; padding:10px; background:#f9f9f9; overflow-y:auto;"></div>
                    <input type="hidden" name="resep_data" id="resep-data">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" id="btn-print-resep-ajax" class="btn btn-primary">🖨️ Print Resep Revisi</button>
            </div>
        </div>
    </div>
</div>


<script>
     $(document).ready(function() {
            $("#agamatabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "info": false,
                "paging": false,
            }).buttons().container().appendTo('#agamatabel_wrapper .col-md-6:eq(0)');
        });
</script>
{{-- SCRIPT GLOBAL --}}
    <style>
    .selected-row {
        background-color: #003366 !important;
        color: white;
    }
    </style>

    <script>
        function showStep1() {
            document.getElementById('step1').style.display = 'block';
            document.getElementById('step2').style.display = 'none';
        }

        function showStep2() {
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
        }

        $(document).ready(function() {
            // Inisialisasi tampilan awal
            showStep1();
        });
    </script>

    <script>
        let semuaResepObj = {};
        const embalasePoin = {{ $embalase ?? 0 }};

        function hapusBarisTerpilih() {
            const tableBody = document.querySelector('#tabel_apotek_harga tbody');
            const selectedRow = tableBody.querySelector('.selected-row');

            if (!selectedRow) {
                Swal.fire('Peringatan', 'Pilih baris yang ingin dihapus.', 'warning');
                return;
            }

            selectedRow.remove();

            // Reorder nomor dan update ulang JSON
            tableBody.querySelectorAll('tr').forEach((tr, index) => {
                tr.cells[0].textContent = index + 1;
            });

            // Update data JSON di hidden input
            const dataJsonArray = [];
            tableBody.querySelectorAll('tr').forEach(tr => {
                const tds = tr.querySelectorAll('td');
                dataJsonArray.push({
                    nama: tds[1].textContent,
                    kode: tds[2].textContent,
                    harga: parseInt(tds[3].textContent.replace(/[Rp.\s]/g, '')),
                    qty: parseInt(tds[4].textContent),
                    total: parseInt(tds[5].textContent.replace(/[Rp.\s]/g, ''))
                });
            });

            document.getElementById('tabel_apotek_harga_hidden').value = JSON.stringify(dataJsonArray);

            hitungTotalHargaKeseluruhan();
            updateTotal();
        }


        //HITUNG TOTAL
        function updateTotal() {
            const subtotal = parseInt($('#sub_total_hidden').val()) || 0;
            const embalase = parseInt($('#embalase_total_hidden').val()) || 0;
            const total = subtotal + embalase;

            const formatted = 'Rp ' + total.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            $('#total').text(formatted);
            $('#total_hidden').val(total);
        }

        //HITUNG EMBIS
        $(document).ready(function () {
            $('#sub_total_hidden, #embalase_total_hidden').on('input change', updateTotal);

            $('#nilai_embis_input').on('input', function () {
                const poin = parseInt($(this).val()) || 0;

                const total = embalasePoin * poin;
                const formatted = 'Rp ' + total.toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });

                $('#embalase_total').text(formatted);
                $('#embalase_total_hidden').val(total);
                $('#embalase_total_hidden').trigger('change');
            });
        });

        //HITUNG SUB TOTAL
        function hitungTotalHargaKeseluruhan() {
            let total = 0;
            const totalTds = document.querySelectorAll('#tabel_apotek_harga tbody tr td:last-child');

            totalTds.forEach(td => {
                const text = td.textContent || td.innerText;
                const angka = parseInt(text.replace(/[^0-9]/g, ''), 10); // Hilangkan Rp dan titik
                if (!isNaN(angka)) {
                    total += angka;
                }
            });

            // Format angka total ke dalam format Rp Indonesia
            const formatted = 'Rp ' + total.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            // Tampilkan ke <label id="sub_total">
            document.getElementById('sub_total').textContent = formatted;

            // Simpan nilai angka bersih ke hidden input
            document.getElementById('sub_total_hidden').value = total;
            $('#sub_total_hidden').trigger('change');
        }

        //SCRIPT BUTTON PER R DAN R FULL
        document.addEventListener('DOMContentLoaded', () => {

            document.getElementById('loadPerR').addEventListener('click', () => {
                const idInput = document.getElementById('no_r').value.trim();
                if (!idInput) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Nomor Resep Kosong',
                        text: 'Masukkan nomor resep terlebih dahulu!',
                    });
                    return;
                }

                // Cek apakah ID ada di semuaResepObj
                const data = semuaResepObj[idInput];
                if (data) {
                    const { jenis, resep } = data;
                    Swal.fire({
                        title: `${jenis} (ID ${idInput})`,
                        icon: 'info',
                        html: `<pre style="text-align:left; white-space:pre-wrap;">${resep}</pre>`,
                        confirmButtonText: 'Tambahkan ke Tabel'
                    }).then(() => {
                        insertResepToTable(resep); // ← ini menambahkan ke tabel
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Resep Tidak Ditemukan',
                        text: `Resep dengan ID ${idInput} tidak ditemukan.`,
                    });
                }
            });

            document.getElementById('loadFull').addEventListener('click', () => {
                const rows = document.querySelectorAll('#tabel_resep_sementara tbody tr');
                if (rows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Resep Kosong',
                        text: 'Pilih pasien terlebih dahulu!',
                    });
                    return;
                }

                semuaResepObj = {}; // reset isi

                rows.forEach(row => {
                    const id = row.getAttribute('data-id');
                    const jenis = row.getAttribute('data-jenis');
                    const resep = row.getAttribute('data-resep');

                    semuaResepObj[id] = {
                    jenis,
                    resep
                    };
                });

                let alertText = 'Semua Resep:\n';
                let allResepText = '';

                for (const id in semuaResepObj) {
                    const { jenis, resep } = semuaResepObj[id];
                    alertText += `${jenis} (ID ${id}): ${resep}\n`;
                    allResepText += resep + ' | ';
                }

                Swal.fire({
                    title: 'Daftar Semua Resep',
                    icon: 'info',
                    html: `<pre style="text-align:left; white-space:pre-wrap;">${alertText.trim()}</pre>`,
                    confirmButtonText: 'Tutup'
                });

                // Masukkan ke tabel
                insertResepToTable(allResepText.trim().replace(/\|\s*$/, '')); // buang | terakhir
            });
        });

        //SCRIPT MEMINDAHKAN DATA DARI TABEL KECIL KE TABEL BESAR
        function insertResepToTable(resepString) {
            const tbody = document.querySelector('#tabel_apotek_harga tbody');
            let jumlah_r = parseInt(document.getElementById('jumlah_r').value) || 1; // default = 1
            let penjamin = document.getElementById('penjamin').value;

            const resepArray = resepString.split('|').map(i => i.trim());

            const promises = resepArray.map((item, index) => {
                return new Promise((resolve) => {
                    const namaMatch = item.match(/^(.+\))/);
                    const nama = namaMatch ? namaMatch[1].trim() : '-';

                    const kuantitasMatch = item.match(/\)\s*(\d+)/);
                    let kuantitas = '-';
                    if (kuantitasMatch) {
                        const nilai = parseInt(kuantitasMatch[1]);
                        kuantitas = nilai * jumlah_r;
                    }

                    $.ajax({
                        url: '/api/apotek/kodeObat',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ nama: nama, penjamin: penjamin }),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            const kode = data.kode || '-';
                            const harga = data.harga || 0; // Ambil harga dari response API

                            resolve({ index, nama, kuantitas, kode, harga });
                        },
                        error: function() {
                            resolve({ index, nama, kuantitas, kode: 'Error', harga: '-' });
                        }
                    });
                });
            });

            Promise.all(promises).then(results => {
                results.sort((a, b) => a.index - b.index);

                const dataJsonArray = [];

                results.forEach(({ index, nama, kuantitas, kode, harga }) => {
                    // 1. Bersihkan harga dari prefix dan titik
                    const hargaBersih = parseInt((harga || '0').replace(/[Rp.\s]/g, ''));

                    // 2. Kalikan dengan kuantitas (pastikan kuantitas sudah number)
                    const jumlah = harga * parseInt(kuantitas);

                    // 3. Format jumlah dengan prefix dan pemisah ribuan
                    const jumlahFormatted = 'Rp ' + jumlah.toLocaleString('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });

                    // 4. Format harga agar tetap terlihat rapi
                    const hargaFormatted = 'Rp ' + harga.toLocaleString('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${tbody.rows.length + 1}</td>
                        <td>${nama}</td>
                        <td class="kode-item">${kode}</td>
                        <td>${hargaFormatted}</td>
                        <td>${kuantitas}</td>
                        <td>${jumlahFormatted}</td>
                    `;
                    tbody.appendChild(row);

                    // Simpan data mentah ke array JSON
                    dataJsonArray.push({
                        nama: nama,
                        kode: kode,
                        harga: harga,
                        qty: kuantitas,
                        total: jumlah
                    });

                    hitungTotalHargaKeseluruhan();
                    updateTotal();
                });

                // Simpan array sebagai JSON string ke input hidden
                document.getElementById('tabel_apotek_harga_hidden').value = JSON.stringify(dataJsonArray);

                tbody.querySelectorAll('tr').forEach(row => {
                    row.addEventListener('click', function () {
                        tbody.querySelectorAll('tr').forEach(r => r.classList.remove('selected-row'));
                        this.classList.add('selected-row');
                    });
                });

                console.log(JSON.stringify(dataJsonArray));
            });


            document.getElementById('no_r').value = "";
            document.getElementById('jumlah_r').value = "";
        }

        //SCRIPT CARI DATA PASIEN DAN RESEP OBATNYA
        function cariResepObat(button) {
            const noRawat = button.dataset.no_rawat;
            const noRm = button.dataset.no_rm;
            const nama = button.dataset.nama;
            const alamat = button.dataset.alamat;
            const dokter = button.dataset.dokter;
            const poli = button.dataset.poli;
            const penjamin = button.dataset.penjamin;
            const resepObatRaw = button.dataset.resep_obat;

            document.getElementById('no_rawat').value = noRawat;
            document.getElementById('no_rm').value = noRm;
            document.getElementById('nama').value = nama;
            document.getElementById('alamat').value = alamat;
            document.getElementById('dokter').value = dokter;
            document.getElementById('poli').value = poli;
            document.getElementById('penjamin').value = penjamin;
            document.getElementById('resep').value = "RESEP";
            $('#poli').val(poli).trigger('change');

            if (document.getElementById('resep').value === 'RESEP') {
                // Contoh AJAX untuk generate kode faktur
                Swal.fire({
                    icon: 'info',
                    title: 'Memuat...',
                    text: 'Mengambil kode faktur',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: '/api/apotek/kodeFaktur',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        Swal.close();
                        $('#faktur_apotek').val(response.kode || '');
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire('Gagal', 'Gagal mengambil kode faktur.', 'error');
                        $('#faktur_apotek').val('');
                    }
                });
            } else {
                // Kalau selain RESEP, kosongkan input faktur
                $('#faktur_apotek').val('');
            }

            // Step 1: decode unicode escape
            const decodedUnicode = JSON.parse(resepObatRaw);

            const cleanJSONString = decodedUnicode.replace(/^"(.*)"$/, '$1');

            // Step 2: parse string jadi array
            const dataArray = JSON.parse(cleanJSONString);

            // Step 3: kelompokkan berdasarkan 'R:/'
            const groups = [];
            let currentGroup = [];

            dataArray.forEach(item => {
                if (item === 'R:/') {
                    if (currentGroup.length > 0) {
                        groups.push(currentGroup);
                    }
                    currentGroup = [];
                } else {
                    currentGroup.push(
                        item.replace(/\\n/g, ' ').replace(/\s+/g, ' ').trim()
                    );
                }
            });

            if (currentGroup.length > 0) {
                groups.push(currentGroup);
            }

            const cleanedStrings = groups.map(group => {
            if (Array.isArray(group)) {
                return group.join(' | ');  // gabungkan obat-obat dalam satu group jadi satu string
            }
            return group;
            });

            // baru render
            renderResepTable(cleanedStrings);

            $('#cariPasienModal').modal('hide');
        }

        //SCRIPT MASUKAN KE TABEL KECIL
        function renderResepTable(dataArr) {
            const tbody = document.querySelector('#tabel_resep_sementara tbody');
            tbody.innerHTML = '';
            semuaResepObj = {}; // reset setiap render ulang

            let idCounter = 1;

            dataArr.forEach(item => {
                const jumlahObat = item.split('|').length;
                const jenis = jumlahObat > 1 ? 'Resep Racik' : 'Resep Obat';

                const row = document.createElement('tr');
                row.setAttribute('data-id', idCounter);
                row.setAttribute('data-resep', item);
                row.setAttribute('data-jenis', jenis);
                row.innerHTML = `
                    <td><strong>${jenis} (${idCounter})</strong></td>
                    <td>${item.split('|').map(o => `• ${o.trim()}`).join('<br>')}</td>
                `;
                tbody.appendChild(row);

                // Simpan ke object global
                semuaResepObj[idCounter] = {
                    jenis,
                    resep: item
                };

                idCounter++;
            });

            let hiddenInput = document.getElementById('hidden_resep_input');
            hiddenInput.value = JSON.stringify(dataArr);
        }
    </script>

    <script>
        // document.addEventListener("DOMContentLoaded", function () {
        //     Inputmask({
        //         alias: "numeric",
        //         groupSeparator: ".",
        //         radixPoint: ",",
        //         autoGroup: true,
        //         digitsOptional: true,
        //         digits: 0,
        //         placeholder: "",
        //         prefix: "Rp ",
        //         rightAlign: false,
        //         removeMaskOnSubmit: true
        //     }).mask("#harga_tambahan");
        // });

    //NARIK HARGA DI BARANG TAMBAHAN
        $(document).ready(function () {
            $('#barang_tambahan').on('change', function () {
                var kode = $(this).find(':selected').data('kode');
                var penjamin = $('#penjamin').val(); // ambil nilai penjamin

                if (!kode || !penjamin) return;

                Swal.fire({
                    icon: 'info',
                    title: 'Memuat...',
                    text: 'Mengambil harga barang',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '/api/apotek/hargaBebas',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        kode: kode,
                        penjamin: penjamin
                    },
                    success: function (response) {
                        $('#harga_tambahan').val(response.harga || 0);
                        Swal.close();
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire('Gagal', 'Gagal mengambil harga.', 'error');
                        $('#harga_tambahan').val(0);
                    }
                });
            });
        });

    //BUTTON BELI BEBAS
        function belibebas() {
            $('#nama').val('Beli Bebas / APS');
            $('#resep').val('BELI BEBAS').trigger('change');
            $('#poli').val('APS').trigger('change');
            $('#penjamin').val('UMUM').trigger('change');

            // Tampilkan loading swal
            Swal.fire({
                icon: 'info',
                title: 'Memuat...',
                text: 'Mengambil data Beli Bebas',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            // Promise untuk generate no_rm
            const getNoRm = $.ajax({
                url: '/api/apotek/BeliBebas',
                method: 'GET'
            });

            // Promise untuk generate kode_faktur
            const getKodeFaktur = $.ajax({
                url: '/api/apotek/KodeFakturBeliBebas',
                method: 'GET'
            });

            // Jalankan kedua AJAX dan tunggu hasilnya
            Promise.all([getNoRm, getKodeFaktur])
                .then(function ([noRmResponse, kodeFakturResponse]) {
                    $('#no_rm').val(noRmResponse.no_rm || '');
                    $('#faktur_apotek').val(kodeFakturResponse.kode_faktur || '');
                    Swal.close(); // Tutup loading
                })
                .catch(function () {
                    Swal.close();
                    Swal.fire('Gagal', 'Gagal mengambil data beli bebas.', 'error');
                    $('#no_rm').val('');
                    $('#faktur_apotek').val('');
                });
        }

    // TAMBAH DATA MANUAL
        function tambahanData() {
            const barangSelect = document.getElementById('barang_tambahan');
            const qtyInput = document.getElementById('qty_tambahan').value;
            const hargaInput = document.getElementById('harga_tambahan').value;

            const nama = barangSelect.options[barangSelect.selectedIndex].text;
            const kode = barangSelect.options[barangSelect.selectedIndex].getAttribute('data-kode');

            // Bersihkan format harga: hapus 'Rp', titik ribuan, dan spasi
            const hargaBersih = parseFloat(hargaInput.replace(/[Rp.\s]/g, '').replace(',', '.')) || 0;

            // Pastikan qty berupa angka
            const qty = parseInt(qtyInput) || 0;

            // Validasi
            if (!kode || qty <= 0 || hargaBersih <= 0) {
                Swal.fire('Peringatan', 'Pastikan semua data terisi dengan benar.', 'warning');
                return;
            }

            const total = hargaInput * qty;

            // Dapatkan elemen tbody dan hitung jumlah baris
            const tableBody = document.querySelector('#tabel_apotek_harga tbody');
            const rowCount = tableBody.rows.length + 1;

            // Format angka ke format lokal (Indonesia)
            const formatRupiah = (angka) => angka.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            // Tambahkan baris ke tabel
            const row = `
                <tr>
                    <td>${rowCount}</td>
                    <td>${nama}</td>
                    <td>${kode}</td>
                    <td>Rp ${formatRupiah(hargaInput)}</td>
                    <td>${qty}</td>
                    <td>Rp ${formatRupiah(total)}</td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);

            // Event klik baris untuk seleksi
            tableBody.querySelectorAll('tr').forEach(row => {
                row.addEventListener('click', function () {
                    tableBody.querySelectorAll('tr').forEach(r => r.classList.remove('selected-row'));
                    this.classList.add('selected-row');
                });
            });

            // Reset input
            $('#barang_tambahan').val('').trigger('change');
            $('#qty_tambahan').val('');
            $('#harga_tambahan').val('');

            hitungTotalHargaKeseluruhan();
            updateTotal();

            const dataJsonArray = [];
            document.querySelectorAll('#tabel_apotek_harga tbody tr').forEach(tr => {
                const tds = tr.querySelectorAll('td');
                const item = {
                    nama: tds[1].textContent,
                    kode: tds[2].textContent,
                    harga: parseInt(tds[3].textContent.replace(/[Rp.\s]/g, '')),
                    qty: parseInt(tds[4].textContent),
                    total: parseInt(tds[5].textContent.replace(/[Rp.\s]/g, ''))
                };
                dataJsonArray.push(item);
            });

            document.getElementById('tabel_apotek_harga_hidden').value = JSON.stringify(dataJsonArray);

            console.log(JSON.stringify(dataJsonArray));
        }
    </script>

{{-- KIRIM DATABASE --}}
    <script>
        function postData() {
            const data = {
                _token: '{{ csrf_token() }}',
                no_rawat: $('#no_rawat').val(),
                no_rm: $('#no_rm').val(),
                nama: $('#nama').val(),
                alamat: $('#alamat').val(),
                resep: $('#resep').val(),
                faktur_apotek: $('#faktur_apotek').val(),
                dokter: $('#dokter').val(),
                poli: $('#poli').val(),
                penjamin: $('#penjamin').val(),
                nilai_embis_input: $('#nilai_embis_input').val(),
                sub_total_hidden: $('#sub_total_hidden').val(),
                embalase_total_hidden: $('#embalase_total_hidden').val(),
                total_hidden: $('#total_hidden').val(),
                note_apotek: $('#note_apotek').val(),
                tabel_apotek_harga_hidden: $('#tabel_apotek_harga_hidden').val()
            };

            $.ajax({
                url: '/apotek/add',
                method: 'POST',
                data: data,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Data berhasil disimpan!',
                        confirmButtonColor: '#3085d6',
                    }).then(() => {
                        location.reload(); // Reload halaman untuk update data
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorText = Object.values(errors).map(err => `- ${err.join(', ')}`).join('<br>');
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal',
                            html: errorText,
                            confirmButtonColor: '#f39c12',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat mengirim data!',
                            confirmButtonColor: '#d33',
                        });
                    }
                }
            });
        }
    </script>

{{-- SCRIPT PDF --}}
    <script>
        $('#printResepDokter').on('click', function () {
            let data = $('#hidden_resep_input').val();
            let note = $('#note_apotek').val();

            Swal.fire({
                title: 'Cetak Data',
                text: "Apakah Anda yakin ingin mencetak resep dokter?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, cetak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form dinamis
                    console.log(data);
                    var form = $('<form>', {
                        method: 'POST',
                        action: "{{ route('apotek.resep_dokter') }}",
                        target: '_blank'
                    });

                    // CSRF token
                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: '{{ csrf_token() }}'
                    }));

                    // Data tambahan
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'note',
                        value: note
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'data',
                        value: data
                    }));

                    // Submit form
                    $('body').append(form);
                    form.submit();
                    form.remove();
                }
            });
        });
    </script>

    {{-- Script resep --}}
    <script>
        $(function () {
            let resepList = [];
            let selectedIndex = -1;

            function renderResep() {
                let html = "";
                resepList.forEach((line, i) => {
                html += `<div class="resep-line d-flex justify-content-between align-items-center"
                        data-index="${i}" style="padding:6px 10px; cursor:pointer; border-bottom:1px solid #ddd; ${i === selectedIndex ? 'background:#d1ecf1;' : ''}">
                    <span class="resep-text">${$('<div>').html(line.replace(/\n/g, "<br>")).html()}</span>`;
                if (i === selectedIndex) {
                    html += `<div class="btn-group btn-group-sm ml-2">
                            <button type="button" class="btn btn-warning btn-up">▲</button>
                            <button type="button" class="btn btn-warning btn-down">▼</button>
                            <button type="button" class="btn btn-success btn-edit">✎</button>
                            <button type="button" class="btn btn-danger btn-delete">✖</button>
                            </div>`;
                }
                html += `</div>`;
                });
                $("#summernote-resep").html(html);
                $("#resep-data").val(JSON.stringify(resepList));
            }

            $("#btn-r-action").click(function () {
                const text = $("#r-text").val().trim();
                resepList.push(text ? `R:/ ${text}` : "R:/");
                $("#r-text").val("");
                renderResep();
            });

            $("#btn-add-obat").click(function () {
                const nama = $("#nama-obat").val();
                const dosis1 = $("#dosis1").val().trim();
                const dosis2 = $("#dosis2").val().trim();
                const signaJumlah1 = $("#signa-jumlah1").val().trim();
                const signaJumlah2 = $("#signa-jumlah2").val().trim();
                const dosis3 = $("#dosis3").val().trim();
                const signaSatuan2 = $("#signa-satuan2").val();
                const instruksi = $("#instruksi").val().trim();

                if (!nama) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih nama obat!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                let line = `${nama}`;
                if (dosis1) line += ` ${dosis1}`;
                if (dosis2) line += ` ${dosis2}`;

                line += "\n";

                if (instruksi) {
                    line += `${instruksi}\n`;
                }

                // Susun signa walau tidak lengkap
                if (signaJumlah1 && signaJumlah2) {
                    line += `${signaJumlah1} x ${signaJumlah2}`;
                    if (dosis3) line += ` ${dosis3}`;
                    if (signaSatuan2) line += ` ${signaSatuan2}`;
                }

                resepList.push(line);
                renderResep();

                // Reset input
                $("#nama-obat").val("");
                $("#dosis1").val("");
                $("#dosis2").val("");
                $("#instruksi").val("");
                $("#signa-jumlah1").val("");
                $("#signa-jumlah2").val("");
                $("#dosis3").val("");
                $("#signa-satuan2").val("");
            });

            // Auto isi dosis2
            $("#nama-obat").on("change", function () {
                const satuan = $(this).find(":selected").data("satuan");
                $("#dosis2").val(satuan ?? "");
            });

            $("#summernote-resep").on("click", ".resep-line", function () {
                const idx = $(this).data("index");
                selectedIndex = selectedIndex === idx ? -1 : idx;
                renderResep();
            });

            $("#summernote-resep").on("click", ".btn-delete", function (e) {
                e.stopPropagation();
                const idx = $(this).closest(".resep-line").data("index");
                resepList.splice(idx, 1);
                selectedIndex = -1;
                renderResep();
            });

            $("#summernote-resep").on("click", ".btn-up", function (e) {
                e.stopPropagation();
                const idx = $(this).closest(".resep-line").data("index");
                if (idx > 0) {
                [resepList[idx - 1], resepList[idx]] = [resepList[idx], resepList[idx - 1]];
                selectedIndex = idx - 1;
                renderResep();
                }
            });

            $("#summernote-resep").on("click", ".btn-down", function (e) {
                e.stopPropagation();
                const idx = $(this).closest(".resep-line").data("index");
                if (idx < resepList.length - 1) {
                [resepList[idx + 1], resepList[idx]] = [resepList[idx], resepList[idx + 1]];
                selectedIndex = idx + 1;
                renderResep();
                }
            });

            $("#summernote-resep").on("click", ".btn-edit", function (e) {
                e.stopPropagation();
                const idx = $(this).closest(".resep-line").data("index");
                const line = resepList[idx];

                // Reset form yang pasti diisi (nama & dosis)
                $("#nama-obat").val("");
                $("#dosis1").val("");
                $("#dosis2").val("");
                $("#instruksi").val("");
                $("#signa-jumlah1").val("");
                $("#signa-jumlah2").val("");
                $("#dosis3").val("");
                $("#signa-satuan2").val("");
                $("#r-text").val("");

                if (line.startsWith("R:/")) {
                    // Jika ini resep bebas
                    const content = line.replace(/^R:\/*\s*/, "");
                    $("#r-text").val(content);
                } else {
                    // Pisah berdasarkan newline, hilangkan baris kosong
                    const parts = line.split("\n").map(p => p.trim()).filter(p => p.length > 0);

                    // === PARSING BARIS 1: nama obat + dosis1 + dosis2 ===
                    if (parts.length > 0) {
                        const tokens = parts[0].split(" ");
                        if (tokens.length >= 3) {
                            // Ambil 2 terakhir sebagai dosis
                            $("#dosis2").val(tokens.pop());
                            $("#dosis1").val(tokens.pop());
                            $("#nama-obat").val(tokens.join(" "));
                        } else if (tokens.length === 2) {
                            $("#dosis1").val(tokens.pop());
                            $("#nama-obat").val(tokens.join(" "));
                        } else if (tokens.length === 1) {
                            $("#nama-obat").val(tokens[0]);
                        }
                    }

                    // === PARSING BARIS 2: instruksi (opsional) ===
                    if (parts.length > 1 && !/^\d+\s*x\s*\d+/.test(parts[1])) {
                        // Pastikan ini bukan signa
                        $("#instruksi").val(parts[1]);
                    }

                    // === PARSING BARIS 3 atau BARIS 2 (jika tidak ada instruksi): signa ===
                    const signaLine = parts.find(p => /^\d+\s*x\s*\d+/.test(p));
                    if (signaLine) {
                        const signaRegex = /^(\d+)\s*x\s*(\d+)(?:\s+(\S+))?(?:\s+(.*))?$/;
                        const match = signaLine.match(signaRegex);
                        if (match) {
                            if (match[1]) $("#signa-jumlah1").val(match[1]);
                            if (match[2]) $("#signa-jumlah2").val(match[2]);
                            if (match[3]) $("#dosis3").val(match[3]);
                            if (match[4]) $("#signa-satuan2").val(match[4]);
                        }
                    }
                }

                // Hapus item yang sedang diedit dan render ulang
                resepList.splice(idx, 1);
                selectedIndex = -1;
                renderResep();
            });

            $("#btn-print-resep-ajax").click(function () {
                const resepData = JSON.stringify(resepList);
                let note = $('#note_revisi').val();

                Swal.fire({
                    title: 'Cetak Data',
                    text: "Apakah Anda yakin ingin mencetak resep dokter?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, cetak!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Buat form dinamis
                        console.log(JSON.stringify(resepList));
                        var form = $('<form>', {
                            method: 'POST',
                            action: "{{ route('apotek.resep_revisi') }}",
                            target: '_blank'
                        });

                        // CSRF token
                        form.append($('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: '{{ csrf_token() }}'
                        }));

                        // Data tambahan
                        form.append($('<input>', {
                            type: 'hidden',
                            name: 'resep_data',
                            value: resepData
                        }));
                        form.append($('<input>', {
                            type: 'hidden',
                            name: 'note',
                            value: note
                        }));

                        // Submit form
                        $('body').append(form);
                        form.submit();
                        form.remove();

                        $('#modalResep').modal('hide');
                    }
                });
                // const resepData = JSON.stringify(resepList);

                // $.ajax({
                //     url: '{{ route('apotek.resep_revisi') }}',
                //     type: 'POST',
                //     data: {
                //         resep_data: resepData,
                //         _token: '{{ csrf_token() }}'
                //     },
                //     xhrFields: {
                //         responseType: 'blob' // penting agar bisa buka PDF dari binary
                //     },
                //     success: function (response, status, xhr) {
                //         const blob = new Blob([response], { type: 'application/pdf' });
                //         const url = window.URL.createObjectURL(blob);
                //         window.open(url, '_blank');
                //     },
                //     error: function (xhr) {
                //         Swal.fire({
                //             icon: 'error',
                //             title: 'Gagal',
                //             text: 'Gagal mencetak resep.'
                //         });
                //     }
                // });
            });
        });
    </script>


@endsection
