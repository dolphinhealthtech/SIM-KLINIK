<script>
    $(document).ready(function () {
        function resetModal() {
            $("#loadingContainer").show();         // Sembunyikan loading bar
            $("#loadingBar").css("width", "0%");   // Reset progress ke 0%
            $("#loadingText").text("0%");          // Reset teks ke 0%
            $(".btn-primary").prop("disabled", false).text("Tambah"); // Aktifkan kembali tombol

            $("#addFormspesialis")[0].reset(); // Reset form input
        }

        // Reset progress bar saat modal dibuka
        $("#addspesialisModal").on("show.bs.modal", function () {
            $("#loadingContainer").show(); // Tampilkan loading saat modal dibuka
            $("#loadingBar").css("width", "0%"); // Reset ke 0%
            $("#loadingText").text("0%"); // Reset teks ke 0%
        });

        $("#addFormspesialis").submit(function (e) {
            e.preventDefault(); // Mencegah submit form default

            // Tampilkan loading bar dan reset ke 0%
            $("#loadingContainer").show();
            $("#loadingBar").css("width", "0%");
            $("#loadingText").text("0%");
            $(".btn-primary").prop("disabled", true).text("Menambahkan...");

            // Simulasi animasi progress dari 0% hingga 100% sebelum AJAX dijalankan
            let progress = 0;
            let interval = setInterval(function () {
                progress += 10; // Tambah 10% setiap 300ms
                $("#loadingBar").css("width", progress + "%");
                $("#loadingText").text(progress + "%"); // Update teks

                if (progress >= 100) {
                    clearInterval(interval); // Hentikan animasi
                    $("#loadingText").text("Complete"); // Ubah teks jadi "Complete"

                    // ** Setelah 100%, jalankan AJAX request **
                    $.ajax({
                        url: "{{ route('spesialis.sync') }}",
                        type: "GET",
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                $('#addspesialisModal').modal('hide');
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
                            }).then(() => {
                                // **🔹 Reset modal ke kondisi awal setelah OK ditekan**
                                resetModal();
                            });

                        }
                    });
                }
            }, 300); // Setiap 300ms, naik 10%
        });
    });
</script>



<script>
        $(document).ready(function() {
            $("#spesialistabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#spesialistabel_wrapper .col-md-6:eq(0)');
        });


        $(document).on('click', '.edit-data-spesialis', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-spesialis');

            $('#spesialisid_edit').val(id);
            $('#nama_edit').val(nama);
             // Pastikan rhesus terpilih dengan benar
        });

        $('#editFormspesialis').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editspesialisModa').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate spesialis!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-goldar', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-spesialis');

            $('#spesialisid_delete').val(id);
            $('#deleteTextspesialis').html(
            `<span>Apa Anda yakin ingin menghapus data spesialis <b>${name}</b> ?</span>`);
        });

        $('#deleteFormspesialis').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deletespesialisModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Spesialis!',
                    });
                }
            });
        });
</script>
