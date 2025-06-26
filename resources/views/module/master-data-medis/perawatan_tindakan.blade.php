@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Perawatan Tindakan</h1>
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
                                <h3 class="card-title">Perawatan Tindakan</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addperawatan_tindakanModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                    <!-- Tombol Export -->
                                    <a href="{{ route('perawatan_tindakan.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>

                                    <!-- Tombol Import (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importperawatan_tindakanModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="perawatan_tindakantabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Kategori</th>
                                            <th class="text-center">Tarif Dokter</th>
                                            <th class="text-center">Tarif Perawat</th>
                                            <th class="text-center">Total Tarif</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($perawatan_tindakan as $perawatan_tindakandata)
                                            <tr>
                                                <td class="text-center">{{ $perawatan_tindakandata->kode }}</td>
                                                <td class="text-center">{{ $perawatan_tindakandata->nama }}</td>
                                                <td class="text-center">{{ $perawatan_tindakandata->perawatan_kategori->nama }}</td>
                                                <td class="text-center">Rp {{ number_format((int)$perawatan_tindakandata->tarif_dokter, 0, ',', '.') }}</td>
                                                <td class="text-center">Rp {{ number_format((int)$perawatan_tindakandata->tarif_perawat, 0, ',', '.') }}</td>
                                                <td class="text-center">Rp {{ number_format((int)$perawatan_tindakandata->tarif_total, 0, ',', '.') }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-perawatan_tindakan"
                                                        data-toggle="modal" data-id="{{ $perawatan_tindakandata->id }}"
                                                        data-nama-perawatan_tindakan="{{ $perawatan_tindakandata->nama }}"
                                                        data-kategori-perawatan_tindakan="{{ $perawatan_tindakandata->perawatan_kategori_id }}"
                                                        data-tarif_dokter-perawatan_tindakan="{{ $perawatan_tindakandata->tarif_dokter }}"
                                                        data-tarif_perawat-perawatan_tindakan="{{ $perawatan_tindakandata->tarif_perawat }}"
                                                        data-tarif_total-perawatan_tindakan="{{ $perawatan_tindakandata->tarif_total }}"
                                                        data-target="#editperawatan_tindakanModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-perawatan_tindakan"
                                                        data-toggle="modal"data-id="{{ $perawatan_tindakandata->id }}"
                                                        data-nama-perawatan_tindakan="{{ $perawatan_tindakandata->nama }}"
                                                        data-target="#deleteperawatan_tindakanModal">
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
<div class="modal fade" id="addperawatan_tindakanModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Perawatan Tindakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormperawatan_tindakan" action="{{ route('perawatan_tindakan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" class="form-control" id="kode" name="kode">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama Perawatan Dan Tindakan</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Kategori Perawatan" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Kategori Perawatan Dan Tindakan</label>
                                <select id="kategori" name="kategori" class="form-control" required>
                                    @foreach($kategori as $data_kategori)
                                        <option value="" disabled selected hidden>Pilih Kategori Perawatan...</option>
                                        <option value="{{ $data_kategori->id }}">{{ $data_kategori->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tarif Dokter</label>
                                <input type="text" class="form-control rupiah" id="tarif_dokter" name="tarif_dokter" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tarif Perawat</label>
                                <input type="text" class="form-control rupiah" id="tarif_perawat" name="tarif_perawat" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Total Tarif</label>
                                <input type="text" class="form-control rupiah" id="tarif_total" name="tarif_total" readonly required>
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
<div class="modal fade" id="editperawatan_tindakanModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Perawatan Tindakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormperawatan_tindakan" action="{{ route('perawatan_tindakan.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="perawatan_tindakanid_edit" name="perawatan_tindakanid_edit">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama Perawatan Dan Tindakan</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama Perawatan Tindakan" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Kategori Perawatan Dan Tindakan</label>
                                <select id="kategori_edit" name="kategori_edit" class="form-control" required>
                                    @foreach($kategori as $data_kategori_edit)
                                        <option value="{{ $data_kategori_edit->id }}">{{ $data_kategori_edit->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tarif Dokter</label>
                                <input type="text" class="form-control rupiah" id="tarif_dokter_edit" name="tarif_dokter_edit" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tarif Perawat</label>
                                <input type="text" class="form-control rupiah" id="tarif_perawat_edit" name="tarif_perawat_edit" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Total Tarif</label>
                                <input type="text" class="form-control rupiah" id="tarif_total_edit" name="tarif_total_edit" readonly required>
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
<div class="modal fade" id="deleteperawatan_tindakanModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormperawatan_tindakan" action="{{ route('perawatan_tindakan.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Kategori Perawatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="perawatan_tindakanid_delete" name="perawatan_tindakanid_delete">
                    <div id="deleteTextperawatan_tindakan"></div>
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
<div class="modal fade" id="importperawatan_tindakanModal" tabindex="-1" role="dialog" aria-labelledby="importperawatan_tindakanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importperawatan_tindakanModalLabel">Import Data Kategori Perawatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('perawatan_tindakan.import') }}" method="POST" enctype="multipart/form-data">
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
            $("#perawatan_tindakantabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#perawatan_tindakantabel_wrapper .col-md-6:eq(0)');

            $('.rupiah').on('input', function () {
                let formatted = formatRupiah($(this).val());
                $(this).val(formatted);
            });
        });

    //SCRIPT ADD
        $(document).ready(function() {
            $('#addFormperawatan_tindakan').on('submit', function(e) {
                e.preventDefault();

                const form = $(this); // simpan referensi form

                generateKode(function (kodeBaru) {
                    $('#kode').val(kodeBaru); // Set ke hidden input

                    // Parse tarif ke angka
                    let tarifDokter = parseRupiah($('#tarif_dokter').val());
                    let tarifPerawat = parseRupiah($('#tarif_perawat').val());
                    let tarifTotal = parseRupiah($('#tarif_total').val());

                    // Set kembali input dengan angka murni
                    $('#tarif_dokter').val(tarifDokter);
                    $('#tarif_perawat').val(tarifPerawat);
                    $('#tarif_total').val(tarifTotal);

                    $.ajax({
                        url: form.attr('action'),
                        method: form.attr('method'),
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                $('#addperawatan_tindakanModal').modal('hide');
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
            });
        });

    // SCRIPT EDIT
        $(document).on('click', '.edit-data-perawatan_tindakan', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-perawatan_tindakan');
            var kategori = $(this).data('kategori-perawatan_tindakan');
            var tarif_dokter = $(this).data('tarif_dokter-perawatan_tindakan');
            var tarif_perawat = $(this).data('tarif_perawat-perawatan_tindakan');
            var tarif_total = $(this).data('tarif_total-perawatan_tindakan');

            $('#perawatan_tindakanid_edit').val(id);
            $('#nama_edit').val(nama);
            $('#kategori_edit').val(kategori);
            $('#tarif_dokter_edit').val(tarif_dokter).trigger('input');
            $('#tarif_perawat_edit').val(tarif_perawat).trigger('input');
            $('#tarif_total_edit').val(tarif_total).trigger('input');
        });

        $('#editFormperawatan_tindakan').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            // Parse tarif ke angka
            let tarifDokterEdit = parseRupiah($('#tarif_dokter_edit').val());
            let tarifPerawatEdit = parseRupiah($('#tarif_perawat_edit').val());
            let tarifTotalEdit = parseRupiah($('#tarif_total_edit').val());

            // Set kembali input dengan angka murni
            $('#tarif_dokter_edit').val(tarifDokterEdit);
            $('#tarif_perawat_edit').val(tarifPerawatEdit);
            $('#tarif_total_edit').val(tarifTotalEdit);

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editperawatan_tindakanModa').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate perawatan tindakan!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-perawatan_tindakan', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-perawatan_tindakan');

            $('#perawatan_tindakanid_delete').val(id);
            $('#deleteTextperawatan_tindakan').html(
            `<span>Apa Anda yakin ingin menghapus data perawatan tindakan <b>${name}</b> ?</span>`);
        });

        $('#deleteFormperawatan_tindakan').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deleteperawatan_tindakanModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Perawatan tindakan!',
                    });
                }
            });
        });

    //SCRIPT TAMBAHAN
        function generateKode(callback) {
            $.ajax({
                url: "{{ route('perawatan_tindakan.getLastKode') }}", // Kamu bikin route ini ya di bawah
                method: 'GET',
                success: function (res) {
                    let lastKode = res.kode || 'TDK-0000'; // fallback kalau belum ada data
                    let number = parseInt(lastKode.split('-')[1]) + 1;
                    let newKode = 'TDK-' + number.toString().padStart(4, '0');
                    callback(newKode);
                },
                error: function () {
                    alert('Gagal mendapatkan kode otomatis.');
                }
            });
        }

        function formatRupiah(angka) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa  = split[0].length % 3,
                rupiah  = split[0].substr(0, sisa),
                ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        function parseRupiah(rp) {
            return parseInt(rp.replace(/[^0-9]/g, '')) || 0;
        }

        $(document).ready(function () {
            $('#tarif_dokter, #tarif_perawat').on('input', function () {
                let dokter = parseRupiah($('#tarif_dokter').val());
                let perawat = parseRupiah($('#tarif_perawat').val());
                let total = dokter + perawat;

                $('#tarif_total').val(formatRupiah(total.toString()));
            });

            $('#tarif_dokter_edit, #tarif_perawat_edit').on('input', function () {
                let dokter_edit = parseRupiah($('#tarif_dokter_edit').val());
                let perawat_edit = parseRupiah($('#tarif_perawat_edit').val());
                let total_edit = dokter_edit + perawat_edit;

                $('#tarif_total_edit').val(formatRupiah(total_edit.toString()));
            });
        });
</script>
@endsection

