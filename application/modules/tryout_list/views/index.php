<section id="tryout-list">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?= $card_title ?></h4>
			<hr>
            <div class="row">
                <?php if (empty($tryouts)): ?>
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-state-icon bg-info">
                            <i class="zmdi zmdi-collection-text"></i>
                        </div>
                        <h5 class="mt-3">Tidak Ada Try Out Tersedia</h5>
                        <p class="text-muted">Belum ada try out yang tersedia saat ini. Silakan kembali lagi nanti.</p>
                    </div>
                </div>
                <?php else: ?>
                <?php foreach ($tryouts as $to): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 tryout-card">
                        <div class="card-header text-white bg-primary">
                            <h5 class="card-title mb-0 text-white" style="font-size: 1rem;">
                                <i class="zmdi zmdi-star mr-1"></i> 
                                <?= htmlspecialchars($to->title) ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="tryout-badge-container mb-2">
                                <span class="badge badge-pill badge-primary mr-1 mb-1"><?= $to->type ?></span>
                                <span class="badge badge-pill badge-warning text-dark mr-1 mb-1"><?= $to->mode ?></span>
                                <?php if (isset($to->is_free) && $to->is_free): ?>
                                <span class="badge badge-pill badge-success mr-1 mb-1">Gratis</span>
                                <?php endif; ?>
                            </div>
                            
                            <p class="card-text tryout-description"><?= htmlspecialchars($to->description) ?></p>
                            
                            <div class="tryout-stats mt-3">
                                <div class="row text-center">
                                    <div class="col-4 stat-item">
                                        <div class="stat-value text-primary"><?= $to->total_questions ?? 0 ?></div>
                                        <div class="stat-label">Soal</div>
                                    </div>
                                    <div class="col-4 stat-item">
                                        <div class="stat-value text-primary"><?= $to->total_duration ?? 0 ?> Menit</div>
                                        <div class="stat-label">Durasi</div>
                                    </div>
                                    <div class="col-4 stat-item">
                                        <div class="stat-value text-primary"><?= $to->total_sessions ?? count($to->sessions) ?></div>
                                        <div class="stat-label">Sesi</div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!empty($to->sessions)): ?>
                                <div class="session-progress mt-4">
                                    <h6 class="text-muted">Progres Sesi:</h6>
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: <?= count($to->sessions) > 0 ? round(($to->completed_sessions / count($to->sessions)) * 100) : 0 ?>%" 
                                             aria-valuenow="<?= $to->completed_sessions ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="<?= count($to->sessions) ?>">
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <?= $to->completed_sessions ?> dari <?= count($to->sessions) ?> sesi
                                    </small>
                                </div>
                                
                                <div class="session-list mt-3">
                                    <h6 class="text-muted">Daftar Sesi:</h6>
                                    <ul class="list-unstyled session-ul">
                                    <?php foreach ($to->sessions as $idx => $session): ?>
                                        <li class="session-item d-flex align-items-center">
                                            <?php 
                                            // Periksa apakah sesi ini sudah selesai dikerjakan
                                            $user_id = $this->session->userdata('user')['id'];
                                            $session_completed = false;
                                            
                                            if ($this->UserTryoutModel->columnExists('tryout_session_id', 'user_tryouts')) {
                                                $user_tryout = $this->UserTryoutModel->getCompletedUserTryoutBySession($user_id, $session->id);
                                                $session_completed = $user_tryout !== null;
                                            }
                                            ?>
                                            <i class="zmdi zmdi-circle <?php 
                                                echo $session_completed ? 'text-success' : 'text-secondary'; 
                                            ?>" style="font-size: 0.5rem; margin-right: 8px;"></i> 
                                            <span class="session-name" title="<?= htmlspecialchars($session->name) ?>"><?= strlen(htmlspecialchars($session->name)) > 20 ? substr(htmlspecialchars($session->name), 0, 20) . '...' : htmlspecialchars($session->name) ?></span>
                                            <?php if ($session->is_random == 1): ?>
                                                <i class="zmdi zmdi-shuffle text-warning m-2" title="Soal Acak"></i>
                                            <?php endif; ?>
                                            <i class="zmdi zmdi-time ml-2 text-info" title="<?= $session->duration_minutes ?> menit"></i>
                                        </li>
                                    <?php endforeach; ?>
                                    </ul>
                                </div>
                                
                                <div class="tryout-actions mt-3">
                                    <?php if ($to->completed_sessions == 0): ?>
                                        <a href="<?= base_url('tryout_list/start/'.$to->id) ?>" class="btn btn-primary btn-block">
                                            <i class="zmdi zmdi-play mr-1"></i> Mulai
                                        </a>
                                    <?php elseif ($to->completed_sessions < count($to->sessions)): ?>
                                        <a href="<?= base_url('tryout_list/start/'.$to->id) ?>" class="btn btn-warning btn-block text-dark">
                                            <i class="zmdi zmdi-refresh mr-1"></i> Lanjutkan
                                        </a>
                                    <?php else: ?>
                                        <div class="text-center">
                                            <a href="<?= base_url('tryout_list/start/'.$to->id) ?>" class="btn btn-info btn-block mt-2">
                                                <i class="zmdi zmdi-replay mr-1"></i> Ulangi
                                            </a>
                                        </div>
										
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning mt-3 mb-0">
                                    <i class="zmdi zmdi-alert-triangle mr-1"></i> Belum ada sesi
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">
                                <i class="zmdi zmdi-calendar mr-1"></i> 
                                <?php if(isset($to->start_time) && $to->start_time): ?>
                                    Mulai: <?= date('d M Y', strtotime($to->start_time)) ?>
                                <?php endif; ?>
                                <?php if(isset($to->end_time) && $to->end_time): ?>
                                    | Selesai: <?= date('d M Y', strtotime($to->end_time)) ?>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.tryout-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 10px;
    overflow: hidden;
}

.tryout-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.stat-item {
    padding: 0.5rem 0;
}

.stat-value {
    font-weight: bold;
    font-size: 1.1rem;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
}

.session-ul {
    max-height: 120px;
    overflow-y: auto;
    padding-left: 5px;
}

.session-item {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.session-name {
    flex-grow: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 0 5px;
}

.tryout-actions .btn {
    border-radius: 8px;
    font-weight: 500;
}

.empty-state {
    text-align: center;
    padding: 2rem;
}

.empty-state-icon {
    width: 60px;
    height: 60px;
    line-height: 60px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.tryout-badge-container {
    min-height: 30px;
}

.tryout-description {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    font-size: 0.9rem;
    color: #5a5a5a;
}
</style>
