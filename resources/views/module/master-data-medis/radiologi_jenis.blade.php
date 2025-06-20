@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Radiologi Jenis</h1>
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Radiologi Jenis</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addradiologi_jenisModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                    
                                    <a href="{{ route('radiologi_jenis.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>

                                    
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importradiologi_jenisModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="radiologi_jenistabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Radiologi Jenis</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($radiologi_jenis as $radiologi_jenisdata)
                                            <tr>
                                                <td class="text-center">{{ $radiologi_jenisdata->nama }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-radiologi_jenis"
                                                        data-toggle="modal" data-id="{{ $radiologi_jenisdata->id }}"
                                                        data-nama-radiologi_jenis="{{ $radiologi_jenisdata->nama }}"
                                                        data-target="#editradiologi_jenisModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-radiologi_jenis"
                                                        data-toggle="modal"data-id="{{ $radiologi_jenisdata->id }}"
                                                        data-nama-radiologi_jenis="{{ $radiologi_jenisdata->nama }}"
                                                        data-target="#deleteradiologi_jenisModal">
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
<div class="modal fade" id="addradiologi_jenisModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah jenis radiologi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormradiologi_jenis" action="{{ route('radiologi_jenis.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nama radiologi_jenis</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama radiologi_jenis" required>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button> 
            </div>
            </form>
        </div>
    </div>
</div>

{{-- modal Edit Role --}}
<div class="modal fade" id="editradiologi_jenisModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit jenis radiologi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormradiologi_jenis" action="{{ route('radiologi_jenis.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="radiologi_jenisid_edit" name="radiologi_jenisid_edit">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nama radiologi_jenis</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama radiologi_jenis" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Perbarui</button> 
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- modal Delete Role --}}
<div class="modal fade" id="deleteradiologi_jenisModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormradiologi_jenis" action="{{ route('radiologi_jenis.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus jenis radiologi </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="radiologi_jenisid_delete" name="radiologi_jenisid_delete">
                    <div id="deleteTextradiologi_jenis"></div>
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
<div class="modal fade" id="importradiologi_jenisModal" tabindex="-1" role="dialog" aria-labelledby="importradiologi_jenisModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importradiologi_jenisModalLabel">Import Data radiologi_jenis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('radiologi_jenis.import') }}" method="POST" enctype="multipart/form-data">
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
            $("#radiologi_jenistabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#radiologi_jenistabel_wrapper .col-md-6:eq(0)');
        });

        $('#addFormradiologi_jenis').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addradiologi_jenisModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: true
                        }).then(() => {
                            $('.modal-backdrop').remove(); 
                            location.reload(); 
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

        $(document).on('click', '.edit-data-radiologi_jenis', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-radiologi_jenis');

            $('#radiologi_jenisid_edit').val(id);
            $('#nama_edit').val(nama);
            
        });

        $('#editFormradiologi_jenis').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editradiologi_jenisModa').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: true
                        }).then(() => {
                            $('.modal-backdrop').remove(); 
                            location.reload(); 
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
                        text: 'Terjadi kesalahan saat mengupdate Radiologi Jenis!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-radiologi_jenis', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-radiologi_jenis');

            $('#radiologi_jenisid_delete').val(id);
            $('#deleteTextradiologi_jenis').html(
            `<span>Apa Anda yakin ingin menghapus data Radiologi Jenis <b>${name}</b> ?</span>`);
        });

        $('#deleteFormradiologi_jenis').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deleteradiologi_jenisModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: true
                        }).then(() => {
                            $('.modal-backdrop').remove(); 
                            location.reload(); 
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
                        text: 'Terjadi kesalahan saat menghapus Radiologi Jenis!',
                    });
                }
            });
        });
</script>
@endsection
