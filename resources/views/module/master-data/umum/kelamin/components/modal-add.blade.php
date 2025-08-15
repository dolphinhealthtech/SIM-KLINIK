<div class="modal fade" id="addkelaminModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addModalLabel">Tambah kelamin</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="addFormkelamin" action="{{ route('kelamin.store') }}" method="POST">
					@csrf
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Nama kelamin</label>
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama kelamin" required>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>kode kelamin</label>
								<input type="text" class="form-control" id="kode" name="kode" placeholder="kode kelamin" required>
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-primary">Tambah</button>
			</div>
			</form>
		</div>
	</div>
</div>

