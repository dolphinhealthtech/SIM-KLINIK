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
                            <button class="btn btn-info mr-2">Panggil</button>
                            <button class="btn btn-info mr-2">Auto</button>
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
                            <input type="text" class="form-control" id="faktur_apotek" name="faktur_apotek" value="JANGAN LUPA SCRIPT AUTO" readonly>
                        </div>
                        <div class="col-md-1">
                            <label>Dokter</label>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="checkbox" id="is_dokter">
                                    </div>
                                </div>
                                <select class="form-control" id="dokter" name="dokter">
                                    <option value="" disabled selected>Pilih Dokter</option>
                                    @foreach ($dokter as $dokterData)
                                        <option value="{{ $dokterData->namauser->name }}">{{ $dokterData->namauser->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>Rawat</label>
                        </div>
                        <div class="col-md-1 text-center">:</div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary btn-block">RAWAT JALAN</button>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control select2bs4" id="poli" name="poli">
                                <option value="" disabled selected>Pilih Poli</option>
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
                                <div style="border: 2px solid black; padding: 10px; width: 100%; max-width: 1000px; min-height: 300px;">
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
                                                    <button class="btn btn-info btn-sm rounded-circle">R/</button>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Barang :</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <select class="form-control" id="barang_bebas" name="barang_bebas">
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
                                                    <input type="number" class="form-control" id="qty">
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
                                                            <input type="text" class="form-control" id="harga_barang_bebas" name="harga_barang_bebas">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-9">
                                                    <button class="btn btn-info mr-2">Tambah</button>
                                                    <button class="btn btn-info">Hapus</button>
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
                                                        <input type="text" class="form-control" id="nilai_embis_input" name="nilai_embis_input">
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
                                                    <span style="position: absolute; right: -10px; top: -15px; font-weight: bold;">-</span>
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
                                {{-- <div class="border border-dark" style="height: 242px; background-color: #fff;"></div> --}}
                                <div style="border: 2px solid black; padding: 10px; width: 100%; max-width: 1000px; min-height: 300px;">
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
                                            <input type="text" class="form-control" id="jumlah_r" name="jumlah_r">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label>Note:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="note">
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
                                            <button class="btn btn-danger btn-block btn-sm">
                                                <i class="fas fa-download"></i> Download
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
                        <button type="button" class="btn btn-success">
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
                <table class="table table-bordered mb-0">
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

{{-- SCRIPT GLOBAL --}}
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

        //HITUNG TOTAL
        function updateTotal() {
            const subtotal = parseInt($('#sub_total_hidden').val()) || 0;
            const embalase = parseInt($('#embalase_total_hidden').val()) || 0;
            const total = subtotal - embalase;

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

                const tbody = document.querySelector('#tabel_apotek_harga tbody');

                // Kosongkan tabel sebelum isi ulang (optional, tergantung kebutuhan)
                tbody.innerHTML = '';

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

                results.forEach(({ index, nama, kuantitas, kode, harga }) => {
                    // 1. Bersihkan harga dari prefix dan titik
                    const hargaBersih = parseInt((harga || '0').replace(/[Rp.\s]/g, ''));

                    // 2. Kalikan dengan kuantitas (pastikan kuantitas sudah number)
                    const jumlah = hargaBersih * parseInt(kuantitas);

                    // 3. Format jumlah dengan prefix dan pemisah ribuan
                    const jumlahFormatted = 'Rp ' + jumlah.toLocaleString('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });

                    // 4. Format harga agar tetap terlihat rapi
                    const hargaFormatted = 'Rp ' + hargaBersih.toLocaleString('id-ID', {
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
                    hitungTotalHargaKeseluruhan();
                    updateTotal();
                });
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
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Inputmask({
                alias: "numeric",
                groupSeparator: ".",
                radixPoint: ",",
                autoGroup: true,
                digitsOptional: true,
                digits: 0,
                placeholder: "",
                prefix: "Rp ",
                rightAlign: false,
                removeMaskOnSubmit: true
            }).mask("#harga_barang_bebas");
        });

        $(document).ready(function () {
            $('#barang_bebas').on('change', function () {
                var kode = $(this).find(':selected').data('kode');

                if (!kode) return;

                $.ajax({
                    url: '/api/apotek/hargaBebas',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        kode: kode
                    },
                    success: function (response) {
                        $('#harga_barang_bebas').val(response.harga || 0);
                    },
                    error: function () {
                        alert('Gagal mengambil harga.');
                        $('#harga_barang_bebas').val(0);
                    }
                });
            });
        });
    </script>


@endsection
