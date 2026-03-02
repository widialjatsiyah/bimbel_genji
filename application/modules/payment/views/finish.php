<section id="payment-finish">
    <div class="card">
        <div class="card-body text-center">
            <h4 class="card-title"><?= $card_title ?></h4>
            
            <?php if ($transaction->transaction_status == 'settlement' || $transaction->transaction_status == 'capture'): ?>
                <div class="alert alert-success">
                    <i class="zmdi zmdi-check-circle" style="font-size: 4rem;"></i>
                    <h3>Pembayaran Berhasil!</h3>
                    <p>Paket Anda telah aktif. Silakan mulai belajar.</p>
                </div>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">Ke Dashboard</a>
            <?php elseif ($transaction->transaction_status == 'pending'): ?>
                <div class="alert alert-warning">
                    <i class="zmdi zmdi-time-restore" style="font-size: 4rem;"></i>
                    <h3>Menunggu Pembayaran</h3>
                    <p>Pembayaran Anda sedang diproses. Silakan cek status secara berkala.</p>
                </div>
                <a href="<?= base_url('my_packages') ?>" class="btn btn-primary">Lihat Paket Saya</a>
            <?php else: ?>
                <div class="alert alert-danger">
                    <i class="zmdi zmdi-close-circle" style="font-size: 4rem;"></i>
                    <h3>Pembayaran Gagal</h3>
                    <p>Status: <?= $transaction->transaction_status ?></p>
                </div>
                <a href="<?= base_url('select_package') ?>" class="btn btn-primary">Pilih Paket Lain</a>
            <?php endif; ?>
        </div>
    </div>
</section>
