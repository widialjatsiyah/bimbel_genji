<div class="container mt-5">
    <div class="card">
        <div class="card-body text-center">
            <h2>Selamat! Anda telah menyelesaikan try out.</h2>
            <p>Skor Anda: <?= $user_tryout->total_score ?? 'Sedang diproses' ?></p>
            <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">Ke Dashboard</a>
            <a href="<?= base_url('tryout/hasil/'.$user_tryout->id) ?>" class="btn btn-success">Lihat Hasil Detail</a>
        </div>
    </div>
	<a href="<?= base_url('user_tryout/export_pdf/'.$user_tryout->id) ?>" class="btn btn-success" target="_blank">
    <i class="fas fa-file-pdf"></i> Ekspor ke PDF
</a>
</div>
