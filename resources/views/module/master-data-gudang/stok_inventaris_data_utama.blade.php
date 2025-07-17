@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Data Stok Inventaris Utama</h1>
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
                                <h3 class="card-title">Rincian Data Inventaris</h3>
                                <div class="card-tools">
                                    <a href="{{ route('stokin_utama.get') }}" class="btn btn-info">
                                        <i class="fa-solid fa-arrow-left"></i> Kembali
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="htt_sub_pemeriksaantabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Harga</th>
                                            <th class="text-center">Lokasi</th>
                                            <th class="text-center">P. Jawab</th>
                                            <th class="text-center">Kondisi</th>
                                            <th class="text-center">No Seri</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stok as $stokdata)
                                            <tr>
                                                <td class="text-center">{{ $stokdata->kode_barang }}</td>
                                                <td class="text-center">{{ $stokdata->nama_barang }}</td>
                                                <td class="text-center">{{ $stokdata->qty_barang }}</td>
                                                <td class="text-center">{{ $stokdata->harga_barang }}</td>
                                                <td class="text-center">{{ $stokdata->lokasi ?? null }}</td>
                                                <td class="text-center">{{ $stokdata->penanggung_jawab ?? null }}</td>
                                                <td class="text-center">{{ $stokdata->kondisi ?? null }}</td>
                                                <td class="text-center">{{ $stokdata->no_seri ?? null }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-stok_inventaris"
                                                        data-toggle="modal"data-id="{{ $stokdata->id }}"
                                                        data-nama_barang="{{ $stokdata->nama_barang }}"
                                                        data-target="#delete_data_stok_inventaris">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                    <a href="#" class="btn btn-warning btn-sm edit-data_stok_inventaris"
                                                        data-toggle="modal"data-id="{{ $stokdata->id }}"
                                                        data-kode="{{ $stokdata->kode_barang }}"
                                                        data-nama="{{ $stokdata->nama_barang }}"
                                                        data-lokasi="{{ $stokdata->lokasi }}"
                                                        data-penanggung_jawab="{{ $stokdata->penanggung_jawab }}"
                                                        data-kondisi="{{ $stokdata->kondisi }}"
                                                        data-no_seri="{{ $stokdata->no_seri }}"
                                                        data-target="#edit_data_stok_inventaris_Modal">
                                                        <i class="fas fa-trash"></i> edit
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

<div class="modal fade" id="edit_data_stok_inventaris_Modal" tabindex="-1" role="dialog" aria-labelledby="edit_data_stok_inventaris_Modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit_data_stok_inventaris_Modal">Update Data Stok Inventaris</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm_data_stok_inventaris" action="{{ route('stokin_data_utama.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <input type="hidden" id="data_id_edit" name="data_id_edit">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Kode Barang</label>
                                <input type="text" class="form-control" id="kode_edit" name="kode_edit"readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit"readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lokasi_barang_edit">Lokasi Barang</label>
                                <input type="text" class="form-control" id="lokasi_barang_edit" name="lokasi_barang_edit" placeholder="Lokasi Barang Digunakan">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="penanggung_jawab_edit">Penanggung Jawab</label>
                                <input type="text" class="form-control" id="penanggung_jawab_edit" name="penanggung_jawab_edit" placeholder="Penanggung Jawab Barang">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="kondisi_barang_edit">Kondisi Barang</label>
                                <select class="form-control" id="kondisi_barang_edit" name="kondisi_barang_edit" style="width: 100%;">
                                    <option value="" disabled selected>-- Pilih Data --</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak Ringan">Rusak Ringan</option>
                                    <option value="Rusak Sedang">Rusak Sedang</option>
                                    <option value="Rusak Berat">Rusak Berat</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="no_seri_edit">No Seri Barang</label>
                                <input type="text" class="form-control" id="no_seri_edit" name="no_seri_edit" placeholder="No Seri Barang Inventaris">
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

{{-- modal Delete Role --}}
<div class="modal fade" id="delete_data_stok_inventaris" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteForm_stok_inventaris" action="{{ route('stokin_data_utama.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Data Stok Inventaris</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="data_inventaris_id_delete" name="data_inventaris_id_delete">
                    <div id="deleteText_stok_inventaris"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
        $(document).ready(function() {
            $("#htt_sub_pemeriksaantabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            }).buttons().container().appendTo('#htt_sub_pemeriksaantabel_wrapper .col-md-6:eq(0)');
        });

        $(document).on('click', '.edit-data_stok_inventaris', function() {
            var id = $(this).data('id');
            var kode = $(this).data('kode');
            var nama = $(this).data('nama');
            var lokasi = $(this).data('lokasi');
            var penanggung_jawab = $(this).data('penanggung_jawab');
            var kondisi = $(this).data('kondisi');
            var no_seri = $(this).data('no_seri');

            $('#data_id_edit').val(id);
            $('#kode_edit').val(kode);
            $('#nama_edit').val(nama);
            $('#lokasi_barang_edit').val(lokasi);
            $('#penanggung_jawab_edit').val(penanggung_jawab);
            $('#kondisi_barang_edit').val(kondisi);
            $('#no_seri_edit').val(no_seri);
        });

        $('#editForm_data_stok_inventaris').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#edit_data_stok_inventaris_Modal').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate Bidang Labotorium!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-stok_inventaris', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama_barang');

            $('#data_inventaris_id_delete').val(id);
            $('#deleteText_stok_inventaris').html(
            `<span>Apa Anda yakin ingin menghapus data stok inventaris <b>${name}</b> ?</span>`);
        });

        $('#deleteForm_stok_inventaris').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#delete_data_stok_inventaris').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Bidang Labotorium!',
                    });
                }
            });
        });
</script>
@endsection
