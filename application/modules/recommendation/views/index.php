<section id="recommendation">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-responsive">
                <table class="table table-bordered" id="table-recommendation">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Rekomendasi Terbaru</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($students as $s): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $s->nama_lengkap ?></td>
                            <td>
                                <?php if (empty($s->recommendations)): ?>
                                    <span class="text-muted">Tidak ada rekomendasi</span>
                                <?php else: ?>
                                    <ul>
                                    <?php foreach ($s->recommendations as $r): ?>
                                        <li class="<?= $r->is_read ? 'text-muted' : 'fw-bold' ?>">
                                            <?= $r->recommendation_text ?>
                                            <small class="text-muted">(<?= date('d M', strtotime($r->created_at)) ?>)</small>
                                            <?php if (!$r->is_read): ?>
                                                <a href="javascript:;" class="btn btn-sm btn-link mark-read" data-id="<?= $r->id ?>">Tandai dibaca</a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary generate-recommendation" data-user="<?= $s->id ?>">Generate Ulang</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
