<section id="meeting-quiz">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <a href="<?= base_url('class_meeting/index/'.$meeting->class_id) ?>" class="btn btn-secondary mb-3">
                <i class="zmdi zmdi-arrow-left"></i> Kembali ke Pertemuan
            </a>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>Tambah Quiz</h5>
                            <form id="form-add-quiz">
                                <input type="hidden" name="meeting_id" value="<?= $meeting_id ?>">
                                <div class="form-group">
                                    <label>Pilih Quiz</label>
                                    <select name="quiz_id" id="quiz-select" class="form-control select2" style="width: 100%;"></select>
                                </div>
                                <div class="form-group">
                                    <label>Urutan (opsional)</label>
                                    <input type="number" name="order_num" class="form-control" min="0" value="0">
                                </div>
                                <button type="button" class="btn btn-primary" id="btn-add-quiz">Tambah</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>Daftar Quiz</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table-quizzes">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul</th>
                                            <th>Deskripsi</th>
                                            <th>Durasi (menit)</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
