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
                        <?php else: $no=1; foreach ($tryouts as $t): ?>
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
                                    'abandoned' => 'secondary'
                                ];
                                ?>
                                <span class="badge bg-<?= $badge[$t->status] ?>"><?= $t->status ?></span>
                            </td>
                            <td><?= $t->total_score ?? '-' ?></td>
                            <td>
                                <?php if ($t->status == 'completed'): ?>
                                <a href="<?= base_url('user_tryout/result/'.$t->id) ?>" class="btn btn-sm btn-info">Lihat Hasil</a>
                                <?php elseif ($t->status == 'in_progress'): ?>
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
