<section id="question">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-action">
                <div class="buttons">
                    <button class="btn btn--raised btn-primary btn--icon-text question-action-add" data-toggle="modal" data-target="#modal-form-question">
                        <i class="zmdi zmdi-plus-circle"></i> Tambah Soal
                    </button>
                </div>
            </div>

            <?php include_once('form.php') ?>

            <div class="table-responsive">
                <table id="table-question" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Soal</th>
                            <th>Mapel</th>
                            <th>Bab</th>
                            <th>Topik</th>
                            <th>Kesulitan</th>
                            <th>Kurikulum</th>
                            <th>Jawaban</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th width="170" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>
