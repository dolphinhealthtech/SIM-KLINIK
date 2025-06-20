@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Data Inventaris</h1>
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
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Data Inventaris</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addinventarisModal" >
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                    <!-- Tombol Export -->
                                    <a href="{{ route('inventaris.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>

                                    <!-- Tombol Import (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importinventarisModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                    <!-- Tombol Sinkron (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#singkroninventarisModal">
                                        <i class="fas fa-file-upload"></i> Singkron
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="inventaristabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode Inventaris</th>
                                            <th class="text-center">Nama Inventaris</th>
                                            <th class="text-center">Kategori Inventaris</th>
                                            <th class="text-center">Masa Pakai Inventaris</th>
                                            <th class="text-center">Deskripsi Inventaris</th>
                                            <th class="text-center" width="15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($inventaris as $inventarisdata)
                                            <tr>
                                                <td class="text-center">{{ $inventarisdata->kode_barang }}</td>
                                                <td class="text-center">{{ $inventarisdata->nama_barang }}</td>
                                                <td class="text-center">{{ $inventarisdata->kategori_barang }}</td>
                                                <td class="text-center">{{ $inventarisdata->masa_pakai_barang }} {{ $inventarisdata->masa_pakai_waktu_barang }}</td>
                                                <td class="text-center">{{ $inventarisdata->deskripsi_barang }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-inventaris"
                                                        data-toggle="modal" data-id="{{ $inventarisdata->id }}"
                                                        data-kode_barang="{{ $inventarisdata->kode_barang }}"
                                                        data-nama_barang="{{ $inventarisdata->nama_barang }}"
                                                        data-kategori_barang="{{ $inventarisdata->kategori_barang }}"
                                                        data-satuan_barang="{{ $inventarisdata->satuan_barang }}"
                                                        data-jenis_barang="{{ $inventarisdata->jenis_barang }}"
                                                        data-masa_pakai_barang="{{ $inventarisdata->masa_pakai_barang }}"
                                                        data-masa_pakai_waktu_barang="{{ $inventarisdata->masa_pakai_waktu_barang }}"
                                                        data-deskripsi_barang="{{ $inventarisdata->deskripsi_barang }}"
                                                        data-target="#editinventarisModal">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>

                                                    <a href="#" class="btn btn-danger btn-sm delete-data-goldar"
                                                        data-toggle="modal"data-id="{{ $inventarisdata->id }}"
                                                        data-nama-inventaris="{{ $inventarisdata->nama_barang }}"
                                                        data-target="#deleteinventarisModal">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    <!-- /.content -->
</div>

<!-- Modal Add Gudang Barang Input -->
<div class="modal fade" id="addinventarisModal" tabindex="-1" role="dialog" aria-labelledby="addinventarisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addinventarisModalLabel">Input Data Inventaris</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForminventaris" action="{{ route('inventaris.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <input type="hidden" class="form-control" id="kode_barang" name="kode_barang" value="321">
                        <div class="col-md-6">
                            <label for="nama_barang">Nama Inventaris</label>
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Masukkan Nama Barang" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kategori_barang">Kategori Inventaris</label>
                            <select class="form-control select2bs4" id="kategori_barang" name="kategori_barang" style="width: 100%;">
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @foreach ($kategori as $kategori_add)
                                    <option value="{{ $kategori_add->nama }}">{{ $kategori_add->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mt-2">
                            <label for="satuan_barang">Satuan Inventaris</label>
                            <select class="form-control select2bs4" id="satuan_barang" name="satuan_barang" style="width: 100%;">
                                <option value="" disabled selected>-- Pilih Satuan --</option>
                                @foreach ($satuan as $satuan_add)
                                    <option value="{{ $satuan_add->nama }}">{{ $satuan_add->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mt-2">
                            <label for="jenis_barang">Jenis Inventaris</label>
                            <select class="form-control" id="jenis_barang" name="jenis_barang" style="width: 100%;">
                                <option value="" disabled selected>-- Pilih Data --</option>
                                <option value="Inventaris">Inventaris</option>
                                <option value="Barang Habis Pakai">Barang Habis Pakai</option>
                            </select>
                        </div>
                        <div class="col-md-3 mt-2">
                            <label for="masa_pakai_barang">Masa Pakai</label>
                            <input type="text" class="form-control" id="masa_pakai_barang" name="masa_pakai_barang" maxlength="2" required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2);" placeholder="Masukkan angka masa pakai">
                        </div>
                        <div class="col-md-3 mt-2">
                            <label>&nbsp;</label>
                            <select class="form-control select2bs4" id="masa_pakai_waktu_barang" name="masa_pakai_waktu_barang" style="width: 100%;">
                                <option value="" disabled selected>-- Masa Pakai --</option>
                                <option value="Tahun">Tahun</option>
                                <option value="Bulan">Bulan</option>
                                <option value="Minggu">Minggu</option>
                                <option value="Hari">Hari</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="deskripsi_barang">Deskripsi Inventaris</label>
                            <textarea class="form-control" id="deskripsi_barang" name="deskripsi_barang" rows="2" placeholder="Masukkan Spesifikasi / Deskripsi singkat" required></textarea>
                        </div>
                    </div>
                    <div class="text-right mt-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit Gudang Barang Input -->
<div class="modal fade" id="editinventarisModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Inventaris</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForminventaris" action="{{ route('inventaris.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="inventarisid_edit" name="inventarisid_edit">
                    <div class="row">
                        <input type="hidden" class="form-control" id="kode_barang_edit" name="kode_barang_edit" value="321">
                        <div class="col-md-6">
                            <label for="nama_barang_edit">Nama Inventaris</label>
                            <input type="text" class="form-control" id="nama_barang_edit" name="nama_barang_edit" placeholder="Masukkan Nama Barang" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kategori_barang_edit">Kategori Inventaris</label>
                            <select class="form-control select2bs4" id="kategori_barang_edit" name="kategori_barang_edit" style="width: 100%;">
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @foreach ($kategori as $kategori_edit)
                                    <option value="{{ $kategori_edit->nama }}">{{ $kategori_edit->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mt-2">
                            <label for="satuan_barang_edit">Satuan Inventaris</label>
                            <select class="form-control select2bs4" id="satuan_barang_edit" name="satuan_barang_edit" style="width: 100%;">
                                <option value="" disabled selected>-- Pilih Satuan --</option>
                                @foreach ($satuan as $satuanEdit)
                                    <option value="{{ $satuanEdit->nama }}">{{ $satuanEdit->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mt-2">
                            <label for="jenis_barang_edit">Jenis Inventaris</label>
                            <select class="form-control" id="jenis_barang_edit" name="jenis_barang_edit" style="width: 100%;">
                                <option value="" disabled selected>-- Pilih Data --</option>
                                <option value="Inventaris">Inventaris</option>
                                <option value="Barang Habis Pakai">Barang Habis Pakai</option>
                            </select>
                        </div>
                        <div class="col-md-3 mt-2">
                            <label for="masa_pakai_barang_edit">Masa Pakai</label>
                            <input type="text" class="form-control" id="masa_pakai_barang_edit" name="masa_pakai_barang_edit" maxlength="2" required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2);" placeholder="Masukkan angka masa pakai">
                        </div>
                        <div class="col-md-3 mt-2">
                            <label for="masa_pakai_waktu_barang_edit">Tahun</label>
                            <select class="form-control select2bs4" id="masa_pakai_waktu_barang_edit" name="masa_pakai_waktu_barang_edit" style="width: 100%;">
                                <option value="" disabled selected>-- Masa Pakai --</option>
                                <option value="Tahun">Tahun</option>
                                <option value="Bulan">Bulan</option>
                                <option value="Minggu">Minggu</option>
                                <option value="Hari">Hari</option>
                            </select>
                        </div>

                        <div class="col-md-12 mt-2">
                            <label for="deskripsi_barang_edit">Deskripsi Inventaris</label>
                            <textarea class="form-control" id="deskripsi_barang_edit" name="deskripsi_barang_edit" rows="2" placeholder="Masukkan Spesifikasi / Deskripsi singkat" required></textarea>
                        </div>
                    </div>
                    <div class="text-right mt-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- modal Delete Role --}}
<div class="modal fade" id="deleteinventarisModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteForminventaris" action="{{ route('inventaris.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Data Inventaris</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="inventarisid_delete" name="inventarisid_delete">
                    <div id="deleteTextinventaris"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importinventarisModal" tabindex="-1" role="dialog" aria-labelledby="importinventarisModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importinventarisModalLabel">Import Data Inventaris</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('inventaris.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Pilih File Excel</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-import"></i> Import
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Singkron -->
<div class="modal fade" id="singkroninventarisModal" tabindex="-1" role="dialog" aria-labelledby="singkroninventarisModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="singkroninventarisModalLabel">Singkron Data Inventaris</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="singkronDabar">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12" id="containerExternal">
                            <label for="external_database">Pilih Tujuan</label>
                            <select class="form-control select2bs4" style="width: 100%;" id="external_database" name="external_database">
                                <option value="" disabled selected>Pilih Nama Tujuan</option>
                                @foreach ($singkron as $datasingkron)
                                    <option value="{{ $datasingkron->id }}">{{ $datasingkron->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <div id="loadingContainer" class="progress" style="display: none; height: 25px;">
                                <div id="loadingBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 0%;">
                                    <span id="loadingText" style="font-weight: bold; color: white; display: block; text-align: center;">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Tambah</button> <!-- Submit button -->
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        function resetModal() {
            $("#loadingContainer").show();         // Sembunyikan loading bar
            $("#loadingBar").css("width", "0%");   // Reset progress ke 0%
            $("#loadingText").text("0%");          // Reset teks ke 0%
            $(".btn-info").prop("disabled", false).text("Tambah"); // Aktifkan kembali tombol

            $("#singkronDabar")[0].reset(); // Reset form input
        }

        // Reset progress bar saat modal dibuka
        $("#singkroninventarisModal").on("show.bs.modal", function () {
            $("#loadingContainer").hide(); // Tampilkan loading saat modal dibuka
            $("#loadingBar").css("width", "0%"); // Reset ke 0%
            $("#loadingText").text("0%"); // Reset teks ke 0%
        });

        $("#singkronDabar").submit(function (e) {
            e.preventDefault(); // Mencegah submit form default

            const id_db = $("#external_database").val();

            // Tampilkan loading bar dan reset ke 0%
            $("#loadingContainer").show();
            $("#containerExternal").hide();
            $("#loadingBar").css("width", "0%");
            $("#loadingText").text("0%");
            $(".btn-info").prop("disabled", true).text("Menambahkan...");

            // Simulasi animasi progress dari 0% hingga 100% sebelum AJAX dijalankan
            let progress = 0;
            let interval = setInterval(function () {
                progress += 10; // Tambah 10% setiap 300ms
                $("#loadingBar").css("width", progress + "%");
                $("#loadingText").text(progress + "%"); // Update teks

                if (progress >= 100) {
                    clearInterval(interval); // Hentikan animasi
                    $("#loadingText").text("Complete"); // Ubah teks jadi "Complete"

                    // ** Setelah 100%, jalankan AJAX request **
                    $.ajax({
                        url: "{{ route('inventaris.singkron', ['id' => '__ID__']) }}".replace('__ID__', id_db),
                        type: "GET",
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                $('#singkroninventarisModal').modal('hide');
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
                            }).then(() => {
                                // **🔹 Reset modal ke kondisi awal setelah OK ditekan**
                                resetModal();
                            });

                        }
                    });
                }
            }, 300); // Setiap 300ms, naik 10%
        });
    });
</script>

<script>
    // Global Scope
    let stepper;

        $(document).ready(function() {
            $("#inventaristabel").DataTable({
                "responsive": true,
                "autoWidth": true,
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "language": {
                    zeroRecords: "Data Kosong !"
                }
            }).buttons().container().appendTo('#inventaristabel_wrapper .col-md-6:eq(0)');
        });

        $(document).ready(function() {
            $('#addinventarisModal').on('shown.bs.modal', function () {
                $.ajax({
                    url: '/api/generate-kode-inventaris', // Pastikan sesuai dengan route API-mu
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#kode_barang').val(response.kode_barang); // Isi hidden input
                            console.log(response.kode_barang);
                        }
                    },
                    error: function() {
                        console.error("Gagal generate kode barang");
                    }
                });
            });
        });


        $('#addForminventaris').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addinventarisModal').modal('hide');
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
                        $('#addForminventaris').find('.is-invalid').removeClass('is-invalid');

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

        $(document).on('click', '.edit-data-inventaris', function() {
            const data = $(this).data();

            $('#inventarisid_edit').val(data.id);
            $('#kode_barang_edit').val(data.kode_barang);
            $('#nama_barang_edit').val(data.nama_barang);
            $('#kategori_barang_edit').val(data.kategori_barang).trigger('change');
            $('#satuan_barang_edit').val(data.satuan_barang).trigger('change');
            $('#jenis_barang_edit').val(data.jenis_barang).trigger('change');
            $('#masa_pakai_barang_edit').val(data.masa_pakai_barang);
            $('#masa_pakai_waktu_barang_edit').val(data.masa_pakai_waktu_barang).trigger('change');
            $('#deskripsi_barang_edit').val(data.deskripsi_barang);
        });

        $('#editForminventaris').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editinventarisModal').modal('hide');
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

                        // Bersihkan error sebelumnya
                        $('#editForminventaris').find('.is-invalid').removeClass('is-invalid');

                        // Loop tiap error dan tampilkan
                        $.each(xhr.responseJSON.errors, function(key, messages) {
                            // Ambil elemen input berdasarkan ID
                            $('#' + key).addClass('is-invalid');

                            // Gabungkan semua pesan error (jika lebih dari 1)
                            messages.forEach(msg => {
                                errorList += `- ${msg}<br>`;
                            });
                        });

                        // Tampilkan dengan SweetAlert
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal!',
                            html: `Terdapat beberapa kesalahan pengisian:<br><br>${errorList}`,
                            confirmButtonText: 'Periksa Kembali',
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

        $(document).on('click', '.delete-data-goldar', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-inventaris');

            $('#inventarisid_delete').val(id);
            $('#deleteTextinventaris').html(
            `<span>Apa Anda yakin ingin menghapus data Inventaris <b>${name}</b> ?</span>`);
        });

        $('#deleteForminventaris').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deleteinventarisModal').modal('hide');
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus Inventaris!',
                    });
                }
            });
        });
</script>

@endsection








