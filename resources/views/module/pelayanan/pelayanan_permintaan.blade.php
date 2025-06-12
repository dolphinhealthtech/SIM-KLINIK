@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Permintaan Radiologi / Laboratorium</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Permintaan</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Data Pasien -->
                <div class="col-md-4">
                    <div class="card card-primary card-outline" style="height: 780px;">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-injured mr-2"></i>Data Pasien</h3>
                        </div>
                        <div class="card-body">
                            <!-- Brand Logo dari sidebar dengan path yang diubah ke public/profile/default.png -->
                            <div class="text-center mb-4">
                                <img src="{{ asset('profile/default.png') }}"
                                    alt="Klinik Logo" class="img-circle elevation-2"
                                    style="width: 100px; height: 100px; opacity: .8">
                            </div>

                            <div class="form-group">
                                <label for="nomor_rm">No. RM</label>
                                <input type="text" class="form-control bg-light" id="nomor_rm" value="{{ $pelayanan->nomor_rm }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="nama">Nama Pasien</label>
                                <input type="text" class="form-control bg-light" id="nama" name="nama" value="{{ $pelayanan->pasien->nama }}" readonly>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                        <input type="text" class="form-control bg-light" id="jenis_kelamin" name="jenis_kelamin" value="{{ $pelayanan->pasien->kelamin->nama }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="penjamin">Penjamin</label>
                                        <input type="text" class="form-control bg-light" id="penjamin" name="penjamin" value="{{ $pelayanan->pendaftaran->penjamin->nama }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="text" class="form-control bg-light" id="tanggal_lahir" name="tanggal_lahir" value="{{ $pelayanan->pasien->tanggal_lahir }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="umur">Umur</label>
                                        <input type="text" class="form-control bg-light" id="umur" value="{{ $umur }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="dokter_pengirim">Dokter Pengirim</label>
                                <input type="text" class="form-control bg-light" id="dokter_pengirim" name="dokter_pengirim" value="{{ $pelayanan->pendaftaran->dokter->namauser->name }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="poli">Poli</label>
                                <input type="text" class="form-control bg-light" id="poli" name="poli" value="{{ $pelayanan->poli->nama }}" readonly>
                            </div>

                            <input type="hidden" id="alamat" name="alamat" value="{{ $pelayanan->pasien->alamat }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-four-radiologi-tab" data-toggle="pill" href="#custom-tabs-four-radiologi" role="tab" aria-controls="custom-tabs-four-radiologi" aria-selected="true">Radiologi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-four-laboratorium-tab" data-toggle="pill" href="#custom-tabs-four-laboratorium" role="tab" aria-controls="custom-tabs-four-laboratorium" aria-selected="false">Laboratorium</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-four-radiologi" role="tabpanel" aria-labelledby="custom-tabs-four-radiologi-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" id="rad_table_hidden" name="rad_table_hidden">
                                        <div style="border: 2px solid black; padding: 10px; width: 100%; max-width: auto; min-height: 255px;">
                                            <div class="table-responsive" style="max-height: 255px; overflow-y: auto;">
                                                <table class="table" id="rad_table" style="border: none;">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10%">No</th>
                                                            <th style="width: 35%">Nama Pemeriksaan</th>
                                                            <th style="width: 35%">Posisi</th>
                                                            <th style="width: 20%">Metode</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {{-- DATA TERISI OTOMATIS NANTI --}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row align-items-center mt-3">
                                            <div class="col-md-2">
                                                <label for="pemeriksaan_radiologi" class="col-form-label">Pemeriksaan</label>
                                            </div>
                                            <div class="col-md-8">
                                                <select class="form-control select2bs4" style="width: 100%;" id="pemeriksaan_radiologi" name="pemeriksaan_radiologi">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    @foreach ($radiologi_pemeriksaan as $radiologi_pemeriksaan_item)
                                                        <option value="{{ $radiologi_pemeriksaan_item->nama }}">{{ $radiologi_pemeriksaan_item->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-primary w-100" id="btn-tambah-rad">Tambah</button>
                                            </div>
                                        </div>
                                        <div class="form-group row align-items-center mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Posisi</label>
                                            </div>
                                            <div class="col-md-5">
                                                <select class="form-control select2bs4" style="width: 100%;" id="jenis_posisi_radiologi" name="jenis_posisi_radiologi">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    @foreach ($radiologi_jenis as $radiologi_jenis_item)
                                                        <option value="{{ $radiologi_jenis_item->nama }}">{{ $radiologi_jenis_item->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control select2bs4" style="width: 100%;" id="posisi_radiologi" name="posisi_radiologi">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    <option value="R">R</option>
                                                    <option value="L">L</option>
                                                    <option value="Both">Both</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger w-100" id="btn-hapus-rad">Hapus</button>
                                            </div>
                                        </div>
                                        <div class="form-group row align-items-center mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Metode</label>
                                            </div>
                                            <div class="col-md-5">
                                                <select class="form-control select2bs4" style="width: 100%;" id="metode_radiologi" name="metode_radiologi">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    <option value="Rutin">Rutin</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Diagnosa Referensi</label>
                                            </div>
                                            <div class="col-md-10">
                                                <select class="form-control select2bs4" style="width: 100%;" id="diagnosa_radiologi" name="diagnosa_radiologi">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    @foreach ($data_icd9 as $radiologi)
                                                        <option value="({{$radiologi->kode_icd9}}) {{$radiologi->nama_icd9}}">({{$radiologi->kode_icd9}}) {{$radiologi->nama_icd9}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">

                                            </div>
                                            <div class="col-md-10">
                                                <small class="form-text text-muted">
                                                    *) Hanya sebagai referensi, bukan diagnosa akhir dari pasien.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Tanggal Periksa</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="datetime-local" class="form-control" id="tanggal_periksa_radiologi" name="tanggal_periksa_radiologi">
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Catatan Dokter</label>
                                            </div>
                                            <div class="col-md-10">
                                                <textarea class="form-control" id="catatan_dokter_radiologi" name="catatan_dokter_radiologi" rows="2" placeholder="Masukkan catatan dokter..."></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-12 text-right">
                                                <button type="button" class="btn btn-success" id="btn-print-rad">
                                                    <i class="fas fa-print"></i> Print
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="custom-tabs-four-laboratorium" role="tabpanel" aria-labelledby="custom-tabs-four-laboratorium-tab">
                                <div class="row">
                                    <input type="hidden" id="lab_table_hidden" name="lab_table_hidden">
                                    <div class="col-md-12">
                                        <div style="border: 2px solid black; padding: 10px; width: 100%; max-width: auto; min-height: 285px;">
                                            <div class="table-responsive" style="max-height: 285px; overflow-y: auto;">
                                                <table class="table" id="lab_table" style="border: none;">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10%">No</th>
                                                            <th style="width: 90%">Nama Pemeriksaan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {{-- DATA TERISI OTOMATIS NANTI --}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row align-items-center mt-3">
                                            <div class="col-md-2">
                                                <label for="bidang_laboratorium" class="col-form-label">Bidang</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control select2bs4" style="width: 100%;" id="bidang_laboratorium" name="bidang_laboratorium">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    <option value="Seluruh Data" data-id="all">Seluruh Data</option>
                                                    @foreach ($data_lab as $data_lab_item)
                                                        <option value="{{ $data_lab_item->nama }}" data-id="{{ $data_lab_item->id }}">{{ $data_lab_item->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-primary w-100" id="btn-tambah-lab">Tambah</button>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger w-100" id="btn-hapus-lab">Hapus</button>
                                            </div>
                                        </div>
                                        <div class="form-group row align-items-center mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Pemeriksaan</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control select2bs4" style="width: 100%;" id="pemeriksaan_laboratorium" name="pemeriksaan_laboratorium">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Diagnosa Referensi</label>
                                            </div>
                                            <div class="col-md-10">
                                                <select class="form-control select2bs4" style="width: 100%;" id="diagnosa_laboratorium" name="diagnosa_laboratorium">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    @foreach ($data_icd9 as $lab)
                                                        <option value="({{$lab->kode_icd9}}) {{$lab->nama_icd9}}">({{$lab->kode_icd9}}) {{$lab->nama_icd9}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">

                                            </div>
                                            <div class="col-md-10">
                                                <small class="form-text text-muted">
                                                    *) Hanya sebagai referensi, bukan diagnosa akhir dari pasien.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Tanggal Periksa</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="datetime-local" class="form-control" id="tanggal_periksa_laboratorium" name="tanggal_periksa_laboratorium">
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Catatan Dokter</label>
                                            </div>
                                            <div class="col-md-10">
                                                <textarea class="form-control" id="catatan_dokter_laboratorium" name="catatan_dokter_laboratorium" rows="3" placeholder="Masukkan catatan dokter..."></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-12 text-right">
                                                <button type="button" class="btn btn-success" id="btn-print-lab">
                                                    <i class="fas fa-print"></i> Print
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    const datetimeInputRadiologi = document.getElementById('tanggal_periksa_radiologi');
    const datetimeInputLaboratorium = document.getElementById('tanggal_periksa_laboratorium');

    datetimeInputRadiologi.addEventListener('click', function () {
        this.showPicker && this.showPicker(); // untuk browser yang support
    });
    datetimeInputLaboratorium.addEventListener('click', function () {
        this.showPicker && this.showPicker(); // untuk browser yang support
    });
</script>

<script>
    $(document).ready(function () {
        $('#bidang_laboratorium').on('change', function () {
            // Ambil id dari atribut data-id, bukan dari value
            let id = $(this).find(':selected').data('id');

            $('#pemeriksaan_laboratorium').empty().append('<option disabled selected>Loading...</option>');

            $.ajax({
                url: `/api/get-pemeriksaan-laboratorium/${id}`,
                type: 'GET',
                success: function (data) {
                    $('#pemeriksaan_laboratorium').empty().append('<option value="" disabled selected>-- Pilih --</option>');
                    $.each(data, function (key, value) {
                        $('#pemeriksaan_laboratorium').append(`<option value="${value.nama_sublaboratorium_bidang}">${value.nama_sublaboratorium_bidang}</option>`);
                    });
                }
            });
        });
    });
</script>

<script>
    let selectedRow = null;
    let labData = [];

    function refreshTable() {
        let tbody = $('#lab_table tbody');
        tbody.empty();

        labData.forEach((item, index) => {
            tbody.append(`
                <tr data-index="${index}" class="lab-row">
                    <td>${index + 1}</td>
                    <td>${item}</td>
                </tr>
            `);
        });

        // Update hidden input as JSON string
        $('#lab_table_hidden').val(JSON.stringify(labData));

        console.log('Data : ',JSON.stringify(labData));
    }

    // Tambah data (dengan cek duplikat)
    $('#btn-tambah-lab').on('click', function () {
        let selected = $('#pemeriksaan_laboratorium').val();

        if (!selected) {
            Swal.fire('Pilih Pemeriksaan terlebih dahulu.', '', 'warning');
            return;
        }

        // Cek duplikat
        if (labData.includes(selected)) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Duplikat',
                text: `"${selected}" sudah ada dalam tabel.`,
                showConfirmButton: true
            });
            return;
        }

        labData.push(selected);
        refreshTable();
        $('#pemeriksaan_laboratorium').val(null).trigger('change');
    });

    // Pilih baris
    $(document).on('click', '.lab-row', function () {
        $('.lab-row').removeClass('table-primary');
        $(this).addClass('table-primary');
        selectedRow = $(this).data('index');
    });

    // Hapus data
    $('#btn-hapus-lab').on('click', function () {
        if (selectedRow === null) {
            Swal.fire('Pilih baris yang ingin dihapus.', '', 'info');
            return;
        }

        labData.splice(selectedRow, 1);
        selectedRow = null;
        refreshTable();
    });
</script>

<script>
    $('#btn-print-lab').on('click', function () {
        const labData = $('#lab_table_hidden').val();
        const diagnosa = $('#diagnosa_laboratorium').val();
        const tanggal = $('#tanggal_periksa_laboratorium').val();
        const catatan = $('#catatan_dokter_laboratorium').val();
        const nama_pasien = $('#nama').val();
        const dokter_pengirim = $('#dokter_pengirim').val();
        const poli = $('#poli').val();
        const jenis_kelamin = $('#jenis_kelamin').val();
        const tanggal_lahir = $('#tanggal_lahir').val();
        const alamat = $('#alamat').val();
        const penjamin = $('#penjamin').val();
        const csrfToken = '{{ csrf_token() }}';

        if (!labData || !diagnosa || !tanggal) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Tidak Lengkap',
                text: 'Pastikan data pemeriksaan dan tanggal periksa sudah diisi.'
            });
            return;
        }

        Swal.fire({
            title: 'Cetak Permintaan Laboratorium?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Cetak!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                // Buat form dinamis
                const form = $('<form>', {
                    method: 'POST',
                    action: '{{ route("laboratorium.print") }}',
                    target: '_blank'
                });

                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: csrfToken
                }));
                form.append($('<input>', { type: 'hidden', name: 'lab_table_hidden', value: labData }));
                form.append($('<input>', { type: 'hidden', name: 'diagnosa_laboratorium', value: diagnosa }));
                form.append($('<input>', { type: 'hidden', name: 'tanggal_periksa_laboratorium', value: tanggal }));
                form.append($('<input>', { type: 'hidden', name: 'catatan_dokter_laboratorium', value: catatan }));
                form.append($('<input>', { type: 'hidden', name: 'nama_pasien', value: nama_pasien }));
                form.append($('<input>', { type: 'hidden', name: 'dokter_pengirim', value: dokter_pengirim }));
                form.append($('<input>', { type: 'hidden', name: 'poli', value: poli }));
                form.append($('<input>', { type: 'hidden', name: 'jenis_kelamin', value: jenis_kelamin }));
                form.append($('<input>', { type: 'hidden', name: 'tanggal_lahir', value: tanggal_lahir }));
                form.append($('<input>', { type: 'hidden', name: 'alamat', value: alamat }));
                form.append($('<input>', { type: 'hidden', name: 'penjamin', value: penjamin }));

                $('body').append(form);
                form.submit();
                form.remove();

                // Setelah submit, redirect ke route dokter
                setTimeout(() => {
                    window.location.href = '{{ route("pelayanad.get") }}';
                }, 1000); // delay 1 detik agar PDF sempat terbuka
            }
        });
    });
