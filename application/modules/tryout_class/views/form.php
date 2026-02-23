<div class="modal fade" id="modal-form-tryout_class" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?= (isset($card_title)) ? $card_title : 'Form Jadwal' ?></h5>
            </div>
            <div class="spinner"><div class="lds-hourglass"></div></div>
            <div class="modal-body">
                <form id="form-tryout_class" autocomplete="off">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="form-group">
                        <label required>Try Out</label>
                        <div class="select">
                            <select name="tryout_id" class="form-control select2 tryout_class-tryout_id" required>
                                <option value=""></option>
                                <?= $list_tryout ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label required>Kelas</label>
                        <div class="select">
                            <select name="class_id" class="form-control select2 tryout_class-class_id" required>
                                <option value=""></option>
                                <?= $list_class ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Waktu Mulai</label>
                                <input type="text" name="start_time" class="form-control flatpickr-datetime tryout_class-start_time" placeholder="YYYY-MM-DD HH:MM:SS" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Waktu Selesai</label>
                                <input type="text" name="end_time" class="form-control flatpickr-datetime tryout_class-end_time" placeholder="YYYY-MM-DD HH:MM:SS" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input tryout_class-is_active" id="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                    </div>

                    <small class="form-text text-muted">Fields with red stars are required.</small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text tryout_class-action-save"><i class="zmdi zmdi-save"></i> Simpan</button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
