<section id="student-material">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <?php if (!empty($subjects)): ?>
            <ul class="nav nav-tabs mb-3" id="subjectTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">Semua</button>
                </li>
                <?php foreach ($subjects as $s): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="subject-<?= $s->id ?>-tab" data-bs-toggle="tab" data-bs-target="#subject-<?= $s->id ?>" type="button" role="tab"><?= $s->name ?></button>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

            <div class="tab-content" id="subjectTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    <div class="row">
                        <?php foreach ($materials as $m): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $m->title ?></h5>
                                    <p class="card-text"><?= $m->description ?></p>
                                    <p>
                                        <span class="badge bg-info"><?= $m->type ?></span>
                                        <?php if ($m->duration_seconds): ?>
                                        <span class="badge bg-secondary"><?= floor($m->duration_seconds / 60) ?> menit</span>
                                        <?php endif; ?>
                                    </p>
                                    <?php if ($m->progress): ?>
                                    <div class="progress mb-2">
                                        <div class="progress-bar" role="progressbar" style="width: <?= $m->progress->progress_percent ?>%;">
                                            <?= $m->progress->progress_percent ?>%
                                        </div>
                                    </div>
                                    <p class="small">Terakhir: <?= date('d M H:i', strtotime($m->progress->last_accessed)) ?></p>
                                    <?php endif; ?>
                                    <a href="<?= base_url('student_material/view/'.$m->id) ?>" class="btn btn-primary" target="_blank">Akses</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php foreach ($subjects as $s): ?>
                <div class="tab-pane fade" id="subject-<?= $s->id ?>" role="tabpanel">
                    <div class="row">
                        <?php 
                        $filtered = array_filter($materials, function($m) use ($s) {
                            return $m->subject_id == $s->id;
                        });
                        ?>
                        <?php if (empty($filtered)): ?>
                        <p class="text-muted">Tidak ada materi untuk mata pelajaran ini.</p>
                        <?php else: ?>
                        <?php foreach ($filtered as $m): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $m->title ?></h5>
                                    <p class="card-text"><?= $m->description ?></p>
                                    <p>
                                        <span class="badge bg-info"><?= $m->type ?></span>
                                        <?php if ($m->duration_seconds): ?>
                                        <span class="badge bg-secondary"><?= floor($m->duration_seconds / 60) ?> menit</span>
                                        <?php endif; ?>
                                    </p>
                                    <?php if ($m->progress): ?>
                                    <div class="progress mb-2">
                                        <div class="progress-bar" role="progressbar" style="width: <?= $m->progress->progress_percent ?>%;">
                                            <?= $m->progress->progress_percent ?>%
                                        </div>
                                    </div>
                                    <p class="small">Terakhir: <?= date('d M H:i', strtotime($m->progress->last_accessed)) ?></p>
                                    <?php endif; ?>
                                    <a href="<?= base_url('student_material/view/'.$m->id) ?>" class="btn btn-primary" target="_blank">Akses</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
