<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
	// Fungsi untuk memperbarui tampilan timer
function updateTimers() {
    $('.time-left').each(function() {
        const timeLeftElement = $(this);
        const secondsLeft = parseInt(timeLeftElement.attr('data-timeleft'));
        const id = timeLeftElement.attr('data-id');
        
        // Kurangi 1 detik karena fungsi ini dipanggil setiap detik
        const newSecondsLeft = Math.max(0, secondsLeft - 1);
        
        // Update nilai di data attribute
        timeLeftElement.attr('data-timeleft', newSecondsLeft);
        
        // Konversi detik ke jam, menit, detik
        const hours = Math.floor(newSecondsLeft / 3600);
        const minutes = Math.floor((newSecondsLeft % 3600) / 60);
        const secs = newSecondsLeft % 60;
        
        // Format waktu
        const formattedTime = 
            String(hours).padStart(2, '0') + ':' + 
            String(minutes).padStart(2, '0') + ':' + 
            String(secs).padStart(2, '0');
        
        // Update tampilan
        $('#time-' + id).text(formattedTime);
        
        // Jika waktu habis
        if(newSecondsLeft <= 0) {
            // Refresh halaman untuk memperbarui status
            location.reload();
        }
    });
}

// Jalankan update timer setiap detik
$(document).ready(function() {
    // Panggil sekali untuk inisialisasi
    updateTimers();
    
    // Set interval untuk update setiap detik
    setInterval(updateTimers, 1000);
});

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
