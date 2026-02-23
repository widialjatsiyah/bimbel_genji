<section id="testimonial">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-action">
                <div class="buttons">
                    <button class="btn btn--raised btn-primary btn--icon-text testimonial-action-add" data-toggle="modal" data-target="#modal-form-testimonial">
                        <i class="zmdi zmdi-plus-circle"></i> Tambah Testimoni
                    </button>
                </div>
            </div>

            <?php include_once('form.php') ?>

            <div class="table-responsive">
                <table id="table-testimonial" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Posisi/Perusahaan</th>
                            <th>Testimoni</th>
                            <th>Rating</th>
                            <th>Urutan</th>
                            <th>Disetujui</th>
                            <th>Status</th>
                            <th width="170" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>
