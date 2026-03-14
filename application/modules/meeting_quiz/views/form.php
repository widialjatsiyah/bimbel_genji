<div class="modal fade" id="modal-form-meeting" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Pertemuan' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-meeting" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <input type="hidden" name="class_id" value="<?= $class_id ?>">

                    <div class="form-group">
                        <label required>Judul Pertemuan</label>
                        <input type="text" name="title" class="form-control meeting-title" maxlength="200" placeholder="Contoh: Pertemuan 1 - Pengantar" required />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control meeting-description" rows="2" placeholder="Deskripsi pertemuan (opsional)"></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" name="date" class="form-control flatpickr-date meeting-date" placeholder="YYYY-MM-DD" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jam Mulai</label>
                                <input type="text" name="start_time" class="form-control flatpickr-time meeting-start_time" placeholder="HH:MM" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jam Selesai</label>
                                <input type="text" name="end_time" class="form-control flatpickr-time meeting-end_time" placeholder="HH:MM" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Link Meeting (opsional)</label>
                        <input type="url" name="meeting_link" class="form-control meeting-meeting_link" placeholder="https://meet.google.com/xxx" />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>Urutan</label>
                        <input type="number" name="order_num" class="form-control meeting-order_num" min="0" value="0" />
                        <i class="form-group__bar"></i>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text meeting-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
