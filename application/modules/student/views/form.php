<div class="modal fade" id="modal-form-student" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Siswa</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-student">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control student-nama_lengkap" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control student-email" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control student-username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control student-password" <?= isset($is_edit) ? '' : 'required' ?>>
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah</small>
                    </div>
                    <?php if($this->session->userdata('user')['role'] == 'Administrator'): ?>
                    <div class="form-group">
                        <label>Sekolah</label>
                        <select name="unit" class="form-control select2 student-unit">
                            <option value="">Pilih Sekolah</option>
                            <?php foreach($this->SchoolModel->getAll() as $school): ?>
                                <option value="<?= $school->id ?>"><?= $school->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Sub Sekolah</label>
                        <select name="sub_unit" class="form-control select2 student-sub_unit">
                            <option value="">Pilih Sub Sekolah</option>
                            <?php foreach($this->SubunitModel->getAll() as $subunit): ?>
                                <option value="<?= $subunit->id ?>"><?= $subunit->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" name="is_active" class="form-check-input student-is_active" checked>
                            <span class="form-check-sign"></span>
                            <span>Aktif</span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success student-action-save">Simpan</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>