<script>
    $(document).ready(function() {
        $("#kelamintabel").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["csv","excel","pdf","print"]
        }).buttons().container().appendTo('#kelamintabel_wrapper .col-md-6:eq(0)');
    });

    $('#addFormkelamin').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#addkelaminModal').modal('hide');
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

    $(document).on('click', '.edit-data-kelamin', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama-kelamin');
        var kode = $(this).data('kode-kelamin');
        $('#kelaminid_edit').val(id);
        $('#nama_edit').val(nama);
        $('#kode_edit').val(kode);
    });

    $('#editFormkelamin').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editkelaminModa').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: true })
                        .then(() => { $('.modal-backdrop').remove(); location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: response.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat mengupdate Jenis kelamin!' });
            }
        });
    });

    $(document).on('click', '.delete-data-kelamin', function() {
        let id = $(this).data('id');
        let name = $(this).data('nama-kelamin');
        $('#kelaminid_delete').val(id);
        $('#deleteTextkelamin').html(`<span>Apa Anda yakin ingin menghapus data Jenis kelamin <b>${name}</b> ?</span>`);
    });

    $('#deleteFormkelamin').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#deletekelaminModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: true })
                        .then(() => { $('.modal-backdrop').remove(); location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: response.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat menghapus Jenis Kelamin!' });
            }
        });
    });
</script>

