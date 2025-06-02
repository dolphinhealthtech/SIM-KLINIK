@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pelayanan</h1>
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
                                <h3 class="card-title">Pelayanan</h3>
                            </div>
                            <div class="card-body">
                                <table id="banktabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nomor RM</th>
                                            <th class="text-center">Nama Pasien</th>
                                            <th class="text-center">Nomor Registrasi</th>
                                            <th class="text-center">Tanggal Kunjungan</th>
                                            <th class="text-center">Poli</th>
                                            <th class="text-center">Dokter</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pelayanan as $pelayanandata)
                                            <tr>
                                                <td class="text-center">{{ $pelayanandata->nomor_rm }}</td>
                                                <td class="text-center">{{ $pelayanandata->pasien->nama }}</td>
                                                <td class="text-center">{{ $pelayanandata->nomor_register }}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($pelayanandata->tanggal_kujungan)->format('d-m-Y') }}</td>
                                                <td class="text-center">{{ $pelayanandata->poli->nama }}</td>
                                                <td class="text-center">{{ $pelayanandata->dokter->namauser->name }}</td>
                                                <td class="text-center">
                                                   <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            Menu
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                                                            @if ($pelayanandata->tindakan_button == 'panggil')
                                                                <a class="dropdown-item" href="{{ route('sopelayana.hadir', ['norawat' => base64_encode($pelayanandata->nomor_register)]) }}">
                                                                    <i class="fas fa-bell"></i> Panggil
                                                                </a>
                                                            @elseif ($pelayanandata->tindakan_button == 'soap')
                                                                <a class="dropdown-item" href="{{ route('sopelayana.get', ['norawat' => base64_encode($pelayanandata->nomor_register)]) }}">
                                                                    <i class="fas fa-file-medical-alt"></i> SOAP & Pemeriksaan
                                                                </a>
                                                            @elseif ($pelayanandata->tindakan_button == 'edit')
                                                                <a class="dropdown-item" href="{{ route('sopelayana.get', ['norawat' => base64_encode($pelayanandata->nomor_register)]) }}">
                                                                    <i class="fas fa-edit"></i> Edit SOAP
                                                                </a>
                                                             @elseif ($pelayanandata->tindakan_button == 'Complete')
                                                                <a class="dropdown-item">
                                                                    <i class="fas fa-edit"></i> Sudah Di cek Dokter
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
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


<script>
        $(document).ready(function() {
            $("#banktabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#banktabel_wrapper .col-md-6:eq(0)');
        });

        $(document).on('click', '.delete-data-bank', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-bank');

            $('#bankid_delete').val(id);
            $('#deleteTextbank').html(
            `<span>Apa Anda yakin ingin menghapus data bank <b>${name}</b> ?</span>`);
        });

        $('#deleteFormbank').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deletebankModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Goldar!',
                    });
                }
            });
        });
</script>
@endsection
