@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard v1</li>
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
                                <h3 class="card-title">Data Barang</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#adddabarModal" >
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                    <!-- Tombol Export -->
                                    <a href="{{ route('dabar.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>

                                    <!-- Tombol Import (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importdabarModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="dabartabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode</th>
                                            <th class="text-center">Nama Barang</th>
                                            <th class="text-center">Nama Industri</th>
                                            <th class="text-center">Jenis Formularium</th>
                                            <th class="text-center">Jenis Generik</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dabar as $dabardata)
                                            <tr>
                                                <td class="text-center">{{ $dabardata->kode_barang }}</td>
                                                <td class="text-center">{{ $dabardata->nama_barang }}</td>
                                                <td class="text-center">{{ $dabardata->nama_industri_barang }}</td>
                                                <td class="text-center">{{ $dabardata->jenis_formularium }}</td>
                                                <td class="text-center">{{ $dabardata->jenis_obat }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-dabar"
                                                        data-toggle="modal" data-id="{{ $dabardata->id }}"
                                                        data-nama-dabar="{{ $dabardata->nama_barang }}"
                                                        data-target="#editdabarModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-goldar"
                                                        data-toggle="modal"data-id="{{ $dabardata->id }}"
                                                        data-nama-dabar="{{ $dabardata->nama_barang }}"
                                                        data-target="#deletedabarModal">
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

<!-- Modal Add Gudang Barang Input -->
<div class="modal fade" id="adddabarModal" tabindex="-1" role="dialog" aria-labelledby="adddabarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adddabarModalLabel">Input Gudang Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormdabar" action="{{ route('dabar.store') }}" method="POST">
                    @csrf
                    <div class="bs-stepper">
                        <div class="bs-stepper-header" role="tablist">
                            <!-- Step 1: Informasi Umum Barang -->
                            <div class="step" data-target="#informasi-umum">
                                <button type="button" class="step-trigger" role="tab" aria-controls="informasi-umum" id="informasi-umum-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Informasi Umum</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <!-- Step 2: Satuan dan Nilai Satuan -->
                            <div class="step" data-target="#satuan-dan-nilai">
                                <button type="button" class="step-trigger" role="tab" aria-controls="satuan-dan-nilai" id="satuan-dan-nilai-trigger">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Satuan dan Nilai</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <!-- Step 3: Penyimpanan dan Informasi Lainnya -->
                            <div class="step" data-target="#penyimpanan-dan-informasi">
                                <button type="button" class="step-trigger" role="tab" aria-controls="penyimpanan-dan-informasi" id="penyimpanan-dan-informasi-trigger">
                                    <span class="bs-stepper-circle">3</span>
                                    <span class="bs-stepper-label">Penyimpanan dan Lainnya</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <!-- Step 1: Informasi Umum Barang -->
                            <div id="informasi-umum" class="content" role="tabpanel" aria-labelledby="informasi-umum-trigger">
                                <div class="row">
                                    <input type="hidden" class="form-control" id="kode_barang" name="kode_barang">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_barang">Nama Barang</label>
                                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kode_kfa">Kode KFA</label>
                                            <input type="text" class="form-control" id="kode_kfa" name="kode_kfa" readonly value="001">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jenis_formularium">Jenis Formularium</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="jenis_formularium" name="jenis_formularium">
                                                <option value="" disabled selected>Pilih Jenis Formularium</option>
                                                <option value="Formularium">Formularium</option>
                                                <option value="Non Formularium">Non Formularium</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="industri_barang">Industri Barang</label>
                                            <input type="text" class="form-control" id="industri_barang" name="industri_barang" readonly value="PT 123">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="jenis_obat">Jenis Obat</label>
                                        <div class="form-group d-flex" id="jenis_obat_container" style="width: 100%;">
                                            <select class="form-control select2bs4" id="jenis_obat" name="jenis_obat" style="width: 100%;">
                                                <option value="" disabled selected>Pilih Jenis Obat</option>
                                                <option value="Generik">Generik</option>
                                                <option value="Non Generik">Non Generik</option>
                                            </select>

                                            <input type="text" class="form-control ml-2" id="jenis_generik" name="jenis_generik" placeholder="Masukkan jenis generik" style="display: none; width: 100%;">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                            </div>

                            <!-- Step 2: Satuan dan Nilai Satuan -->
                            <div id="satuan-dan-nilai" class="content" role="tabpanel" aria-labelledby="satuan-dan-nilai-trigger">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="satuan_kecil">Satuan Kecil</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="satuan_kecil" name="satuan_kecil">
                                                <option value="" disabled selected>Pilih Satuan Kecil</option>
                                                @foreach ($satuan as $satuanKecil)
                                                    <option value="{{ $satuanKecil->nama }}">{{ $satuanKecil->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nilai_satuan_kecil">Nilai Satuan Kecil</label>
                                            <div class="d-flex align-items-center">
                                                <input type="number" class="form-control flex-grow-1 mr-2" id="nilai_satuan_kecil" name="nilai_satuan_kecil" placeholder="Masukkan nilai satuan kecil">
                                                <label id="label_satuan_kecil" class="text-nowrap">Dalam 1</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="satuan_sedang">Satuan Sedang</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="satuan_sedang" name="satuan_sedang">
                                                <option value="" disabled selected>Pilih Satuan Sedang</option>
                                                @foreach ($satuan as $satuanSedang)
                                                    <option value="{{ $satuanSedang->nama }}">{{ $satuanSedang->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nilai_satuan_sedang">Nilai Satuan Sedang</label>
                                            <div class="d-flex align-items-center">
                                                <input type="number" class="form-control flex-grow-1 mr-2" id="nilai_satuan_sedang" name="nilai_satuan_sedang" placeholder="Masukkan nilai satuan sedang">
                                                <label id="label_satuan_sedang" class="text-nowrap">Dalam 1</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="satuan_besar">Satuan Besar</label>
                                            <select class="form-control select2bs4" id="satuan_besar" name="satuan_besar">
                                                <option value="" disabled selected>Pilih Satuan Besar</option>
                                                @foreach ($satuan as $satuanBesar)
                                                    <option value="{{ $satuanBesar->nama }}">{{ $satuanBesar->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                            </div>

                            <!-- Step 3: Penyimpanan dan Informasi Lainnya -->
                            <div id="penyimpanan-dan-informasi" class="content" role="tabpanel" aria-labelledby="penyimpanan-dan-informasi-trigger">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tempat_penyimpanan">Tempat Penyimpanan</label>
                                            <input type="text" class="form-control" id="tempat_penyimpanan" name="tempat_penyimpanan" placeholder="Masukkan tempat penyimpanan">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Masukkan barcode">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="barang_kategori">Kategori Barang</label>
                                            <select class="form-control select2bs4" id="barang_kategori" name="barang_kategori">
                                                <option value="" disabled selected>Pilih Kategori Barang</option>
                                                @foreach ($kategori as $kategoriData)
                                                    <option value="{{ $kategoriData->nama }}">{{ $kategoriData->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bentuk_sediaan">Bentuk Sediaan</label>
                                            <select class="form-control select2bs4" id="bentuk_sediaan" name="bentuk_sediaan">
                                                <option value="" disabled selected>Pilih Bentuk Sediaan</option>
                                                <option value="Padat">Padat</option>
                                                <option value="Cair">Cair</option>
                                                <option value="Gas">Gas</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- modal Edit Role --}}
<div class="modal fade" id="editdabarModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Master Data dabar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormdabar" action="{{ route('dabar.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="dabarid_edit" name="dabarid_edit">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nama Jenis dabar</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama Jenis dabar" required>
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
<div class="modal fade" id="deletedabarModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormdabar" action="{{ route('dabar.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Master Data dabar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="dabarid_delete" name="dabarid_delete">
                    <div id="deleteTextdabar"></div>
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
<div class="modal fade" id="importdabarModal" tabindex="-1" role="dialog" aria-labelledby="importdabarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importdabarModalLabel">Import Data dabar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('dabar.import') }}" method="POST" enctype="multipart/form-data">
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
            $("#dabartabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#dabartabel_wrapper .col-md-6:eq(0)');
        });

        $(document).ready(function() {
            $('#adddabarModal').on('shown.bs.modal', function () {
                $.ajax({
                    url: '/api/generate-kode-data-barang', // Pastikan sesuai dengan route API-mu
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#kode_barang').val(response.kode_barang); // Isi hidden input
                        }
                    },
                    error: function() {
                        console.error("Gagal generate kode barang");
                    }
                });
            });
        });


        $('#addFormdabar').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#adddabarModal').modal('hide');
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
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        let errorList = '';

                        // Hapus class 'is-invalid' dari semua input dulu (optional, biar bersih)
                        $('#addFormdabar').find('.is-invalid').removeClass('is-invalid');

                        Object.entries(xhr.responseJSON.errors).forEach(([key, value]) => {
                            errorList += `- ${value[0]}<br>`;
                            $(`#${key}`).addClass('is-invalid'); // Tambahkan class error ke input
                        });

                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal!',
                            html: `Terdapat beberapa input yang belum valid:<br><br>${errorList}`,
                        });
                    } else {
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
                }
            });
        });

        $(document).on('click', '.edit-data-dabar', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-dabar');

            $('#dabarid_edit').val(id);
            $('#nama_edit').val(nama);
             // Pastikan rhesus terpilih dengan benar
        });

        $('#editFormdabar').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editdabarModa').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate dabar!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-goldar', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-dabar');

            $('#dabarid_delete').val(id);
            $('#deleteTextdabar').html(
            `<span>Apa Anda yakin ingin menghapus data dabar <b>${name}</b> ?</span>`);
        });

        $('#deleteFormdabar').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deletedabarModal').modal('hide');
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

