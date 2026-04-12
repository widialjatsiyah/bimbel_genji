<section id="tryout_session">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-action">
                <div class="buttons">
                    <button class="btn btn--raised btn-primary btn--icon-text tryout_session-action-add" data-toggle="modal" data-target="#modal-form-tryout_session">
                        <i class="zmdi zmdi-plus-circle"></i> Tambah Sesi
                    </button>
                </div>
            </div>

            <?php include_once('form.php') ?>
            <?php include_once('manage_questions.php') ?>

            <div class="table-responsive">
                <table id="table-tryout_session" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Try Out</th>
                            <th>Nama Sesi</th>
                            <th>Urutan</th>
                            <th>Durasi (menit)</th>
                            <th>Jumlah Soal</th>
                            <th>Acak Soal</th>
                            <th>Metode Skor</th>
                            <th>Waktu PerSoal</th>
                            <th>Deskripsi</th>
                            <th width="220" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>
