<section id="class-meeting">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <a href="<?= base_url('dashboard_tutor') ?>" class="btn btn-secondary mb-3">
                <i class="zmdi zmdi-arrow-left"></i> Kembali ke Dashboard
            </a>

            <div class="table-action">
                <div class="buttons">
                    <button class="btn btn--raised btn-primary btn--icon-text meeting-action-add" data-toggle="modal" data-target="#modal-form-meeting">
                        <i class="zmdi zmdi-plus-circle"></i> Tambah Pertemuan
                    </button>
                </div>
            </div>

            <?php include_once('form.php') ?>

            <div class="table-responsive">
                <table id="table-meeting" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Link Meeting</th>
                            <th>Urutan</th>
                            <th width="170" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>
