@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Master Asuransi</h1>
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
                                <h3 class="card-title">Master Asuransi</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addasuransiModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                    <!-- Tombol Export -->
                                    <a href="{{ route('asuransi.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>

                                    <!-- Tombol Import (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importasuransiModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="asuransitabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asuransi as $asuransidata)
                                            <tr>
                                                <td class="text-center">{{ $asuransidata->nama }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-asuransi"
                                                        data-toggle="modal"
                                                        data-id="{{ $asuransidata->id }}"
                                                        data-nama="{{ $asuransidata->nama }}"
                                                        data-kode="{{ $asuransidata->kode }}"
                                                        data-jenis="{{ $asuransidata->jenis_asuransi }}"
                                                        data-verifikasi="{{ $asuransidata->verif_pasien }}"
                                                        data-filter="{{ $asuransidata->filter_obat }}"
                                                        data-tglmulai="{{ $asuransidata->tanggal_mulai }}"
                                                        data-tglakhir="{{ $asuransidata->tanggal_akhir }}"
                                                        data-alamat="{{ $asuransidata->alamat_asuransi }}"
                                                        data-telpas="{{ $asuransidata->no_telp_asuransi }}"
                                                        data-faks="{{ $asuransidata->faksimil }}"
                                                        data-pic="{{ $asuransidata->pic }}"
                                                        data-telppic="{{ $asuransidata->no_telp_pic }}"
                                                        data-jabatan="{{ $asuransidata->jabatan_pic }}"
                                                        data-bank="{{ $asuransidata->bank }}"
                                                        data-rekening="{{ $asuransidata->no_rekening }}"
                                                        data-target="#editasuransiModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>

                                                    <a href="#" class="btn btn-danger btn-sm delete-data-asuransi"
                                                        data-toggle="modal"data-id="{{ $asuransidata->id }}"
                                                        data-nama-asuransi="{{ $asuransidata->nama }}"
                                                        data-target="#deleteasuransiModal">
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
<div class="modal fade" id="addasuransiModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah asuransi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormasuransi" action="{{ route('asuransi.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Nama asuransi</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama asuransi" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Kode asuransi</label>
                                <input type="text" class="form-control" id="kode" name="kode" placeholder="Kode asuransi" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Jenis Asuransi</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="jenis" name="jenis">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="Asuransi">Asuransi</option>
                                    <option value="Perusahaan Swasta">Perusahaan Swasta</option>
                                    <option value="Perusahaan Pemerintah/BUMN/BUMD">Perusahaan Pemerintah/BUMN/BUMD</option>
                                    <option value="Institusi Pemerintah">Institusi Pemerintah</option>
                                    <option value="Yayasan Sosial">Yayasan Sosial</option>
                                    <option value="Lain Lain">Lain Lain</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Verifikai Pasien</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="verifikasi" name="verifikasi">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="Prosedural (Managed)">Prosedural (Managed)</option>
                                    <option value="Bebas (Un-Managed)">Bebas (Un-Managed)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Filter Obat Ditanggung</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="filter_obat" name="filter_obat">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Berlaku Mulai</label>
                                <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Berlaku Hingga</label>
                                <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Alamat Asuransi</label>
                                <input type="text" class="form-control" id="alamat" name="alamat">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>No Telp Asuransi</label>
                                <input type="text" class="form-control" id="no_telp_asuransi" name="no_telp_asuransi">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Faksimil</label>
                                <input type="text" class="form-control" id="faksimil_asuransi" name="faksimil_asuransi">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Contact Person</label>
                                <input type="text" class="form-control" id="pic" name="pic">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Telp Contact Person</label>
                                <input type="text" class="form-control" id="no_telp_pic" name="no_telp_pic">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Jabatan Contact Person</label>
                                <input type="text" class="form-control" id="jabatan_pic" name="jabatan_pic">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bank Akun</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="bank" name="bank">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    @foreach ($bank as $bankdata)
                                        <option value="{{ $bankdata->nama }}">{{ $bankdata->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>No Rekening</label>
                                <input type="text" class="form-control" id="no_rekening" name="no_rekening">
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
<div class="modal fade" id="editasuransiModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit asuransi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormasuransi" action="{{ route('asuransi.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="asuransiid_edit" name="asuransiid_edit">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Nama asuransi</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama asuransi" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Kode asuransi</label>
                                <input type="text" class="form-control" id="kode_edit" name="kode_edit" placeholder="Kode asuransi" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Jenis Asuransi</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="jenis_edit" name="jenis_edit">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="Asuransi">Asuransi</option>
                                    <option value="Perusahaan Swasta">Perusahaan Swasta</option>
                                    <option value="Perusahaan Pemerintah/BUMN/BUMD">Perusahaan Pemerintah/BUMN/BUMD</option>
                                    <option value="Institusi Pemerintah">Institusi Pemerintah</option>
                                    <option value="Yayasan Sosial">Yayasan Sosial</option>
                                    <option value="Lain Lain">Lain Lain</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Verifikai Pasien</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="verifikasi_edit" name="verifikasi_edit">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="Prosedural (Managed)">Prosedural (Managed)</option>
                                    <option value="Bebas (Un-Managed)">Bebas (Un-Managed)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Filter Obat Ditanggung</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="filter_obat_edit" name="filter_obat_edit">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Berlaku Mulai</label>
                                <input type="date" class="form-control" id="tgl_mulai_edit" name="tgl_mulai_edit">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Berlaku Hingga</label>
                                <input type="date" class="form-control" id="tgl_akhir_edit" name="tgl_akhir_edit">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Alamat Asuransi</label>
                                <input type="text" class="form-control" id="alamat_edit" name="alamat_edit">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>No Telp Asuransi</label>
                                <input type="text" class="form-control" id="no_telp_asuransi_edit" name="no_telp_asuransi_edit">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Faksimil</label>
                                <input type="text" class="form-control" id="faksimil_asuransi_edit" name="faksimil_asuransi_edit">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Contact Person</label>
                                <input type="text" class="form-control" id="pic_edit" name="pic_edit">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Telp Contact Person</label>
                                <input type="text" class="form-control" id="no_telp_pic_edit" name="no_telp_pic_edit">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Jabatan Contact Person</label>
                                <input type="text" class="form-control" id="jabatan_pic_edit" name="jabatan_pic_edit">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bank Akun</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="bank_edit" name="bank_edit">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    @foreach ($bank as $bankdata_edit)
                                        <option value="{{ $bankdata_edit->nama }}">{{ $bankdata_edit->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>No Rekening</label>
                                <input type="text" class="form-control" id="no_rekening_edit" name="no_rekening_edit">
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
<div class="modal fade" id="deleteasuransiModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormasuransi" action="{{ route('asuransi.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus asuransi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="asuransiid_delete" name="asuransiid_delete">
                    <div id="deleteTextasuransi"></div>
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
<div class="modal fade" id="importasuransiModal" tabindex="-1" role="dialog" aria-labelledby="importasuransiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importasuransiModalLabel">Import Data asuransi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('asuransi.import') }}" method="POST" enctype="multipart/form-data">
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
            $("#asuransitabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#asuransitabel_wrapper .col-md-6:eq(0)');
        });

        $('#addFormasuransi').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addasuransiModal').modal('hide');
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

        $(document).on('click', '.edit-data-asuransi', function () {
            $('#asuransiid_edit').val($(this).data('id'));
            $('#nama_edit').val($(this).data('nama'));
            $('#kode_edit').val($(this).data('kode'));
            $('#jenis_edit').val($(this).data('jenis')).trigger('change');
            $('#verifikasi_edit').val($(this).data('verifikasi')).trigger('change');
            $('#filter_obat_edit').val($(this).data('filter')).trigger('change');
            $('#tgl_mulai_edit').val($(this).data('tglmulai'));
            $('#tgl_akhir_edit').val($(this).data('tglakhir'));
            $('#alamat_edit').val($(this).data('alamat'));
            $('#no_telp_asuransi_edit').val($(this).data('telpas'));
            $('#faksimil_asuransi_edit').val($(this).data('faks'));
            $('#pic_edit').val($(this).data('pic'));
            $('#no_telp_pic_edit').val($(this).data('telppic'));
            $('#jabatan_pic_edit').val($(this).data('jabatan'));
            $('#bank_edit').val($(this).data('bank')).trigger('change');
            $('#no_rekening_edit').val($(this).data('rekening'));
        });

        $('#editFormasuransi').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editasuransiModa').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate asuransi!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-asuransi', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-asuransi');

            $('#asuransiid_delete').val(id);
            $('#deleteTextasuransi').html(
            `<span>Apa Anda yakin ingin menghapus data asuransi <b>${name}</b> ?</span>`);
        });

        $('#deleteFormasuransi').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deleteasuransiModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus asuransi!',
                    });
                }
            });
        });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tgl_mulai = document.getElementById('tgl_mulai');
        const tgl_akhir = document.getElementById('tgl_akhir');

        tgl_mulai.addEventListener('click', function () {
            tgl_mulai.showPicker?.() || tgl_mulai.focus(); // Buka date picker jika didukung, atau fokus
        });

        tgl_akhir.addEventListener('click', function () {
            tgl_akhir.showPicker?.() || tgl_akhir.focus(); // Buka date picker jika didukung, atau fokus
        });
    });
</script>

@endsection
