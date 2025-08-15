<script>
        $(document).ready(function() {
            $("#pekerjaantabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                {
                    text: '<i class="fas fa-plus"></i> Tambah',
                    type: 'button',
                    className: 'btn btn-info mr-2',
                    action: function () {
                        $('#addpekerjaanModal').modal('show');
                    }
                },
                {
                    text: '<i class="fas fa-file-download"></i> Export',
                    type: 'button',
                    className: 'btn btn-success mr-2',
                    action: function () {
                        window.location.href = "{{ route('pekerjaan.export') }}";
                    }
                },
                {
                    text: '<i class="fas fa-file-upload"></i> Import',
                    type: 'button',
                    className: 'btn btn-warning', // tombol terakhir tidak perlu margin
                    action: function () {
                        $('#importpekerjaanModal').modal('show');
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
            }).buttons().container().appendTo('#pekerjaantabel_wrapper .col-md-6:eq(0)');
        });

        $('#addFormpekerjaan').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addpekerjaanModal').modal('hide');
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

        $(document).on('click', '.edit-data-pekerjaan', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-pekerjaan');

            $('#pekerjaanid_edit').val(id);
            $('#nama_edit').val(nama);
             // Pastikan rhesus terpilih dengan benar
        });

        $('#editFormpekerjaan').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editpekerjaanModa').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate pekerjaan!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-pekerjaan', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-pekerjaan');

            $('#pekerjaanid_delete').val(id);
            $('#deleteTextpekerjaan').html(
            `<span>Apa Anda yakin ingin menghapus data pekerjaan <b>${name}</b> ?</span>`);
        });

        $('#deleteFormpekerjaan').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deletepekerjaanModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Pekerjaan!',
                    });
                }
            });
        });
</script>
