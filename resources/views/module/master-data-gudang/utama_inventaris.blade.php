
@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Gudang Utama Inventaris</h1>
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
                <div class="row">
                    <!-- Kolom Kiri - Daftar Permintaan -->
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Daftar Permintaan Inventaris</h3>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="filterKlinik">Filter Klinik:</label>
                                        <select class="form-control" id="filterKlinik">
                                            <option value="">Semua Klinik</option>
                                            <option value="Klinik Balaraja">Klinik Balaraja</option>
                                            <option value="Klinik Jaya">Klinik Jaya</option>
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
                                <h3 class="card-title">Form Permintaan Inventaris</h3>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <input type="hidden" id="nama_klinik_additional" name="nama_klinik_additional">
                                    <input type="hidden" id="kode_request_additional" name="kode_request_additional">
                                    <input type="hidden" id="tanggal_request_additional" name="tanggal_request_additional">
                                    <div class="col-md-8">
                                        <label for="barang_manual ">Pilih Data:</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="barang_manual" name="barang_manual">
                                            <option value="" disabled selected>Pilih Data</option>
                                            @foreach ($inventaris as $inventarisData)
                                                <option value="{{ $inventarisData->nama_barang }}" data-kode-barang="{{ $inventarisData->kode_barang }}">
                                                    {{ $inventarisData->nama_barang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="qty_manual">Jumlah:</label>
                                        <div class="d-flex">
                                            <input type="number" class="form-control" id="qty_manual" name="qty_manual" placeholder="Jumlah">
                                            <button class="btn btn-info ml-2" onclick="addObatManual()">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-bordered table-striped" id="approval_permintaan">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode Inventaris</th>
                                            <th class="text-center">Nama Inventaris</th>
                                            <th class="text-center">Jenis Inventaris</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Item akan ditambahkan secara dinamis -->
                                    </tbody>
                                </table>

                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-success" onclick="kirimpermintaan()">
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
                    <h5 class="modal-title" id="detailModalLabel">Detail Permintaan Inventaris</h5>
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
                                    <th class="text-center" width="15%">Kode Inventaris</th>
                                    <th class="text-center" width="50%">Nama Inventaris</th>
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
        const CURRENT_USER_ID = {{ auth()->user()->id }};
        const CURRENT_USER_NAME = "{{ auth()->user()->name }}";

        function openModal(kodeRequest) {
            // Tampilkan loading sebelum proses fetch
            Swal.fire({
                icon: 'info',
                title: 'Memuat data...',
                text: 'Harap tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Cari elemen <tr> berdasarkan kodeRequest
            const row = document.querySelector(`tr[ondblclick="openModal('${kodeRequest}')"]`);
            const kodeKlinik = row.dataset.kodeKlinik;
            const namaKlinik = row.dataset.namaKlinik;
            const tanggalInput = row.dataset.tanggalInput;

            // Isi elemen di modal
            document.getElementById('detailKlinik').textContent = namaKlinik;
            document.getElementById('detailKodeRequest').textContent = kodeRequest;
            document.getElementById('detailTanggal').textContent = tanggalInput;
            document.getElementById('detail_klinik').value = namaKlinik;
            document.getElementById('detail_kode_request').value = kodeRequest;
            document.getElementById('detail_tanggal').value = tanggalInput;

            // Fetch detail data dari backend
            fetch(`/api/data-master-gudang/utama/inventaris/getDetails/${kodeRequest}`)
                .then(response => response.json())
                .then(data => {
                    Swal.close(); // Tutup loading Swal saat data berhasil diterima

                    // Kosongkan dan isi ulang isi tabel
                    const table = document.getElementById('detailTable');
                    const tableBody = table.querySelector('tbody');
                    tableBody.innerHTML = '';

                    // Simpan JSON ke input hidden
                    const hiddenInput = document.getElementById('detail_data');
                    const jsonData = JSON.stringify(data.details);
                    hiddenInput.value = jsonData;

                    // Tambahkan data ke dalam tabel
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
                    $('#detailModal').modal('show');
                })
                .catch(error => {
                    Swal.close(); // Tutup loading Swal jika error
                    console.error('Error fetching details:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Data',
                        text: 'Terjadi kesalahan saat mengambil data detail.'
                    });
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

            // Tampilkan loading Swal sebelum request
            Swal.fire({
                icon: 'info',
                title: 'Mengirim data...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('inventarisutama.konfirmasi') }}",
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
                            url: `/api/data-master-gudang/utama/inventaris/getData/${item.kode_barang}`,
                            method: 'GET'
                        }).then(dataResponse => {
                            const dataDasar = dataResponse.harga_dasar || {};
                            return {
                                ...item,
                                jenis_barang: dataDasar.jenis_barang ?? 0,
                                kategori_barang: dataDasar.kategori_barang ?? 0,
                                satuan_barang: dataDasar.satuan_barang ?? 0,
                            };
                        }).catch(() => {
                            return {
                                ...item,
                                jenis_barang: 0,
                                kategori_barang: 0,
                                satuan_barang: 0,
                            };
                        });
                    });

                    Promise.all(promises).then(fullData => {
                        Swal.close(); // Tutup swal loading
                        $('#detailModal').modal('hide');

                        Swal.fire({
                            title: 'Berhasil dikonfirmasi!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            document.getElementById('nama_klinik_additional').value = klinik;
                            document.getElementById('kode_request_additional').value = kodeRequest;
                            document.getElementById('tanggal_request_additional').value = tanggal;

                            const approvalTableBody = document.querySelector('#approval_permintaan tbody');
                            approvalTableBody.innerHTML = '';

                            fullData.forEach(item => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td class="text-center">${item.kode_barang}</td>
                                    <td class="text-center">${item.nama_barang}</td>
                                    <td class="text-center">${item.jenis_barang}</td>
                                    <td class="text-center">${item.qty}</td>
                                    <td class="text-center d-none">${item.kategori_barang}</td>
                                    <td class="text-center d-none">${item.satuan_barang}</td>
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

        function addObatManual() {
            const select = document.getElementById('barang_manual');
            const selectedOption = select.options[select.selectedIndex];

            const namaBarang = selectedOption.value;
            const kodeBarang = selectedOption.getAttribute('data-kode-barang');
            const jumlah = document.getElementById('qty_manual').value;

            // VALIDASI 1: Harus ada input
            if (!namaBarang || !jumlah || jumlah <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data tidak lengkap',
                    text: 'Silakan pilih data inventaris dan isi jumlah yang valid.'
                });
                return;
            }

            // VALIDASI 2: Tabel harus punya setidaknya 1 baris (bisa dianggap dummy baris sebelumnya)
            const tbody = document.querySelector('#approval_permintaan tbody');
            if (tbody.children.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tabel kosong',
                    text: 'Tabel harus memiliki data request terlebih dahulu.'
                });

                $('#barang_manual').val('').trigger('change');
                document.getElementById('qty_manual').value = '';

                return;
            }

            // VALIDASI 3: Cek duplikasi berdasarkan KODE OBAT
            const existingRows = tbody.querySelectorAll('tr');
            for (let row of existingRows) {
                const kodeInTable = row.cells[0].innerText.trim();
                if (kodeInTable === kodeBarang) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Duplikasi Obat',
                        text: 'Inventaris dengan kode tersebut sudah ditambahkan.'
                    });

                    $('#barang_manual').val('').trigger('change');
                    document.getElementById('qty_manual').value = '';

                    return;
                }
            }

            Swal.fire({
                icon: 'info',
                title: 'Memuat data...',
                text: 'Harap tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // BUAT BARIS BARU
            $.ajax({
                url: `/api/data-master-gudang/utama/inventaris/getData/${kodeBarang}`,
                method: 'GET'
            }).then(response => {
                Swal.close(); // Tutup loading Swal saat data berhasil diterima

                const data = response.harga_dasar || {};
                const jenis = data.jenis_barang ?? 0;
                const kategori = data.kategori_barang ?? 0;
                const satuan = data.satuan_barang ?? 0;

                // BUAT BARIS BARU
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td class="text-center">${kodeBarang}</td>
                    <td class="text-center">${namaBarang}</td>
                    <td class="text-center">${jenis}</td>
                    <td class="text-center">${jumlah}</td>
                    <td class="text-center d-none">${kategori}</td>
                    <td class="text-center d-none">${satuan}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;

                tbody.appendChild(newRow);

                // RESET INPUT
                $('#barang_manual').val('').trigger('change');
                document.getElementById('qty_manual').value = '';

                Swal.fire({
                    icon: 'success',
                    title: 'Inventaris ditambahkan',
                    showConfirmButton: false,
                    timer: 1000
                });
            }).catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengambil Harga',
                    text: 'Terjadi kesalahan saat mengambil harga dasar.'
                });
            });
        }

        function kirimpermintaan() {
            // Ambil nilai dari input hidden
            const namaKlinik = document.getElementById("nama_klinik_additional").value;
            const kodeRequest = document.getElementById("kode_request_additional").value;
            const tanggalRequest = document.getElementById("tanggal_request_additional").value;

            // Ambil data dari tabel
            const table = document.getElementById("approval_permintaan").getElementsByTagName("tbody")[0];
            const rows = table.getElementsByTagName("tr");
            const items = [];

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName("td");

                const item = {
                    kode_barang: cells[0]?.innerText.trim() || "",
                    nama_barang: cells[1]?.innerText.trim() || "",
                    jenis: cells[2]?.innerText.trim() || "",
                    jumlah: cells[3]?.innerText.trim() || "",
                    kategori: cells[4]?.innerText.trim() || "",
                    satuan: cells[5]?.innerText.trim() || ""
                };

                items.push(item);
            }

            // Ubah ke JSON string
            const jsonData = JSON.stringify(items, null, 2);

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin memproses permintaan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, proses!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
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
                        url: '/api/data-master-gudang/utama/inventaris/proses-permintaan',
                        type: 'POST',
                        data: {
                            nama_klinik: namaKlinik,
                            kode_request: kodeRequest,
                            tanggal_request: tanggalRequest,
                            items_json: jsonData,
                            user_id: CURRENT_USER_ID,
                            user_name: CURRENT_USER_NAME
                        },
                        success: function(response) {
                            Swal.close(); // Tutup loading

                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.open('/api/data-master-gudang/pdf/inventaris/' + response.data);
                                    location.reload(); // Reload halaman setelah klik OK
                                });
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Peringatan!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Terjadi kesalahan saat mengirim permintaan.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMsg,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }



    </script>

@endsection
