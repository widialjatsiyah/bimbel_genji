<section id="school">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-action">
                <div class="buttons">
                    <button class="btn btn--raised btn-primary btn--icon-text school-action-add" data-toggle="modal" data-target="#modal-form-school">
                        <i class="zmdi zmdi-plus-circle"></i> Tambah Sekolah
                    </button>
                </div>
            </div>

            <?php include_once('form.php') ?>

            <div class="table-responsive">
                <table id="table-school" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Sekolah</th>
                            <th>Alamat</th>
                            <th>Email Kontak</th>
                            <th>No. Telepon</th>
                            <th>Logo URL</th>
                            <th width="170" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>
