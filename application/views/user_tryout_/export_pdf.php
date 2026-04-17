<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hasil Try Out</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; line-height: 1.4; }
        h2 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px; }
        h3 { color: #34495e; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 9pt; }
        th { background: #3498db; color: white; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 6px; vertical-align: top; }
        .correct { background-color: #d4edda; }
        .wrong { background-color: #f8d7da; }
        .skipped { background-color: #fff3cd; }
        .info { margin-bottom: 20px; }
        .info p { margin: 3px 0; }
        .rank { font-weight: bold; color: #27ae60; }
    </style>
</head>
<body>
    <h2>Laporan Hasil Try Out</h2>
    <div class="info">
        <p><strong>Nama:</strong> <?= $user->nama_lengkap ?></p>
        <p><strong>Try Out:</strong> <?= $tryout->title ?></p>
        <p><strong>Tanggal:</strong> <?= date('d M Y H:i', strtotime($user_tryout->start_time)) ?></p>
        <p><strong>Skor Total:</strong> <?= $user_tryout->total_score ?? 'Belum dihitung' ?></p>
        <p><strong>Ranking Nasional:</strong> <span class="rank"><?= $user_tryout->ranking_national ?? '-' ?></span></p>
        <p><strong>Ranking Sekolah:</strong> <span class="rank"><?= $user_tryout->ranking_school ?? '-' ?></span></p>
    </div>

    <?php foreach ($sessions as $session): ?>
        <h3><?= $session->name ?></h3>
        <?php if ($session->result): ?>
        <p>
            Benar: <?= $session->result->correct_count ?>, 
            Salah: <?= $session->result->wrong_count ?>, 
            Dilewati: <?= $session->result->skipped_count ?>, 
            Skor: <?= $session->result->score ?>
        </p>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="40%">Soal</th>
                    <th width="10%">Jawaban User</th>
                    <th width="10%">Kunci</th>
                    <th width="10%">Status</th>
                    <th width="25%">Pembahasan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($session->questions as $q): ?>
                <?php
                $status = 'Dilewati';
                $class = 'skipped';
                $user_answer = $q->user_answer;
                if ($user_answer && !empty($user_answer->answer)) {
                    if ($user_answer->answer == $q->detail->correct_option) {
                        $status = 'Benar';
                        $class = 'correct';
                    } else {
                        $status = 'Salah';
                        $class = 'wrong';
                    }
                }
                ?>
                <tr class="<?= $class ?>">
                    <td><?= $no++ ?></td>
                    <td><?= strip_tags($q->detail->question_text) ?></td>
                    <td><?= $user_answer->answer ?? '-' ?></td>
                    <td><?= $q->detail->correct_option ?></td>
                    <td><?= $status ?></td>
                    <td><?= strip_tags($q->detail->explanation) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</body>
</html>
