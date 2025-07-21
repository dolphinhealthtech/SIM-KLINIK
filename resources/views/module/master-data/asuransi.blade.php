@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Master Asuransi</h1>
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
                                <h3 class="card-title">Master Asuransi</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addasuransiModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                    <!-- Tombol Export -->
                                    <a href="{{ route('asuransi.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>

                                    <!-- Tombol Import (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importasuransiModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="asuransitabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asuransi as $asuransidata)
                                            <tr>
                                                <td class="text-center">{{ $asuransidata->nama }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-asuransi"
                                                        data-toggle="modal" data-id="{{ $asuransidata->id }}"
                                                        data-nama-asuransi="{{ $asuransidata->nama }}"
                                                        data-kode-asuransi="{{ $asuransidata->kode }}"
                                                        data-target="#editasuransiModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-asuransi"
                                                        data-toggle="modal"data-id="{{ $asuransidata->id }}"
                                                        data-nama-asuransi="{{ $asuransidata->nama }}"
                                                        data-target="#deleteasuransiModal">
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

{{-- modal Add Role --}}
<div class="modal fade" id="addasuransiModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah asuransi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormasuransi" action="{{ route('asuransi.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Nama asuransi</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama asuransi" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Kode asuransi</label>
                                <input type="text" class="form-control" id="kode" name="kode" placeholder="Kode asuransi" required>
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
<div class="modal fade" id="editasuransiModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit asuransi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormasuransi" action="{{ route('asuransi.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="asuransiid_edit" name="asuransiid_edit">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Nama asuransi</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama asuransi" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Kode asuransi</label>
                                <input type="text" class="form-control" id="kode_edit" name="kode_edit" placeholder="Kode asuransi" required>
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
<div class="modal fade" id="deleteasuransiModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormasuransi" action="{{ route('asuransi.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus asuransi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="asuransiid_delete" name="asuransiid_delete">
                    <div id="deleteTextasuransi"></div>
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
<div class="modal fade" id="importasuransiModal" tabindex="-1" role="dialog" aria-labelledby="importasuransiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importasuransiModalLabel">Import Data asuransi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('asuransi.import') }}" method="POST" enctype="multipart/form-data">
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
            $("#asuransitabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#asuransitabel_wrapper .col-md-6:eq(0)');
        });

        $('#addFormasuransi').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addasuransiModal').modal('hide');
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

        $(document).on('click', '.edit-data-asuransi', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-asuransi');
            var kode = $(this).data('kode-asuransi');

            $('#asuransiid_edit').val(id);
            $('#nama_edit').val(nama);
            $('#kode_edit').val(kode);
             // Pastikan rhesus terpilih dengan benar
        });

        $('#editFormasuransi').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editasuransiModa').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate asuransi!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-asuransi', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-asuransi');

            $('#asuransiid_delete').val(id);
            $('#deleteTextasuransi').html(
            `<span>Apa Anda yakin ingin menghapus data asuransi <b>${name}</b> ?</span>`);
        });

        $('#deleteFormasuransi').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deleteasuransiModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus asuransi!',
                    });
                }
            });
        });
</script>
@endsection
