<section id="question_group">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-action">
                <div class="buttons">
                    <a href="<?= base_url('question_group/form') ?>" class="btn btn--raised btn-primary btn--icon-text">
                        <i class="zmdi zmdi-plus-circle"></i> Buat Grup Soal Baru
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table id="table-question-group" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>ID Grup</th>
                            <th>Jumlah Soal</th>
                            <th>Soal Utama</th>
                            <th>Dibuat</th>
                            <th width="170" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>