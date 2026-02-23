<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('#btn-calculate').click(function() {
            $.ajax({
                url: '<?= base_url('student_progress/ajax_calculate') ?>',
                type: 'post',
                success: function(response) {
                    if (response) {
                        notify('Perkembangan berhasil dihitung', 'success');
                        location.reload();
                    }
                }
            });
        });

        // Data untuk chart
        var history = <?= json_encode($history) ?>;
        var labels = [];
        var academicData = [];
        var consistencyData = [];
        var psychologicalData = [];
        var spiritualData = [];

        history.forEach(function(item) {
            labels.push(item.snapshot_date);
            academicData.push(item.skor_akademik);
            consistencyData.push(item.skor_konsistensi);
            psychologicalData.push(item.skor_psikologis);
            spiritualData.push(item.skor_spiritual);
        });

        var ctx = document.getElementById('progressChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Akademik', data: academicData, borderColor: 'blue', fill: false },
                    { label: 'Konsistensi', data: consistencyData, borderColor: 'green', fill: false },
                    { label: 'Psikologis', data: psychologicalData, borderColor: 'orange', fill: false },
                    { label: 'Spiritual', data: spiritualData, borderColor: 'purple', fill: false }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, max: 100 }
                }
            }
        });
    });
</script>
