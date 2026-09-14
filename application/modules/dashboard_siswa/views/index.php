<section id="dashboard-siswa" class="bg-light p-3">
    <div class="container-fluid">
        <!-- Header Welcome -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm overflow-hidden" >
                    <div class="card-body p-4 text-white">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="font-weight-bold mb-1">Halo, <?= $this->session->userdata('user')['nama_lengkap'] ?>! 👋</h3>
                                <p class="mb-0 opacity-75">Senang melihatmu kembali. Mari raih impianmu bersama Genji!</p>
                            </div>
                            <div class="col-auto d-none d-md-block">
                                <i class="fas fa-graduation-cap fa-4x opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 py-2" style="border-radius: 12px; border-left: 4px solid #4e73df !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Skor Terakhir</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $latest_tryout->total_score ?? '0' ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-trophy fa-2x text-gray-200"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 py-2" style="border-radius: 12px; border-left: 4px solid #1cc88a !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Progres Materi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $material_progress['completed'] ?? 0 ?>/<?= $material_progress['total'] ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book-reader fa-2x text-gray-200"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 py-2" style="border-radius: 12px; border-left: 4px solid #36b9cc !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Status Kesiapan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $progress->status_kesiapan ?? 'Mulai Belajar' ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-200"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 py-2" style="border-radius: 12px; border-left: 4px solid #f6c23e !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Ranking Kamu</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $my_rank ? '#'.$my_rank : '-' ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-medal fa-2x text-gray-200"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content Area -->
            <div class="col-lg-8">
                <!-- Ranking Per Sesi Dynamic Cards -->
                <?php if (empty($session_cards)): ?>
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4 text-center text-muted">
                            <i class="fas fa-layer-group fa-3x mb-3 opacity-25"></i>
                            <p>Belum ada sesi tryout yang tersedia dari paket aktif kamu.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($session_cards as $card): ?>
                        <?php $sess = $card['session']; ?>
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-award mr-2"></i><?= $sess->session_name ?></h6>
                                    <small class="text-muted"><?= $sess->tryout_title ?></small>
                                </div>
                                <?php if ($card['user_rank']): ?>
                                    <span class="badge badge-pill badge-primary px-3 py-2">
                                        Rangking Kamu: #<?= $card['user_rank'] ?> / <?= $card['total_participants'] ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" style="font-size: 0.85rem;">
                                        <thead class="bg-light text-muted text-uppercase small">
                                            <tr>
                                                <th class="border-0 px-4" width="60">No</th>
                                                <th class="border-0">Nama Siswa</th>
                                                <th class="border-0 text-center">Percobaan</th>
                                                <th class="border-0 text-right px-4">Skor Terbaik</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($card['participants'])): ?>
                                                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada peserta yang menyelesaikan sesi ini.</td></tr>
                                            <?php else: ?>
                                                <?php foreach ($card['participants'] as $i => $p): ?>
                                                    <?php $is_me = ((int)$p->user_id === (int)$this->session->userdata('user')['id']); ?>
                                                    <tr class="<?= $is_me ? 'table-primary font-weight-bold' : '' ?>">
                                                        <td class="px-4 align-middle">
                                                            <?php if ($i < 3): ?>
                                                                <span class="badge badge-pill badge-<?= $i == 0 ? 'warning' : ($i == 1 ? 'secondary' : 'info') ?>" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.7rem;">
                                                                    <?= $i + 1 ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="ml-1"><?= $i + 1 ?></span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="align-middle">
                                                            <?= $p->nama_lengkap ?>
                                                            <?php if($is_me): ?> <span class="badge badge-primary" style="font-size: 0.6rem;">KAMU</span> <?php endif; ?>
                                                        </td>
                                                        <td class="text-center align-middle text-muted small"><?= $p->attempt_count ?>x</td>
                                                        <td class="text-right px-4 align-middle font-weight-bold text-primary">
                                                            <?= number_format($p->best_score, 1) ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php if ($card['total_participants'] > 20): ?>
                                <div class="card-footer bg-white border-0 py-2 text-center">
                                    <small class="text-muted">Menampilkan 20 dari <?= $card['total_participants'] ?> peserta</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Sidebar Content Area Redirected: Grafik & Aktivitas Row -->

                <!-- Grafik & Aktivitas Row -->
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                            <div class="card-header bg-white border-0 py-3">
                                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-area mr-2"></i>Perkembangan Skor</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="progressChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Content -->
            <div class="col-lg-4">
                <!-- Sesi Kamu -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-check mr-2"></i>Skor Sesi Kamu</h6>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($my_session_ranking)): ?>
                            <div class="p-4 text-center text-muted">Belum ada sesi diikuti.</div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($my_session_ranking as $my): ?>
                                    <div class="list-group-item border-0 px-4 py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div style="flex: 1;">
                                                <div class="font-weight-bold mb-0"><?= $my->session_name ?></div>
                                                <small class="text-muted d-block text-truncate" style="max-width: 150px;"><?= $my->tryout_title ?></small>
                                            </div>
                                            <div class="text-right">
                                                <div class="h5 font-weight-bold text-primary mb-0"><?= number_format($my->best_score, 1) ?></div>
                                                <small class="badge badge-success"><?= $my->attempt_count ?>x percobaan</small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Try Out Tersedia -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-play-circle mr-2"></i>Try Out Tersedia</h6>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($available_tryouts)): ?>
                            <div class="p-4 text-center text-muted">Semua try out sudah dikerjakan! 🎉</div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach (array_slice($available_tryouts, 0, 5) as $to): ?>
                                    <div class="list-group-item border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                                        <div class="text-truncate mr-2" style="max-width: 180px;">
                                            <div class="font-weight-bold text-dark mb-0"><?= $to->title ?></div>
                                            <small class="text-muted"><?= $to->type ?></small>
                                        </div>
                                        <a href="<?= base_url('user_tryout/start/'.$to->id) ?>" class="btn btn-sm btn-primary shadow-sm" style="border-radius: 8px;">Mulai</a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Rekomendasi -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; background-color: #f8f9fc;">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-lightbulb text-warning mr-2"></i>Tips Untukmu</h6>
                    </div>
                    <div class="card-body pt-0">
                        <?php if (empty($recommendations)): ?>
                            <p class="small text-muted">Terus berlatih untuk dapatkan rekomendasi belajar.</p>
                        <?php else: ?>
                            <div class="small">
                                <?php foreach (array_slice($recommendations, 0, 3) as $rec): ?>
                                    <div class="mb-3 p-3 bg-white shadow-sm" style="border-radius: 10px; border-left: 3px solid #f6c23e;">
                                        <?= $rec->recommendation_text ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
