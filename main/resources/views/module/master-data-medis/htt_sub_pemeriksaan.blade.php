@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Sub pemeriksaan</h1>
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
                                <h3 class="card-title">Sub pemeriksaan</h3>
                                <div class="card-tools">
                                    <a href="{{ route('htt_pemeriksaan.get') }}" class="btn btn-info">
                                        <i class="fa-solid fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addhtt_sub_pemeriksaanModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="htt_sub_pemeriksaantabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama Pemeriksaan</th>
                                            <th class="text-center">Nama Sub Pemeriksaan</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($htt_sub_pemeriksaan as $htt_sub_pemeriksaandata)
                                            <tr>
                                                <td class="text-center">{{ $htt_sub_pemeriksaandata->nama_pemeriksaan }}</td>
                                                <td class="text-center">{{ $htt_sub_pemeriksaandata->nama_subpemeriksaan }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-pemeriksaan_htt"
                                                        data-toggle="modal"data-id="{{ $htt_sub_pemeriksaandata->id }}"
                                                        data-nama-htt_sub_pemeriksaan="{{ $htt_sub_pemeriksaandata->nama_subpemeriksaan }}"
                                                        data-target="#deletehtt_sub_pemeriksaanModal">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-goldar"
                                                        data-toggle="modal"data-id="{{ $htt_sub_pemeriksaandata->id }}"
                                                        data-nama-htt_sub_pemeriksaan="{{ $htt_sub_pemeriksaandata->nama_subpemeriksaan }}"
                                                        data-target="#edithtt_sub_pemeriksaanModal">
                                                        <i class="fas fa-trash"></i> edit
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
<div class="modal fade" id="addhtt_sub_pemeriksaanModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Pemeriksaan Sub Head To Toe</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormhtt_sub_pemeriksaan" action="{{ route('htt_sub_pemeriksaan.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <input type="hidden" id="htt_sub_pemeriksaan_id" name="htt_sub_pemeriksaan_id" value="{{ $htt_pemeriksaan->id }}" >
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bagian Pemeriksaan</label>
                                <input type="text" class="form-control" id="nama_sub_pemeriksaan" name="nama_sub_pemeriksaan" value="{{ $htt_pemeriksaan->nama_pemeriksaan }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bagian Sub Pemeriksaan</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Sub Pemeriksaan" required>
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

<div class="modal fade" id="edithtt_sub_pemeriksaanModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Pemeriksaan Sub Head To Toe</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormhtt_sub_pemeriksaan" action="{{ route('htt_sub_pemeriksaan.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <input type="hidden" id="htt_sub_pemeriksaanid_edit" name="htt_sub_pemeriksaanid_edit">
                        <input type="hidden" id="htt_sub_pemeriksaan_id_edit" name="htt_sub_pemeriksaan_id_edit" value="{{ $htt_pemeriksaan->id }}" >
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bagian Pemeriksaan</label>
                                <input type="text" class="form-control" id="nama_pemeriksaan_edit" name="nama_pemeriksaan_edit" value="{{ $htt_pemeriksaan->nama_pemeriksaan }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bagian Sub Pemeriksaan</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama Sub Pemeriksaan" required>
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

{{-- modal Delete Role --}}
<div class="modal fade" id="deletehtt_sub_pemeriksaanModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormhtt_sub_pemeriksaan" action="{{ route('htt_sub_pemeriksaan.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Pemeriksaan Sub Head To Toe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="htt_sub_pemeriksaanid_delete" name="htt_sub_pemeriksaanid_delete">
                    <div id="deleteTexthtt_sub_pemeriksaan"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>



<script>
        $(document).ready(function() {
            $("#htt_sub_pemeriksaantabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#htt_sub_pemeriksaantabel_wrapper .col-md-6:eq(0)');
        });

        $('#addFormhtt_sub_pemeriksaan').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addhtt_sub_pemeriksaanModal').modal('hide');
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

        $(document).on('click', '.edit-data-goldar', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-htt_sub_pemeriksaan');

            $('#htt_sub_pemeriksaanid_edit').val(id);
            $('#nama_edit').val(nama);
             // Pastikan rhesus terpilih dengan benar
        });

        $('#editFormpemeriksaan_htt').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editpemeriksaan_httModa').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate sub pemeriksaan htt!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-pemeriksaan_htt', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-htt_sub_pemeriksaan');

            $('#htt_sub_pemeriksaanid_delete').val(id);
            $('#deleteTexthtt_sub_pemeriksaan').html(
            `<span>Apa Anda yakin ingin menghapus data pemeriksaan htt <b>${name}</b> ?</span>`);
        });

        $('#deleteFormhtt_sub_pemeriksaan').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deletehtt_sub_pemeriksaanModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus sub pemeriksaan htt!',
                    });
                }
            });
        });
</script>
@endsection
