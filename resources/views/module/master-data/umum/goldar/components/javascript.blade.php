<script>
    $(document).ready(function() {
        $("#goldartabel").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": false,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
                "infoFiltered": "(disaring dari _MAX_ total entri)",
            }
        }).buttons().container().appendTo('#goldartabel_wrapper .col-md-6:eq(0)');
    });

    $('#addFormgoldar').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#addgoldarModal').modal('hide');
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

    $(document).on('click', '.edit-data-goldar', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama-goldar');
        var rhesus = $(this).data('rhesus');
        $('#goldarid_edit').val(id);
        $('#nama_edit').val(nama);
        if (rhesus === "null" || rhesus === null || rhesus === undefined) {
            $('#rhesus_edit').val("null");
        } else {
            $('#rhesus_edit').val(rhesus);
        }
    });

    $('#editFormgoldar').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editgoldarModa').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: true })
                        .then(() => { $('.modal-backdrop').remove(); location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: response.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat mengupdate Golongan Darah!' });
            }
        });
    });

    $(document).on('click', '.delete-data-goldar', function() {
        let id = $(this).data('id');
        let name = $(this).data('nama-goldar');
        let rhesus = $(this).data('rhesus');
        $('#goldarid_delete').val(id);
        $('#deleteTextgoldar').html(`<span>Apa Anda yakin ingin menghapus data Golongan darah <b>${name}${rhesus}</b> ?</span>`);
    });

    $('#deleteFormgoldar').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#deletegoldarModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: true })
                        .then(() => { $('.modal-backdrop').remove(); location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: response.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat menghapus Golongan Darah!' });
            }
        });
    });
</script>

