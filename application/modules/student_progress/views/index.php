<section id="student_progress">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5>Skor Kesiapan</h5>
                            <h2><?= isset($latest) ? $latest->skor_kesiapan : 'Belum ada data' ?></h2>
                            <p>Status: 
                                <?php if (isset($latest)): ?>
                                    <?php if ($latest->status_kesiapan == 'Siap'): ?>
                                        <span class="badge bg-success">Siap</span>
                                    <?php elseif ($latest->status_kesiapan == 'Perlu Penguatan'): ?>
                                        <span class="badge bg-warning">Perlu Penguatan</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Perlu Pendampingan Intensif</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <button class="btn btn-primary" id="btn-calculate">Hitung Ulang Perkembangan</button>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h5>Detail Skor</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Akademik</th>
                            <td><?= isset($latest) ? $latest->skor_akademik : '-' ?></td>
                        </tr>
                        <tr>
                            <th>Konsistensi</th>
                            <td><?= isset($latest) ? $latest->skor_konsistensi : '-' ?></td>
                        </tr>
                        <tr>
                            <th>Psikologis</th>
                            <td><?= isset($latest) ? $latest->skor_psikologis : '-' ?></td>
                        </tr>
                        <tr>
                            <th>Spiritual</th>
                            <td><?= isset($latest) ? $latest->skor_spiritual : '-' ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Riwayat Perkembangan</h5>
                    <canvas id="progressChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>
