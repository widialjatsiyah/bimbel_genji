<div class="modal fade" id="modal-form-faq" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form FAQ' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-faq" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="text" name="category" class="form-control faq-category" maxlength="50" placeholder="Misal: Umum, Akun, Try Out" />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label required>Pertanyaan</label>
                        <input type="text" name="question" class="form-control faq-question" placeholder="Pertanyaan" required />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label required>Jawaban</label>
                        <textarea name="answer" class="form-control faq-answer" rows="5" placeholder="Jawaban" required></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Urutan</label>
                                <input type="number" name="order_num" class="form-control faq-order_num" min="0" value="0" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check" style="margin-top: 35px;">
                                    <input type="checkbox" name="is_active" class="form-check-input faq-is_active" id="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text faq-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
