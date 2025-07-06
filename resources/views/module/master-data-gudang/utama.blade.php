
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
                                    <input type="hidden" id="nama_klinik_additional" name="nama_klinik_additional">
                                    <input type="hidden" id="kode_request_additional" name="kode_request_additional">
                                    <input type="hidden" id="tanggal_request_additional" name="tanggal_request_additional">
                                    <div class="col-md-8">
                                        <label for="obat_manual ">Pilih Obat:</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="obat_manual" name="obat_manual">
                                            <option value="" disabled selected>Pilih Obat</option>
                                            @foreach ($dabar as $dabarData)
                                                <option value="{{ $dabarData->nama_barang }}" data-kode-barang="{{ $dabarData->kode_barang }}">
                                                    {{ $dabarData->nama_barang }}
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
        const CURRENT_USER_ID = {{ auth()->user()->id }};
        const CURRENT_USER_NAME = "{{ auth()->user()->name }}";

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

            fetch(`/api/data-master-gudang/utama/getDetails/${kodeRequest}`)
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
                            document.getElementById('nama_klinik_additional').value = klinik;
                            document.getElementById('kode_request_additional').value = kodeRequest;
                            document.getElementById('tanggal_request_additional').value = tanggal;

                            $('#detailModal').modal('hide');

                            const approvalTableBody = document.querySelector('#approval_permintaan tbody');
                            approvalTableBody.innerHTML = '';

                            fullData.forEach(item => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td class="text-center">${item.kode_obat_alkes}</td>
                                    <td class="text-center">${item.nama_obat_alkes}</td>
                                    <td class="text-center">Rp ${parseFloat(item.harga_dasar).toLocaleString('id-ID', {
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

        function addObatManual() {
            const select = document.getElementById('obat_manual');
            const selectedOption = select.options[select.selectedIndex];

            const namaObat = selectedOption.value;
            const kodeObat = selectedOption.getAttribute('data-kode-barang');
            const jumlah = document.getElementById('qty_manual').value;

            // VALIDASI 1: Harus ada input
            if (!namaObat || !jumlah || jumlah <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data tidak lengkap',
                    text: 'Silakan pilih obat dan isi jumlah yang valid.'
                });
                return;
            }

            // VALIDASI 2: Tabel harus punya setidaknya 1 baris (bisa dianggap dummy baris sebelumnya)
            const tbody = document.querySelector('#approval_permintaan tbody');
            if (tbody.children.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tabel kosong',
                    text: 'Tabel harus memiliki data awal terlebih dahulu.'
                });
                return;
            }

            // VALIDASI 3: Cek duplikasi berdasarkan KODE OBAT
            const existingRows = tbody.querySelectorAll('tr');
            for (let row of existingRows) {
                const kodeInTable = row.cells[0].innerText.trim();
                if (kodeInTable === kodeObat) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Duplikasi Obat',
                        text: 'Obat dengan kode tersebut sudah ditambahkan.'
                    });
                    return;
                }
            }

            // AMBIL HARGA DASAR VIA AJAX
            $.ajax({
                url: `/api/data-master-gudang/utama/getHargaDasar/${kodeObat}`,
                method: 'GET'
            }).then(response => {
                const hargaDasar = response.harga_dasar ?? 0;

                // BUAT BARIS BARU
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td class="text-center">${kodeObat}</td>
                    <td class="text-center">${namaObat}</td>
                    <td class="text-center">Rp ${parseFloat(hargaDasar).toLocaleString('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    })}
                    </td>
                    <td class="text-center">${jumlah}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;

                tbody.appendChild(newRow);

                // RESET INPUT
                $('#obat_manual').val('').trigger('change');
                document.getElementById('qty_manual').value = '';

                Swal.fire({
                    icon: 'success',
                    title: 'Obat ditambahkan',
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

            console.log("== INPUT HIDDEN ==");
            console.log("Nama Klinik:", namaKlinik);
            console.log("Kode Request:", kodeRequest);
            console.log("Tanggal Request:", tanggalRequest);

            // Ambil data dari tabel
            const table = document.getElementById("approval_permintaan").getElementsByTagName("tbody")[0];
            const rows = table.getElementsByTagName("tr");
            const items = [];

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName("td");

                const item = {
                    kode_obat: cells[0]?.innerText.trim() || "",
                    nama_obat: cells[1]?.innerText.trim() || "",
                    harga_dasar: cells[2]?.innerText.trim() || "",
                    jumlah: cells[3]?.innerText.trim() || ""
                };

                items.push(item);
            }

            // Ubah ke JSON string
            const jsonData = JSON.stringify(items, null, 2);

            console.log("== DATA TABEL (JSON) ==");
            console.log(jsonData);

            $.ajax({
                url: '/api/data-master-gudang/utama/proses-permintaan',
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
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.open('/api/data-master-gudang/pdf/' + response.data);
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



    </script>

@endsection
