@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Alergi</h1>
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
                                <h3 class="card-title">alergi</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addalergiModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="alergitabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Jenis alergi</th>
                                            <th class="text-center">Nama alergi</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($alergi as $alergidata)
                                            <tr>
                                                <td class="text-center">@if ($alergidata->kode_jenis_alergi == '01')
                                                    Makanan
                                                @elseif ($alergidata->kode_jenis_alergi == '02')
                                                    Udara
                                                @elseif ($alergidata->kode_jenis_alergi == '03')
                                                    Obat
                                                @else
                                                    Tidak Diketahui
                                                @endif</td>
                                                <td class="text-center">{{ $alergidata->nama_jenis_alergi }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-alergi"
                                                        data-toggle="modal"data-id="{{ $alergidata->id }}"
                                                        data-nama-alergi="{{ $alergidata->nama_jenis_alergi }}"
                                                        data-target="#deletealergiModal">
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
<div class="modal fade" id="addalergiModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah alergi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormalergi" action="{{ route('alergi.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nama alergi</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="jenis_alergi" name="jenis_alergi">
                                    <option value="" disabled selected>Jenis Alergi</option>
                                    <option value="01">alergi Makanan</option>
                                    <option value="02">alergi Udara</option>
                                    <option value="03">alergi Obat</option>
                                </select>
                            </div>
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
                <button type="submit" class="btn btn-primary">Tambah</button> <!-- Submit button -->
            </div>
            </form>
        </div>
    </div>
</div>


{{-- modal Delete Role --}}
<div class="modal fade" id="deletealergiModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormalergi" action="{{ route('alergi.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus alergi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="alergiid_delete" name="alergiid_delete">
                    <div id="deleteTextalergi"></div>
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
            $("#alergitabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#alergitabel_wrapper .col-md-6:eq(0)');
        });
        $('#addFormalergi').on('submit', function(e) {
            e.preventDefault();

            // Sembunyikan dropdown jenis_alergi
            $('#jenis_alergi').closest('.form-group').hide();

            // Tampilkan loading bar
            $('#loadingContainer').show();
            let width = 0;
            let interval = setInterval(() => {
                if (width >= 90) return; // Biarkan sisa 10% sampai respon sukses
                width++;
                $('#loadingBar').css('width', width + '%');
                $('#loadingText').text(width + '%');
            }, 20);

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    clearInterval(interval);
                    $('#loadingBar').css('width', '100%');
                    $('#loadingText').text('100%');

                    if (response.success) {
                        $('#addalergiModal').modal('hide');
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
                        $('#jenis_alergi').closest('.form-group').show(); // Tampilkan lagi
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    clearInterval(interval);
                    $('#loadingBar').css('width', '100%');
                    $('#loadingText').text('Gagal!');

                    $('#jenis_alergi').closest('.form-group').show();

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



        $(document).on('click', '.delete-data-alergi', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-alergi');

            $('#alergiid_delete').val(id);
            $('#deleteTextalergi').html(
            `<span>Apa Anda yakin ingin menghapus data alergi <b>${name}</b> ?</span>`);
        });

        $('#deleteFormalergi').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deletealergiModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Alergi!',
                    });
                }
            });
        });
</script>
@endsection
