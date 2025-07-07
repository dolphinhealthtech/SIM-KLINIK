@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Penyesuaian Barang</h1>
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
                                <h3 class="card-title">Penyesuaian Barang</h3>
                            </div>
                            <form id="addFormPenyesuaian" action="{{ route('stok_penyesuaian.store') }}" method="POST">
                                @csrf
                                <div class="card-body">
                                    <input type="hidden" id="kode_obat" name="kode_obat">
                                    <div class="form-group row align-items-center">
                                        <label for="aktifitas_penyesuaian" class="col-md-1 col-form-label">Aktivitas</label>
                                        <label for="aktifitas_penyesuaian" class="col-md-1 col-form-label">:</label>
                                        <div class="col-md-6">
                                            <select class="form-control select2bs4" id="aktifitas_penyesuaian" name="aktifitas_penyesuaian" required>
                                                <option value="" disabled selected>-- Pilih Aktivitas --</option>
                                                <option value="stok_opname">Stok Opname</option>
                                                <option value="koreksi_manual">Koreksi Manual</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="harga_penyesuaian" class="col-md-1 col-form-label">Harga Acuan</label>
                                        <label for="harga_penyesuaian" class="col-md-1 col-form-label">:</label>
                                        <div class="col-md-6">
                                            <select class="form-control select2bs4" id="harga_penyesuaian" name="harga_penyesuaian" required>
                                                <option value="" disabled selected>-- Pilih Aktivitas --</option>
                                                <option value="harga_jual_1">Harga Jual 1 (BPJS)</option>
                                                <option value="harga_jual_2">Harga Jual 2 (Asuransi)</option>
                                                <option value="harga_jual_3">Harga Jual 3 (Umum)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="obat_penyesuaian" class="col-md-1 col-form-label">Nama Obat</label>
                                        <label for="obat_penyesuaian" class="col-md-1 col-form-label">:</label>
                                        <div class="col-md-6">
                                            <select class="form-control select2bs4" id="obat_penyesuaian" name="obat_penyesuaian" required>
                                                <option value="" disabled selected>-- Pilih Obat --</option>
                                                @foreach ($obat as $obatData)
                                                    <option data-satuan="{{ $obatData->satuan_kecil }}" data-kode="{{ $obatData->kode_barang }}" value="{{ $obatData->nama_barang }}">
                                                        {{ $obatData->nama_barang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="keterangan_qty_penyesuaian" class="col-md-1 col-form-label">Jumlah</label>
                                        <label for="keterangan_qty_penyesuaian" class="col-md-1 col-form-label">:</label>
                                        <div class="col-md-4">
                                            <select class="form-control select2bs4" id="keterangan_qty_penyesuaian" name="keterangan_qty_penyesuaian" required>
                                                <option value="" disabled selected>-- Ubah Sebanyak --</option>
                                                <option value="tambahkan">Tambahkan Sebanyak</option>
                                                <option value="kurangi">Kurangi Sebanyak</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control" id="qty_penyesuaian" name="qty_penyesuaian" min="0">
                                        </div>
                                        <span class="col-md-2" id="satuan_text">Satuan</span>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="alasan_penyesuaian" class="col-md-1 col-form-label">Alasan</label>
                                        <label for="alasan_penyesuaian" class="col-md-1 col-form-label">:</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" id="alasan_penyesuaian" name="alasan_penyesuaian" placeholder="Tulis alasan penyesuaian">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="expired_penyesuaian" class="col-md-1 col-form-label">Expired</label>
                                        <label for="expired_penyesuaian" class="col-md-1 col-form-label">:</label>
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" id="expired_penyesuaian" name="expired_penyesuaian">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    <!-- /.content -->
    </div>

<script>
    $(document).ready(function() {
        // DataTable untuk tabel utama
        $("#stoktabel").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": [
                "csv",
                "excel",
                "pdf",
                "print",
            ],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(disaring dari _MAX_ data keseluruhan)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        }).buttons().container().appendTo('#stoktabel_wrapper .col-md-6:eq(0)');
    });

    $('#addFormPenyesuaian').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: true
                    }).then(() => {
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

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const expired_penyesuaian = document.getElementById('expired_penyesuaian');

        expired_penyesuaian.addEventListener('click', function () {
            expired_penyesuaian.showPicker?.() || expired_penyesuaian.focus(); // Buka date picker jika didukung, atau fokus
        });
    });

    $(document).ready(function () {
        $('#obat_penyesuaian').on('change', function () {
            var satuan = $(this).find(':selected').data('satuan') || 'Satuan';
            $('#satuan_text').text(satuan);
            var kode = $(this).find(':selected').data('kode');
            $('#kode_obat').val(kode);
        });
    });

    $(document).ready(function () {
        $('#aktifitas_penyesuaian').on('change', function () {
            const value = $(this).val();

            if (value === 'stok_opname') {
                // Disable "ubah sebanyak"
                $('#keterangan_qty_penyesuaian').prop('disabled', true).val('');

                // Set alasan otomatis dan readonly
                $('#alasan_penyesuaian')
                    .val('Penyesuaian stok opname')
                    .prop('readonly', true);
            } else {
                // Enable kembali "ubah sebanyak"
                $('#keterangan_qty_penyesuaian').prop('disabled', false);

                // Kosongkan & izinkan input alasan
                $('#alasan_penyesuaian')
                    .val('') // kosongkan isi input
                    .attr('placeholder', 'Tulis alasan penyesuaian') // set placeholder
                    .prop('readonly', false); // aktifkan kembali input
            }
        });
    });
</script>
@endsection












