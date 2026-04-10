<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Try Out - <?= $session->name ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
</head>
<body>
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
                        <a href="<?= base_url('user_tryout/export_pdf/'.$user_tryout->id) ?>" class="btn btn-export-pdf">
                            <i class="fas fa-file-pdf"></i> Ekspor ke PDF
                        </a>
                        
                        <a href="<?= base_url('tryout_list') ?>" class="btn btn-home">
                            <i class="fas fa-home"></i> Kembali ke Daftar Try Out
                        </a>
                        
                        <?php if ($next_session): ?>
                            <a href="<?= base_url('user_tryout/start_next/'.$user_tryout->id.'/'.$next_session->id) ?>" class="btn btn-next">
                                <i class="fas fa-arrow-right"></i> Lanjut ke <?= $next_session->name ?>
                            </a>
                        <?php else: ?>
                            <p class="mt-3 text-success">🎉 Selamat! Anda telah menyelesaikan semua sesi untuk tryout ini.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Tidak perlu logika tambahan untuk halaman hasil
    </script>
</body>
</html>