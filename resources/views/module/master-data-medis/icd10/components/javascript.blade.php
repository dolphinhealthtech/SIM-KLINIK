<script>
        $(document).ready(function() {
            $("#icd10tabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#icd10tabel_wrapper .col-md-6:eq(0)');
        });

        $('#singForicd10').on('submit', function(e) {
            e.preventDefault();

            const $form = $(this);
            const $loadingContainer = $('#loadingContainer');
            const $loadingBar = $('#loadingBar');
            const $loadingText = $('#loadingText');
            const $jenisAlergiGroup = $('#jenis_alergi').closest('.form-group');

            // Sembunyikan dropdown jenis_alergi
            $jenisAlergiGroup.hide();

            // Reset dan tampilkan loading bar
            let progress = 0;
            $loadingBar.css('width', '0%');
            $loadingText.text('0%');
            $loadingContainer.show();

            const interval = setInterval(() => {
                if (progress >= 90) return;
                progress++;
                $loadingBar.css('width', progress + '%');
                $loadingText.text(progress + '%');
            }, 20);

            $.ajax({
                url: $form.attr('action'),
                method: $form.attr('method'),
                data: $form.serialize(),
                success: function(response) {
                    clearInterval(interval);
                    $loadingBar.css('width', '100%');
                    $loadingText.text('100%');

                    if (response.success) {
                        $('#addalergiModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('.modal-backdrop').remove(); // Bersihkan backdrop
                            location.reload();
                        });
                    } else {
                        $jenisAlergiGroup.show();
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    clearInterval(interval);
                    $loadingBar.css('width', '100%');
                    $loadingText.text('Gagal!');
                    $jenisAlergiGroup.show();

                    const errorMessage = xhr.responseJSON?.message || "Terjadi kesalahan saat menyimpan data.";
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                }
            });
        });


        $('#addFormicd10').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addicd10Modal').modal('hide');
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

        $(document).on('click', '.edit-data-icd10', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-icd10');
            var rhesus = $(this).data('rhesus'); // Ambil data rhesus dari tombol edit


            $('#icd10id_edit').val(id);
            $('#nama_edit').val(nama);
             // Pastikan rhesus terpilih dengan benar
            if (rhesus === "null" || rhesus === null || rhesus === undefined) {
                $('#kode_edit').val("null");
            } else {
                $('#kode_edit').val(rhesus);
            }
        });

        $('#editFormicd10').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editicd10Moda').modal('hide');
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
                        text: 'Terjadi kesalahan saat ICD10!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-icd10', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-icd10');
            let rhesus = $(this).data('rhesus'); // Ambil data rhesus dari tombol edit
            $('#icd10id_delete').val(id);
            $('#deleteTexticd10').html(
            `<span>Apa Anda yakin ingin menghapus data ICD10 <b>${name}</b> ?</span>`);
        });

        $('#deleteFormicd10').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deleteicd10Modal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus ICD10!',
                    });
                }
            });
        });
</script>
