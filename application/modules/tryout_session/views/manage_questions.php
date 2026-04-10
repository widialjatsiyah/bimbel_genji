<div class="modal fade" id="modal-manage-questions" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    Kelola Soal dalam Sesi
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-manage-questions" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <input type="hidden" name="current_session_id" id="current_session_id" />

                    <div class="form-group">
                        <label>Sesi Try Out: <span id="session_name_label"></span></label>
                    </div>

                    <div class="form-group">
                        <label required>Pilihan Soal</label>
                        <div class="select">
                            <select name="question_ids[]" id="question_select" class="form-control select2-multiple" multiple="multiple" data-placeholder="Pilih satu atau lebih soal">
                                <!-- Options will be populated via AJAX -->
                            </select>
                        </div>
                        <small class="form-text text-muted">Pilih satu atau lebih soal dengan menahan tombol Ctrl/Cmd saat klik</small>
                    </div>

                    <div class="form-group">
                        <label required>Metode Penomoran Soal</label>
                        <div class="radio radio--charlie">
                            <label>
                                <input type="radio" name="ordering_method" value="sequential" checked>
                                <span class="choice">
                                    <strong>Urutkan secara berurutan</strong> - Mulai dari nomor yang ditentukan dan terus bertambah
                                </span>
                            </label>
                        </div>
                        <div class="radio radio--charlie">
                            <label>
                                <input type="radio" name="ordering_method" value="auto">
                                <span class="choice">
                                    <strong>Otomatis</strong> - Gunakan urutan terakhir pada sesi dan tambahkan soal-soal baru
                                </span>
                            </label>
                        </div>
                        
                        <div id="start-order-input" class="mt-2">
                            <label for="start_order">Nomor Urut Awal:</label>
                            <input type="number" name="start_order" class="form-control" min="1" value="1" />
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <h5>Daftar Soal dalam Sesi Ini</h5>
                        <table id="table-session-questions" class="table table-bordered">
                            <thead class="thead-default">
                                <tr>
                                    <th width="10%">No</th>
                                    <th width="75%">Isi Soal</th>
                                    <th>Nilai</th>
                                    <th width="100" class="text-center">#</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text manage-questions-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan Soal
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

