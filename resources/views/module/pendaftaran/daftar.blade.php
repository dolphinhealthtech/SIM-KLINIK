@extends('layouts.dashbord')


@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pasien</h1>
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
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $pasienallold }}</h3>

                            <p>Total Pasien lama</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $pasienallnewnow }}</h3>

                            <p>Total Pasien Baru Bulan ini</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $pasienall }}</h3>

                            <p>Total Pasien</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $pasiennoverif }}</h3>

                            <p>Pasien Belun Verifikasi</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
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
                                        <th class="text-center">No Antrian</th>
                                        <th class="text-center">No Registarsi</th>
                                        <th class="text-center">Tanggal Registarsi</th>
                                        <th class="text-center">No RM</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Nomor Telepon</th>
                                        <th class="text-center" width="25%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

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
                <form id="addFormsuku" action="{{ route('suku.store') }}" method="POST">
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
</script>
@endsection