{{-- Tambahan --}}
<script>
    $(document).ready(function () {
        // Jika pakai Select2, gunakan event dari jQuery
        $('#satuan_kecil').on('change', function () {
            const value = $(this).val();
            $('#label_satuan_kecil').text(value + ' Dalam 1');
        });

        $('#satuan_sedang').on('change', function () {
            const value = $(this).val();
            $('#label_satuan_sedang').text(value + ' Dalam 1');
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })
</script>

<script>
    $(document).ready(function() {
        $('#jenis_obat').change(function() {
            var selectedValue = $(this).val();  // Mendapatkan nilai yang dipilih
            var jenisGenerikInput = $('#jenis_generik');
            var jenisObatSelect = $('#jenis_obat');

            if (selectedValue === 'Generik') {
                // Ketika Generik dipilih
                $('#jenis_obat_container').css('display', 'flex');  // Memastikan kontainer tetap flex
                jenisObatSelect.css('width', '0%');  // Set lebar select menjadi 50%
                jenisGenerikInput.show().css('width', '100%');  // Menampilkan input teks untuk jenis generik dan set lebar menjadi 50%
            } else {
                // Ketika Non Generik dipilih
                jenisObatSelect.css('width', '100%');  // Set lebar select menjadi 100%
                jenisGenerikInput.hide();  // Menyembunyikan input teks
                jenisGenerikInput.val('Non Generic');  // Memberikan nilai "Non Generic" pada input ketika Non Generik dipilih
            }
        });
    });
</script>
@endsection
