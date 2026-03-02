<section id="assign-class">
    <div class="card">
        <div class="card-header">
            <h4><?= $card_title ?></h4>
            <a href="<?= base_url('student') ?>" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <form id="form-assign">
                <input type="hidden" name="student_id" value="<?= $student->id ?>">
                <div class="form-group">
                    <label>Pilih Kelas</label>
                    <select name="class_ids[]" class="form-control select2" multiple style="width:100%">
                        <?php foreach ($classes as $c): ?>
                        <option value="<?= $c->id ?>" <?= in_array($c->id, $enrolled_ids) ? 'selected' : '' ?>>
                            <?= $c->name ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="button" class="btn btn-primary" id="btn-save-assign">Simpan</button>
            </form>
        </div>
    </div>
</section>
<script>
    $('#btn-save-assign').click(function() {
        var data = $('#form-assign').serialize();
        $.post('<?= base_url('student/ajax_save_assign') ?>', data, function(res) {
            if (res.status) {
                notify(res.data, 'success');
            } else {
                notify(res.data, 'danger');
            }
        }, 'json');
    });
</script>
