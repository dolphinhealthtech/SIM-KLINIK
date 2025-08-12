@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h5 class="text-muted text-center">Selamat datang di modul Pendaftaran Pasien</h5>
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
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $pasienallold }}</h3>
                            <p>Total Pasien Terdaftar</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a class="small-box-footer">&nbsp;</a>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <div class="row text-center text-white">
                                <div class="col-md-6">
                                    <h3>{{ $jumlahDokter }}</h3>
                                    <p>Jumlah Dokter</p>
                                </div>
                                <div class="col-md-6">
                                    <h3>{{ $totalPasien }}</h3>
                                    <p>Total Pasien</p>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="small-box-footer" data-toggle="modal" data-target="#rekapModal">
                            Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $pasienallnewnow }}</h3>
                            <p>Total Pasien Selesai Di layani</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a class="small-box-footer">&nbsp;</a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="userstabel" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">Pasien</th>
                                        <th class="text-center">Pendaftaran</th>
                                        <th class="text-center">No.Registrasi</th>
                                        <th class="text-center">Tanggal Registrasi</th>
                                        <th class="text-center">No.RM</th>
                                        <th class="text-center">No.Antrian</th>
                                        <th class="text-center">Poli</th>
                                        <th class="text-center">Penjamin</th>
                                        <th class="text-center">Dokter</th>
                                        <th class="text-center" width="15%">Tindakan</th>
                                    </tr>
                                </thead>
                                @foreach ($pendaftaran as $pendaftarandata)
                                <tbody>
                                        <td class="text-center">{{ $pendaftarandata->pasien->nama }}</td>
                                        <td class="text-center">
                                            @switch($pendaftarandata->status->Status_aplikasi)
                                                @case(1)
                                                    Aplikasi Offline
                                                    @break
                                                @case(2)
                                                    Aplikasi Online
                                                    @break
                                                @case(3)
                                                    Sistem BPJS / MJKN
                                                    @break
                                                @default
                                                    Tidak diketahui
                                            @endswitch
                                        </td>
                                        <td class="text-center">{{ $pendaftarandata->nomor_register }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($pendaftarandata->tanggal_kujungan)->format('d-m-Y') }}</td>
                                        <td class="text-center">{{ $pendaftarandata->nomor_rm }}</td>
                                        <td class="text-center">{{ $pendaftarandata->antrian }}</td>
                                        <td class="text-center">{{ $pendaftarandata->poli->nama }}</td>
                                        <td class="text-center">{{ $pendaftarandata->penjamin->nama }}</td>
                                        <td class="text-center">{{ $pendaftarandata->dokter->namauser->name }}</td>
                                        <td class="text-center">
                                            <!-- Batal -->
                                            @if ($pendaftarandata->status->status_pendaftaran == 1)
                                            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill batal-data-pasien me-1"
                                                data-id="{{ $pendaftarandata->status->id }}"
                                                data-nama-pasien="{{ $pendaftarandata->pasien->nama }}">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill panggil-data-pasien me-1"
                                                data-id="{{ $pendaftarandata->status->id }}"
                                                data-nama-pasien="{{ $pendaftarandata->pasien->nama }}">
                                                <i class="fas fa-phone"></i> Hadir
                                            </button>
                                            @endif
                                            @if ($pendaftarandata->status->status_pendaftaran == 2)
                                            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill batal-data-pasien-pcare me-1"
                                                data-id="{{ $pendaftarandata->status->id }}"
                                                data-nama-pasien="{{ $pendaftarandata->pasien->nama }}">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </button>
                                            @endif
                                        </td>

                                    </tbody>
                                    @endforeach
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
<div class="modal fade" id="addreispasienModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormsuku" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Pasien</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="pasien" name="pasien" value="{{ old('pasien') }}">
                                    <option value="" disabled selected>Pilih Pasien</option>
                                    @foreach ($pasiens as $pasiendata)
                                        <option value="{{ $pasiendata->id }}">{{ $pasiendata->nama }} - {{ $pasiendata->no_rm }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Poli</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="poli_id" name="poli_id">
                                    <option value="" disabled selected>Pilih Poli</option>
                                    @foreach ($poli as $polidata)
                                        <option value="{{ $polidata->id }}">{{ $polidata->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Jadwal Kunjungan</label>
                                <input type="text" class="form-control datetimepicker-input" id="tanggal_kunjungan" name="tanggal_kunjungan" data-toggle="datetimepicker" data-target="#tanggal_kunjungan"/>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Dokter</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="dokter_id" name="dokter_id">
                                    <option value="" disabled selected>Pilih Dokter</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Penjamin Pasien</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="penjamin_id" name="penjamin_id">
                                    @foreach ($penjamin as $penjamindata)
                                        <option value="{{ $penjamindata->id }}">{{ $penjamindata->nama }}</option>
                                    @endforeach
                                </select>
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

{{-- modal Panggil Role --}}
<div class="modal fade" id="panggilModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormbatal" action="{{ route('pendaftaran.hadir') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmasi Pendaftaran Pasien Hadir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="hadirid_delete" name="hadirid_delete">
                    <div id="deleteTexthadir"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Hadir</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- modal Delete Role --}}
<div class="modal fade" id="deletebatalModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormbatal" action="{{ route('pendaftaran.batal') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Batal Pendaftaran Pasien</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="batalid_delete" name="batalid_delete">
                    <div id="deleteTextbatal"></div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="alasanpembatalan">Alasan Pembatalan</label>
                            <input type="text" class="form-control" id="alasanpembatalan" name="alasanpembatalan" placeholder="Masukkan lasan pembatalan" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- modal Delete Role --}}
