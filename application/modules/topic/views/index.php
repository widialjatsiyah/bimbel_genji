<section id="topic">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-action">
                <div class="buttons">
                    <button class="btn btn--raised btn-primary btn--icon-text topic-action-add" data-toggle="modal" data-target="#modal-form-topic">
                        <i class="zmdi zmdi-plus-circle"></i> Tambah Topik
                    </button>
                </div>
            </div>

            <?php include_once('form.php') ?>

            <div class="table-responsive">
                <table id="table-topic" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Topik</th>
                            <th>Urutan</th>
                            <th>Bab</th>
                            <th>Mata Pelajaran</th>
                            <th width="170" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>
