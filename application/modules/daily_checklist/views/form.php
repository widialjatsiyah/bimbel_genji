<div class="modal fade" id="modal-form-material" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Materi' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-material" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="form-group">
                        <label required>Judul Materi</label>
                        <input type="text" name="title" class="form-control material-title" maxlength="200" placeholder="Contoh: Video Turunan Fungsi" required />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Tipe</label>
                                <div class="select">
                                    <select name="type" class="form-control material-type" required>
                                        <option value="video">Video</option>
                                        <option value="pdf">PDF</option>
                                        <option value="modul">Modul</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>URL</label>
                                <input type="text" name="url" class="form-control material-url" placeholder="https://..." required />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mata Pelajaran</label>
                                <div class="select">
                                    <select name="subject_id" class="form-control select2 material-subject_id" data-placeholder="Pilih Mata Pelajaran (opsional)">
                                        <option value=""></option>
                                        <?= $list_subject ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bab</label>
                                <div class="select">
                                    <select name="chapter_id" class="form-control select2 material-chapter_id" data-placeholder="Pilih Bab (opsional)">
                                        <option value=""></option>
                                        <?= $list_chapter ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control material-description" rows="3" placeholder="Deskripsi materi (opsional)"></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Durasi (detik)</label>
                                <input type="number" name="duration_seconds" class="form-control material-duration_seconds" min="0" placeholder="Misal: 3600 untuk 1 jam" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check" style="margin-top: 35px;">
                                    <input type="checkbox" name="is_active" class="form-check-input material-is_active" id="is_active" value="1" checked>
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
                <button type="button" class="btn btn-success btn--icon-text material-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
