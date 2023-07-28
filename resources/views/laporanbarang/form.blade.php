<div id="modal-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('laporanbarang.index') }}" method="GET">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah Periode Laporan</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <input type="text" name="tanggal_awal" class="form-control datepicker" value="{{ $tanggalAwal }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <input type="text" name="tanggal_akhir" class="form-control datepicker" value="{{ $tanggalAkhir }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ubah</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
