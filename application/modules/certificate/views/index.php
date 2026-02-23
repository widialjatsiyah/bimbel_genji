<section id="certificate">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Try Out</th>
                            <th>Nomor Sertifikat</th>
                            <th>Diterbitkan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($certificates as $c): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $c->tryout_title ?? '-' ?></td>
                            <td><?= $c->certificate_number ?></td>
                            <td><?= date('d M Y', strtotime($c->issued_at)) ?></td>
                            <td>
                                <a href="<?= base_url('certificate/view/'.$c->id) ?>" class="btn btn-sm btn-primary" target="_blank">Lihat</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($certificates)): ?>
                        <tr><td colspan="5" class="text-center">Belum ada sertifikat.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
