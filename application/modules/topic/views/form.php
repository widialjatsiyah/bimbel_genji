<div class="modal fade" id="modal-form-topic" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Topik' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-topic" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="form-group">
                        <label required>Bab</label>
                        <div class="select">
                            <select name="chapter_id" class="form-control select2 topic-chapter_id" data-placeholder="Pilih Bab" required>
                                <option value=""></option>
                                <?php 
                                // Tampilkan option dengan format "Bab: Nama Bab (Mata Pelajaran)"
                                $chapters = $this->ChapterModel->getAll([], 'name', 'asc');
                                foreach ($chapters as $chapter) {
                                    $subject = $this->SubjectModel->getDetail(['id' => $chapter->subject_id]);
                                    $subject_name = $subject ? $subject->name : '-';
                                    echo '<option value="' . $chapter->id . '">' . $chapter->name . ' (' . $subject_name . ')</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label required>Nama Topik</label>
                        <input type="text" name="name" class="form-control topic-name" maxlength="100" placeholder="Contoh: Turunan Fungsi Aljabar" required />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Urutan</label>
                        <input type="number" name="order_num" class="form-control topic-order_num" min="0" placeholder="Urutan (opsional)" />
                        <i class="form-group__bar"></i>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text topic-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
