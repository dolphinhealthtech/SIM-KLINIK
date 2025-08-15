<script>
        $(document).ready(function() {
            $("#perawatan_tindakantabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#perawatan_tindakantabel_wrapper .col-md-6:eq(0)');

            $('.rupiah').on('input', function () {
                let formatted = formatRupiah($(this).val());
                $(this).val(formatted);
            });
        });

    //SCRIPT ADD
        $(document).ready(function() {
            $('#addFormperawatan_tindakan').on('submit', function(e) {
                e.preventDefault();

                const form = $(this); // simpan referensi form

                generateKode(function (kodeBaru) {
                    $('#kode').val(kodeBaru); // Set ke hidden input

                    // Parse tarif ke angka
                    let tarifDokter = parseRupiah($('#tarif_dokter').val());
                    let tarifPerawat = parseRupiah($('#tarif_perawat').val());
                    let tarifTotal = parseRupiah($('#tarif_total').val());

                    // Set kembali input dengan angka murni
                    $('#tarif_dokter').val(tarifDokter);
                    $('#tarif_perawat').val(tarifPerawat);
                    $('#tarif_total').val(tarifTotal);

                    $.ajax({
                        url: form.attr('action'),
                        method: form.attr('method'),
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                $('#addperawatan_tindakanModal').modal('hide');
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
        });

    // SCRIPT EDIT
        $(document).on('click', '.edit-data-perawatan_tindakan', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-perawatan_tindakan');
            var kategori = $(this).data('kategori-perawatan_tindakan');
            var tarif_dokter = $(this).data('tarif_dokter-perawatan_tindakan');
            var tarif_perawat = $(this).data('tarif_perawat-perawatan_tindakan');
            var tarif_total = $(this).data('tarif_total-perawatan_tindakan');

            $('#perawatan_tindakanid_edit').val(id);
            $('#nama_edit').val(nama);
            $('#kategori_edit').val(kategori);
            $('#tarif_dokter_edit').val(tarif_dokter).trigger('input');
            $('#tarif_perawat_edit').val(tarif_perawat).trigger('input');
            $('#tarif_total_edit').val(tarif_total).trigger('input');
        });

        $('#editFormperawatan_tindakan').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            // Parse tarif ke angka
            let tarifDokterEdit = parseRupiah($('#tarif_dokter_edit').val());
            let tarifPerawatEdit = parseRupiah($('#tarif_perawat_edit').val());
            let tarifTotalEdit = parseRupiah($('#tarif_total_edit').val());

            // Set kembali input dengan angka murni
            $('#tarif_dokter_edit').val(tarifDokterEdit);
            $('#tarif_perawat_edit').val(tarifPerawatEdit);
            $('#tarif_total_edit').val(tarifTotalEdit);

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editperawatan_tindakanModa').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate perawatan tindakan!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-perawatan_tindakan', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-perawatan_tindakan');

            $('#perawatan_tindakanid_delete').val(id);
            $('#deleteTextperawatan_tindakan').html(
            `<span>Apa Anda yakin ingin menghapus data perawatan tindakan <b>${name}</b> ?</span>`);
        });

        $('#deleteFormperawatan_tindakan').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deleteperawatan_tindakanModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Perawatan tindakan!',
                    });
                }
            });
        });

    //SCRIPT TAMBAHAN
        function generateKode(callback) {
            $.ajax({
                url: "{{ route('perawatan_tindakan.getLastKode') }}", // Kamu bikin route ini ya di bawah
                method: 'GET',
                success: function (res) {
                    let lastKode = res.kode || 'TDK-0000'; // fallback kalau belum ada data
                    let number = parseInt(lastKode.split('-')[1]) + 1;
                    let newKode = 'TDK-' + number.toString().padStart(4, '0');
                    callback(newKode);
                },
                error: function () {
                    alert('Gagal mendapatkan kode otomatis.');
                }
            });
        }

        function formatRupiah(angka) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa  = split[0].length % 3,
                rupiah  = split[0].substr(0, sisa),
                ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        function parseRupiah(rp) {
            return parseInt(rp.replace(/[^0-9]/g, '')) || 0;
        }

        $(document).ready(function () {
            $('#tarif_dokter, #tarif_perawat').on('input', function () {
                let total = 0;
                let dokter = parseRupiah($('#tarif_dokter').val());
                let perawat = parseRupiah($('#tarif_perawat').val());
                total = dokter + perawat;
                $('#tarif_total').val(formatRupiah(total.toString()));
            });

            $('#tarif_dokter_edit, #tarif_perawat_edit').on('input', function () {
                let total_edit = 0;
                let dokter_edit = parseRupiah($('#tarif_dokter_edit').val());
                let perawat_edit = parseRupiah($('#tarif_perawat_edit').val());
                total_edit = dokter_edit + perawat_edit;
                $('#tarif_total_edit').val(formatRupiah(total_edit.toString()));
            });
        });
</script>
