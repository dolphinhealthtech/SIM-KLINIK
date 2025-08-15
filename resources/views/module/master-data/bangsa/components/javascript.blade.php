<script>
    $(document).ready(function() {
        $("#bangsatabel").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["csv","excel","pdf","print"]
        }).buttons().container().appendTo('#bangsatabel_wrapper .col-md-6:eq(0)');
    });

    $('#addFormbangsa').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#addbangsaModal').modal('hide');
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

    $(document).on('click', '.edit-data-bangsa', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama-bangsa');
        $('#bangsaid_edit').val(id);
        $('#nama_edit').val(nama);
    });

    $('#editFormbangsa').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editbangsaModa').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: true })
                        .then(() => { $('.modal-backdrop').remove(); location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: response.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat mengupdate bangsa!' });
            }
        });
    });

    $(document).on('click', '.delete-data-bangsa', function() {
        let id = $(this).data('id');
        let name = $(this).data('nama-bangsa');
        $('#bahasaid_delete').val(id);
        $('#deleteTextbangsa').html(`<span>Apa Anda yakin ingin menghapus data bangsa <b>${name}</b> ?</span>`);
    });

    $('#deleteFormbangsa').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#deletebangsaModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: true })
                        .then(() => { $('.modal-backdrop').remove(); location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: response.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat menghapus Bangsa!' });
            }
        });
    });
</script>

