@extends('layouts.dashbord')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Permintaan Obat Klinik</h1>
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
                            <i class="fas fa-plus-circle"></i> Buat Permintaan Baru
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
                                    Menunggu Persetujuan Permintaan
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
                                            <th class="text-center">Kode Permintaan</th>
                                            <th class="text-center">Tanggal Permintaan</th>
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
                                    Permintaan Obat
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
                                            <th class="text-center">Kode Permintaan</th>
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
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRequestModalLabel">Tambah Permintaan Obat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addRequestForm" action="{{ route('gudangrequest.store') }}" method="POST">
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
                                        <span class="bs-stepper-label">Permintaan Obat / Alkes</span>
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
                                            <select class="form-control select2bs4" style="width: 100%;" id="external_database" name="external_database">
                                                <option value="" disabled selected>Pilih Nama Tujuan</option>
                                                @if ($singkron->isEmpty())
                                                    <option value="Gudang Utama">Gudang Utama</option>
                                                @else
                                                    @foreach ($singkron as $datasingkron)
                                                        <option value="{{ $datasingkron->id }}">{{ $datasingkron->name }}</option>
                                                    @endforeach
                                                @endif

                                            </select>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="stepper.next()">Selanjutnya</button>
                                </div>

                                <!-- Step 2: Request Obat atau Alkes -->
                                <div id="requestObatAlkes" class="content" role="tabpanel" aria-labelledby="requestObatAlkes-trigger">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="kode_request">Kode Permintaan</label>
                                                <input type="text" class="form-control" id="kode_request" name="kode_request" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="nama_obat_alkes">Obat / Alkes</label>
                                                <select class="form-control select2bs4 mt-2" style="width: 100%;" id="nama_obat_alkes" name="nama_obat_alkes">
                                                    <option value="" disabled selected>Pilih Obat/Alkes</option>
                                                    @foreach ($dabar as $dabarData)
                                                        <option value="{{ $dabarData->nama_barang }}"
                                                            data-kode-barang="{{ $dabarData->kode_barang }}">
                                                            {{ $dabarData->nama_barang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="jumlah_obat_alkes">Jumlah</label>
                                                <input type="text" class="form-control" id="jumlah_obat_alkes" name="jumlah_obat_alkes" placeholder="Masukan jumlahnya">
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
                                                <table id="tabel-obat" class="table table-bordered table-striped">
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
                                    <button type="button" class="btn btn-primary" onclick="stepper.previous()">Sebelumnya</button>
                                    <button type="submit" class="btn btn-primary">Kirim</button>
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
                    <h5 class="modal-title" id="detailsModalLabel">Rincian Permintaan</h5>
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
                                        <th class="text-center">Kode Barang</th>
                                        <th class="text-center">Nama Barang</th>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Details Approval --}}
    <div class="modal fade" id="approveDetailsModal" tabindex="-1" role="dialog" aria-labelledby="approveDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveDetailsModalLabel">Rincian Permintaan Disetujui</h5>
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
                                        <th class="text-center">Kode Barang</th>
                                        <th class="text-center">Nama Barang</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Expired</th>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
            }).mask("#jumlah_obat_alkes");

            $('#addRequestModal').on('shown.bs.modal', function () {
                stepper = new Stepper(document.querySelector('#addRequestModal .bs-stepper'));

                $.ajax({
                    url: "{{ route('request.getLastKode') }}",
                    method: 'GET',
                    success: function (res) {
                        $('#kode_request').val(res.kode_request); // tampilkan di input
                    },
                    error: function () {
                        alert('Gagal generate kode request.');
                    }
                });

            });
        });

        function addNewDataToTabel() {
            let nomorUrut = 1;
            const select = document.getElementById('nama_obat_alkes');
            const selectedOption = select.options[select.selectedIndex];
            const namaObat = selectedOption.value;
            const kodeObat = selectedOption.getAttribute('data-kode-barang');
            const jumlah = document.getElementById('jumlah_obat_alkes').value.trim();

            if (!namaObat || !jumlah) {
                alert("Harap pilih obat dan masukkan jumlah.");
                return;
            }

            if (isNaN(jumlah) || parseInt(jumlah) <= 0) {
                alert("Jumlah harus berupa angka lebih dari 0.");
                return;
            }

            // Tambahkan baris ke tabel
            const tbody = document.querySelector('#tabel-obat tbody');
            const row = document.createElement('tr');

            row.innerHTML = `
                <td style="width: 5%;">${nomorUrut}</td>
                <td>${kodeObat}</td>
                <td>${namaObat}</td>
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
                    nama_obat: cells[2].textContent,
                    jumlah: cells[3].textContent
                };
                tableData.push(data);
            });

            // Perbarui input hidden dengan data tabel dalam format JSON
            document.getElementById('data_tabel_request').value = JSON.stringify(tableData);

            // Reset input
            $('#nama_obat_alkes').val(null).trigger('change');
            $('#jumlah_obat_alkes').val('');
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
            fetch(`/api/data-master-gudang/request/getDetails/${kodeRequest}`)
                .then(response => response.json())
                .then(data => {
                    // Set judul
                    const modalTitle = document.getElementById('modal-title-text');
                    modalTitle.textContent = `Details Request Dengan Kode Request : ${kodeRequest}`;

                    // Kosongkan dan isi ulang isi tabel
                    const table = document.getElementById('detailsTable');
                    const tableBody = table.querySelector('tbody');
                    tableBody.innerHTML = '';

                    data.details.forEach((detail, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="text-center">${index + 1}</td>
                            <td class="text-center">${detail.kode_obat_alkes}</td>
                            <td class="text-center">${detail.nama_obat_alkes}</td>
                            <td class="text-center">${detail.qty}</td>
                        `;
                        tableBody.appendChild(row);
                    });

                    // Tampilkan modal
                    $('#detailsModal').modal('show');
                })
                .catch(error => {
                    console.error('Error fetching details:', error);
                });
        }

        function detailDataKirim(kodeRequest) {
            // Set judul modal
            $('#modal-title-text').text('Details untuk Kode Request: ' + kodeRequest);

            // Kosongkan isi tabel dulu
            let tbody = $('#approveDetailsTable tbody');
            tbody.empty();

            // Tampilkan modal
            $('#approveDetailsModal').modal('show');

            // Panggil API
            $.ajax({
                url: '/api/data-master-gudang/request/detailsAprroval/' + encodeURIComponent(kodeRequest),
                method: 'GET',
                success: function(response) {
                    let data = response.details; // Ambil array dari properti 'details'

                    if (data.length === 0) {
                        tbody.append('<tr><td colspan="6" class="text-center">Data tidak ditemukan</td></tr>');
                        return;
                    }

                    data.forEach(function(item, index) {
                        let row = `
                            <tr>
                                <td class="text-center">${index + 1}</td>
                                <td class="text-center">${item.kode_obat_alkes}</td>
                                <td>${item.nama_obat_alkes}</td>
                                <td class="text-center">${item.qty}</td>
                                <td class="text-center">${item.expired}</td>
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
                    $.ajax({
                        url: `/api/data-master-gudang/request/terimaData/${id}`,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            user_id: CURRENT_USER_ID,
                            user_name: CURRENT_USER_NAME
                        },
                        success: function(response) {
                            Swal.fire(
                                'Berhasil!',
                                response.message,
                                'success'
                            );

                            // REFRESH tabel di dalam modal
                            detailDataKirim(kodeRequest);
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menyimpan.',
                                'error'
                            );
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
                    $.ajax({
                        url: `/api/data-master-gudang/request/tolakData/${id}`,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            user_id: CURRENT_USER_ID,
                            user_name: CURRENT_USER_NAME
                        },
                        success: function(response) {
                            Swal.fire(
                                'Ditolak!',
                                response.message,
                                'success'
                            );

                            // Refresh isi tabel
                            detailDataKirim(kodeRequest);
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menolak data.',
                                'error'
                            );
                        }
                    });
                }
            });
        }




    </script>
@endsection
