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
                                            <th class="text-center">No.Antrian</th>
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
                                                        <span class="badge badge-primary rounded-pill">Pemeriksaan</span>
                                                    @elseif ($pelayanandata->tindakan_button == 'edit')
                                                        <span class="badge badge-info rounded-pill">Menunggu Dokter</span>
                                                    @elseif ($pelayanandata->tindakan_button == 'Complete')
                                                        <span class="badge badge-success rounded-pill">Sudah Dicek Dokter</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $pelayanandata->nomor_rm }}</td>
                                                <td class="text-center">{{ $pelayanandata->pasien->nama }}</td>
                                                <td class="text-center">{{ $pelayanandata->pendaftaran->antrian }}</td>
                                                <td class="text-center">{{ $pelayanandata->nomor_register }}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($pelayanandata->tanggal_kujungan)->format('d-m-Y') }}</td>
                                                <td class="text-center">{{ $pelayanandata->poli->nama }}</td>
                                                <td class="text-center">{{ $pelayanandata->dokter->namauser->name }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $norawat = base64_encode($pelayanandata->nomor_register);
                                                    @endphp

                                                    @if ($pelayanandata->tindakan_button == 'panggil')
                                                        <button type="button"
                                                                class="btn btn-outline-warning btn-sm rounded-pill pasien-hadir"
                                                                data-url="{{ route('sopelayana.hadir', ['norawat' => $norawat]) }}"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="Panggil pasien ke ruangan">
                                                            <i class="fas fa-bell"></i> Panggil
                                                        </button>

                                                    @elseif ($pelayanandata->tindakan_button == 'soap')
                                                        <button type="button"
                                                                class="btn btn-outline-primary btn-sm rounded-pill"
                                                                onclick="window.location.href='{{ route('sopelayana.get', ['norawat' => $norawat]) }}'"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="Lanjutkan ke SOAP & Pemeriksaan">
                                                            <i class="fas fa-file-medical-alt"></i> Pemeriksaan
                                                        </button>

                                                    @elseif ($pelayanandata->tindakan_button == 'edit')
                                                        <button type="button"
                                                                class="btn btn-outline-info btn-sm rounded-pill"
                                                                onclick="window.location.href='{{ route('sopelayana.edit', ['norawat' => $norawat]) }}'"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="Edit data SOAP yang sudah diisi">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button type="button" class="btn btn-outline-warning btn-sm rounded-pill dokter-data-pasien"
                                                            data-id="{{ $pelayanandata->id }}"
                                                            data-poli="{{ $pelayanandata->pendaftaran->poli_id }}"
                                                            data-nama="{{ $pelayanandata->pendaftaran->pasien->nama }}"
                                                            data-tgl-kunjung="{{ $pelayanandata->pendaftaran->tanggal_kujungan }}">
                                                            <i class="fas fa-user-md"></i> Ubah Dokter
                                                        </button>
                                                    @elseif ($pelayanandata->tindakan_button == 'Complete')
                                                        <button type="button"
                                                                class="btn btn-outline-success btn-sm rounded-pill disabled"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="Sudah selesai diperiksa oleh dokter">
                                                            <i class="fas fa-check-circle"></i> Dicek
                                                        </button>
                                                    @endif
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
    $(document).on('click', '.dokter-data-pasien', function() {
        let id = $(this).data('id');
        let tanggal = $(this).data('tgl-kunjung');
        let poli = $(this).data('poli');
        let nama = $(this).data('nama');

        // SweetAlert untuk konfirmasi rubah dokter
        Swal.fire({
            title: 'Rubah Dokter',
            html: `
                <div class="text-left">
                    <p><strong>Pasien:</strong> ${nama}</p>
                    <div class="form-group">
                        <label for="dokter_select">Pilih Dokter Baru:</label>
                        <select id="dokter_select" class="form-control" style="width: 100%;">
                            <option value="">Memuat dokter...</option>
                        </select>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#6c757d',
            preConfirm: () => {
                const dokterId = document.getElementById('dokter_select').value;
                if (!dokterId) {
                    Swal.showValidationMessage('Silakan pilih dokter terlebih dahulu');
                    return false;
                }
                return dokterId;
            },
            didOpen: () => {
                // Load dokter berdasarkan poli dan tanggal
                if (poli && tanggal) {
                    let formattedDatetime = tanggal.replace('T', ' ') + ':00';

                    $.ajax({
                        url: `/api/get-dokter-by-poli/${poli}`,
                        method: 'GET',
                        data: { datetime: formattedDatetime },
                        success: function (data) {
                            let select = document.getElementById('dokter_select');
                            select.innerHTML = '<option value="">Pilih Dokter</option>';
                            data.forEach(function (dokter) {
                                select.innerHTML += `<option value="${dokter.id}">${dokter.namauser.name}</option>`;
                            });
                        },
                        error: function (xhr, status, error) {
                            document.getElementById('dokter_select').innerHTML = '<option value="">Gagal memuat dokter</option>';
                        }
                    });
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(result);

                // Tampilkan loading
                Swal.fire({
                    title: 'Mengupdate...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                // Submit update dokter
                $.ajax({
                    url: "{{ route('sopelayana.dokter.update') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        rubahdokter_id: id,
                        poli_id_update: poli,
                        tanggal_kunjungan_update: tanggal,
                        dokter_id_update: result.value
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Dokter berhasil diupdate',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Terjadi kesalahan saat mengupdate dokter';

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            let errorList = [];
                            Object.keys(errors).forEach(function(field) {
                                errors[field].forEach(function(message) {
                                    errorList.push(message);
                                });
                            });
                            errorMessage = errorList.join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Update Dokter!',
                            html: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
</script>
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
