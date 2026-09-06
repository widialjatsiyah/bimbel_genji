<section id="user_package">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-action">
                <div class="buttons">
                    <button class="btn btn--raised btn-primary btn--icon-text user_package-action-add" data-toggle="modal" data-target="#modal-form-user_package">
                        <i class="zmdi zmdi-plus-circle"></i> Tambah Paket Pengguna
                    </button>
                </div>
            </div>

            <?php include_once('form.php') ?>

            <div class="modal fade" id="modal-payment-proof-review" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Verifikasi Bukti Pembayaran</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <strong>User:</strong> <span id="proof-review-user"></span><br>
                                <strong>Paket:</strong> <span id="proof-review-package"></span><br>
                                <strong>Catatan:</strong> <span id="proof-review-note">-</span>
                            </div>
                            <div id="proof-review-preview" class="text-center border p-2" style="min-height: 240px;"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success action-review-decision" data-decision="approve"><i class="zmdi zmdi-check"></i> Terima</button>
                            <button type="button" class="btn btn-danger action-review-decision" data-decision="reject"><i class="zmdi zmdi-close"></i> Tolak</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="table-user_package" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Pengguna</th>
                            <th>Paket</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Akhir</th>
                            <th>Status</th>
                            <th>Status Pembayaran</th>
                            <th width="170" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>
