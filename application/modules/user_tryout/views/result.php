
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .result-card {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            margin-top: 40px;
            margin-bottom: 40px;
        }
        .score-box {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }
        .score-value {
            font-size: 4rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .btn-home {
            background: #28a745;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            margin-right: 10px;
        }
        .btn-next {
            background: #0d6efd;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-export-pdf {
            background: #dc3545;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            margin-right: 10px;
        }
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="result-card">
                    <h2 class="text-center mb-4">Hasil Try Out</h2>
                    <h3 class="text-center text-muted mb-4"><?= $session->name ?></h3>
                    
                    <div class="score-box">
                        <h4>Skor Akhir Anda</h4>
                        <div class="score-value"><?= $user_tryout->total_score ?></div>
                        <p>Total dari <?= $session->question_count ?> soal</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="stats-card">
                                <h5>Waktu Pengerjaan</h5>
                                <p>
                                    <?= date('d M Y, H:i', strtotime($user_tryout->start_time)) ?> - 
                                    <?= date('H:i', strtotime($user_tryout->end_time)) ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stats-card">
                                <h5>Metode Penilaian</h5>
                                <p>
                                    <?= $session->scoring_method === 'correct_incorrect' ? 'Benar/Salah' : 'Poin per Soal' ?>
                                    <?php if ($session->is_random == 1): ?>
                                        <span class="badge bg-warning">Soal Diacak</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">

    <div class="d-flex flex-wrap justify-content-center gap-2">

        <!-- Export PDF -->
        <a href="<?= base_url('user_tryout/export_pdf/'.$user_tryout->id) ?>" 
           class="btn btn-danger px-3">

            <i class="zmdi zmdi-file-pdf"></i>
            Ekspor PDF

        </a>


        <!-- Bookmark -->
        <a href="<?= base_url('bookmark') ?>" 
           class="btn btn-warning px-3">

            <i class="zmdi zmdi-bookmark"></i>
            Bookmark

        </a>

        <!-- Recalculate Essay Scores -->
        <button type="button" class="btn btn-info px-3" onclick="recalculateEssayScores()">
            <i class="zmdi zmdi-refresh"></i>
            Hitung Ulang Skor Essay
        </button>

        <!-- Kembali -->
        <a href="<?= base_url('tryout_list') ?>" 
           class="btn btn-secondary px-3">

            <i class="zmdi zmdi-home"></i>
            Daftar Try Out

        </a>


        <!-- Next Session -->
        <?php if ($next_session): ?>

            <a href="<?= base_url('user_tryout/start_next/'.$user_tryout->id.'/'.$next_session->id) ?>" 
               class="btn btn-primary px-3">

                <i class="zmdi zmdi-arrow-right"></i>
                Lanjut ke <?= $next_session->name ?>

            </a>

        <?php else: ?>

            <div class="w-100 mt-3">

                <p class="text-success fw-bold">
                    🎉 Selamat! Anda telah menyelesaikan semua sesi.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function recalculateEssayScores() {
            // Disable button dan tampilkan pesan loading
            const btn = document.querySelector('[onclick="recalculateEssayScores()"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Menghitung...';
            
            // Ambil ID user_tryout dari URL atau dari variabel PHP
            const userTryoutId = <?= $user_tryout->id ?>;
            
            // Kirim permintaan AJAX ke endpoint khusus untuk menghitung ulang skor essay
            $.ajax({
                url: '<?= base_url("user_tryout/recalculate_essay_scores/".$user_tryout->id) ?>',
                method: 'POST',
                data: {
                    user_tryout_id: userTryoutId
                },
                success: function(response) {
                    const result = JSON.parse(response);
                    if(result.status) {
                        alert('Skor essay berhasil dihitung ulang!');
                        // Refresh halaman untuk menampilkan skor terbaru
                        location.reload();
                    } else {
                        alert('Gagal menghitung ulang skor essay: ' + result.message);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menghitung ulang skor essay');
                },
                complete: function() {
                    // Kembalikan tombol ke kondisi semula
                    btn.disabled = false;
                    btn.innerHTML = '<i class="zmdi zmdi-refresh"></i> Hitung Ulang Skor Essay';
                }
            });
        }
    </script>
</body>
</html>