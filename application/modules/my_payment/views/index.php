<section id="my-payment">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-responsive">
                <table id="table-my-payment" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Order ID</th>
                            <th>Nama Paket</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Verifikasi Bukti</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-manual-payment" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Bukti Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Order: <strong id="manual-payment-order"></strong></p>
                <input type="hidden" id="manual-payment-order-id">
                <div id="manual-payment-existing-proof" class="alert alert-info d-none"></div>
                <div class="form-group">
                    <label for="manual-payment-proof">Bukti pembayaran (JPG, PNG, PDF, maksimal 4 MB)</label>
                    <input type="file" id="manual-payment-proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                </div>
                <div class="form-group">
                    <label for="manual-payment-note">Catatan (opsional)</label>
                    <textarea id="manual-payment-note" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="manual-payment-submit">Kirim Bukti</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
