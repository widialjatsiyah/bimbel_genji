<div class="modal fade" id="modal-form-subject" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Mata Pelajaran' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-subject" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="form-group">
                        <label required>Nama Mata Pelajaran</label>
                        <input type="text" name="name" class="form-control subject-name" maxlength="50" placeholder="Contoh: Matematika" required />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Kode</label>
                        <input type="text" name="code" class="form-control subject-code" maxlength="20" placeholder="Contoh: MTK" />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control subject-description" rows="3" placeholder="Deskripsi mata pelajaran (opsional)"></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text subject-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
