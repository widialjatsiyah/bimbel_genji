<section id="classes">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-action mb-3">
                <div class="buttons">
                    <button class="btn btn-primary classes-action-add" data-toggle="modal" data-target="#modal-form-classes">
                        <i class="zmdi zmdi-plus-circle"></i> Tambah Kelas
                    </button>
                </div>
            </div>

            <?php include_once('form.php') ?>

            <div class="table-responsive">
                <table id="table-classes" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Kelas</th>
                            <th>Tingkat</th>
                            <th>Tahun Ajaran</th>
                            <th>Sekolah</th>
                            <th>Wali Kelas</th>
                            <th width="170" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>
