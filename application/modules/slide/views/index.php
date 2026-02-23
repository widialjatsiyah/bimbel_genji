<section id="slide">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-action">
                <div class="buttons">
                    <button class="btn btn--raised btn-primary btn--icon-text slide-action-add" data-toggle="modal" data-target="#modal-form-slide">
                        <i class="zmdi zmdi-plus-circle"></i> Tambah Slide
                    </button>
                </div>
            </div>

            <?php include_once('form.php') ?>

            <div class="table-responsive">
                <table id="table-slide" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Sub Judul</th>
                            <th>Tombol</th>
                            <th>Urutan</th>
                            <th>Status</th>
                            <th width="170" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>
