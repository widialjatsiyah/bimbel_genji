<section id="user-tryout-history" class="p-3">
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <div>
                    <h4 class="font-weight-bold text-primary mb-1"><i class="fas fa-history mr-2"></i><?= $card_title ?></h4>
                    <p class="text-muted small mb-0">Daftar semua sesi latihan dan ujian try out yang telah atau sedang kamu ikuti.</p>
                </div>
                <a href="<?= base_url('dashboard_siswa') ?>" class="btn btn-outline-primary btn-sm px-3" style="border-radius: 8px;">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                </a>
            </div>

            <div class="table-responsive">
                <table id="table-history" class="table table-striped table-hover align-middle mb-0" style="font-size:0.92rem;">

                    <thead class="bg-light text-muted text-uppercase small">
                        <tr>
                            <th class="border-0 px-3" width="50">No</th>
                            <th class="border-0">Try Out & Sesi</th>
                            <th class="border-0">Waktu Pengerjaan</th>
                            <th class="border-0 text-center">Status</th>
                            <th class="border-0 text-center">Skor</th>
                            <th class="border-0 text-center px-3" width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tryouts)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-list fa-3x mb-3 text-gray-300 d-block"></i>
                                Belum ada riwayat try out yang dikerjakan.
                            </td>
                        </tr>
                        <?php else: $no=1; foreach ($tryouts as $t): 
                            // Periksa apakah sesi telah kadaluarsa
                            $is_expired = false;
                            if ($t->status === 'in_progress') {
                                $this->load->model('UserTryoutModel');
                                $is_expired = $this->UserTryoutModel->isSessionExpired($t->id);
                            }
                            
                            // Jika status adalah in_progress dan belum expired, hitung sisa waktu
                            $time_left = null;
                            if ($t->status === 'in_progress' && !$is_expired) {
                                $start_time = strtotime($t->start_time);
                                
                                $this->load->model('TryoutSessionModel');
                                $session = null;
                                
                                if (isset($t->tryout_session_id) && $t->tryout_session_id) {
                                    $session = $this->TryoutSessionModel->getDetail(['id' => $t->tryout_session_id]);
                                } else {
                                    $session = $this->TryoutSessionModel->getFirstSession($t->tryout_id);
                                }
                                
                                if ($session && isset($session->duration_minutes)) {
                                    $duration_seconds = $session->duration_minutes * 60;
                                    $end_time = $start_time + $duration_seconds;
                                    $current_time = time();
                                    $time_left = $end_time - $current_time;
                                    
                                    if ($time_left <= 0) {
                                        $time_left = 0;
                                        $is_expired = true;
                                    }
                                }
                            }
                        ?>
                        <tr>
                            <td class="px-3 text-muted align-middle"><?= $no++ ?></td>
                            <td class="align-middle">
                                <div class="font-weight-bold text-dark"><?= $t->tryout_title ?></div>
                                <?php if (!empty($t->session_name)): ?>
                                    <div class="small text-primary font-weight-bold"><i class="fas fa-layer-group mr-1"></i><?= $t->session_name ?></div>
                                <?php else: ?>
                                    <div class="small text-muted">Sesi Standar / Utama</div>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle text-muted small">
                                <div><i class="far fa-calendar-alt mr-1"></i><?= date('d M Y, H:i', strtotime($t->start_time)) ?></div>
                                <?php if ($t->end_time): ?>
                                    <div><i class="far fa-clock mr-1"></i>Selesai: <?= date('d M Y, H:i', strtotime($t->end_time)) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle text-center">
                                <?php if ($is_expired): ?>
                                    <span class="badge badge-pill badge-danger px-3 py-1">Waktu Habis</span>
                                <?php elseif ($t->status === 'completed'): ?>
                                    <span class="badge badge-pill badge-success px-3 py-1">Selesai</span>
                                <?php elseif ($t->status === 'in_progress'): ?>
                                    <span class="badge badge-pill badge-warning px-3 py-1">Sedang Berjalan</span>
                                    <?php if ($time_left !== null): ?>
                                        <div class="small text-muted mt-1 time-left" data-timeleft="<?= $time_left ?>" data-id="<?= $t->id ?>">
                                            Sisa: <span id="time-<?= $t->id ?>" class="font-weight-bold text-danger">-</span>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-pill badge-secondary px-3 py-1"><?= ucfirst($t->status) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle text-center font-weight-bold text-primary h6 mb-0">
                                <?= $t->total_score !== null ? number_format($t->total_score, 1) : '-' ?>
                            </td>
                            <td class="align-middle text-center px-3">
                                <?php if ($is_expired): ?>
                                    <span class="text-muted small">Selesai Otomatis</span>
                                <?php elseif ($t->status == 'completed'): ?>
                                    <a href="<?= base_url('user_tryout/result/'.$t->id) ?>" class="btn btn-sm btn-outline-info shadow-sm px-3" style="border-radius: 8px;">
                                        <i class="fas fa-poll mr-1"></i> Hasil
                                    </a>
                                <?php elseif ($t->status == 'in_progress' && !$is_expired): ?>
                                    <a href="<?= base_url('user_tryout/resume/'.$t->id) ?>" class="btn btn-sm btn-warning shadow-sm px-3" style="border-radius: 8px;">
                                        <i class="fas fa-play mr-1"></i> Lanjut
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- 
<script>
</script> -->
