<section id="class-detail">
    <div class="card">
        <div class="card-header">
            <h4><?= $class->name ?></h4>
            <p>Tahun Ajaran: <?= $class->academic_year ?? '-' ?></p>
        </div>
        <div class="card-body">
            <a href="<?= base_url('dashboard_tutor') ?>" class="btn btn-secondary mb-3">&laquo; Kembali</a>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Email</th>
                            <th>Skor Terakhir</th>
                            <th>Status Kesiapan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($students as $s): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $s->nama_lengkap ?></td>
                            <td><?= $s->email ?></td>
                            <td><?= $s->latest_tryout->total_score ?? '-' ?></td>
                            <td>
                                <?php if ($s->progress): ?>
                                    <?php
                                    $badge = [
                                        'Siap' => 'success',
                                        'Perlu Penguatan' => 'warning',
                                        'Perlu Pendampingan Intensif' => 'danger'
                                    ];
                                    ?>
                                    <span class="badge bg-<?= $badge[$s->progress->status_kesiapan] ?>">
                                        <?= $s->progress->status_kesiapan ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Belum Ada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('dashboard_tutor/student_detail/'.$s->id) ?>" class="btn btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
