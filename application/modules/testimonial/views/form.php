<div class="modal fade" id="modal-form-testimonial" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Testimoni' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-testimonial" autocomplete="off" enctype="multipart/form-data">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Nama</label>
                                <input type="text" name="name" class="form-control testimonial-name" maxlength="100" required />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Posisi</label>
                                <input type="text" name="position" class="form-control testimonial-position" maxlength="100" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Perusahaan</label>
                                <input type="text" name="company" class="form-control testimonial-company" maxlength="100" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rating (1-5)</label>
                                <select name="rating" class="form-control testimonial-rating">
                                    <option value="">Pilih</option>
                                    <?php for ($i=1; $i<=5; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?> bintang</option>
                                    <?php endfor; ?>
                                </select>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label required>Testimoni</label>
                        <textarea name="content" class="form-control testimonial-content" rows="4" required></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" name="photo" class="form-control testimonial-photo" accept="image/*" />
                        <i class="form-group__bar"></i>
                        <small class="text-muted">Format: jpg, jpeg, png, gif. Maks 2MB.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Urutan</label>
                                <input type="number" name="order_num" class="form-control testimonial-order_num" min="0" value="0" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-check" style="margin-top: 35px;">
                                    <input type="checkbox" name="is_approved" class="form-check-input testimonial-is_approved" id="is_approved" value="1" checked>
                                    <label class="form-check-label" for="is_approved">Disetujui</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-check" style="margin-top: 35px;">
                                    <input type="checkbox" name="is_active" class="form-check-input testimonial-is_active" id="is_active" value="1" checked>
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
                <button type="button" class="btn btn-success btn--icon-text testimonial-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
