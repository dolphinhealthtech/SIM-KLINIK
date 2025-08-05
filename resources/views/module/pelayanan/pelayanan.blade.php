@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h5 class="text-muted text-center">Selamat datang di modul Pelayanan Perawat</h5>
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
                            <div class="card-body">
                                <table id="banktabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">No.RM</th>
                                            <th class="text-center">Pasien</th>
                                            <th class="text-center">No.Registrasi</th>
                                            <th class="text-center">Tanggal Kunjungan</th>
                                            <th class="text-center">Poli</th>
                                            <th class="text-center">Dokter</th>
                                            <th class="text-center" width="25%">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pelayanan as $pelayanandata)
                                            <tr>
                                                <td class="text-center">
                                                    @if ($pelayanandata->tindakan_button == 'panggil')
                                                        <span class="badge badge-warning rounded-pill">Belum Hadir</span>
                                                    @elseif ($pelayanandata->tindakan_button == 'soap')
                                                        <span class="badge badge-primary rounded-pill">SOAP</span>
                                                    @elseif ($pelayanandata->tindakan_button == 'edit')
                                                        <span class="badge badge-info rounded-pill">Edit SOAP</span>
                                                    @elseif ($pelayanandata->tindakan_button == 'Complete')
                                                        <span class="badge badge-success rounded-pill">Sudah Dicek Dokter</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $pelayanandata->nomor_rm }}</td>
                                                <td class="text-center">{{ $pelayanandata->pasien->nama }}</td>
                                                <td class="text-center">{{ $pelayanandata->nomor_register }}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($pelayanandata->tanggal_kujungan)->format('d-m-Y') }}</td>
                                                <td class="text-center">{{ $pelayanandata->poli->nama }}</td>
                                                <td class="text-center">{{ $pelayanandata->dokter->namauser->name }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        @if ($pelayanandata->tindakan_button == 'panggil')
                                                            <a href="javascript:void(0);"
                                                            class="btn btn-sm btn-warning rounded-pill shadow pasien-hadir"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Panggil pasien ke ruangan"
                                                            data-url="{{ route('sopelayana.hadir', ['norawat' => base64_encode($pelayanandata->nomor_register)]) }}">
                                                                <i class="fas fa-bell"></i> Panggil
                                                            </a>

                                                        @elseif ($pelayanandata->tindakan_button == 'soap')
                                                            <a href="{{ route('sopelayana.get', ['norawat' => base64_encode($pelayanandata->nomor_register)]) }}"
                                                            class="btn btn-sm btn-primary rounded-pill shadow"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Lanjutkan ke SOAP & Pemeriksaan">
                                                                <i class="fas fa-file-medical-alt"></i> Pemeriksaan Pasien
                                                            </a>

                                                        @elseif ($pelayanandata->tindakan_button == 'edit')
                                                            <a href="{{ route('sopelayana.get', ['norawat' => base64_encode($pelayanandata->nomor_register)]) }}"
                                                            class="btn btn-sm btn-info rounded-pill shadow"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Edit data SOAP yang sudah diisi">
                                                                <i class="fas fa-edit"></i> Edit Pemeriksaan Pasien
                                                            </a>

                                                        @elseif ($pelayanandata->tindakan_button == 'Complete')
                                                            <span class="btn btn-sm btn-success rounded-pill shadow-sm disabled"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="Sudah selesai diperiksa oleh dokter">
                                                                <i class="fas fa-check-circle"></i> Dicek
                                                            </span>
                                                        @endif
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
                "buttons": false,
                "language": {
                    "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                    "zeroRecords": "Tidak ada data yang cocok dengan pencarian Anda",
                    "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _MAX_ total entri)",
                    "search": "Cari:",
                }
            }).buttons().container().appendTo('#banktabel_wrapper .col-md-6:eq(0)');
        });

        $('.pasien-hadir').on('click', function(e) {
            e.preventDefault();

            let url = $(this).data('url');

            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    if (response.success) {
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
                        text: 'Terjadi kesalahan saat Pemanggilan Pasien!',
                    });
                }
            });
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
                        text: 'Terjadi kesalahan saat menghapus Pelayanan!',
                    });
                }
            });
        });
</script>
@endsection
