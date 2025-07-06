@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">ICD 10</h1>
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
                                <h3 class="card-title">ICD 10</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addalergiModal">
                                        <i class="fas fa-plus"></i> Sinkron
                                    </button>

                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addicd10Modal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                    <!-- Tombol Export -->
                                    <a href="{{ route('icd10.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>

                                    <!-- Tombol Import (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importGoldarModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="icd10tabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama ICD 10</th>
                                            <th class="text-center">Kode ICD 10 </th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($icd10 as $icd10data)
                                            <tr>
                                                <td class="text-center">{{ $icd10data->nama_icd10 }}</td>
                                                <td class="text-center">{{ $icd10data->kode_icd10 }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-icd10"
                                                        data-toggle="modal" data-id="{{ $icd10data->id }}"
                                                        data-nama-icd10="{{ $icd10data->nama_icd10 }}"
                                                        data-rhesus="{{ $icd10data->kode_icd10 }}"
                                                        data-target="#editicd10Moda">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-icd10"
                                                        data-toggle="modal"data-id="{{ $icd10data->id }}"
                                                        data-nama-icd10="{{ $icd10data->nama_icd10 }}"
                                                        data-rhesus="{{ $icd10data->kode_icd10 }}"
                                                        data-target="#deleteicd10Modal">
                                                        <i class="fas fa-trash"></i> Hapus
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


<div class="modal fade" id="addalergiModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah ICD 10</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="singForicd10" action="{{ route('icd10.singkron') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="kode_icd">Kode ICD-10</label>
                                <input type="text" class="form-control" id="kode_icd" name="kode_icd" placeholder="Masukkan Kode ICD-10" required>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div id="loadingContainer" class="progress mt-2" style="display: none; height: 25px;">
                                <div id="loadingBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 0%;">
                                    <span id="loadingText" class="w-100 d-block text-center font-weight-bold text-white">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button> <!-- Submit button -->
            </div>
            </form>
        </div>
    </div>
</div>


{{-- modal Add Role --}}
<div class="modal fade" id="addicd10Modal" tabindex="-1" role="dialog" aria-labelledby="addicd10Label">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addicd10Label">Tambah Master ICD 10</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormicd10" action="{{ route('icd10.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama ICD 10</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama ICD 10" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="kode">Kode ICD 10 </label>
                                <input type="text" class="form-control" id="kode" name="kode" placeholder="Kode ICD 10" required>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button> <!-- Submit button -->
            </div>
            </form>
        </div>
    </div>
</div>

{{-- modal Edit Role --}}
<div class="modal fade" id="editicd10Moda" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Master ICD 10</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormicd10" action="{{ route('icd10.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="icd10id_edit" name="icd10id_edit">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama ICD 10</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama ICD 10" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="kode_edit">Kode ICD 10 </label>
                                <input type="text" class="form-control" id="kode_edit" name="kode_edit" placeholder="Kode ICD 10" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Perbarui</button> <!-- Submit button -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- modal Delete Role --}}
<div class="modal fade" id="deleteicd10Modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormicd10" action="{{ route('icd10.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Data ICD 10</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="icd10id_delete" name="icd10id_delete">
                    <div id="deleteTexticd10"></div>
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
<div class="modal fade" id="importGoldarModal" tabindex="-1" role="dialog" aria-labelledby="importGoldarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importGoldarModalLabel">Import Data ICD 10</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('icd10.import') }}" method="POST" enctype="multipart/form-data">
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

<script>
        $(document).ready(function() {
            $("#icd10tabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#icd10tabel_wrapper .col-md-6:eq(0)');
        });

        $('#singForicd10').on('submit', function(e) {
            e.preventDefault();

            const $form = $(this);
            const $loadingContainer = $('#loadingContainer');
            const $loadingBar = $('#loadingBar');
            const $loadingText = $('#loadingText');
            const $jenisAlergiGroup = $('#jenis_alergi').closest('.form-group');

            // Sembunyikan dropdown jenis_alergi
            $jenisAlergiGroup.hide();

            // Reset dan tampilkan loading bar
            let progress = 0;
            $loadingBar.css('width', '0%');
            $loadingText.text('0%');
            $loadingContainer.show();

            const interval = setInterval(() => {
                if (progress >= 90) return;
                progress++;
                $loadingBar.css('width', progress + '%');
                $loadingText.text(progress + '%');
            }, 20);

            $.ajax({
                url: $form.attr('action'),
                method: $form.attr('method'),
                data: $form.serialize(),
                success: function(response) {
                    clearInterval(interval);
                    $loadingBar.css('width', '100%');
                    $loadingText.text('100%');

                    if (response.success) {
                        $('#addalergiModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('.modal-backdrop').remove(); // Bersihkan backdrop
                            location.reload();
                        });
                    } else {
                        $jenisAlergiGroup.show();
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    clearInterval(interval);
                    $loadingBar.css('width', '100%');
                    $loadingText.text('Gagal!');
                    $jenisAlergiGroup.show();

                    const errorMessage = xhr.responseJSON?.message || "Terjadi kesalahan saat menyimpan data.";
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                }
            });
        });


        $('#addFormicd10').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addicd10Modal').modal('hide');
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

        $(document).on('click', '.edit-data-icd10', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-icd10');
            var rhesus = $(this).data('rhesus'); // Ambil data rhesus dari tombol edit


            $('#icd10id_edit').val(id);
            $('#nama_edit').val(nama);
             // Pastikan rhesus terpilih dengan benar
            if (rhesus === "null" || rhesus === null || rhesus === undefined) {
                $('#kode_edit').val("null");
            } else {
                $('#kode_edit').val(rhesus);
            }
        });

        $('#editFormicd10').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editicd10Moda').modal('hide');
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
                        text: 'Terjadi kesalahan saat ICD10!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-icd10', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-icd10');
            let rhesus = $(this).data('rhesus'); // Ambil data rhesus dari tombol edit
            $('#icd10id_delete').val(id);
            $('#deleteTexticd10').html(
            `<span>Apa Anda yakin ingin menghapus data ICD10 <b>${name}</b> ?</span>`);
        });

        $('#deleteFormicd10').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deleteicd10Modal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus ICD10!',
                    });
                }
            });
        });
</script>
@endsection
