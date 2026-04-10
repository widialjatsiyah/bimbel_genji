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
                            
                            <?php if (!empty($to->sessions)): ?>
                                <div class="mt-3">
                                    <p><strong>Sesi:</strong></p>
                                    <ul class="list-unstyled">
                                    <?php foreach ($to->sessions as $idx => $session): ?>
                                        <li>
                                            <i class="fa fa-circle text-<?php 
                                                echo ($to->completed_sessions > $idx) ? 'success' : 'secondary'; 
                                            ?>"></i> 
                                            <?= $session->name ?> 
                                            <?php if ($session->is_random == 1): ?>
                                                <span class="badge bg-warning">Acak</span>
                                            <?php endif; ?>
                                            <span class="badge bg-info"><?= $session->scoring_method == 'correct_incorrect' ? 'Benar/Salah' : 'Poin per Soal' ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                    </ul>
                                    
                                    <?php if ($to->completed_sessions == 0): ?>
                                        <a href="<?= base_url('tryout_list/start/'.$to->id) ?>" class="btn btn-primary">Mulai</a>
                                    <?php elseif ($to->completed_sessions < count($to->sessions)): ?>
                                        <a href="<?= base_url('tryout_list/start/'.$to->id) ?>" class="btn btn-warning">Lanjutkan</a>
                                    <?php else: ?>
                                        <span class="badge bg-success">Selesai</span>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Tryout ini belum memiliki sesi.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>