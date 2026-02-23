<section id="tryout">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-action">
                <div class="buttons">
                    <button class="btn btn--raised btn-primary btn--icon-text tryout-action-add" data-toggle="modal" data-target="#modal-form-tryout">
                        <i class="zmdi zmdi-plus-circle"></i> Tambah Try Out
                    </button>
                </div>
            </div>

            <?php include_once('form.php') ?>

            <div class="table-responsive">
                <table id="table-tryout" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Tipe</th>
                            <th>Mode</th>
                            <th>Durasi (menit)</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Publikasi</th>
                            <th width="170" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>
