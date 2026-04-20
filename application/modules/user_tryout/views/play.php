<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= isset($session) && $session ? $session->name : 'Try Out' ?> - Try Out</title>
	<!-- Bootstrap 5 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<style>
		body {
			background-color: #f4f7fc;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}

		.timer-container {
			position: sticky;
			top: 0;
			background: white;
			z-index: 1000;
			padding: 15px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
			margin-bottom: 20px;
		}

		.question-container {
			background: white;
			border-radius: 10px;
			padding: 30px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
			margin-bottom: 20px;
		}

		.question-number {
			font-size: 1.2rem;
			font-weight: 600;
			color: #2c3e50;
			margin-bottom: 20px;
		}

		.question-text {
			font-size: 1.1rem;
			margin-bottom: 30px;
			line-height: 1.6;
			background-color: #f8f9fa;
			padding: 20px;
			border-radius: 8px;
			border-left: 4px solid #0d6efd;
		}

		.options-list {
			list-style: none;
			padding: 0;
		}

		.options-list li {
			margin-bottom: 15px;
			padding: 20px;
			background: #f8f9fa;
			border-radius: 12px;
			cursor: pointer;
			transition: all 0.3s;
			border: 2px solid transparent;
			position: relative;
			overflow: hidden;
		}

		.options-list li:hover {
			transform: translateY(-3px);
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
			background: #e9ecef;
		}

		.options-list li.selected {
			background: #cfe2ff;
			border-color: #0d6efd;
			box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2);
		}

		.options-list li input[type="radio"] {
			margin-right: 15px;
			visibility: hidden;
		}

		.option-letter {
			display: inline-block;
			width: 35px;
			height: 35px;
			line-height: 35px;
			text-align: center;
			background: #0d6efd;
			color: white;
			border-radius: 50%;
			margin-right: 15px;
			font-weight: bold;
		}

		.option-content {
			display: inline-block;
			vertical-align: top;
		}

		.option-image {
			margin-top: 10px;
			text-align: center;
		}

		.option-image img {
			max-width: 100%;
			height: auto;
			border-radius: 8px;
			border: 1px solid #ddd;
		}

		.nav-questions {
			background: white;
			border-radius: 10px;
			padding: 20px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
			position: sticky;
			top: 100px;
		}

		.nav-questions h5 {
			margin-bottom: 15px;
			font-weight: 600;
		}

		.question-grid {
			display: grid;
			grid-template-columns: repeat(5, 1fr);
			gap: 2px;
		}

		.question-grid-item {
			width: 45px;
			height: 45px;
			display: flex;
			align-items: center;
			justify-content: center;
			background: #e9ecef;
			border-radius: 8px;
			cursor: pointer;
			font-weight: 500;
			transition: all 0.2s;
		}

		.question-grid-item.answered {
			background: #28a745;
			color: white;
		}

		.question-grid-item.unsure {
			background: #ffc107;
			color: black;
		}

		.question-grid-item.skipped {
			background: #dc3545;
			color: white;
		}

		.btn-unsure {
			background: #ffc107;
			color: black;
			border: none;
			padding: 10px 20px;
			border-radius: 8px;
			font-weight: 500;
			margin-right: 10px;
		}

		.btn-unsure.active {
			background: #e0a800;
			box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.5);
		}

		.btn-next {
			background: #0d6efd;
			color: white;
			padding: 10px 30px;
			border: none;
			border-radius: 8px;
			font-weight: 500;
		}

		.btn-prev {
			background: #6c757d;
			color: white;
			padding: 10px 30px;
			border: none;
			border-radius: 8px;
			font-weight: 500;
			margin-right: 10px;
		}

		.question-group-header {
			background: #e7f3ff;
			padding: 15px;
			border-radius: 8px;
			margin-bottom: 20px;
			border-left: 4px solid #0d6efd;
		}

		.option-item.correct-answer {
			background: #d4edda;
			border: 2px solid #28a745;
		}

		.option-item.incorrect-answer {
			background: #f8d7da;
			border: 2px solid #dc3545;
		}

		.btn-bookmark.active {
			background: #ffc107;
			color: black;
			border-color: #ffc107;
		}

		.question-number.bookmarked {
			color: #ffc107;
		}

		.essay-answer-area {
			margin: 20px 0;
			padding: 20px;
			background: #f8f9fa;
			border-radius: 8px;
		}

		.essay-answer-textarea {
			width: 100%;
			margin: 10px 0;
			padding: 15px;
			border: 1px solid #ced4da;
			border-radius: 6px;
			resize: vertical;
		}
	</style>
