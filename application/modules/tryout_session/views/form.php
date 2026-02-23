<div class="modal fade" id="modal-form-tryout_session" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Sesi Try Out' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-tryout_session" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="form-group">
                        <label required>Try Out</label>
                        <div class="select">
                            <select name="tryout_id" class="form-control select2 tryout_session-tryout_id" data-placeholder="Pilih Try Out" required>
                                <option value=""></option>
                                <?= $list_tryout ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label required>Nama Sesi</label>
                        <input type="text" name="name" class="form-control tryout_session-name" maxlength="100" placeholder="Contoh: TPS" required />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label required>Urutan</label>
                                <input type="number" name="session_order" class="form-control tryout_session-session_order" min="1" placeholder="1" required />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label required>Durasi (menit)</label>
                                <input type="number" name="duration_minutes" class="form-control tryout_session-duration_minutes" min="1" placeholder="90" required />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label required>Jumlah Soal</label>
                                <input type="number" name="question_count" class="form-control tryout_session-question_count" min="1" placeholder="90" required />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control tryout_session-description" rows="2" placeholder="Deskripsi sesi (opsional)"></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text tryout_session-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
