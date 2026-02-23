<div class="modal fade" id="modal-form-chapter" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Bab' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-chapter" autocomplete="off">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="form-group">
                        <label required>Mata Pelajaran</label>
                        <div class="select">
                            <select name="subject_id" class="form-control select2 chapter-subject_id" data-placeholder="Pilih Mata Pelajaran" required>
                                <?= $list_subject ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label required>Nama Bab</label>
                        <input type="text" name="name" class="form-control chapter-name" maxlength="100" placeholder="Contoh: Trigonometri" required />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Urutan</label>
                        <input type="number" name="order_num" class="form-control chapter-order_num" min="0" placeholder="Urutan (opsional)" />
                        <i class="form-group__bar"></i>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text chapter-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
