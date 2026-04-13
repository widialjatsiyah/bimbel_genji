<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hasil Try Out - <?= $session->name ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .summary {
            background-color: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .question-block {
            border: 1px solid #ddd;
            margin: 15px 0;
            padding: 15px;
            border-radius: 5px;
            page-break-inside: avoid;
        }
        .question-text {
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #ccc;
        }
        .options {
            margin-left: 20px;
        }
        .option {
            margin: 5px 0;
        }
        .selected {
            background-color: #e6f3ff;
            padding: 3px 5px;
            border-radius: 3px;
        }
        .correct {
            color: green;
            font-weight: bold;
        }
        .incorrect {
            color: red;
        }
        .image-container {
            text-align: center;
            margin: 10px 0;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Hasil Try Out - <?= $session->name ?></h1>
        <p>Tanggal: <?= date('d M Y H:i', strtotime($user_tryout->start_time)) ?></p>
    </div>

    <div class="info-section">
        <h3>Data Pengguna</h3>
        <p><strong>Nama:</strong> <?= $this->session->userdata('user')['nama_lengkap'] ?></p>
        <p><strong>Waktu Pengerjaan:</strong> <?= date('d M Y H:i', strtotime($user_tryout->start_time)) ?> - <?= date('H:i', strtotime($user_tryout->end_time)) ?></p>
        <p><strong>Durasi:</strong> <?= $session->duration_minutes ?> menit</p>
    </div>

    <div class="summary">
        <h3>Ringkasan Hasil</h3>
        <p><strong>Jumlah Soal:</strong> <?= count($questions) ?></p>
        <p><strong>Jawaban Benar:</strong> <?= $correct_count ?></p>
        <p><strong>Nilai:</strong> <?= number_format(($correct_count / count($questions)) * 100, 2) ?></p>
        <p><strong>Metode Penilaian:</strong> 
            <?= $session->scoring_method === 'correct_incorrect' ? 'Benar/Salah' : 'Poin per Soal' ?>
            <?php if ($session->is_random == 1): ?>
                (Soal Diacak)
            <?php endif; ?>
        </p>
    </div>

    <h3>Daftar Soal dan Jawaban</h3>
    
    <?php foreach ($questions as $index => $question): ?>
        <div class="question-block">
            <div class="question-text">
                <strong><?= $index + 1 ?>. <?= $question->question_text ?></strong>
                <?php if (!empty($question->question_image)): ?>
                    <div class="image-container">
                        <img src="<?= base_url($question->question_image) ?>" alt="Gambar Soal" style="max-width: 500px; height: auto;" />
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="options">
                <?php
                $options = [
                    'A' => $question->option_a,
                    'B' => $question->option_b,
                    'C' => $question->option_c,
                    'D' => $question->option_d,
                    'E' => $question->option_e
                ];
                
                foreach ($options as $key => $value):
                    if (!empty($value)):
                        $isSelected = isset($answers[$question->question_id]) && $answers[$question->question_id]->answer === $key;
                        $isCorrect = $key === $question->correct_option;
                        $optionClass = '';
                        
                        if ($isSelected && $isCorrect) {
                            $optionClass = 'selected correct';
                        } elseif ($isSelected && !$isCorrect) {
                            $optionClass = 'selected incorrect';
                        } elseif (!$isSelected && $isCorrect) {
                            $optionClass = 'correct';
                        } else {
                            $optionClass = '';
                        }
                        ?>
                        <div class="option <?= $optionClass ?>">
                            <?= $key ?>. <?php 
							if($question->option_type === 'image') {
								echo '<img src="' . base_url($value) . '" alt="Gambar Opsi" style="max-width: 200px; height: auto;" />';
							} else {
								echo $value;
							} ?>
                            <?php if ($isSelected): ?> <em>(Jawaban Anda)</em> <?php endif; ?>
                            <?php if ($isCorrect): ?> <em>(Benar)</em> <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>
