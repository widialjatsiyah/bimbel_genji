<section id="package-detail">
    <div class="card">
        <div class="card-header">
            <h4><?= $package->name ?></h4>
            <p><strong>Harga:</strong> Rp <?= number_format($package->price, 0, ',', '.') ?> | <strong>Durasi:</strong> <?= $package->duration_days ?> hari</p>
            <a href="<?= base_url('package') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Paket</a>
        </div>
        <div class="card-body">
            <h5>Deskripsi Paket</h5>
            <p><?= $package->description ?></p>

            <hr>

            <h5>Item dalam Paket</h5>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="btn-group" role="group">
                        <button class="btn btn-success btn-sm add-item" data-type="tryout">Tambah Tryout</button>
                        <button class="btn btn-success btn-sm add-item" data-type="class">Tambah Kelas</button>
                        <button class="btn btn-success btn-sm add-item" data-type="material">Tambah Materi</button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tipe</th>
                            <th>Nama Item</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="item-list">
                        <?php foreach ($items as $item): ?>
                        <tr id="item-<?= $item->id ?>">
                            <td><?= ucfirst($item->item_type) ?></td>
                            <td><?= $item->item_name ?></td>
                            <td>
                                <button class="btn btn-sm btn-danger remove-item" data-id="<?= $item->id ?>">Hapus</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($items)): ?>
                        <tr><td colspan="3" class="text-center">Belum ada item dalam paket.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal untuk memilih item -->
<div class="modal fade" id="modal-add-item" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Item ke Paket</h5>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="form-add-item">
                    <input type="hidden" name="package_id" value="<?= $package->id ?>">
                    <input type="hidden" name="type" id="item-type">
                    <div class="form-group">
                        <label>Pilih Item</label>
                        <select name="item_id" id="item-select" class="form-control select2" ></select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-add-item">Tambah</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
