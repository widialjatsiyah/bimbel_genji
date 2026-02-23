<div class="modal fade" id="modal-form-package" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Paket' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-package" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="form-group">
                        <label required>Nama Paket</label>
                        <input type="text" name="name" class="form-control package-name" maxlength="100" placeholder="Contoh: Paket Premium 1 Bulan" required />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control package-description" rows="2" placeholder="Deskripsi paket (opsional)"></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Harga (Rp)</label>
                                <input type="text" name="price" class="form-control package-price mask-money" placeholder="0" required />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Durasi (hari)</label>
                                <input type="number" name="duration_days" class="form-control package-duration_days" min="0" value="30" placeholder="0 = tidak terbatas" required />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Fitur (pisahkan dengan baris baru)</label>
                        <textarea name="features" class="form-control package-features" rows="4" placeholder="Contoh:&#10;Akses semua try out&#10;Pembahasan video&#10;Analisis mendalam"></textarea>
                        <i class="form-group__bar"></i>
                        <small class="form-text text-muted">Setiap fitur dalam baris baru.</small>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input package-is_active" id="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text package-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
