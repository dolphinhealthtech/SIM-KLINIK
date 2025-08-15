<script>
        $(document).ready(function() {
            $("#alergitabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#alergitabel_wrapper .col-md-6:eq(0)');
        });
        $('#addFormalergi').on('submit', function(e) {
            e.preventDefault();

            // Sembunyikan dropdown jenis_alergi
            $('#jenis_alergi').closest('.form-group').hide();

            // Tampilkan loading bar
            $('#loadingContainer').show();
            let width = 0;
            let interval = setInterval(() => {
                if (width >= 90) return; // Biarkan sisa 10% sampai respon sukses
                width++;
                $('#loadingBar').css('width', width + '%');
                $('#loadingText').text(width + '%');
            }, 20);

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    clearInterval(interval);
                    $('#loadingBar').css('width', '100%');
                    $('#loadingText').text('100%');

                    if (response.success) {
                        $('#addalergiModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: true
                        }).then(() => {
                            $('.modal-backdrop').remove();
                            location.reload();
                        });
                    } else {
                        $('#jenis_alergi').closest('.form-group').show(); // Tampilkan lagi
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    clearInterval(interval);
                    $('#loadingBar').css('width', '100%');
                    $('#loadingText').text('Gagal!');

                    $('#jenis_alergi').closest('.form-group').show();

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



        $(document).on('click', '.delete-data-alergi', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-alergi');

            $('#alergiid_delete').val(id);
            $('#deleteTextalergi').html(
            `<span>Apa Anda yakin ingin menghapus data alergi <b>${name}</b> ?</span>`);
        });

        $('#deleteFormalergi').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deletealergiModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Alergi!',
                    });
                }
            });
        });
</script>
