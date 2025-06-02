@extends('layouts.dashbord')


@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Pendaftaran Pasien</h1>
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
                <div class="col-lg-6 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $pasienallold }}</h3>

                            <p>Total Pasien Terdaftar</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-6 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $pasienallnewnow }}</h3>

                            <p>Total Pasien Selesai Di layani</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addreispasienModal">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>

                            </div>
                        </div>
                        <div class="card-body">
                            <table id="userstabel" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        {{-- <th class="text-center">No Antrian</th> --}}
                                        <th class="text-center">Nama Pasien</th>
                                        <th class="text-center">Pedaftaran</th>
                                        <th class="text-center">No Registarsi</th>
                                        <th class="text-center">Tanggal Registarsi</th>
                                        <th class="text-center">No RM</th>
                                        <th class="text-center">No. Antrian</th>
                                        <th class="text-center">Poli Tujuan</th>
                                        <th class="text-center">Nama Dokter</th>
                                        <th class="text-center" width="15%">Action</th>
                                    </tr>
                                </thead>
                                @foreach ($pendaftaran as $pendaftarandata)
                                <tbody>
                                        <td class="text-center">{{ $pendaftarandata->pasien->nama }}</td>
                                        <td class="text-center">
                                            @switch($pendaftarandata->status->Status_aplikasi)
                                                @case(1)
                                                    Applikasi Offline
                                                    @break
                                                @case(2)
                                                    Applikasi Onlaine
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
                                        <td class="text-center">{{ $pendaftarandata->dokter->namauser->name }}</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-success btn-flat dropdown-toggle dropdown-icon" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                                   Pilih Aksi
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a class="dropdown-item batal-data-pasien" data-toggle="modal" data-target="#deletebatalModal" data-id="{{ $pendaftarandata->status->id }}"
                                                        data-nama-pasien="{{ $pendaftarandata->pasien->nama }}" ><i class="fas fa-trash"></i> Batal</a></li>
                                                        @if ($pendaftarandata->status->status_pendaftaran == 1)
                                                        <li>
                                                            <a class="dropdown-item panggil-data-pasien"
                                                               data-toggle="modal"
                                                               data-target="#panggilModal"
                                                               data-id="{{ $pendaftarandata->status->id }}"
                                                               data-nama-pasien="{{ $pendaftarandata->pasien->nama }}">
                                                               <i class="fas fa-trash"></i> Panggil
                                                            </a>
                                                        </li>
                                                    @endif

                                                    <li><a class="dropdown-item dokter-data-pasien" data-toggle="modal" data-id="{{ $pendaftarandata->id }}" data-poli="{{ $pendaftarandata->poli_id }}" data-nama="{{ $pendaftarandata->pasien->nama }}"  data-tgl-kunjung="{{ $pendaftarandata->tanggal_kujungan }}" data-target="#deleterubahModal"><i class="fas fa-trash"></i> Rubah Dokter</a></li>
                                                </ul>
                                            </div>
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
                <form id="addFormsuku" action="{{ route('pendaftaran.add') }}" method="POST">
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
                                <label>Jadwal Kunjunagan</label>
                                <input type="datetime-local" class="form-control" id="tanggal_kunjungan" name="tanggal_kunjungan">
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
                <button type="submit" class="btn btn-primary">Tambah</button> <!-- Submit button -->
            </div>
            </form>
        </div>
    </div>
</div>

{{-- modal Panggil Role --}}
<div class="modal fade" id="panggilModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormhadir" action="{{ route('pendaftaran.hadir') }}" method="POST">
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


<script>
    $(document).ready(function () {
        // Jika poli atau tanggal berubah
        $('#poli_id, #tanggal_kunjungan').on('change', function () {
            let poliId = $('#poli_id').val();
            let datetime = $('#tanggal_kunjungan').val(); // format input datetime-local: yyyy-MM-ddTHH:mm

            if (poliId && datetime) {
                // Format datetime jadi Y-m-d H:i:s
                let formattedDatetime = datetime.replace('T', ' ') + ':00';

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
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                        console.error("Response Text:", xhr.responseText);
                        alert('Gagal mengambil data dokter.');
                    }
                });
            }
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

    $(document).on('click', '.dokter-data-pasien', function() {
            let id = $(this).data('id');
            let tanggal = $(this).data('tgl-kunjung');
            let poli = $(this).data('poli');
            let nama = $(this).data('nama');

            $('#rubahdokter_id').val(id);
            $('#tanggal_kunjungan_update').val(tanggal);
            $('#poli_id_update').val(poli);

            $('#namapasien').html(
            `<span> Pasien Dengan nama  <b>${nama}</b> </span>`);

            if (poli && tanggal) {
                // Format datetime jadi Y-m-d H:i:s
                let formattedDatetime = tanggal.replace('T', ' ') + ':00';

                $.ajax({
                    url: `/api/get-dokter-by-poli/${poli}`,
                    method: 'GET',
                    data: { datetime: formattedDatetime },
                    success: function (data) {
                        $('#dokter_id_update').empty().append(`<option value="">Pilih Dokter</option>`);
                        data.forEach(function (dokter) {
                            $('#dokter_id_update').append(`<option value="${dokter.id}">${dokter.namauser.name}</option>`);
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                        console.error("Response Text:", xhr.responseText);
                        alert('Gagal mengambil data dokter.');
                    }
                });
            }
    });
</script>





<script>
    $(document).ready(function() {
        $("#userstabel").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": [
                "csv",
                "excel",
                "pdf",
                "print",
            ]
        }).buttons().container().appendTo('#userstabel_wrapper .col-md-6:eq(0)');
    });

    $(document).on('click', '.batal-data-pasien', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-pasien');

            $('#batalid_delete').val(id);
            $('#deleteTextbatal').html(
            `<span>Apa Anda yakin ingin membatalkan Antrian Pasien <b>${name}</b> ?</span>`);
        });
    $(document).on('click', '.panggil-data-pasien', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-pasien');

            $('#hadirid_delete').val(id);
            $('#deleteTexthadir').html(
            `<span>Apa Anda yakin Pasien <b>${name}</b> Hadir ?</span>`);
        });

</script>
@endsection
