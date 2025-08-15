    <!-- Modal Jadwal Dokter -->
    <div class="modal fade" id="jadwaldokterModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <form id="deleteFormdokter" action="#" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="nama-dokter-jadwal" class="modal-title"></h5>

                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div id="calendarDokter" style="height:500px; min-width: 100%;"></div>

                    </div>
                    <div class="modal-footer">
                        @if(isset($is_bpjs_active) && $is_bpjs_active)
                            <button type="button"
                                    class="btn btn-info sinkron-jadwal-btn"
                                    data-route-template="{{ route('jadwal.sinkron', ['id' => '__ID__']) }}">
                                <i class="far fa-clock"></i> Sinkron Jadwal
                            </button>

                        @endif
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>