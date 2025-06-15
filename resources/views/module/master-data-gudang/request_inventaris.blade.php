@extends('layouts.dashbord')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Request Data Inventaris</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">

                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Info Boxes -->
                {{-- <div class="row mb-3">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary py-1">
                            <div class="inner">
                                <h6>0</h6>
                                <p class="mb-0">Total Request</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary py-1">
                            <div class="inner">
                                <h6>0</h6>
                                <p class="mb-0">Menunggu Approval</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary py-1">
                            <div class="inner">
                                <h6>0</h6>
                                <p class="mb-0">Jenis Obat Tersedia</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-pills"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary py-1">
                            <div class="inner">
                                <h6>0</h6>
                                <p class="mb-0">Stok Obat Menipis</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <!-- Action Button -->
                <div class="row mb-2">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRequestModal">
                            <i class="fas fa-plus-circle"></i> Buat Request Obat Baru
                        </button>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="row">
                    <!-- Left Column: Approve Data Obat -->
                    <div class="col-md-6">
                        <div class="card card-outline">
                            <div class="card-header bg-white">
                                <h3 class="card-title">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Approve Data Obat
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="approveTable" class="table table-bordered table-striped table-hover">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th class="text-center">Kode Request</th>
                                            <th class="text-center">Tanggal Request</th>
                                            <th class="text-center" width="20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data_kirim as $index => $data_kirimData)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">{{ $data_kirimData->kode_request }}</td>
                                                <td class="text-center">{{ $data_kirimData->tanggal_request }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-info btn-sm" onclick="detailDataKirim('{{ $data_kirimData->kode_request }}');">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Request Obat -->
                    <div class="col-md-6">
                        <div class="card card-outline">
                            <div class="card-header bg-white">
                                <h3 class="card-title">
                                    <i class="fas fa-clipboard-list mr-1"></i>
                                    Request Obat
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="requestTable" class="table table-bordered table-striped table-hover">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th class="text-center">Kode Request</th>
                                            <th class="text-center">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($request as $index => $requestData)
                                            <tr class="table-row" ondblclick="openModal('{{ $requestData->kode_request }}')"
                                                style="background-color:
                                                    {{ $requestData->status == 0 ? '#f8d7da' :
                                                    ($requestData->status == 1 ? '#fff3cd' :
                                                    ($requestData->status == 2 ? '#d4edda' : 'transparent')) }};">
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">{{ $requestData->kode_request }}</td>
                                                <td class="text-center">{{ $requestData->tanggal_input }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Row: Stok Obat -->
                {{-- <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-outline">
                            <div class="card-header bg-white">
                                <h3 class="card-title">
                                    <i class="fas fa-pills mr-1"></i>
                                    Stok Obat Klinik
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="stokTable" class="table table-bordered table-striped table-hover">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th class="text-center" width="15%">Kode</th>
                                            <th class="text-center">Nama Obat</th>
                                            <th class="text-center" width="15%">Jumlah</th>
                                            <th class="text-center" width="15%">Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stok as $index => $stokData)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">{{ $stokData->kode_obat_alkes }}</td>
                                                <td class="text-center">{{ $stokData->nama_obat_alkes }}</td>
                                                <td class="text-center">{{ $stokData->qty }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-info btn-sm" onclick="detailDataStok();">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <!-- Modal Tambah Request -->
    <div class="modal fade" id="addRequestModal" tabindex="-1" role="dialog" aria-labelledby="addRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRequestModalLabel">Tambah Request Data Inventaris</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addRequestForm" action="{{ route('inventarisrequest.store') }}" method="POST">
                        @csrf
                        <div class="bs-stepper">
                            <div class="bs-stepper-header" role="tablist">
                                <!-- Step 1: Koneksi External Database -->
                                <div class="step" data-target="#koneksiExternal">
                                    <button type="button" class="step-trigger" role="tab" aria-controls="koneksiExternal" id="koneksiExternal-trigger">
                                        <span class="bs-stepper-circle">1</span>
                                        <span class="bs-stepper-label">Pilih Koneksi (Gudang Utama)</span>
                                    </button>
                                </div>
                                <div class="line"></div>
                                <!-- Step 2: Request Obat atau Alkes -->
                                <div class="step" data-target="#requestObatAlkes">
                                    <button type="button" class="step-trigger" role="tab" aria-controls="requestObatAlkes" id="requestObatAlkes-trigger">
                                        <span class="bs-stepper-circle">2</span>
                                        <span class="bs-stepper-label">Request Obat / Alkes</span>
                                    </button>
                                </div>
                            </div>
                            <div class="bs-stepper-content">
                                <!-- Step 1: Koneksi External Database -->
                                <div id="koneksiExternal" class="content" role="tabpanel" aria-labelledby="koneksiExternal-trigger">
                                    <div class="form-group row">
                                        <input type="hidden" id="data_tabel_request" name="data_tabel_request">
                                        <div class="col-sm-12">
                                            <label for="external_database">Pilih Tujuan</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="external_database" name="external_database" required>
                                                <option value="" disabled selected>Pilih Nama Tujuan</option>
                                                @foreach ($singkron as $datasingkron)
                                                    <option value="{{ $datasingkron->id }}">{{ $datasingkron->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                    <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                                </div>

                                <!-- Step 2: Request Obat atau Alkes -->
                                <div id="requestObatAlkes" class="content" role="tabpanel" aria-labelledby="requestObatAlkes-trigger">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="kode_request">Kode Request</label>
                                                <input type="text" class="form-control" id="kode_request" name="kode_request" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="nama_barang">Barang Inventaris</label>
                                                <select class="form-control select2bs4 mt-2" style="width: 100%;" id="nama_barang" name="nama_barang">
                                                    <option value="" disabled selected>Pilih Barang Inventaris</option>
                                                    @foreach ($inventaris as $inventarisData)
                                                        <option value="{{ $inventarisData->nama_barang }}"
                                                            data-kode-barang="{{ $inventarisData->kode_barang }}">
                                                            {{ $inventarisData->nama_barang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="jumlah_barang">Jumlah</label>
                                                <input type="text" class="form-control" id="jumlah_barang" name="jumlah_barang" placeholder="Masukan jumlahnya">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group d-flex flex-column align-items-end">
                                                <label class="d-block invisible">Aksi</label>
                                                <button type="button" onclick="addNewDataToTabel()" class="btn btn-primary">
                                                    Tambah Data Sementara
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="table-responsive">
                                                <table id="tabel-data" class="table table-bordered table-striped">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th style="width: 5%;">No</th>
                                                            <th>Kode Obat</th>
                                                            <th>Nama Obat</th>
                                                            <th>Jumlah</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Data akan ditambahkan secara dinamis di sini -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Details Request --}}
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <h4 id="modal-title-text" class="fw-bold"></h4>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="detailsTable" class="table table-bordered table-striped table-hover">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th class="text-center">Kode Obat/Alkes</th>
                                        <th class="text-center">Nama Obat/Alkes</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Diisi oleh JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Details Approval --}}
    <div class="modal fade" id="approveDetailsModal" tabindex="-1" role="dialog" aria-labelledby="approveDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveDetailsModalLabel">Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <h4 id="modal-title-text" class="fw-bold"></h4>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="approveDetailsTable" class="table table-bordered table-striped table-hover">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th class="text-center">Kode</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Jenis</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Deskripsi</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Diisi oleh JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btn-close-refresh" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        const CURRENT_USER_ID = {{ auth()->user()->id }};
        const CURRENT_USER_NAME = "{{ auth()->user()->name }}";

        $(document).ready(function(){
            Inputmask({
                alias: "numeric",
                rightAlign: false,
                digits: 0,
                allowMinus: false,
                autoGroup: false
            }).mask("#jumlah_barang");

            $('#addRequestModal').on('shown.bs.modal', function () {
                stepper = new Stepper(document.querySelector('#addRequestModal .bs-stepper'));

                $.ajax({
                    url: "{{ route('inventaris.request_getLastKode') }}",
                    method: 'GET',
                    beforeSend: function () {
                        // Tampilkan loading Swal
                        Swal.fire({
                            icon: 'info',
                            title: 'Mengambil Kode...',
                            text: 'Harap tunggu',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (res) {
                        // Tutup swal saat sukses
                        Swal.close();

                        // Tampilkan hasil di input
                        $('#kode_request').val(res.kode_request);
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal generate kode request.'
                        });
                    }
                });

            });

            $('#btn-close-refresh').on('click', function () {
                setTimeout(() => location.reload(), 300);
            });
        });

        let nomorUrut = 1;

        function addNewDataToTabel() {
            const select = document.getElementById('nama_barang');
            const selectedOption = select.options[select.selectedIndex];
            const namaInventaris = selectedOption.value;
            const kodeInventaris = selectedOption.getAttribute('data-kode-barang');
            const jumlah = document.getElementById('jumlah_barang').value.trim();

            if (!namaInventaris || !jumlah) {
                alert("Harap pilih barang dan masukkan jumlah.");
                return;
            }

            if (isNaN(jumlah) || parseInt(jumlah) <= 0) {
                alert("Jumlah harus berupa angka lebih dari 0.");
                return;
            }

            // Tambahkan baris ke tabel
            const tbody = document.querySelector('#tabel-data tbody');
            const row = document.createElement('tr');

            row.innerHTML = `
                <td style="width: 5%;">${nomorUrut}</td>
                <td>${kodeInventaris}</td>
                <td>${namaInventaris}</td>
                <td>${jumlah}</td>
            `;

            tbody.appendChild(row);

            // Increment nomor urut untuk baris berikutnya
            nomorUrut++;

            // Ambil data tabel dan simpan dalam array
            const tableData = [];
            const rows = tbody.querySelectorAll('tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const data = {
                    no: cells[0].textContent,
                    kode_barang: cells[1].textContent,
                    nama_barang: cells[2].textContent,
                    jumlah: cells[3].textContent
                };
                tableData.push(data);
            });

            // Perbarui input hidden dengan data tabel dalam format JSON
            document.getElementById('data_tabel_request').value = JSON.stringify(tableData);

            // Reset input
            $('#nama_barang').val(null).trigger('change');
            $('#jumlah_barang').val('');
        }

        $('#addRequestForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addRequestModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: true
                        }).then(() => {
                            $('.modal-backdrop').remove(); // Hapus backdrop jika masih ada
                            location.reload(); // Reload halaman untuk update data
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        let errorList = '';

                        // Hapus class 'is-invalid' dari semua input dulu (optional, biar bersih)
                        $('#addRequestForm').find('.is-invalid').removeClass('is-invalid');

                        Object.entries(xhr.responseJSON.errors).forEach(([key, value]) => {
                            errorList += `- ${value[0]}<br>`;
                            $(`#${key}`).addClass('is-invalid'); // Tambahkan class error ke input
                        });

                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal!',
                            html: `Terdapat beberapa input yang belum valid:<br><br>${errorList}`,
                        });
                    } else {
                        let errorMessage = "Terjadi kesalahan dalam menyimpan data!";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                }
            });
        });

        function openModal(kodeRequest) {
            // Tampilkan loading Swal
            Swal.fire({
                icon: 'info',
                title: 'Memuat data...',
                text: 'Harap tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/api/data-master-gudang/request/inventaris/getDetails/${kodeRequest}`)
                .then(response => response.json())
                .then(data => {
                    // Tutup loading
                    Swal.close();

                    // Set judul modal
                    const modalTitle = document.getElementById('modal-title-text');
                    modalTitle.textContent = `Details Request Dengan Kode Request : ${kodeRequest}`;

                    // Kosongkan isi tabel
                    const table = document.getElementById('detailsTable');
                    const tableBody = table.querySelector('tbody');
                    tableBody.innerHTML = '';

                    // Tambahkan baris data
                    data.details.forEach((detail, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="text-center">${index + 1}</td>
                            <td class="text-center">${detail.kode_barang}</td>
                            <td class="text-center">${detail.nama_barang}</td>
                            <td class="text-center">${detail.qty}</td>
                        `;
                        tableBody.appendChild(row);
                    });

                    // Tampilkan modal
                    $('#detailsModal').modal('show');
                })
                .catch(error => {
                    Swal.close(); // Tutup loading kalau gagal
                    console.error('Error fetching details:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Data',
                        text: 'Terjadi kesalahan saat mengambil data detail.'
                    });
                });
        }

        function detailDataKirim(kodeRequest) {
            // Set judul modal
            $('#modal-title-text').text('Details untuk Kode Request: ' + kodeRequest);

            // Kosongkan isi tabel dulu
            let tbody = $('#approveDetailsTable tbody');
            tbody.empty();

            // Tampilkan loading sementara dengan Swal
            Swal.fire({
                title: 'Memuat data...',
                text: 'Mohon tunggu sebentar',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Panggil API
            $.ajax({
                url: '/api/data-master-gudang/request/inventaris/detailsAprroval/' + encodeURIComponent(kodeRequest),
                method: 'GET',
                success: function(response) {
                    Swal.close();

                    let data = response.details; // Ambil array dari properti 'details'

                    // Tampilkan modal
                    $('#approveDetailsModal').modal('show');

                    if (data.length === 0) {
                        tbody.append('<tr><td colspan="8" class="text-center">Data tidak ditemukan</td></tr>');
                        return;
                    }

                    data.forEach(function(item, index) {
                        let row = `
                            <tr>
                                <td class="text-center">${index + 1}</td>
                                <td class="text-center">${item.kode_barang}</td>
                                <td>${item.nama_barang}</td>
                                <td class="text-center">${item.kategori_barang}</td>
                                <td class="text-center">${item.jenis_barang}</td>
                                <td class="text-center">${item.qty_barang}</td>
                                <td class="text-center">${item.detail_barang}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success" onclick="terimaData(${item.id}, '${kodeRequest}')">Terima</button>
                                    <button class="btn btn-sm btn-danger" onclick="tolakData(${item.id}, '${kodeRequest}')">Tolak</button>
                                </td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                },

                error: function(xhr, status, error) {
                    tbody.append('<tr><td colspan="6" class="text-center text-danger">Gagal memuat data: ' + error + '</td></tr>');
                }
            });
        }

        function terimaData(id, kodeRequest) {
            Swal.fire({
                title: 'Terima Data?',
                text: "Data akan dipindahkan ke klinik!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745', // Hijau
                cancelButtonColor: '#d33',     // Merah
                confirmButtonText: 'Ya, Terima!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ✅ Tampilkan loading Swal
                    Swal.fire({
                        icon: 'info',
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: `/api/data-master-gudang/request/inventaris/terimaData/${id}`,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            user_id: CURRENT_USER_ID,
                            user_name: CURRENT_USER_NAME
                        },
                        success: function(response) {
                            Swal.close(); // Tutup loading

                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // ✅ Refresh isi tabel setelah klik OK
                                detailDataKirim(kodeRequest);
                            });
                        },
                        error: function(xhr) {
                            Swal.close(); // Tutup loading
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menyimpan.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }

        function tolakData(id, kodeRequest) {
            Swal.fire({
                title: 'Tolak Data?',
                text: "Stok akan dikembalikan ke gudang utama!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ✅ Tampilkan loading Swal saat proses AJAX berjalan
                    Swal.fire({
                        icon: 'info',
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: `/api/data-master-gudang/request/inventaris/tolakData/${id}`,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            user_id: CURRENT_USER_ID,
                            user_name: CURRENT_USER_NAME
                        },
                        success: function(response) {
                            Swal.close(); // Tutup loading saat sukses atau error

                            if (response.success) {
                                Swal.fire({
                                    title: 'Ditolak!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Refresh tabel hanya setelah user klik OK
                                    detailDataKirim(kodeRequest);
                                });
                            } else {
                                Swal.fire({
                                    title: 'Tidak Ditemukan!',
                                    text: response.message || 'Data tidak ditemukan atau sudah diproses sebelumnya.',
                                    icon: 'warning',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.close(); // Tutup loading jika error
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menolak data.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }

    </script>
@endsection
