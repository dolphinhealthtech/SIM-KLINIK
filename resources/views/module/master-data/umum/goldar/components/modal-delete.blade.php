<div class="modal fade" id="deletegoldarModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
	<div class="modal-dialog">
		<form id="deleteFormgoldar" action="{{ route('goldar.destroy') }}" method="POST">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="deleteModalLabel">Hapus Golongan Darah</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span>&times;</span>
					</button>
				</div>
				<div class="modal-body">
					@csrf
					<input type="hidden" id="goldarid_delete" name="goldarid_delete">
					<div id="deleteTextgoldar"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-danger">Hapus</button>
				</div>
			</div>
		</form>
	</div>
</div>

