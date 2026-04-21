<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section id="select-package">
    <div class="container">
        <div class="row justify-content-center bg-light py-5 rounded">
            <div class="col-lg-8 text-center">
                <h2 class="mb-3"><?= isset($card_title) ? $card_title : 'Paket Pembelajaran' ?></h2>
                <p class="text-muted">Pilih paket yang sesuai dengan kebutuhan belajar Anda untuk akses materi, latihan soal, dan ujian simulasi.</p>
            </div>
        </div>
        <div class="row">
            <?php if (empty($packages)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">Belum ada paket tersedia saat ini. Silakan kembali lagi nanti.</div>
                </div>
            <?php else: ?>
                <?php foreach ($packages as $p): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card package-card h-100 shadow-sm border-0">
                        <div class="card-header text-center py-4 bg-primary text-white rounded-top">
                            <h5 class="mb-1 text-white"><?= $p->name ?></h5>
                            <small class="opacity-75">Paket Terpopuler</small>
                        </div>
                        <div class="card-body text-center pt-5 pb-4 px-4">
                            <div class="price-display mb-3">
                                <span class="currency">Rp</span>
                                <span class="price-amount"><?= number_format($p->price, 0, ',', '.') ?></span>
                            </div>
                            <p class="package-description"><?= substr($p->description, 0, 120) . (strlen($p->description) > 120 ? '...' : '') ?></p>
                            
                            <div class="features-list my-4 text-left">
                                <div class="feature-item d-flex mb-2">
                                    <div class="feature-icon mr-3"><i class="zmdi zmdi-check-circle text-success"></i></div>
                                    <div class="feature-text"><strong>Durasi Akses:</strong> <?= $p->duration_days ?> hari penuh</div>
                                </div>
                                <div class="feature-item d-flex mb-2">
                                    <div class="feature-icon mr-3"><i class="zmdi zmdi-check-circle text-success"></i></div>
                                    <div class="feature-text"><strong>Materi Lengkap</strong></div>
                                </div>
                                <div class="feature-item d-flex mb-2">
                                    <div class="feature-icon mr-3"><i class="zmdi zmdi-check-circle text-success"></i></div>
                                    <div class="feature-text"><strong>Tryout & Latihan Soal</strong></div>
                                </div>
                                <div class="feature-item d-flex mb-2">
                                    <div class="feature-icon mr-3"><i class="zmdi zmdi-check-circle text-success"></i></div>
                                    <div class="feature-text"><strong>Dukungan Mentor</strong></div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 mt-3">
                                <a href="<?= base_url('payment/checkout/'.$p->id) ?>" class="btn btn-primary btn-lg custom-btn">Pilih Paket Ini</a>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent py-3 text-center">
                            <small class="text-muted">Dapatkan akses sekarang dan mulai belajar secara efektif</small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.package-card {
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.package-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
}

.price-display {
    display: flex;
    justify-content: center;
    align-items: baseline;
    gap: 5px;
}

.currency {
    font-size: 1.2rem;
    color: #6c757d;
}

.price-amount {
    font-size: 2.5rem;
    font-weight: 700;
    color: #007bff;
}

.package-description {
    color: #6c757d;
    line-height: 1.6;
}

.features-list .feature-icon {
    min-width: 24px;
}

.custom-btn {
    border-radius: 8px;
    padding: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    background-color: #007bff;
    border: none;
    transition: all 0.3s ease;
}

.custom-btn:hover {
    background-color: #0069d9;
    transform: scale(1.02);
}
</style>