<div class="modal fade" id="deleterubahModal" tabindex="-1" role="dialog" aria-labelledby="deleterubahModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormrubah" action="{{ route('pendaftaran.dokter.update') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleterubahModalLabel">Peruabahan Dokter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="rubahdokter_id" name="rubahdokter_id">
                    <input type="hidden" id="poli_id_update" name="poli_id_update">
                    <input type="hidden" id="tanggal_kunjungan_update" name="tanggal_kunjungan_update">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label id="namapasien"></label>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Dokter</label>
                            <select class="form-control select2bs4" style="width: 100%;" id="dokter_id_update" name="dokter_id_update">
                                <option value="" disabled selected>Pilih Dokter</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Detail Data Box --}}
<div class="modal fade" id="rekapModal" tabindex="-1" role="dialog" aria-labelledby="rekapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="rekapModalLabel"></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @foreach($rekapPerPoliDokter as $rekap)
                        <div class="col-md-4 col-sm-6 mb-3 mx-auto">
                            <div class="p-3 border rounded bg-light text-center">
                                <h4>Pasien Terdaftar: {{ $rekap->jumlah }}</h4>
                                <p class="mb-1"><strong>Poli:</strong> {{ $rekap->poli->nama ?? 'Tidak diketahui' }}</p>
                                <p class="mb-0"><strong>Dokter:</strong> {{ $rekap->dokter->namauser->name ?? 'Tidak diketahui' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(function () {
    $('#tanggal_kunjungan').datetimepicker({
        format: 'YYYY-MM-DD HH:mm', // Format 24 jam
        icons: { time: 'far fa-clock' } // Tidak muncul icon di input
    });
});
</script>
<script>
    $(document).ready(function () {
        $('#poli_id').on('change', ambilDokter);
        $('#tanggal_kunjungan').on('change.datetimepicker', ambilDokter);

        function ambilDokter() {
            let poliId = $('#poli_id').val();
            let datetime = $('#tanggal_kunjungan').val();

            if (poliId && datetime) {
                let formattedDatetime = datetime + ':00';
                console.log("Mengambil dokter...");
                console.log("Poli ID:", poliId);
                console.log("Datetime:", formattedDatetime);

                $.ajax({
                    url: `/api/get-dokter-by-poli/${poliId}`,
                    method: 'GET',
                    data: { datetime: formattedDatetime },
                    success: function (data) {
                        $('#dokter_id').empty().append(`<option value="">Pilih Dokter</option>`);
                        data.forEach(function (dokter) {
                            $('#dokter_id').append(`<option value="${dokter.id}">${dokter.namauser.name}</option>`);
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal mengambil data dokter!'
                        });
                    }
                });
            }
        }


        // Form submit untuk tambah pasien dengan SweetAlert
        $('#addFormsuku').on('submit', function(e) {
            e.preventDefault();

            // Validasi form sebelum submit
            let isValid = true;
            let errorMessages = [];

            // Validasi Pasien
            if (!$('#pasien').val()) {
                isValid = false;
                errorMessages.push('Pasien harus dipilih');
            }

            // Validasi Poli
            if (!$('#poli_id').val()) {
                isValid = false;
                errorMessages.push('Poli harus dipilih');
            }

            // Validasi Tanggal Kunjungan
            if (!$('#tanggal_kunjungan').val()) {
                isValid = false;
                errorMessages.push('Tanggal kunjungan harus diisi');
            }

            // Validasi Dokter
            if (!$('#dokter_id').val()) {
                isValid = false;
                errorMessages.push('Dokter harus dipilih');
            }

            // Validasi Penjamin
            if (!$('#penjamin_id').val()) {
                isValid = false;
                errorMessages.push('Penjamin pasien harus dipilih');
            }

            // Jika tidak valid, tampilkan error
            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Tidak Lengkap!',
                    html: errorMessages.join('<br>'),
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menambah data pasien ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Tambah!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Menyimpan...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Submit form dengan AJAX
                    $.ajax({
                        url: "{{ route('pendaftaran.add') }}",
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Cetak atau tampilkan nomor antrian
                                        let noAntrian = response.noantrian || 'Tidak ada nomor antrian';
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'Nomor Antrian Anda',
                                            html: `<div id="printArea"><h1 style="font-size: 3rem; text-align: center;">${noAntrian}</h1></div>`,
                                            showConfirmButton: true
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Buat jendela print khusus
                                                let printContents = document.getElementById('printArea').innerHTML;
                                                let originalTitle = document.title;
                                                let printWindow = window.open('', '', 'height=500,width=400');

                                                printWindow.document.write('<html><head><title>Cetak Nomor Antrian</title>');
                                                printWindow.document.write('</head><body style="text-align:center; font-family:sans-serif;">');
                                                printWindow.document.write(printContents);
                                                printWindow.document.write('</body></html>');

                                                printWindow.document.close();
                                                printWindow.focus();
                                                printWindow.print();
                                                printWindow.close();
                                            }
                                        }).then(() => {
                                            location.reload(); // Reload halaman untuk update data
                                        });
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message || 'Terjadi kesalahan saat memproses data'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            let errorMessage = 'Terjadi kesalahan saat menyimpan data.';

                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                // Validasi Laravel
                                let messages = [];
                                Object.values(xhr.responseJSON.errors).forEach(msgArr => {
                                    msgArr.forEach(msg => messages.push(msg));
                                });
                                errorMessage = messages.join('<br>');
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                // Coba ambil dari "message"
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                                // Ambil dari "error" (kasus kamu)
                                errorMessage = xhr.responseJSON.error;
                            } else if (xhr.status >= 500) {
                                errorMessage = 'Terjadi kesalahan server.';
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                html: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
</script>

<script>
        $('#deleteFormhadir').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#panggilModal').modal('hide');
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

<script>
    $(document).ready(function() {
        $("#userstabel").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": [
            {
                text: '<i class="fas fa-plus"></i> Tambah',
                type: 'button',
                className: 'btn btn-primary',
                action: function () {
                $('#addreispasienModal').modal('show'); // <== Bootstrap 4-compatible
                }
            },
            ],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                "infoEmpty": "Tidak ada entri yang tersedia",
                "infoFiltered": "(difilter dari _MAX_ total entri)",
            }
        }).buttons().container().appendTo('#userstabel_wrapper .col-md-6:eq(0)');
    });

    // SweetAlert untuk Batal Pendaftaran
    $(document).on('click', '.batal-data-pasien-pcare', function() {
        let id = $(this).data('id');
        let name = $(this).data('nama-pasien');

        Swal.fire({
            title: 'Batal Pendaftaran PCare',
            html: `
                <div class="text-left">
                    <p>Apakah Anda yakin ingin membatalkan antrian pasien <strong>${name}</strong>?</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Tidak',
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Membatalkan...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                // Submit pembatalan
                $.ajax({
                    url: "{{ route('pendaftaran.batal.pcare') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        batalid_delete: id
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Pendaftaran berhasil dibatalkan',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Terjadi kesalahan saat membatalkan pendaftaran';

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
                            title: 'Gagal Batalkan Pendaftaran!',
                            html: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    // SweetAlert untuk Batal Pendaftaran
    $(document).on('click', '.batal-data-pasien', function() {
        let id = $(this).data('id');
        let name = $(this).data('nama-pasien');

        Swal.fire({
            title: 'Batal Pendaftaran Antrian',
            html: `
                <div class="text-left">
                    <p>Apakah Anda yakin ingin membatalkan antrian pasien <strong>${name}</strong>?</p>
                    <div class="form-group mt-3">
                        <label for="alasan_batal">Alasan Pembatalan:</label>
                        <input type="text" id="alasan_batal" class="form-control" placeholder="Masukkan alasan pembatalan" required>
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Tidak',
            preConfirm: () => {
                const alasan = document.getElementById('alasan_batal').value;
                if (!alasan) {
                    Swal.showValidationMessage('Alasan pembatalan harus diisi');
                    return false;
                }
                return alasan;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Membatalkan...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                // Submit pembatalan
                $.ajax({
                    url: "{{ route('pendaftaran.batal') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        batalid_delete: id,
                        alasanpembatalan: result.value
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Pendaftaran berhasil dibatalkan',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Terjadi kesalahan saat membatalkan pendaftaran';

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
                            title: 'Gagal Batalkan Pendaftaran!',
                            html: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    // SweetAlert untuk Panggil Pasien (Hadir)
    $(document).on('click', '.panggil-data-pasien', function() {
        let id = $(this).data('id');
        let name = $(this).data('nama-pasien');

        Swal.fire({
            title: 'Konfirmasi Kehadiran',
            text: `Apakah pasien ${name} sudah hadir?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hadir!',
            cancelButtonText: 'Belum Hadir'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                // Submit kehadiran
                $.ajax({
                    url: "{{ route('pendaftaran.hadir') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        hadirid_delete: id
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Status kehadiran pasien berhasil diupdate',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Terjadi kesalahan saat mengupdate status kehadiran';

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
                            title: 'Gagal Update Status!',
                            html: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
</script>

{{-- Script untuk menampilkan pesan sukses/error dari session --}}
@if(session('success'))
<script>
    $(document).ready(function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000
        });
    });
</script>
@endif

@if(session('error'))
<script>
    $(document).ready(function() {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            showConfirmButton: true
        });
    });
</script>
@endif

@if($errors->any())
<script>
    $(document).ready(function() {
        let errorMessages = @json($errors->all());
        Swal.fire({
            icon: 'error',
            title: 'Validation Error!',
            html: errorMessages.join('<br>'),
            showConfirmButton: true
        });
    });
</script>
@endif

@endsection
