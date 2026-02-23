<script>
    $(document).ready(function() {
        $('.btn-remove').click(function() {
            var id = $(this).data('id');
            if (confirm('Hapus dari favorit?')) {
                $.post('<?= base_url('bookmark/ajax_remove_by_id/') ?>' + id, function(res) {
                    if (res.status) {
                        location.reload();
                    }
                }, 'json');
            }
        });
    });
</script>
