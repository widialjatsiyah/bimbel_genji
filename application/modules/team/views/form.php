<div class="modal fade" id="modal-form-team" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Anggota Tim' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-team" autocomplete="off" enctype="multipart/form-data">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Nama</label>
                                <input type="text" name="name" class="form-control team-name" maxlength="100" required />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jabatan</label>
                                <input type="text" name="position" class="form-control team-position" maxlength="100" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Bio</label>
                        <textarea name="bio" class="form-control team-bio" rows="3"></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Facebook</label>
                                <input type="url" name="social_facebook" class="form-control team-social_facebook" placeholder="https://facebook.com/..." />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Twitter</label>
                                <input type="url" name="social_twitter" class="form-control team-social_twitter" placeholder="https://twitter.com/..." />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Instagram</label>
                                <input type="url" name="social_instagram" class="form-control team-social_instagram" placeholder="https://instagram.com/..." />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>LinkedIn</label>
                                <input type="url" name="social_linkedin" class="form-control team-social_linkedin" placeholder="https://linkedin.com/..." />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Urutan</label>
                                <input type="number" name="order_num" class="form-control team-order_num" min="0" value="0" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Foto</label>
                                <input type="file" name="photo" class="form-control team-photo" accept="image/*" />
                                <i class="form-group__bar"></i>
                                <small class="text-muted">Format: jpg, jpeg, png, gif. Maks 2MB.</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-check" style="margin-top: 35px;">
                                    <input type="checkbox" name="is_active" class="form-check-input team-is_active" id="is_active" value="1" checked>
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
                <button type="button" class="btn btn-success btn--icon-text team-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
