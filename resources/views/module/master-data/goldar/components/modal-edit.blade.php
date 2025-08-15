<div class="modal fade" id="editgoldarModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editModalLabel">Edit Golongan Darah</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="editFormgoldar" action="{{ route('goldar.update') }}" method="POST">
					@csrf
					<input type="hidden" id="goldarid_edit" name="goldarid_edit">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Nama Golongan Darah</label>
								<input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama Golongan" required>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="rhesus">Rhesus Darah </label>
								<select class="form-control" name="rhesus_edit" id="rhesus_edit" required>
									<option selected="selected" disabled>Pilih Rhesus</option>
									<option value="-">Rhesus - </option>
									<option value="+">Rhesus +</option>
									<option value="null">Rhesus Tidak Diketahui</option>
								</select>
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