</head>

<body>
	<div class="timer-container">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6">
					<h4><?= isset($session) && $session ? $session->name : 'Try Out' ?></h4>
				</div>
				<div class="col-md-6 text-end">
					<div class="timer" id="timer"><?= isset($session) && $session ? gmdate("H:i:s", $session->duration_minutes * 60) : '00:00:00' ?></div>
					<div class="question-timer" id="question-timer" style="display: none; font-weight: bold; color: #dc3545; margin-top: 5px;"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<!-- Kolom Kiri: Soal -->
			<div class="col-md-8">
				<div class="question-container">
					<div class="question-number">
						Soal <span id="current-number">1</span> dari <?= $total_questions ?>
					</div>
					<div class="question-text" id="question-text">
						<!-- akan diisi JavaScript -->
					</div>
					<div class="options" id="options-container">
						<!-- akan diisi JavaScript -->
					</div>
					<div class="d-flex justify-content-between mt-4">
						<div>
							<button class="btn-unsure" id="btn-unsure">
								<i class="fas fa-question-circle"></i> Ragu-ragu
							</button>
							<button class="btn btn-outline-warning" id="btn-bookmark">
								<i class="fas fa-bookmark"></i> Bookmark
							</button>
						</div>
						<div>
							<button class="btn-prev" id="btn-prev" disabled>Sebelumnya</button>
							<button class="btn-next" id="btn-next">Selanjutnya</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Kolom Kanan: Navigasi Soal -->
			<div class="col-md-4">
				<div class="nav-questions">
					<h5>Navigasi Soal</h5>
					<div class="question-grid" id="question-grid">
						<!-- akan diisi JavaScript -->
					</div>
					<hr>
					<div class="legend d-flex justify-content-between mt-3">
						<div>
							<span class="badge bg-success">Hijau</span> Yakin
						</div>

						<div>
							<span class="badge bg-warning">Kuning</span> Ragu
						</div>

						<div>
							<span class="badge bg-danger">Merah</span> Dilewati
						</div>
					</div>
					<button class="btn btn-primary w-100 mt-3" id="btn-submit-session">Selesai & Lanjut</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Hidden data -->
	<input type="hidden" id="user_tryout_id" value="<?= $user_tryout->id ?>">
	<input type="hidden" id="session_id" value="<?= isset($session) && $session ? $session->id : '' ?>">
	<input type="hidden" id="total_questions" value="<?= $total_questions ?>">
	<input type="hidden" id="enable_time_per_question" value="<?= isset($session->enable_time_per_question) ? $session->enable_time_per_question : 0 ?>">
	<input type="hidden" id="time_per_question" value="<?= isset($session->time_per_question) ? $session->time_per_question : 0 ?>">

	<!-- Container untuk notifikasi -->
	<div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999; background-color: #f8f9fa; border: 1px solid #dee2e6; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);"></div>

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		$(document).ready(function() {
			// Define BASE_URL for use in JavaScript
			var BASE_URL = '<?= base_url() ?>';

			// Data soal dari server (dikirim dalam bentuk JSON)
			var questions = <?= json_encode($questions) ?>;
			var answerMap = <?= json_encode($answer_map) ?>;
			var currentIndex = 0;
			var totalQuestions = questions.length;
			var userTryoutId = $('#user_tryout_id').val();
			var sessionId = $('#session_id').val();
			var timerInterval;
			var questionTimerInterval;
			var timeLeft = <?= isset($session) && $session ? $session->duration_minutes * 60 : 0 ?>; // detik
			var questionTimeLeft = 0; // untuk timer per soal
			var bookmarkMap = <?= json_encode($bookmark_map) ?>;
			var enableTimePerQuestion = $('#enable_time_per_question').val() == 1;
			var timePerQuestion = parseInt($('#time_per_question').val());

			// Debug: Tampilkan jumlah soal di console
			// console.log("Jumlah soal:", totalQuestions);
			// console.log("Data soal:", questions);

			renderQuestion(currentIndex);

			initializeTimer();

			// Check session expiry after 3 seconds to allow page to fully load
			setTimeout(checkSessionExpired, 3000);

			// Then check periodically every minute
			setInterval(checkSessionExpired, 60000);

			function updateGrid() {
				var gridHtml = '';
				for (var i = 0; i < totalQuestions; i++) {
					var qid = questions[i].question_id;
					var statusClass = '';

					// Periksa apakah soal ini sudah dijawab
					if (answerMap[qid]) {
						var isUnsure = answerMap[qid].is_unsure == 1;

						if (isUnsure) {
							statusClass = 'unsure';
						} else if (questions[i].question_type === 'essay' &&
							answerMap[qid].hasOwnProperty('answer_text') &&
							answerMap[qid].answer_text &&
							answerMap[qid].answer_text.trim() !== '') {
							// Soal essay dianggap dijawab jika jawaban tidak kosong
							statusClass = 'answered';
						} else if (questions[i].question_type === 'multiple_choice' &&
							answerMap[qid].hasOwnProperty('answer') &&
							answerMap[qid].answer) {
							// Soal pilihan ganda dianggap dijawab jika jawaban dipilih
							statusClass = 'answered';
						} else {
							statusClass = 'skipped';
						}
					} else {
						statusClass = 'skipped';
					}

					var currentClass = (i === currentIndex) ? 'current' : '';
					gridHtml += `<div class="question-grid-item ${statusClass} ${currentClass}" data-index="${i}">${i+1}</div>`;
				}
				$('#question-grid').html(gridHtml);
			}

			// Inisialisasi tampilan
			function renderQuestion(index) {
				if (!questions[index]) {
					console.error("Soal pada index " + index + " tidak ditemukan");
					return;
				}

				var q = questions[index];
				var questionData = q; // Data soal sudah digabungkan di model

				// Cek apakah soal ini bagian dari grup
				var questionHtml = '';

				// Jika soal ini adalah soal utama dalam grup, tampilkan header grup dan konten soal
				if (questionData.is_group_main && questionData.group_id !== null) {
					// Temukan rentang nomor soal dalam grup
					var groupQuestions = questions.filter(item =>
						item.group_id === questionData.group_id
					).sort((a, b) => a.group_order - b.group_order);

					// Temukan nomor urut soal dalam keseluruhan daftar soal
					var mainQuestionIndex = questions.indexOf(questionData);
					var startNumber = mainQuestionIndex + 1; // Nomor dimulai dari 1
					var endNumber = startNumber + groupQuestions.length - 1;

					questionHtml += '<div>';
					questionHtml += '<div class="alert alert-info"><strong>Untuk soal nomor ' + startNumber + ' - ' + endNumber + '</strong><br/>' + questionData.question_text + '</div>';

					// Tambahkan gambar soal jika ada
					if (questionData.question_image) {
						questionHtml += '<div class="mb-3"><img src="' + BASE_URL + questionData.question_image + '" class="img-fluid rounded" style="max-width: 100%; height: auto;" alt="Gambar Soal Grup" /></div>';
					}
					questionHtml += '</div>';
				}
				// Jika soal ini bagian dari grup tapi bukan utama
				else if (questionData.group_id !== null && !questionData.is_group_main) {
					// Temukan nomor urut soal ini dalam keseluruhan daftar soal
					var questionIndex = questions.indexOf(questionData);
					var number = questionIndex + 1;

					questionHtml += '<div class="question-group-member mb-3">';
					questionHtml += '<div class="alert alert-light border"><strong>Soal (' + number + '):</strong> ' + questionData.question_text + '</div>';

					// Tambahkan gambar soal jika ada
					if (questionData.question_image) {
						questionHtml += '<div class="mb-3"><img src="' + BASE_URL + questionData.question_image + '" class="img-fluid rounded" style="max-width: 100%; height: auto;" alt="Gambar Soal dalam Grup" /></div>';
					}
					questionHtml += '</div>';
				}
				// Soal biasa, bukan bagian dari grup
				else {
					questionHtml += questionData.question_text;

					// Tambahkan gambar soal jika ada
					if (questionData.question_image) {
						questionHtml += '<div class="mb-3"><img src="' + BASE_URL + questionData.question_image + '" class="img-fluid rounded" style="max-width: 100%; height: auto;" alt="Gambar Soal" /></div>';
					}
				}

				$('#question-text').html(questionHtml);

				var optionsHtml = '';
				// Check if the question type is multiple choice to show options
				if (questionData.question_type === 'multiple_choice') {
					// console.log(questionData);
					optionsHtml += '<ul class="options-list">';
					var letters = ['A', 'B', 'C', 'D', 'E'];
					for (var i = 0; i < 5; i++) {
						var letter = letters[i];
						// Get option content and type dynamically per option
						var optContent = questionData['option_' + letter.toLowerCase()];
						var optType = questionData.option_type; // Default to 'text'

						// Skip if no content
						if (!optContent) continue;

						var checked = (answerMap[questions[currentIndex].question_id] && answerMap[questions[currentIndex].question_id].hasOwnProperty('answer') && answerMap[questions[currentIndex].question_id].answer === letter) ? 'checked' : '';
						var isSelected = (answerMap[questions[currentIndex].question_id] && answerMap[questions[currentIndex].question_id].hasOwnProperty('answer') && answerMap[questions[currentIndex].question_id].answer === letter) ? 'selected' : '';
						// console.log('Option ' + letter + ':', optContent, 'Type:', optType, 'Checked:', checked);
						var optionContent = '';
						if (optType === 'image') {
							optionContent = '<img src="' + BASE_URL + optContent + '" class="img-fluid" style="max-width: 200px; margin-top: 10px;" alt="Gambar Opsi" />';
						} else {
							optionContent = optContent;
						}

						optionsHtml += `
							<li class="option-item ${isSelected}" data-option="${letter}" data-question-id="${questions[currentIndex].question_id}">
								<input type="radio" name="option" value="${letter}" ${checked}>
								<span class="option-letter">${letter}</span> ${optionContent}
							</li>`
					}
					optionsHtml += '</ul>';
				} else if (questionData.question_type === 'essay') {
					// For essay questions, show a text area for answers
					var userEssayAnswer = '';
					if (answerMap[questions[currentIndex].question_id] &&
						answerMap[questions[currentIndex].question_id].hasOwnProperty('answer_text')) {
						userEssayAnswer = answerMap[questions[currentIndex].question_id].answer_text || '';
					}

					optionsHtml += `
						<div class="essay-answer-area">
							<label for="essay-answer-${questions[currentIndex].question_id}">Jawaban Esai:</label>
							<textarea 
								id="essay-answer-${questions[currentIndex].question_id}" 
								class="form-control essay-answer-textarea" 
								rows="6" 
								placeholder="Tulis jawaban esai Anda di sini...">${userEssayAnswer}</textarea>
						
						</div>
					`;
				}
				$('#options-container').html(optionsHtml);

				// Set status ragu
				if (answerMap[q.question_id] && answerMap[q.question_id].is_unsure == 1) {
					$('#btn-unsure').addClass('active');
				} else {
					$('#btn-unsure').removeClass('active');
				}

				// Set status bookmark
				if (bookmarkMap && bookmarkMap[q.question_id]) {
					$('#btn-bookmark').addClass('active');
					$('.question-number').addClass('bookmarked');
				} else {
					$('#btn-bookmark').removeClass('active');
					$('.question-number').removeClass('bookmarked');
				}

				// Update nomor soal
				$('#current-number').text(index + 1);

				// Update navigasi grid
				updateGrid();

				// Panggil updateGrid lagi untuk memastikan status soal terbaru ditampilkan
				setTimeout(updateGrid, 0);

				// Mulai timer per soal jika diaktifkan
				if (enableTimePerQuestion) {
					startQuestionTimer();
				}
			}

			// Fungsi untuk mengelola timer per soal
			function startQuestionTimer() {
				// Reset timer sebelumnya jika ada
				stopQuestionTimer();

				var currentQuestion = questions[currentIndex];
				
				// Cek apakah soal ini bagian dari grup dan merupakan soal utama grup
				if (currentQuestion.group_id !== null && currentQuestion.is_group_main) {
					// Ini adalah soal utama grup, inisialisasi timer untuk seluruh grup
					// Hitung jumlah soal dalam grup ini
					var groupQuestions = questions.filter(item =>
						item.group_id === currentQuestion.group_id
					).sort((a, b) => a.group_order - b.group_order);
					
					// Total waktu untuk seluruh soal dalam grup
					questionTimeLeft = timePerQuestion * groupQuestions.length;
				} 
				// Untuk soal anggota grup atau soal non-grup, gunakan waktu per soal
				else if (currentQuestion.group_id !== null && !currentQuestion.is_group_main) {
					// Untuk soal anggota grup, cek apakah timer grup sudah berjalan
					// Jika ya, jangan mulai ulang timer
					var mainQuestion = questions.find(item =>
						item.group_id === currentQuestion.group_id && item.is_group_main
					);
					
					// Kita tidak ingin menginisialisasi ulang timer jika sudah berjalan untuk grup ini
					// Jadi kita langsung kembalikan fungsi jika ini bukan soal utama grup
					return;
				} 
				else {
					// Soal non-grup, gunakan waktu per soal standar
					questionTimeLeft = timePerQuestion;
				}

				// Update tampilan timer
				updateQuestionTimerDisplay();

				// Mulai interval timer
				questionTimerInterval = setInterval(function() {
					questionTimeLeft--;

					if (questionTimeLeft <= 0) {
						questionTimeLeft = 0;
						stopQuestionTimer();
						handleQuestionTimeout();
					}

					updateQuestionTimerDisplay();
				}, 1000);

				// Tampilkan elemen timer per soal
				$('#question-timer').show();
			}

			function stopQuestionTimer() {
				if (questionTimerInterval) {
					clearInterval(questionTimerInterval);
					questionTimerInterval = null;
				}
				$('#question-timer').hide();
			}

			function updateQuestionTimerDisplay() {
				const minutes = Math.floor(questionTimeLeft / 60);
				const seconds = questionTimeLeft % 60;

				const formattedTime =
					String(minutes).padStart(2, '0') + ':' +
					String(seconds).padStart(2, '0');

				document.getElementById('question-timer').textContent = 'Waktu soal: ' + formattedTime;

				// Beri peringatan saat waktu hampir habis
				if (questionTimeLeft <= 30) { // 30 detik terakhir
					document.getElementById('question-timer').style.color = '#dc3545'; // Merah
					document.getElementById('question-timer').style.fontWeight = 'bold';
				} else {
					document.getElementById('question-timer').style.color = '';
					document.getElementById('question-timer').style.fontWeight = '';
				}
			}

			function handleQuestionTimeout() {
				// Pindah ke soal berikutnya saat waktu habis
				if (currentIndex < totalQuestions - 1) {
					currentIndex++;
					renderQuestion(currentIndex);
					$('#btn-prev').prop('disabled', false);

					if (currentIndex === totalQuestions - 1) {
						$('#btn-next').text('Selesai');
					} else {
						$('#btn-next').text('Selanjutnya');
					}

					// Pastikan timer soal baru dimulai
					if (enableTimePerQuestion) {
						startQuestionTimer();
					}

					// Tampilkan notifikasi bahwa waktu soal habis
					showNotification('Waktu untuk soal ini telah habis!', 'warning');
				} else {
					// Jika sudah di soal terakhir, selesaikan sesi
					if (confirm('Waktu untuk soal terakhir telah habis. Apakah Anda ingin menyelesaikan sesi ini?')) {
						window.location.href = '<?= base_url("user_tryout/submit_session/") ?>' + userTryoutId + '/' + sessionId;
					}
				}

				// Update grid untuk menunjukkan status soal terbaru
				updateGrid();
			}

			// Event handler untuk textarea essay - autosave saat user mengetik
			$(document).on('input propertychange paste', '[id^="essay-answer-"]', function() {
				var questionId = $(this).attr('id').replace('essay-answer-', '');
				var answerText = $(this).val();

				// Kirim jawaban ke server
				$.ajax({
					url: '<?= base_url("user_tryout/ajax_save_essay_answer") ?>',
					method: 'POST',
					data: {
						user_tryout_id: userTryoutId,
						question_id: questionId,
						answer_text: answerText,
						is_unsure: $('#btn-unsure').hasClass('active') ? 1 : 0
					},
					success: function(res) {
						var res = JSON.parse(res);
						if (res.status) {
							// Update answerMap
							if (!answerMap[questionId]) answerMap[questionId] = {};
							answerMap[questionId].answer_text = answerText;
							answerMap[questionId].is_unsure = $('#btn-unsure').hasClass('active') ? 1 : 0;

							// Update grid untuk menunjukkan bahwa soal ini sudah dijawab
							updateGrid();
						}
					},
					error: function() {
						// Tidak menampilkan error karena ini hanya autosave
					}
				});
			});

			// Event klik option - gunakan event delegation karena elemen dibuat secara dinamis
			$(document).on('click', '.option-item', function() {
				var option = $(this).data('option');
				var qid = $(this).data('question-id');

				// Hapus kelas selected dari semua option
				$('.option-item').removeClass('selected');
				$(this).addClass('selected');

				// Set radio
				$(this).find('input[type="radio"]').prop('checked', true);

				// Simpan jawaban via AJAX
				$.ajax({
					url: '<?= base_url("user_tryout/ajax_save_answer") ?>',
					method: 'POST',
					data: {
						user_tryout_id: userTryoutId,
						question_id: qid,
						answer: option,
						is_unsure: $('#btn-unsure').hasClass('active') ? 1 : 0
					},
					success: function(res) {
						var res = JSON.parse(res);
						console.log(res.status);
						if (res.status) {
							// Update answerMap
							if (!answerMap[qid]) answerMap[qid] = {};
							answerMap[qid].answer = option;
							answerMap[qid].is_unsure = $('#btn-unsure').hasClass('active') ? 1 : 0;
							updateGrid();
						}
					}
				});
			});

			// Tombol ragu-ragu
			$('#btn-unsure').click(function() {
				$(this).toggleClass('active');
				var qid = questions[currentIndex].question_id;
				var isUnsure = $(this).hasClass('active') ? 1 : 0;

				$.ajax({
					url: '<?= base_url("user_tryout/ajax_mark_unsure") ?>',
					method: 'POST',
					data: {
						user_tryout_id: userTryoutId,
						question_id: qid,
						is_unsure: isUnsure
					},
					success: function(res) {
						var res = JSON.parse(res);
						// console.log(res.status);
						if (res.status) {
							if (!answerMap[qid]) answerMap[qid] = {};
							answerMap[qid].is_unsure = isUnsure;
							updateGrid();
						}
					}
				});
			});

			// Navigasi next
			$('#btn-next').click(function() {
				if (enableTimePerQuestion) {
					// Jika waktu per soal diaktifkan, hentikan timer dan lanjut ke soal berikutnya
					stopQuestionTimer();
				}

				if (currentIndex < totalQuestions - 1) {
					currentIndex++;
					renderQuestion(currentIndex);
					$('#btn-prev').prop('disabled', false);
				} else {
					// Jika sudah di soal terakhir, arahkan ke submit
					$('#btn-submit-session').click();
					return;
				}

				if (currentIndex === totalQuestions - 1) {
					$('#btn-next').text('Selesai');
				} else {
					$('#btn-next').text('Selanjutnya');
				}

				// Panggil updateGrid untuk memperbarui status soal
				updateGrid();
			});

			// Navigasi prev - hanya aktif jika waktu per soal tidak diaktifkan
			$('#btn-prev').click(function() {
				if (enableTimePerQuestion) {
					// Jika waktu per soal diaktifkan, tidak boleh kembali ke soal sebelumnya
					showNotification('Tidak dapat kembali ke soal sebelumnya saat fitur waktu per soal aktif!', 'removed');
					return;
				}

				if (currentIndex > 0) {
					currentIndex--;
					renderQuestion(currentIndex);
					$('#btn-next').text('Selanjutnya');
				}
				if (currentIndex === 0) {
					$('#btn-prev').prop('disabled', true);
				}

				// Panggil updateGrid untuk memperbarui status soal
				updateGrid();
			});

			// Klik grid navigasi
			$(document).on('click', '.question-grid-item', function() {
				if (enableTimePerQuestion) {
					// Jika waktu per soal diaktifkan, tidak boleh pindah soal secara manual
					showNotification('Tidak dapat pindah soal secara manual saat fitur waktu per soal aktif!', 'removed');
					return;
				}

				var index = $(this).data('index');
				if (index !== undefined) {
					currentIndex = index;
					renderQuestion(currentIndex);
					$('#btn-prev').prop('disabled', currentIndex === 0);
					$('#btn-next').text(currentIndex === totalQuestions - 1 ? 'Selesai' : 'Selanjutnya');

					// Panggil updateGrid untuk memperbarui status soal
					updateGrid();
				}
			});

			// Submit sesi
			$('#btn-submit-session').click(function() {
				if (confirm('Apakah Anda yakin ingin menyelesaikan sesi ini?')) {
					window.location.href = '<?= base_url("user_tryout/submit_session/") ?>' + userTryoutId + '/' + sessionId;
				}
			});

			$('#btn-bookmark').click(function() {
				var qid = questions[currentIndex].question_id;

				$.ajax({
					url: '<?= base_url("user_tryout/toggle_bookmark") ?>',
					method: 'POST',
					data: {
						user_tryout_id: userTryoutId,
						question_id: qid
					},
					success: function(res) {
						var res = JSON.parse(res);
						if (res.status) {
							// Toggle bookmark status
							if (!bookmarkMap) bookmarkMap = {};

							if (res.action === 'added') {
								bookmarkMap[qid] = true;
								$(this).addClass('active');
								$('.question-number').addClass('bookmarked');

								// Tampilkan notifikasi
								showNotification('Bookmark ditambahkan!', 'success');
							} else {
								delete bookmarkMap[qid];
								$(this).removeClass('active');
								$('.question-number').removeClass('bookmarked');

								// Tampilkan notifikasi
								showNotification('Bookmark dihapus!', 'removed');
							}

							// Update tampilan tombol
							if (res.action === 'added') {
								$('#btn-bookmark').addClass('active');
							} else {
								$('#btn-bookmark').removeClass('active');
							}

							updateGrid();
						}
					}.bind(this),
					error: function(xhr, status, error) {
						console.error("Error toggling bookmark:", error);
						showNotification('Gagal mengubah bookmark!', 'removed');
					}
				});
			});

		});

		// Timer functionality
		// Calculate remaining time based on start time
		function initializeTimer() {
			// Ambil data sesi dari backend untuk menghitung sisa waktu
			const startTime = new Date('<?= $user_tryout->start_time ?>').getTime();
			const now = new Date().getTime();
			const elapsed = Math.floor((now - startTime) / 1000); // in seconds

			// Dapatkan durasi sesi dalam detik
			const durationInSeconds = <?= isset($session) && $session ? $session->duration_minutes * 60 : 0 ?>;
			var timeLeft = durationInSeconds - elapsed;

			if (timeLeft <= 0) {
				timeLeft = 0;
				// Time is up, complete the session
				handleSessionTimeout();
			}

			updateTimerDisplay(timeLeft);

			// Start the countdown timer
			startTimer(timeLeft);
		}

		function startTimer(initialTime) {
			var timeLeft = initialTime;
			var timerInterval = setInterval(function() {
				timeLeft--;

				if (timeLeft <= 0) {
					timeLeft = 0;
					clearInterval(timerInterval);
					handleSessionTimeout();
				}

				updateTimerDisplay(timeLeft);
			}, 1000);
		}

		function updateTimerDisplay(remainingTime) {
			const hours = Math.floor(remainingTime / 3600);
			const minutes = Math.floor((remainingTime % 3600) / 60);
			const seconds = remainingTime % 60;

			const formattedTime =
				String(hours).padStart(2, '0') + ':' +
				String(minutes).padStart(2, '0') + ':' +
				String(seconds).padStart(2, '0');

			document.getElementById('timer').textContent = formattedTime;

			// Change color when time is running low (less than 10 minutes)
			if (remainingTime <= 600) { // 10 minutes in seconds
				document.getElementById('timer').style.color = '#dc3545'; // Red color
				document.getElementById('timer').style.fontWeight = 'bold';
			} else {
				document.getElementById('timer').style.color = '';
				document.getElementById('timer').style.fontWeight = '';
			}
		}

		function handleSessionTimeout() {
			// Show timeout modal
			$('#time-expired-modal').modal('show');

			// Disable all interaction
			$('.options-list li').addClass('disabled').off('click');
			$('#btn-prev, #btn-next, #btn-submit-session').prop('disabled', true);

			// Submit the session automatically
			$.post('<?= base_url("user_tryout/complete/" . $user_tryout->id) ?>', function(response) {
				setTimeout(function() {
					window.location.href = '<?= base_url("user_tryout/result/" . $user_tryout->id) ?>';
				}, 2000);
			});
		}

		function showNotification(message, type) {
			// Hapus notifikasi sebelumnya dengan tipe yang sama
			$('.notification.' + type).remove();

			const notification = $(`
            <div class="notification ${type} show alert alert-dismissible fade show" role="alert" style="margin-bottom: 10px;">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'times-circle'}"></i> 
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);

			$('#notification-container').append(notification);

			// Hapus notifikasi setelah 3 detik
			setTimeout(() => {
				notification.removeClass('show');
				notification.fadeOut(300, function() {
					notification.remove();
				});
			}, 3000);
		}


		// Check if session has expired
		function checkSessionExpired() {
			$.ajax({
				url: '<?= base_url("user_tryout/ajax_check_session_expired/" . $user_tryout->id) ?>',
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					if (response.expired) {
						$('#time-expired-modal').modal('show');
					}
				},
				error: function(xhr, status, error) {
					console.log('Error checking session expiration: ' + error);
				}
			});
		}

		// Redirect to result page
		function redirectToResult() {
			window.location.href = '<?= base_url("user_tryout/result/" . $user_tryout->id) ?>';
		}
	</script>
</body>

</html>