</script>

<script>
    let selectedRadRow = null;
    let radData = [];

    function refreshRadTable() {
        let tbody = $('#rad_table tbody');
        tbody.empty();

        radData.forEach((item, index) => {
            tbody.append(`
                <tr data-index="${index}" class="rad-row">
                    <td>${index + 1}</td>
                    <td>${item.pemeriksaan}</td>
                    <td>${item.jenis_posisi} - ${item.posisi}</td>
                    <td>${item.metode}</td>
                </tr>
            `);
        });

        $('#rad_table_hidden').val(JSON.stringify(radData));
        console.log('Data : ', JSON.stringify(radData));
    }

    $('#btn-tambah-rad').on('click', function () {
        const pemeriksaan = $('#pemeriksaan_radiologi').val();
        const jenisPosisi = $('#jenis_posisi_radiologi').val();
        const posisi = $('#posisi_radiologi').val();
        const metode = $('#metode_radiologi').val();

        if (!pemeriksaan || !jenisPosisi || !posisi || !metode) {
            Swal.fire('Lengkapi semua field hingga metode sebelum menambah data.', '', 'warning');
            return;
        }

        const newItem = {
            pemeriksaan,
            jenis_posisi: jenisPosisi,
            posisi,
            metode
        };

        const isDuplicate = radData.some(item =>
            item.pemeriksaan === newItem.pemeriksaan
        );

        if (isDuplicate) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Duplikat',
                text: `Data "${pemeriksaan}" sudah ditambahkan ke dalam tabel.`,
            });
            return;
        }

        radData.push(newItem);
        refreshRadTable();

        // Reset input
        $('#pemeriksaan_radiologi').val(null).trigger('change');
        $('#jenis_posisi_radiologi').val(null).trigger('change');
        $('#posisi_radiologi').val(null).trigger('change');
        $('#metode_radiologi').val(null).trigger('change');
    });

    $(document).on('click', '.rad-row', function () {
        $('.rad-row').removeClass('table-primary');
        $(this).addClass('table-primary');
        selectedRadRow = $(this).data('index');
    });

    $('#btn-hapus-rad').on('click', function () {
        if (selectedRadRow === null) {
            Swal.fire('Pilih baris yang ingin dihapus.', '', 'info');
            return;
        }

        radData.splice(selectedRadRow, 1);
        selectedRadRow = null;
        refreshRadTable();
    });
