<div class="modal fade" id="modal-form-tryout" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Try Out' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-tryout" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="form-group">
                        <label required>Judul Try Out</label>
                        <input type="text" name="title" class="form-control tryout-title" maxlength="200" placeholder="Contoh: Try Out UTBK Nasional 2026" required />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control tryout-description" rows="2" placeholder="Deskripsi try out (opsional)"></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label required>Tipe</label>
                                <div class="select">
                                    <select name="type" class="form-control tryout-type" required>
                                        <option value="UTBK">UTBK</option>
                                        <option value="TKA">TKA</option>
                                        <option value="Jurusan">Jurusan</option>
                                        <option value="Sekolah">Sekolah</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label required>Mode</label>
                                <div class="select">
                                    <select name="mode" class="form-control tryout-mode" required>
                                        <option value="resmi">Resmi</option>
                                        <option value="latihan">Latihan</option>
                                        <option value="evaluasi">Evaluasi</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Durasi Total (menit)</label>
                                <input type="number" name="total_duration" class="form-control tryout-total_duration" min="0" placeholder="Opsional" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Waktu Mulai</label>
                                <input type="text" name="start_time" class="form-control flatpickr-datetime tryout-start_time" placeholder="YYYY-MM-DD HH:MM:SS" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Waktu Selesai</label>
                                <input type="text" name="end_time" class="form-control flatpickr-datetime tryout-end_time" placeholder="YYYY-MM-DD HH:MM:SS" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_published" class="form-check-input tryout-is_published" id="is_published" value="1" checked>
                            <label class="form-check-label" for="is_published">Publikasikan</label>
                        </div>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text tryout-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
