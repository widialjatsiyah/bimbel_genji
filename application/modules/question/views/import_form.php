<div class="modal fade" id="modal-form-question-import" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Impor Soal dari Excel</h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-question-import" autocomplete="off" enctype="multipart/form-data">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Petunjuk Penggunaan:</h6>
                                <ol class="mb-0">
                                    <li>Unduh template Excel terlebih dahulu dengan klik tombol "Unduh Template"</li>
                                    <li>Isi data soal sesuai kolom yang ditentukan</li>
                                    <li>Pastikan kolom wajib diisi tidak kosong</li>
                                    <li>Pilih mata pelajaran yang sesuai</li>
                                    <li>Upload file Excel yang telah diisi</li>
                                </ol>
                            </div>
                            
                            <div class="form-group">
                                <label required>Pilih Mata Pelajaran:</label>
                                <div class="select">
                                    <select class="form-control select2 question-import-subject_id" name="subject_id" data-placeholder="Pilih Mata Pelajaran" required>
                                        <option value=""></option>
                                        <?= $list_subject ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label required>Pilih File Excel:</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input question-import-file" id="importFile" name="import_file" accept=".xlsx,.xls" required>
                                    <label class="custom-file-label" for="importFile">Pilih file...</label>
                                </div>
                                <i class="form-group__bar"></i>
                                <small class="form-text text-muted">Format file yang didukung: .xlsx atau .xls (maks. 2MB)</small>
                            </div>
                            
                            <div class="form-group mt-4">
                                <button type="button" class="btn btn-outline-primary question-import-download-template">
                                    <i class="zmdi zmdi-download"></i> Unduh Template Excel
                                </button>
                                <small class="form-text text-muted mt-2">
                                    Template berisi contoh format pengisian dan petunjuk kolom yang harus diisi
                                </small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text question-import-action-save">
                    <i class="zmdi zmdi-upload"></i> Mulai Impor
                </button>
                <button type="button" class="btn btn-light btn--icon-text question-import-action-cancel" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>