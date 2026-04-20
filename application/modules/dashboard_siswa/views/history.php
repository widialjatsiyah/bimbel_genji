<section id="user-tryout-history">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?= $card_title ?></h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Try Out</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Skor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tryouts)): ?>
                        <tr><td colspan="7" class="text-center">Belum ada riwayat try out.</td></tr>
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
                                
                                // Dapatkan durasi dari sesi terkait
                                $this->load->model('TryoutSessionModel');
                                $session = null;
                                
                                // Cek apakah user_tryout memiliki sesi terkait
                                if (isset($t->tryout_session_id) && $t->tryout_session_id) {
                                    $session = $this->TryoutSessionModel->getDetail(['id' => $t->tryout_session_id]);
                                } else {
                                    // Jika tidak ada sesi spesifik, ambil sesi pertama dari tryout
                                    $session = $this->TryoutSessionModel->getFirstSession($t->tryout_id);
                                }
                                
                                if ($session && isset($session->duration_minutes)) {
                                    $duration_seconds = $session->duration_minutes * 60; // konversi menit ke detik
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
                            <td><?= $no++ ?></td>
                            <td><?= $t->tryout_title ?></td>
                            <td><?= date('d M Y H:i', strtotime($t->start_time)) ?></td>
                            <td><?= $t->end_time ? date('d M Y H:i', strtotime($t->end_time)) : '-' ?></td>
                            <td>
                                <?php
                                $badge = [
                                    'in_progress' => 'warning',
                                    'completed' => 'success',
                                    'abandoned' => 'secondary',
                                    'expired' => 'danger'
                                ];
                                
                                $status_text = $t->status;
                                if ($is_expired) {
                                    $status_text = 'expired';
                                }
                                ?>
                                <span class="badge bg-<?= $badge[$status_text] ?>"><?= $status_text ?></span>
                                <?php if ($is_expired): ?>
                                    <span class="text-danger">(Waktu Habis)</span>
                                <?php elseif ($time_left !== null && $t->status === 'in_progress'): ?>
                                    <div class="time-left" data-timeleft="<?= $time_left ?>" data-id="<?= $t->id ?>">
                                        <small>Sisa waktu: <span id="time-<?= $t->id ?>">-</span></small>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= $t->total_score ?? '-' ?></td>
                            <td>
                                <?php if ($is_expired): ?>
                                    <span class="text-muted">Sesi Berakhir</span>
                                <?php elseif ($t->status == 'completed'): ?>
                                <a href="<?= base_url('user_tryout/result/'.$t->id) ?>" class="btn btn-sm btn-info">Lihat Hasil</a>
                                <?php elseif ($t->status == 'in_progress' && !$is_expired): ?>
                                <a href="<?= base_url('user_tryout/resume/'.$t->id) ?>" class="btn btn-sm btn-warning">Lanjutkan</a>
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

<!-- <script>

</script> -->
