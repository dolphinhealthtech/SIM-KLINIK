<div class="modal fade" id="editpekerjaanModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editModalLabel">Edit pekerjaan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="editFormpekerjaan" action="{{ route('pekerjaan.update') }}" method="POST">
					@csrf
					<input type="hidden" id="pekerjaanid_edit" name="pekerjaanid_edit">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label>Nama pekerjaan</label>
								<input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama pekerjaan" required>
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

