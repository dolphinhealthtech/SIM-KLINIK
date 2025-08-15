<script>
    $(document).ready(function() {
        $("#bahasatabel").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["csv","excel","pdf","print"]
        }).buttons().container().appendTo('#bahasatabel_wrapper .col-md-6:eq(0)');
    });

    $('#addFormbahasa').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#addbahasaModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: true })
                        .then(() => { $('.modal-backdrop').remove(); location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: response.message });
                }
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan dalam menyimpan data!';
                Swal.fire({ icon: 'error', title: 'Error!', text: errorMessage });
            }
        });
    });

    $(document).on('click', '.edit-data-bahasa', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama-bahasa');
        $('#bahasaid_edit').val(id);
        $('#nama_edit').val(nama);
    });

    $('#editFormbahasa').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editbahasaModa').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: true })
                        .then(() => { $('.modal-backdrop').remove(); location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: response.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat mengupdate bahasa!' });
            }
        });
    });

    $(document).on('click', '.delete-data-bahasa', function() {
        let id = $(this).data('id');
        let name = $(this).data('nama-bahasa');
        $('#bahasaid_delete').val(id);
        $('#deleteTextbahasa').html(`<span>Apa Anda yakin ingin menghapus data bahasa <b>${name}</b> ?</span>`);
    });

    $('#deleteFormbahasa').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#deletebahasaModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: true })
                        .then(() => { $('.modal-backdrop').remove(); location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: response.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat menghapus Bahasa!' });
            }
        });
    });
</script>

