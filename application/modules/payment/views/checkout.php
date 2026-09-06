<section id="checkout">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Checkout Paket: <?= $package->name ?></h4>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>Ringkasan Pesanan</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <td>Paket</td>
                                    <td><?= $package->name ?></td>
                                </tr>
                                <tr>
                                    <td>Durasi</td>
                                    <td><?= $package->duration_days ?> hari</td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td><strong>Rp <?= number_format($package->price, 0, ',', '.') ?></strong></td>
                                </tr>
                            </table>
                            
                            <div class="d-flex justify-content-center flex-wrap" style="gap: 8px;">
                                <button class="btn btn-success btn-lg" id="pay-button">
                                    <i class="zmdi zmdi-account-balance"></i> Bayar Virtual Account
                                </button>
                                <button class="btn btn-primary btn-lg" id="manual-pay-button">
                                    <i class="zmdi zmdi-upload"></i> Bayar Manual
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Metode Pembayaran</h5>
                            <p>Silakan pilih Virtual Account melalui Midtrans atau upload bukti pembayaran manual.</p>
                            <ul>
                                <li>Transfer Bank (BCA, Mandiri, BNI, BRI)</li>
                                <li>Kartu Kredit</li>
                                <li>E-Wallet (GoPay, OVO, Dana, LinkAja)</li>
                                <li>Indomaret / Alfamart</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-checkout-manual-payment" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pembayaran Manual</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Transfer sesuai total pembayaran, lalu upload bukti transfer untuk diverifikasi admin.</p>
                <div class="form-group">
                    <label for="checkout-payment-proof">Bukti pembayaran (JPG, PNG, PDF, maksimal 4 MB)</label>
                    <input type="file" id="checkout-payment-proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                </div>
                <div class="form-group">
                    <label for="checkout-payment-note">Catatan (opsional)</label>
                    <textarea id="checkout-payment-note" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="checkout-submit-manual">Kirim Bukti</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
