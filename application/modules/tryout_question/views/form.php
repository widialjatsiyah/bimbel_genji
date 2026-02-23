<div class="modal fade" id="modal-form-tryout_question" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Soal Try Out' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-tryout_question" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="form-group">
                        <label required>Sesi Try Out</label>
                        <div class="select">
                            <select name="tryout_session_id" class="form-control select2 tryout_question-tryout_session_id" data-placeholder="Pilih Sesi" required>
                                <option value=""></option>
                                <?= $list_session ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label required>Soal</label>
                        <div class="select">
                            <select name="question_id" class="form-control select2 tryout_question-question_id" data-placeholder="Pilih Soal" required>
                                <option value=""></option>
                                <?= $list_question ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label required>Urutan Soal</label>
                        <input type="number" name="question_order" class="form-control tryout_question-question_order" min="1" placeholder="Urutan soal dalam sesi" required />
                        <i class="form-group__bar"></i>
                        <small class="form-text text-muted">Urutan soal dimulai dari 1.</small>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text tryout_question-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
