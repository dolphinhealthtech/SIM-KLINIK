<script>
        $(document).ready(function() {
            $("#asuransitabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                {
                    text: '<i class="fas fa-plus"></i> Tambah',
                    type: 'button',
                    className: 'btn btn-info mr-2',
                    action: function () {
                        $('#addasuransiModal').modal('show');
                    }
                },
                {
                    text: '<i class="fas fa-file-download"></i> Export',
                    type: 'button',
                    className: 'btn btn-success mr-2',
                    action: function () {
                        window.location.href = "{{ route('asuransi.export') }}";
                    }
                },
                {
                    text: '<i class="fas fa-file-upload"></i> Import',
                    type: 'button',
                    className: 'btn btn-warning', // tombol terakhir tidak perlu margin
                    action: function () {
                        $('#importasuransiModal').modal('show');
                    }
                }
            ],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
                "infoFiltered": "(disaring dari _MAX_ total entri)",
            }
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
