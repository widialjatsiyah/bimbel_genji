<script>
    $(document).ready(function() {
        $('.mark-read').click(function() {
            var id = $(this).data('id');
            $.post('<?= base_url('recommendation/ajax_mark_read/') ?>' + id, function(res) {
                if (res.status) location.reload();
            });
        });

        $('.generate-recommendation').click(function() {
            var userId = $(this).data('user');
            var btn = $(this);
            btn.prop('disabled', true).text('Memproses...');
            $.post('<?= base_url('recommendation/ajax_generate_for_user/') ?>' + userId, function(res) {
                if (res.status) {
                    alert(res.count + ' rekomendasi berhasil dibuat.');
                    location.reload();
                }
            }).always(function() {
                btn.prop('disabled', false).text('Generate Ulang');
            });
        });
    });
</script>
