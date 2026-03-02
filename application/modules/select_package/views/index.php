<section id="select-package">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <p class="card-subtitle">Pilih paket yang sesuai dengan kebutuhan belajar Anda.</p>

            <div class="row mt-4">
                <?php if (empty($packages)): ?>
                <div class="col-12">
                    <div class="alert alert-info">Belum ada paket tersedia.</div>
                </div>
                <?php else: ?>
                    <?php foreach ($packages as $p): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                <h5 class="card-title text-primary"><?= $p->name ?></h5>
                                <h3 class="text-success">Rp <?= number_format($p->price, 0, ',', '.') ?></h3>
                                <p class="card-text"><?= $p->description ?></p>
                                <p><strong>Durasi:</strong> <?= $p->duration_days ?> hari</p>
                               <a href="<?= base_url('payment/checkout/'.$p->id) ?>" class="btn btn-success btn-block">Beli Paket</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