</script>

<script>
    $('#btn-print-rad').on('click', function () {
        const radData = $('#rad_table_hidden').val();
        const diagnosa = $('#diagnosa_radiologi').val();
        const tanggal = $('#tanggal_periksa_radiologi').val();
        const catatan = $('#catatan_dokter_radiologi').val();
        const nama_pasien = $('#nama').val();
        const dokter_pengirim = $('#dokter_pengirim').val();
        const poli = $('#poli').val();
        const jenis_kelamin = $('#jenis_kelamin').val();
        const tanggal_lahir = $('#tanggal_lahir').val();
        const alamat = $('#alamat').val();
        const penjamin = $('#penjamin').val();
        const csrfToken = '{{ csrf_token() }}';

        if (!radData || !diagnosa || !tanggal) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Tidak Lengkap',
                text: 'Pastikan data pemeriksaan dan tanggal periksa sudah diisi.'
            });
            return;
        }

        Swal.fire({
            title: 'Cetak Permintaan Laboratorium?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Cetak!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                // Buat form dinamis
                const form = $('<form>', {
                    method: 'POST',
                    action: '{{ route("radiologi.print") }}',
                    target: '_blank'
                });

                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: csrfToken
                }));
                form.append($('<input>', { type: 'hidden', name: 'rad_table_hidden', value: radData }));
                form.append($('<input>', { type: 'hidden', name: 'diagnosa_radiologi', value: diagnosa }));
                form.append($('<input>', { type: 'hidden', name: 'tanggal_periksa_radiologi', value: tanggal }));
                form.append($('<input>', { type: 'hidden', name: 'catatan_dokter_radiologi', value: catatan }));
                form.append($('<input>', { type: 'hidden', name: 'nama_pasien', value: nama_pasien }));
                form.append($('<input>', { type: 'hidden', name: 'dokter_pengirim', value: dokter_pengirim }));
                form.append($('<input>', { type: 'hidden', name: 'poli', value: poli }));
                form.append($('<input>', { type: 'hidden', name: 'jenis_kelamin', value: jenis_kelamin }));
                form.append($('<input>', { type: 'hidden', name: 'tanggal_lahir', value: tanggal_lahir }));
                form.append($('<input>', { type: 'hidden', name: 'alamat', value: alamat }));
                form.append($('<input>', { type: 'hidden', name: 'penjamin', value: penjamin }));

                $('body').append(form);
                form.submit();
                form.remove();

                // Setelah submit, redirect ke route dokter
                setTimeout(() => {
                    window.location.href = '{{ route("pelayanad.get") }}';
                }, 1000); // delay 1 detik agar PDF sempat terbuka
            }
        });
    });
</script>

@endsection
