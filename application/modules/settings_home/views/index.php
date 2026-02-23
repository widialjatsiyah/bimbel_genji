<section id="setting">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <form id="form-setting" autocomplete="off" enctype="multipart/form-data">
                <!-- CSRF -->
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Informasi Umum</h5>
                        <div class="form-group">
                            <label>Judul Situs</label>
                            <input type="text" name="site_title" class="form-control" value="<?= @$settings['site_title'] ?>" />
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Meta</label>
                            <textarea name="meta_description" class="form-control" rows="2"><?= @$settings['meta_description'] ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Kata Kunci Meta</label>
                            <input type="text" name="meta_keywords" class="form-control" value="<?= @$settings['meta_keywords'] ?>" />
                        </div>
                        <div class="form-group">
                            <label>Tentang Kami</label>
                            <textarea name="about_us" class="form-control" rows="4"><?= @$settings['about_us'] ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Logo & Favicon</h5>
                        <div class="form-group">
                            <label>Logo</label>
                            <?php if (!empty($settings['site_logo'])): ?>
                                <div class="mb-2">
                                    <img src="<?= base_url($settings['site_logo']) ?>" style="max-height: 60px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="logo" class="form-control" accept="image/*" />
                            <small class="text-muted">Format: jpg, jpeg, png, gif, svg. Maks 2MB.</small>
                        </div>
                        <div class="form-group">
                            <label>Favicon</label>
                            <?php if (!empty($settings['favicon'])): ?>
                                <div class="mb-2">
                                    <img src="<?= base_url($settings['favicon']) ?>" style="max-height: 30px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="favicon" class="form-control" accept="image/*" />
                            <small class="text-muted">Format: ico, png, jpg. Maks 1MB.</small>
                        </div>
                    </div>
                </div>

                <hr class="my-4" />

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Kontak</h5>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="contact_email" class="form-control" value="<?= @$settings['contact_email'] ?>" />
                        </div>
                        <div class="form-group">
                            <label>Telepon</label>
                            <input type="text" name="contact_phone" class="form-control" value="<?= @$settings['contact_phone'] ?>" />
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="address" class="form-control" rows="2"><?= @$settings['address'] ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Media Sosial</h5>
                        <div class="form-group">
                            <label>Facebook URL</label>
                            <input type="url" name="facebook_url" class="form-control" value="<?= @$settings['facebook_url'] ?>" />
                        </div>
                        <div class="form-group">
                            <label>Twitter URL</label>
                            <input type="url" name="twitter_url" class="form-control" value="<?= @$settings['twitter_url'] ?>" />
                        </div>
                        <div class="form-group">
                            <label>Instagram URL</label>
                            <input type="url" name="instagram_url" class="form-control" value="<?= @$settings['instagram_url'] ?>" />
                        </div>
                        <div class="form-group">
                            <label>YouTube URL</label>
                            <input type="url" name="youtube_url" class="form-control" value="<?= @$settings['youtube_url'] ?>" />
                        </div>
                    </div>
                </div>

                <hr class="my-4" />

                <div class="row">
                    <div class="col-md-12">
                        <h5 class="mb-3">Footer</h5>
                        <div class="form-group">
                            <label>Copyright</label>
                            <textarea name="footer_copyright" class="form-control" rows="2"><?= @$settings['footer_copyright'] ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group text-center mt-4">
                    <button type="button" class="btn btn-success btn--icon-text" id="btn-save-setting">
                        <i class="zmdi zmdi-save"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
