
@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Gudang Utama</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Gudang Utama</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Kolom Kiri - Daftar Permintaan -->
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Daftar Permintaan Obat</h3>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="filterKlinik">Filter Klinik:</label>
                                        <select class="form-control" id="filterKlinik">
                                            <option value="">Semua Klinik</option>
                                            <option value="Klinik Balaraja">Klinik Balaraja</option>
                                            <option value="Klinik Jaya">Klinik Jaya</option>
                                            <option value="Klinik Sentosa">Klinik Sentosa</option>
                                            <option value="Klinik Makmur">Klinik Makmur</option>
                                        </select>
                                    </div>
                                </div>

                                <table class="table table-bordered table-striped" id="permintaanTable">
                                    <thead>
                                        <tr>
                                            <th>Klinik</th>
                                            <th>Kode</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($request as $requestData)
                                        <tr class="table-row" ondblclick="openModal('{{ $requestData->kode_request }}')"
                                                data-kode-klinik="{{ $requestData->kode_klinik }}"
                                                data-nama-klinik="{{ $requestData->nama_klinik }}"
                                                data-tanggal-input="{{ $requestData->tanggal_input }}"
                                                style="background-color:
                                                    {{ $requestData->status == 0 ? '#f8d7da' :
                                                    ($requestData->status == 1 ? '#fff3cd' :
                                                    ($requestData->status == 2 ? '#d4edda' : 'transparent')) }};">
                                                <td>{{ $requestData->nama_klinik }}</td>
                                                <td>{{ $requestData->kode_request }}</td>
                                                <td>{{ $requestData->tanggal_input }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan - Form Permintaan -->
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Form Permintaan Obat</h3>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="obatSelect ">Pilih Obat:</label>
                                        <select class="form-control select2bs4" id="obatSelect">
                                            <option value="">Pilih Obat</option>
                                            <!-- Opsi obat akan diisi secara dinamis -->
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="jumlahObat">Jumlah:</label>
                                        <div class="d-flex">
                                            <input type="number" class="form-control" id="jumlahObat" placeholder="Jumlah" min="1" value="1">
                                            <button class="btn btn-info ml-2" id="addObatBtn">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-bordered table-striped" id="approval_permintaan">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode Obat</th>
                                            <th class="text-center">Nama Obat</th>
                                            <th class="text-center">Harga Dasar</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Item akan ditambahkan secara dinamis -->
                                    </tbody>
                                </table>

                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-success" id="konfirmasiRequestBtn">
                                        <i class="fas fa-save"></i> Simpan Permintaan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Detail Obat -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailModalLabel">Detail Permintaan Obat</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{-- <form id="confirmFormRequest" action="{{ route('gudangutama.konfirmasi') }}" method="POST">
                    @csrf --}}
                    <div class="modal-body">
                        <input type="hidden" id="detail_data" name="detail_data">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Klinik:</strong> <span id="detailKlinik"></span>
                                <input type="hidden" id="detail_klinik" name="detail_klinik">
                            </div>
                            <div class="col-md-6">
                                <strong>Kode Permintaan:</strong> <span id="detailKodeRequest"></span>
                                <input type="hidden" id="detail_kode_request" name="detail_kode_request">
                            </div>
                            <div class="col-md-3">
                                <strong>Tanggal:</strong> <span id="detailTanggal"></span>
                                <input type="hidden" id="detail_tanggal" name="detail_tanggal">
                            </div>
                        </div>

                        <table id="detailTable" class="table table-bordered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center" width="15%">Kode Obat</th>
                                    <th class="text-center" width="50%">Nama Obat</th>
                                    <th class="text-center" width="15%">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Detail item akan ditambahkan secara dinamis -->
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Tutup
                        </button>
                        <button type="button" class="btn btn-success" onclick="konfirmasi_permintaan()">
                            <i class="fas fa-check"></i> Konfirmasi Permintaan
                        </button>
                    </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>

    <script>
        function openModal(kodeRequest) {
            // Cari elemen <tr> yang dipanggil saat double click
            const row = document.querySelector(`tr[ondblclick="openModal('${kodeRequest}')"]`);

            // Ambil data-* dari row tersebut
            const kodeKlinik = row.dataset.kodeKlinik;
            const namaKlinik = row.dataset.namaKlinik;
            const tanggalInput = row.dataset.tanggalInput;

            // Isi elemen detail di modal
            document.getElementById('detailKlinik').textContent = namaKlinik;
            document.getElementById('detailKodeRequest').textContent = kodeRequest;
            document.getElementById('detailTanggal').textContent = tanggalInput;
            document.getElementById('detail_klinik').value = namaKlinik;
            document.getElementById('detail_kode_request').value = kodeRequest;
            document.getElementById('detail_tanggal').value = tanggalInput;

            fetch(`/api/data-master-gudang/request/getDetails/${kodeRequest}`)
                .then(response => response.json())
                .then(data => {
                    // Kosongkan dan isi ulang isi tabel
                    const table = document.getElementById('detailTable');
                    const tableBody = table.querySelector('tbody');
                    tableBody.innerHTML = '';

                     // Input hidden untuk simpan data JSON
                    const hiddenInput = document.getElementById('detail_data');
                    const jsonData = JSON.stringify(data.details);
                    hiddenInput.value = jsonData;

                    // Log hasil JSON
                    console.log('JSON Detail Data:', jsonData);

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
                    $('#detailModal').modal('show');
                })
                .catch(error => {
                    console.error('Error fetching details:', error);
                });
        }

        function konfirmasi_permintaan() {
            const detailData = document.getElementById('detail_data').value;
            const klinik = document.getElementById('detail_klinik').value;
            const kodeRequest = document.getElementById('detail_kode_request').value;
            const tanggal = document.getElementById('detail_tanggal').value;

            let parsedData = [];
            try {
                parsedData = JSON.parse(detailData);
            } catch (e) {
                Swal.fire('Error', 'Data tidak valid!', 'error');
                return;
            }

            $.ajax({
                url: "{{ route('gudangutama.konfirmasi') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    detail_data: detailData,
                    detail_klinik: klinik,
                    detail_kode_request: kodeRequest,
                    detail_tanggal: tanggal
                },
                success: function (response) {
                    // Lanjut hanya kalau konfirmasi sukses
                    let promises = parsedData.map(item => {
                        return $.ajax({
                            url: `/api/data-master-gudang/utama/getHargaDasar/${item.kode_obat_alkes}`,
                            method: 'GET'
                        }).then(hargaResponse => {
                            return {
                                ...item,
                                harga_dasar: hargaResponse.harga_dasar ?? 0
                            };
                        }).catch(() => {
                            return {
                                ...item,
                                harga_dasar: 0
                            };
                        });
                    });

                    Promise.all(promises).then(fullData => {
                        Swal.fire({
                            title: 'Berhasil dikonfirmasi!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#detailModal').modal('hide');

                            const approvalTableBody = document.querySelector('#approval_permintaan tbody');
                            approvalTableBody.innerHTML = '';

                            fullData.forEach(item => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${item.kode_obat_alkes}</td>
                                    <td>${item.nama_obat_alkes}</td>
                                    <td class="text-end">Rp ${parseFloat(item.harga_dasar).toLocaleString('id-ID', {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    })}</td>
                                    <td class="text-center">${item.qty}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                `;
                                approvalTableBody.appendChild(row);
                            });
                        });
                    });
                },
                error: function (xhr) {
                    Swal.fire('Gagal', 'Terjadi kesalahan saat mengirim data.', 'error');
                    console.error(xhr.responseText);
                }
            });
        }




    </script>

@endsection
