<script type="text/javascript">
    $(document).ready(function() {

        // Load riwayat
        loadHistory();

        $('#btn-save-checklist').click(function() {
            $.ajax({
                url: '<?= base_url('daily_checklist/ajax_save') ?>',
                type: 'post',
                data: $('#form-checklist').serialize(),
                success: function(response) {
                    if (response.status) {
                        notify(response.data, 'success');
                        loadHistory();
                    } else {
                        notify(response.data, 'danger');
                    }
                }
            });
        });

        function loadHistory() {
            $.get('<?= base_url('daily_checklist/ajax_get_history') ?>', function(data) {
                var html = '<table class="table table-sm">';
                html += '<thead><tr><th>Tanggal</th><th>Ibadah</th><th>Belajar</th><th>Mood</th></tr></thead><tbody>';
                $.each(data, function(i, item) {
                    var checklist = JSON.parse(item.checklist_data);
                    var ibadah = (checklist.shalat_subuh ? 'Subuh ' : '') +
                                 (checklist.shalat_dzuhur ? 'Dzuhur ' : '') +
                                 (checklist.shalat_ashar ? 'Ashar ' : '') +
                                 (checklist.shalat_maghrib ? 'Maghrib ' : '') +
                                 (checklist.shalat_isya ? 'Isya ' : '') +
                                 (checklist.tilawah ? 'Tilawah' : '');
                    html += '<tr>';
                    html += '<td>' + item.date + '</td>';
                    html += '<td>' + (ibadah || '-') + '</td>';
                    html += '<td>' + (checklist.belajar_menit || 0) + ' menit</td>';
                    html += '<td>' + (item.mood_rating || '-') + '</td>';
                    html += '</tr>';
                });
                html += '</tbody></table>';
                $('#history-container').html(html);
            }, 'json');
        }
    });
</script>
