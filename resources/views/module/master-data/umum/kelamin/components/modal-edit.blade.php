<div class="modal fade" id="editkelaminModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editModalLabel">Edit kelamin</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="editFormkelamin" action="{{ route('kelamin.update') }}" method="POST">
					@csrf
					<input type="hidden" id="kelaminid_edit" name="kelaminid_edit">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Nama kelamin</label>
								<input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama kelamin" required>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>kode kelamin</label>
								<input type="text" class="form-control" id="kode_edit" name="kode_edit" placeholder="kode kelamin" required>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary">Perbarui</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

