@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <form id="addFormpembelian" action="{{ route('inventaris_pembelian_utama.add') }}" method="POST">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-10">
                            <h1 class="m-0">Pembelian Inventaris Gudang Utama</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-2 d-flex justify-content-end">
                            <input type="text" class="form-control" id="kode_pembelian_inventaris" name="kode_pembelian_inventaris" readonly>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-4">
                            <div class="card">
                                <div class="card-body">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="barang_investasi">Barang Inventaris</label>
                                            <select class="form-control select2bs4" id="barang_investasi" name="barang_investasi" style="width: 100%;">
                                                <option value="" disabled selected>-- Pilih Data --</option>
                                                @foreach ($inventaris as $inventarisData)
                                                    <option value="{{ $inventarisData->nama_barang }}"
                                                        data-kode_barang="{{ $inventarisData->kode_barang }}"
                                                        data-kategori_barang="{{ $inventarisData->kategori_barang }}"
                                                        data-satuan_barang="{{ $inventarisData->satuan_barang }}"
                                                        data-jenis_barang="{{ $inventarisData->jenis_barang }}"
                                                        data-masa_waktu_barang="{{ $inventarisData->masa_pakai_barang }}"
                                                        data-waktu_barang="{{ $inventarisData->masa_pakai_waktu_barang }}"
                                                        data-deskripsi_barang="{{ $inventarisData->deskripsi_barang }}">
                                                        {{ $inventarisData->nama_barang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-7">
                                            <label for="qty_investasi">Qty Pembelian</label>
                                            <input type="text" class="form-control" id="qty_investasi" name="qty_investasi" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);" placeholder="Masukkan qty">
                                        </div>
                                        <div class="col-md-5">
                                            <label>&nbsp;</label>
                                            <input type="text" class="form-control" id="satuan_investasi" name="satuan_investasi" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="harga_barang_investasi">Harga Pembelian</label>
                                            <input type="text" class="form-control" id="harga_barang_investasi" name="harga_barang_investasi">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="lokasi_barang">Lokasi Barang</label>
                                            <input type="text" class="form-control" id="lokasi_barang" name="lokasi_barang" placeholder="Lokasi Barang Digunakan">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="kondisi_barang_investasi">Kondisi Barang</label>
                                            <select class="form-control" id="kondisi_barang_investasi" name="kondisi_barang_investasi" style="width: 100%;">
                                                <option value="" disabled selected>-- Pilih Data --</option>
                                                <option value="Baik">Baik</option>
                                                <option value="Rusak Ringan">Rusak Ringan</option>
                                                <option value="Rusak Sedang">Rusak Sedang</option>
                                                <option value="Rusak Berat">Rusak Berat</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="masa_akhir_penggunaan_barang">Tgl Akhir Penggunaan</label>
                                            <input type="text" class="form-control" id="masa_akhir_penggunaan_barang" name="masa_akhir_penggunaan_barang" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tanggal_pembelian_barang">Tgl Pembelian Barang</label>
                                            <input type="date" class="form-control" id="tanggal_pembelian_barang" name="tanggal_pembelian_barang" onclick="this.showPicker && this.showPicker()">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="detail_barang">Deskripsi Barang</label>
                                            <textarea class="form-control" id="detail_barang" name="detail_barang" rows="2" placeholder="Masukkan Spesifikasi / Deskripsi barang"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button type="button" onclick="deleteSelectedRows()" class="btn btn-danger btn-block">Hapus</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button" onclick="addNewDataToTabel()" class="btn btn-primary btn-block">Tambah</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="card">
                                <div class="card-body">
                                    <input type="hidden" id="data_hidden" name="data_hidden">
                                    <div style="border: 2px solid black; padding: 10px; width: 100%; max-width: auto; min-height: 480px;">
                                        <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                                            <table class="table" id="dataTable" style="border: none;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;">No</th>
                                                        <th style="width: 25%;">Nama</th>
                                                        <th style="width: 10%;">Qty</th>
                                                        <th style="width: 15%;">Harga</th>
                                                        <th style="width: 15%;">Lokasi</th>
                                                        <th style="width: 15%;">Kondisi</th>
                                                        <th style="width: 15%;">Tgl Akhir Pakai</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- DATA TERISI OTOMATIS NANTI --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- Total -->
                                    <div class="form-group row mt-4">
                                        <div class="col-md-2">
                                            <h4 style="font-weight: bold">Total</h4>
                                        </div>
                                        <div class="col-md-1">
                                            <h4 style="font-weight: bold">:</h4>
                                        </div>
                                        <div class="col-md-4">
                                            <h4 style="font-weight: bold" id="total_keseluruhan"></h4>
                                            <input type="hidden" id="total_keseluruhan_input" name="total_keseluruhan_input">
                                        </div>
                                    </div>

                                    <!-- Penerima -->
                                    <div class="form-group row mb-0 d-flex align-items-center">
                                        <div class="col-md-2">
                                            <label for="penerima_barang">Penerima Barang</label>
                                        </div>
                                        <div class="col-md-1">
                                            <label>:</label>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control select2bs4 mt-2" id="penerima_barang" name="penerima_barang">
                                                <option value="" disabled selected>Pilih Penerima</option>
                                                @foreach ($user as $userData)
                                                    <option value="{{ $userData->name }}">{{ $userData->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-5 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save mr-1"></i> Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </form>
    </div>

    <script>
        $('#addFormpembelian').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addFormpembelian').modal('hide');
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
            });
        });

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
            }).mask("#harga_barang_investasi");
        });

        $(document).ready(function () {
            Swal.fire({
                icon: 'info',
                title: 'Mengambil kode pembelian...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '/api/generate-kode-pembelian-inventaris',
                method: 'GET',
                success: function(response) {
                    Swal.close(); // Tutup loading swal
                    if (response.success) {
                        // Isi input nomor faktur dengan nomor yang dihasilkan
                        $('#kode_pembelian_inventaris').val(response.kode);
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Gagal!',
                            text: 'Gagal mendapatkan kode pembelian.'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan dalam mengambil nomor faktur.'
                    });
                }
            });

            $('#barang_investasi').on('change', function () {
                const selected = $(this).find(':selected');

                // Jika tidak ada pilihan valid, keluar dari function
                if (!selected || selected.val() === null || selected.val() === '') {
                    return;
                }

                const satuan = selected.data('satuan_barang');
                const jumlah = parseInt(selected.data('masa_waktu_barang'));
                const waktu = selected.data('waktu_barang'); // Tahun, Bulan, Minggu, Hari

                // Tampilkan satuan barang
                $('#satuan_investasi').val(satuan);

                // Hitung masa akhir penggunaan
                let today = new Date();
                let akhir = new Date(today);

                switch (waktu.toLowerCase()) {
                    case 'tahun':
                        akhir.setFullYear(akhir.getFullYear() + jumlah);
                        break;
                    case 'bulan':
                        akhir.setMonth(akhir.getMonth() + jumlah);
                        break;
                    case 'minggu':
                        akhir.setDate(akhir.getDate() + (jumlah * 7));
                        break;
                    case 'hari':
                        akhir.setDate(akhir.getDate() + jumlah);
                        break;
                    default:
                        console.warn('Satuan waktu tidak dikenal:', waktu);
                }

                // Format ke yyyy-mm-dd
                const formatted = akhir.toISOString().split('T')[0];
                $('#masa_akhir_penggunaan_barang').val(formatted);
            });
        });
    </script>

    <script>
        let counter = 1;
        let dataInventaris = [];

        function addNewDataToTabel() {
            const selectElement = document.getElementById("barang_investasi");
            const selectedOption = selectElement.options[selectElement.selectedIndex];

            const nama = selectedOption.value;
            const qty = document.getElementById("qty_investasi").value;
            const harga = document.getElementById("harga_barang_investasi").value;
            const lokasi = document.getElementById("lokasi_barang").value;
            const kondisi = document.getElementById("kondisi_barang_investasi").value;
            const tglAkhir = document.getElementById("masa_akhir_penggunaan_barang").value;
            const tglPembelian = document.getElementById("tanggal_pembelian_barang").value;
            const detail = document.getElementById("detail_barang").value;

            // Ambil semua data-* attribute
            const kode = selectedOption.getAttribute("data-kode_barang");
            const kategori = selectedOption.getAttribute("data-kategori_barang");
            const satuan = selectedOption.getAttribute("data-satuan_barang");
            const jenis = selectedOption.getAttribute("data-jenis_barang");
            const masaPakai = selectedOption.getAttribute("data-masa_waktu_barang");
            const waktuPakai = selectedOption.getAttribute("data-waktu_barang");
            const deskripsi = selectedOption.getAttribute("data-deskripsi_barang");

            if (!nama || !qty || !harga || !lokasi || !kondisi || !tglAkhir || !tglPembelian ||!deskripsi) {
                alert("Semua data wajib diisi!");
                return;
            }

            // Cek duplikat berdasarkan kode_barang
            const isDuplicate = dataInventaris.some(item => item.kode_barang === kode && item.nama_barang === nama);

            if (isDuplicate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplikat Data!',
                    text: 'Barang dengan kode dan nama yang sama sudah ditambahkan.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    clearForm();
                });
                return;
            }


            const hargaRaw = parseInt(harga.replace(/[^0-9]/g, ''));
            let total = hargaRaw * qty;
            let totalFormatted = 'Rp ' + total.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            })

            // Tambah baris ke tabel
            const tbody = document.querySelector("#dataTable tbody");
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${counter}</td>
                <td>${nama}</td>
                <td>${qty}</td>
                <td>${totalFormatted}</td>
                <td>${lokasi}</td>
                <td>${kondisi}</td>
                <td>${tglAkhir}</td>
            `;
            tbody.appendChild(row);

            // Tambah ke array untuk JSON (termasuk semua data-* yang diminta)
            dataInventaris.push({
                nama_barang: nama,
                kode_barang: kode,
                kategori_barang: kategori,
                satuan_barang: satuan,
                jenis_barang: jenis,
                masa_pakai_barang: masaPakai,
                masa_pakai_waktu_barang: waktuPakai,
                deskripsi_barang: deskripsi,
                qty_pembelian: qty,
                harga_pembelian: total,
                harga_satuan: hargaRaw,
                lokasi_barang: lokasi,
                kondisi_barang: kondisi,
                masa_akhir_penggunaan: tglAkhir,
                tanggal_pembelian: tglPembelian,
                detail_barang: detail,
            });

            // Simpan ke input hidden
            document.getElementById("data_hidden").value = JSON.stringify(dataInventaris);

            console.log(JSON.stringify(dataInventaris));

            counter++;
            hitungTotalSubTotalRupiah();
            clearForm();
        }

        function clearForm() {
            $('#barang_investasi').val(null).trigger('change');
            document.getElementById("qty_investasi").value = "";
            document.getElementById("harga_barang_investasi").value = "";
            document.getElementById("lokasi_barang").value = "";
            document.getElementById("kondisi_barang_investasi").value = "";
            document.getElementById("masa_akhir_penggunaan_barang").value = "";
            document.getElementById("tanggal_pembelian_barang").value = "";
            document.getElementById("satuan_investasi").value = "";
            document.getElementById("detail_barang").value = "";
        }

        const style = document.createElement('style');
        style.innerHTML = `
            .selected-row {
                background-color: #007bff !important;
                color: white;
            }
        `;
        document.head.appendChild(style);

        // Toggle row selection
        document.addEventListener("click", function (e) {
            const row = e.target.closest("#dataTable tbody tr");
            if (row) {
                // Jika sudah selected, maka unselect
                if (row.classList.contains("selected-row")) {
                    row.classList.remove("selected-row");
                } else {
                    // Hapus semua dulu
                    document.querySelectorAll("#dataTable tbody tr").forEach(tr => tr.classList.remove("selected-row"));
                    // Tambahkan ke baris yang diklik
                    row.classList.add("selected-row");
                }
            }
        });

        function deleteSelectedRows() {
            const selectedRow = document.querySelector("#dataTable tbody tr.selected-row");
            if (!selectedRow) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops!',
                    text: 'Pilih baris yang ingin dihapus terlebih dahulu.',
                    confirmButtonColor: '#3085d6',
                });
                return;
            }

            const namaBarang = selectedRow.children[1].textContent;

            // Hapus dari array
            dataInventaris = dataInventaris.filter(item => item.nama_barang !== namaBarang);

            // Update input hidden
            document.getElementById("data_hidden").value = JSON.stringify(dataInventaris);

            // Hapus dari tabel
            selectedRow.remove();

            // Perbarui penomoran No
            document.querySelectorAll("#dataTable tbody tr").forEach((row, index) => {
                row.children[0].textContent = index + 1;
            });

            counter = dataInventaris.length + 1;

            console.log('update : ',JSON.stringify(dataInventaris));
            hitungTotalSubTotalRupiah();
        }

        function hitungTotalSubTotalRupiah() {
            let total = 0;

            $('#dataTable tbody tr').each(function () {
                const teks = $(this).find('td').eq(3).text().trim();
                const angka = parseInt(teks.replace(/[^\d]/g, ''));
                if (!isNaN(angka)) {
                    total += angka;
                }
            });

            const totalFormatted = 'Rp ' + total.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            $('#total_keseluruhan').text(totalFormatted);
            $('#total_keseluruhan_input').val(total);
        }
    </script>


@endsection











