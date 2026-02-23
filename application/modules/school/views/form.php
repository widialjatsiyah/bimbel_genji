<div class="modal fade" id="modal-form-school" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Sekolah' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-school" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="form-group">
                        <label required>Nama Sekolah</label>
                        <input type="text" name="name" class="form-control school-name" maxlength="100" placeholder="Contoh: SMA Negeri 1 Jakarta" required />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="address" class="form-control school-address" rows="3" placeholder="Alamat lengkap"></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Email Kontak</label>
                        <input type="email" name="contact_email" class="form-control school-contact_email" maxlength="100" placeholder="contoh@sekolah.sch.id" />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" name="contact_phone" class="form-control school-contact_phone" maxlength="20" placeholder="021-12345678" />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>URL Logo</label>
                        <input type="url" name="logo_url" class="form-control school-logo_url" maxlength="255" placeholder="https://example.com/logo.png" />
                        <i class="form-group__bar"></i>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text school-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
