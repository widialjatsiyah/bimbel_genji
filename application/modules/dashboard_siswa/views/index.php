<section id="dashboard-siswa">
    <div class="row">
        <!-- Selamat Datang -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4>Halo, <?= $this->session->userdata('user')['nama_lengkap'] ?>!</h4>
                    <p>Selamat datang di dashboard siswa. Terus semangat belajar!</p>
                </div>
            </div>
        </div>

        <!-- Statistik Cepat -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Skor Terakhir</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $latest_tryout->total_score ?? '-' ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Progres Materi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $material_progress['completed'] ?? 0 ?>/<?= $material_progress['total'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Status Kesiapan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $progress->status_kesiapan ?? 'Belum Ada' ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Checklist Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $daily_checklist_today ? 'Sudah' : 'Belum' ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Perkembangan -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Grafik Perkembangan (30 Hari Terakhir)</h6>
                </div>
                <div class="card-body">
                    <canvas id="progressChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Rekomendasi -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Rekomendasi Belajar</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($recommendations)): ?>
                        <p class="text-muted">Tidak ada rekomendasi saat ini.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($recommendations as $rec): ?>
                            <li class="list-group-item"><?= $rec->recommendation_text ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Try Out Tersedia -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Try Out Tersedia</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($available_tryouts)): ?>
                        <p class="text-muted">Belum ada try out baru.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($available_tryouts as $to): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= $to->title ?>
                                <a href="<?= base_url('user_tryout/start/'.$to->id) ?>" class="btn btn-sm btn-primary">Mulai</a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Aktivitas Terakhir -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Aktivitas Terakhir</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_activities)): ?>
                        <p class="text-muted">Belum ada aktivitas.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($recent_activities as $act): ?>
                            <li class="list-group-item">
                                <?= $act->tryout_title ?> - 
                                <span class="badge bg-<?= $act->status == 'completed' ? 'success' : 'warning' ?>">
                                    <?= $act->status ?>
                                </span>
                                <small class="text-muted float-end"><?= date('d M H:i', strtotime($act->created_at)) ?></small>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
