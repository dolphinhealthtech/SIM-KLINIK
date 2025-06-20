@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pendidikan</h1>
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
                                <h3 class="card-title">pendidikan</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addpendidikanModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                    <!-- Tombol Export -->
                                    <a href="{{ route('pendidikan.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>

                                    <!-- Tombol Import (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importpendidikanModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="pendidikantabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama pendidikan</th>
                                            <th class="text-center">Kode pendidikan</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendidikan as $pendidikandata)
                                            <tr>
                                                <td class="text-center">{{ $pendidikandata->nama }}</td>
                                                <td class="text-center">{{ $pendidikandata->kode }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-pendidikan"
                                                        data-toggle="modal" data-id="{{ $pendidikandata->id }}"
                                                        data-nama-pendidikan="{{ $pendidikandata->nama }}"
                                                        data-kode-pendidikan="{{ $pendidikandata->kode }}"
                                                        data-urutan-pendidikan="{{ $pendidikandata->urutan }}"
                                                        data-target="#editpendidikanModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-pendidikan"
                                                        data-toggle="modal"data-id="{{ $pendidikandata->id }}"
                                                        data-nama-pendidikan="{{ $pendidikandata->nama }}"
                                                        data-target="#deletependidikanModal">
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

{{-- modal Add Role --}}
<div class="modal fade" id="addpendidikanModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah pendidikan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormpendidikan" action="{{ route('pendidikan.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama pendidikan</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama pendidikan" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>kode pendidikan</label>
                                <input type="text" class="form-control" id="kode" name="kode" placeholder="kode pendidikan" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Urutan pendidikan</label>
                                <input type="number" class="form-control" id="urutan" name="urutan" placeholder="urutan pendidikan" required>
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
<div class="modal fade" id="editpendidikanModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit pendidikan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormpendidikan" action="{{ route('pendidikan.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="pendidikanid_edit" name="pendidikanid_edit">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama pendidikan</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama pendidikan" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>kode pendidikan</label>
                                <input type="text" class="form-control" id="kode_edit" name="kode_edit" placeholder="kode pendidikan" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Uratan pendidikan</label>
                                <input type="number" class="form-control" id="urutan_edit" name="urutan_edit" placeholder="urutan pendidikan" required>
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
<div class="modal fade" id="deletependidikanModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormpendidikan" action="{{ route('pendidikan.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus pendidikan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="pendidikanid_delete" name="pendidikanid_delete">
                    <div id="deleteTextpendidikan"></div>
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
<div class="modal fade" id="importpendidikanModal" tabindex="-1" role="dialog" aria-labelledby="importpendidikanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importpendidikanModalLabel">Import Data pendidikan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pendidikan.import') }}" method="POST" enctype="multipart/form-data">
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
            $("#pendidikantabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#pendidikantabel_wrapper .col-md-6:eq(0)');
        });

        $('#addFormpendidikan').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addpendidikanModal').modal('hide');
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

        $(document).on('click', '.edit-data-pendidikan', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-pendidikan');
            var kode = $(this).data('kode-pendidikan');
            var urutan = $(this).data('urutan-pendidikan');

            $('#pendidikanid_edit').val(id);
            $('#nama_edit').val(nama);
            $('#kode_edit').val(kode);
            $('#urutan_edit').val(urutan);
             // Pastikan rhesus terpilih dengan benar
        });

        $('#editFormpendidikan').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editpendidikanModa').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate pendidikan!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-pendidikan', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-pendidikan');

            $('#pendidikanid_delete').val(id);
            $('#deleteTextpendidikan').html(
            `<span>Apa Anda yakin ingin menghapus data pendidikan <b>${name}</b> ?</span>`);
        });

        $('#deleteFormpendidikan').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deletependidikanModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Pendidikan!',
                    });
                }
            });
        });
</script>
@endsection
