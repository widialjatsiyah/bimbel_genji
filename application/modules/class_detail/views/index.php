<section id="class-detail">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?= $class->name ?></h4>
            <p><?= $class->description ?></p>
            <hr>
            <h5>Daftar Pertemuan</h5>
            <?php if (empty($meetings)): ?>
                <p class="text-muted">Belum ada pertemuan.</p>
            <?php else: ?>
                <div class="accordion" id="accordionMeetings">
                    <?php foreach ($meetings as $index => $m): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= $m->id ?>">
                            <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $m->id ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $m->id ?>">
                                <?= $m->title ?> (<?= $m->date ?: 'Tanpa tanggal' ?>)
                            </button>
                        </h2>
                        <div id="collapse<?= $m->id ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" aria-labelledby="heading<?= $m->id ?>" data-bs-parent="#accordionMeetings">
                            <div class="accordion-body">
                                <p><?= $m->description ?></p>
                                <?php if ($m->meeting_link): ?>
                                    <p>Link: <a href="<?= $m->meeting_link ?>" target="_blank"><?= $m->meeting_link ?></a></p>
                                <?php endif; ?>
                                <h6>Materi:</h6>
                                <?php if (empty($m->materials)): ?>
                                    <p class="text-muted">Tidak ada materi.</p>
                                <?php else: ?>
                                    <ul>
                                        <?php foreach ($m->materials as $mat): ?>
                                        <li><a href="<?= base_url('material/view/'.$mat->material_id) ?>" target="_blank"><?= $mat->title ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                                <h6>Quiz:</h6>
                                <?php if (empty($m->quizzes)): ?>
                                    <p class="text-muted">Tidak ada quiz.</p>
                                <?php else: ?>
                                    <ul>
                                        <?php foreach ($m->quizzes as $qz): ?>
                                        <li><a href="<?= base_url('user_tryout/start/'.$qz->quiz_id) ?>"><?= $qz->quiz_title ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
