<section id="tryout-list">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?= $card_title ?></h4>
            <div class="row">
                <?php if (empty($tryouts)): ?>
                <div class="col-12">
                    <p class="text-muted">Tidak ada try out tersedia saat ini.</p>
                </div>
                <?php else: ?>
                <?php foreach ($tryouts as $to): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= $to->title ?></h5>
                            <p class="card-text"><?= $to->description ?></p>
                            <p><strong>Tipe:</strong> <?= $to->type ?></p>
                            <p><strong>Mode:</strong> <?= $to->mode ?></p>
                            <a href="<?= base_url('user_tryout/start/'.$to->id) ?>" class="btn btn-primary">Mulai</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
