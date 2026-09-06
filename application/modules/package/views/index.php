<section id="package">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-action">
                <div class="buttons">
                    <button class="btn btn--raised btn-primary btn--icon-text package-action-add" data-toggle="modal" data-target="#modal-form-package">
                        <i class="zmdi zmdi-plus-circle"></i> Tambah Paket
                    </button>
                </div>
            </div>

            <?php include_once('form.php') ?>

            <div class="modal fade" id="modal-import-user-package" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Import User ke Paket</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted">Paket: <strong id="import-package-name"></strong></p>
                            <div class="alert alert-info">
                                Kolom wajib: <strong>Email User</strong>. Isi <strong>Paket (ID/Nama)</strong> untuk memetakan setiap baris ke tabel packages. Jika kosong, paket yang dipilih akan digunakan. Tanggal dan status boleh dikosongkan untuk memakai nilai default paket.
                            </div>
                            <div class="form-group">
                                <label for="package-import-file">File Excel (.xlsx/.xls)</label>
                                <input type="file" id="package-import-file" class="form-control" accept=".xlsx,.xls">
                            </div>
                            <small class="text-muted">Email harus sudah terdaftar pada tabel user.</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="package-download-template">Download Template</button>
                            <button type="button" class="btn btn-success" id="package-do-import">Import</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="table-package" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Paket</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Durasi (hari)</th>
                            <th>Fitur</th>
                            <th>Status</th>
                            <th width="170" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>
