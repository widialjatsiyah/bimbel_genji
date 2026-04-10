<section id="tryout_question">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-action">
                <div class="buttons">
                    <button class="btn btn--raised btn-primary btn--icon-text tryout_question-action-add" data-toggle="modal" data-target="#modal-form-tryout_question">
                        <i class="zmdi zmdi-plus-circle"></i> Tambah Soal
                    </button>
                </div>
            </div>

            <?php include_once('form.php') ?>

            <div class="table-responsive">
                <table id="table-tryout_question" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Sesi Try Out</th>
                            <th>Soal</th>
                            <th>Urutan</th>
                            <th>Poin</th>
                            <th width="220" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>