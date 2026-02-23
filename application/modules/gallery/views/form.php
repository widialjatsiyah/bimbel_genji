<div class="modal fade" id="modal-form-gallery" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Galeri' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-gallery" autocomplete="off" enctype="multipart/form-data">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="form-group">
                        <label required>Judul</label>
                        <input type="text" name="title" class="form-control gallery-title" maxlength="200" placeholder="Contoh: Kegiatan Try Out Nasional" required />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control gallery-description" rows="3" placeholder="Deskripsi gambar (opsional)"></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="text" name="category" class="form-control gallery-category" maxlength="50" placeholder="Contoh: Event, Kegiatan, dll" />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Gambar</label>
                        <input type="file" name="image" class="form-control gallery-image" accept="image/*" required />
                        <i class="form-group__bar"></i>
                        <small class="text-muted">Format: jpg, jpeg, png, gif. Maks 2MB.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Urutan</label>
                                <input type="number" name="order_num" class="form-control gallery-order_num" min="0" value="0" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check" style="margin-top: 35px;">
                                    <input type="checkbox" name="is_active" class="form-check-input gallery-is_active" id="is_active" value="1" checked>
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
                <button type="button" class="btn btn-success btn--icon-text gallery-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
