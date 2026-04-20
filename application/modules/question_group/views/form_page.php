<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?= $card_title ?></h4>
                
                <form id="form-question-group" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <input type="hidden" name="group_id" value="<?php echo isset($group_data['group_data']['group_id']) ? $group_data['group_data']['group_id'] : '' ?>" />

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Pilih Soal</label>
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Soal Tersedia</h5>
                                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                                <table id="table-available-questions" class="table table-striped table-bordered">
                                                    <thead class="sticky-top">
                                                        <tr>
                                                            <th width="50">No</th>
                                                            <th>Soal</th>
                                                            <th width="100" class="text-center">#</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Data akan diisi oleh JavaScript -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>Soal Terpilih</h5>
                                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                                <table id="table-selected-questions" class="table table-striped table-bordered">
                                                    <thead class="sticky-top">
                                                        <tr>
                                                            <th width="50">No</th>
                                                            <th>Soal</th>
                                                            <th width="100" class="text-center">#</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Data akan diisi oleh JavaScript -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="main_question_id" class="col-sm-12 control-label">Soal Utama</label>
                                <div class="col-sm-12">
                                    <div class="form-group form-group--float form-group--active">
                                        <select name="main_question_id" id="main_question_id" class="select2" style="width: 100%;">
                                            <option value="">Pilih Soal Utama</option>
                                            <!-- Options will be loaded via AJAX -->
                                        </select>
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions" style="margin-top: 20px;">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success btn--icon-text question_group-action-save">
                                    <i class="zmdi zmdi-save"></i> Simpan
                                </button>
                                <button type="button" class="btn btn-light btn--icon-text question_group-action-cancel">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Kirim data grup ke JavaScript jika sedang dalam mode edit
    <?php if (isset($group_data) && $group_data): ?>
    var groupData = <?php echo json_encode($group_data); ?>;
    <?php endif; ?>
</script>