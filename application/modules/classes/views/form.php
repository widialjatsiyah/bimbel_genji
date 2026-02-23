<div class="modal fade" id="modal-form-classes" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Kelas' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-classes" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="form-group">
                        <label required>Sekolah</label>
                        <div class="select">
                            <select name="school_id" class="form-control select2 classes-school_id" data-placeholder="Pilih Sekolah" required>
                                <option value=""></option>
                                <?= $list_school ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label required>Nama Kelas</label>
                        <input type="text" name="name" class="form-control classes-name" maxlength="100" placeholder="Contoh: XII MIPA 1" required />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Tahun Ajaran</label>
                        <input type="text" name="academic_year" class="form-control classes-academic_year" maxlength="20" placeholder="Contoh: 2024/2025" />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Tingkat Kelas</label>
                        <input type="text" name="grade_level" class="form-control classes-grade_level" maxlength="20" placeholder="Contoh: 12, 11, 10" />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Wali Kelas</label>
                        <div class="select">
                            <select name="teacher_id" class="form-control select2 classes-teacher_id" data-placeholder="Pilih Wali Kelas (opsional)">
                                <option value=""></option>
                                <?= $list_teacher ?>
                            </select>
                        </div>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text classes-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
