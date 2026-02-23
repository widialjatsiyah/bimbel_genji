<section id="dashboard-tutor">
    <!-- Statistik Cepat -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Kelas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_classes ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-school fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Siswa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_students ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Siswa Perlu Perhatian</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($students_in_need) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Kelas -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Kelas Anda</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Kelas</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Rata-rata Skor TO</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($classes)): ?>
                                <tr><td colspan="5" class="text-center">Tidak ada kelas.</td></tr>
                                <?php else: ?>
                                <?php foreach ($classes as $c): ?>
                                <tr>
                                    <td><?= $c->name ?></td>
                                    <td><?= $c->academic_year ?? '-' ?></td>
                                    <td><?= $c->student_count ?></td>
                                    <td><?= $c->avg_score ?: '-' ?></td>
                                    <td>
                                        <a href="<?= base_url('dashboard_tutor/class_detail/'.$c->id) ?>" class="btn btn-sm btn-info">Detail</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Siswa Perlu Perhatian Khusus -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-danger">Siswa Perlu Pendampingan Intensif</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($students_in_need)): ?>
                        <p class="text-muted">Semua siswa dalam kondisi baik.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($students_in_need as $s): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= $s->nama_lengkap ?> (<?= $s->class_name ?>)
                                <span class="badge bg-danger">Merah</span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Rekomendasi Terbaru -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Rekomendasi Terbaru</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_recommendations)): ?>
                        <p class="text-muted">Tidak ada rekomendasi baru.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($recent_recommendations as $r): ?>
                            <li class="list-group-item">
                                <strong><?= $r->nama_lengkap ?>:</strong> <?= $r->recommendation_text ?>
                                <small class="text-muted d-block"><?= date('d M H:i', strtotime($r->created_at)) ?></small>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
