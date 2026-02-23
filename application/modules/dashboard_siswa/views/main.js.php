<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $.get('<?= base_url('dashboard_siswa/ajax_get_chart_data') ?>', function(data) {
            var labels = data.map(item => item.snapshot_date);
            var akademik = data.map(item => item.skor_akademik);
            var kesiapan = data.map(item => item.skor_kesiapan);

            var ctx = document.getElementById('progressChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Akademik',
                            data: akademik,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            tension: 0.1
                        },
                        {
                            label: 'Kesiapan',
                            data: kesiapan,
                            borderColor: 'rgba(255, 159, 64, 1)',
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        });
    });
</script>
