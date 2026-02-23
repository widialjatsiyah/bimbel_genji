<section id="bookmark">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-responsive">
                <table class="table table-bordered" id="table-bookmark">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Soal</th>
                            <th>Ditandai Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($bookmarks as $b): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $b->question_text ?></td>
                            <td><?= date('d M Y H:i', strtotime($b->created_at)) ?></td>
                            <td>
                                <a href="javascript:;" class="btn btn-sm btn-danger btn-remove" data-id="<?= $b->id ?>">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($bookmarks)): ?>
                        <tr><td colspan="4" class="text-center">Belum ada soal favorit.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
