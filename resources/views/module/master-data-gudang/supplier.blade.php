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
                                <h3 class="card-title">Supplier Industri</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addsupplierModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                    <!-- Tombol Export -->
                                    <a href="{{ route('supplier.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>

                                    <!-- Tombol Import (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importsupplierModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="suppliertabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode</th>
                                            <th class="text-center">Nama Supplier Industri</th>
                                            <th class="text-center">Nama PIC</th>
                                            <th class="text-center">No. Telepon PIC</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($supplier as $supplierdata)
                                            <tr>
                                                <td class="text-center">{{ $supplierdata->kode }}</td>
                                                <td class="text-center">{{ $supplierdata->nama }}</td>
                                                <td class="text-center">{{ $supplierdata->nama_pic }}</td>
                                                <td class="text-center">{{ $supplierdata->telepon_pic }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-supplier"
                                                        data-toggle="modal" data-id="{{ $supplierdata->id }}"
                                                        data-nama-supplier="{{ $supplierdata->nama }}"
                                                        data-nama-pic-supplier="{{ $supplierdata->nama_pic }}"
                                                        data-telepon-pic-supplier="{{ $supplierdata->telepon_pic }}"
                                                        data-target="#editsupplierModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-goldar"
                                                        data-toggle="modal"data-id="{{ $supplierdata->id }}"
                                                        data-nama-supplier="{{ $supplierdata->nama }}"
                                                        data-nama-pic-supplier="{{ $supplierdata->nama_pic }}"
                                                        data-telepon-pic-supplier="{{ $supplierdata->telepon_pic }}"
                                                        data-target="#deletesupplierModal">
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

{{-- modal Add Role --}}
<div class="modal fade" id="addsupplierModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Master Data Supplier Industri</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormsupplier" action="{{ route('supplier.store') }}" method="POST">
                    @csrf
                    <input type="hidden" class="form-control" id="kode" name="kode">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nama Supplier Industri</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Supplier Industri" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama PIC</label>
                                <input type="text" class="form-control" id="nama_pic" name="nama_pic" placeholder="Nama PIC Supplier" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Telepon PIC</label>
                                <input type="text" class="form-control telepon" id="telepon_pic" name="telepon_pic" placeholder="No. Telepon PIC" required>
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
<div class="modal fade" id="editsupplierModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Master Data supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormsupplier" action="{{ route('supplier.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="supplierid_edit" name="supplierid_edit">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nama Supplier Industri</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama Supplier Industri" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama PIC</label>
                                <input type="text" class="form-control" id="nama_pic_edit" name="nama_pic_edit" placeholder="Nama PIC Supplier" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Telepon PIC</label>
                                <input type="text" class="form-control telepon" id="telepon_pic_edit" name="telepon_pic_edit" placeholder="No Telepon PIC Supplier" required>
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
<div class="modal fade" id="deletesupplierModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormsupplier" action="{{ route('supplier.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Master Data supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="supplierid_delete" name="supplierid_delete">
                    <div id="deleteTextsupplier"></div>
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
<div class="modal fade" id="importsupplierModal" tabindex="-1" role="dialog" aria-labelledby="importsupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importsupplierModalLabel">Import Data supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('supplier.import') }}" method="POST" enctype="multipart/form-data">
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
            $("#suppliertabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#suppliertabel_wrapper .col-md-6:eq(0)');

            $('.telepon').on('input', function () {
                let raw = $(this).val().replace(/\D/g, '').substring(0, 12); // Ambil hanya angka, batasi 12 digit
                let formatted = raw.match(/.{1,4}/g)?.join('-') || ''; // Format jadi XXXX-XXXX-XXXX
                $(this).val(formatted);
            });
        });

    //SCRIPT ADD
        $('#addFormsupplier').on('submit', function(e) {
            e.preventDefault();

            const form = $(this); // simpan referensi form

            generateKode(function (kodeBaru) {
                $('#kode').val(kodeBaru);

                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#addsupplierModal').modal('hide');
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

        $(document).on('click', '.edit-data-supplier', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-supplier');
            var nama_pic = $(this).data('nama-pic-supplier');
            var telepon_pic = $(this).data('telepon-pic-supplier');

            $('#supplierid_edit').val(id);
            $('#nama_edit').val(nama);
            $('#nama_pic_edit').val(nama_pic);
            $('#telepon_pic_edit').val(telepon_pic);
        });

        $('#editFormsupplier').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editsupplierModa').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate supplier!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-goldar', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-supplier');

            $('#supplierid_delete').val(id);
            $('#deleteTextsupplier').html(
            `<span>Apa Anda yakin ingin menghapus data supplier <b>${name}</b> ?</span>`);
        });

        $('#deleteFormsupplier').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deletesupplierModal').modal('hide');
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

        //SCRIPT TAMBAHAN
        function generateKode(callback) {
            $.ajax({
                url: "{{ route('supplier_industri.getLastKode') }}",
                method: 'GET',
                success: function (res) {
                    let lastKode = res.kode || 'SUP-0000'; // fallback kalau belum ada data
                    let number = parseInt(lastKode.split('-')[1]) + 1;
                    let newKode = 'SUP-' + number.toString().padStart(4, '0');
                    callback(newKode);
                },
                error: function () {
                    alert('Gagal mendapatkan kode otomatis.');
                }
            });
        }
</script>
@endsection
