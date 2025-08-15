    <script>
        $(document).ready(function() {
            $("#pendidikantabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                {
                    text: '<i class="fas fa-plus"></i> Tambah',
                    type: 'button',
                    className: 'btn btn-info mr-2',
                    action: function () {
                        $('#addpendidikanModal').modal('show');
                    }
                },
                {
                    text: '<i class="fas fa-file-download"></i> Export',
                    type: 'button',
                    className: 'btn btn-success mr-2',
                    action: function () {
                        window.location.href = "{{ route('pendidikan.export') }}";
                    }
                },
                {
                    text: '<i class="fas fa-file-upload"></i> Import',
                    type: 'button',
                    className: 'btn btn-warning', // tombol terakhir tidak perlu margin
                    action: function () {
                        $('#importpendidikanModal').modal('show');
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
            }).buttons().container().appendTo('#pendidikantabel_wrapper .col-md-6:eq(0)');
        });

        $('#addFormpendidikan').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addpendidikanModal').modal('hide');
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

        $(document).on('click', '.edit-data-pendidikan', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-pendidikan');
            var kode = $(this).data('kode-pendidikan');
            var urutan = $(this).data('urutan-pendidikan');

            $('#pendidikanid_edit').val(id);
            $('#nama_edit').val(nama);
            $('#kode_edit').val(kode);
            $('#urutan_edit').val(urutan);
            // Pastikan rhesus terpilih dengan benar
        });

        $('#editFormpendidikan').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editpendidikanModa').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate pendidikan!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-pendidikan', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-pendidikan');

            $('#pendidikanid_delete').val(id);
            $('#deleteTextpendidikan').html(
                `<span>Apa Anda yakin ingin menghapus data pendidikan <b>${name}</b> ?</span>`);
        });

        $('#deleteFormpendidikan').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deletependidikanModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Pendidikan!',
                    });
                }
            });
        });
    </script>
