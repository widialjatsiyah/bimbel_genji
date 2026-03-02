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
                            
                            <button class="btn btn-success btn-lg" id="pay-button">
                                <i class="zmdi zmdi-card"></i> Bayar Sekarang
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Metode Pembayaran</h5>
                            <p>Kami menerima berbagai metode pembayaran melalui Midtrans:</p>
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
