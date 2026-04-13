<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $session->name ?> - Try Out</title>
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
					<h4><?= $session->name ?></h4>
				</div>
				<div class="col-md-6 text-end">
					<div class="timer" id="timer"><?= gmdate("H:i:s", $session->duration_minutes * 60) ?></div>
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
	<input type="hidden" id="session_id" value="<?= $session->id ?>">
	<input type="hidden" id="total_questions" value="<?= $total_questions ?>">
	<input type="hidden" id="enable_time_per_question" value="<?= $session->enable_time_per_question ?? 0 ?>">
	<input type="hidden" id="time_per_question" value="<?= $session->time_per_question ?? 0 ?>">

	<!-- Container untuk notifikasi -->
	<div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

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
			var timeLeft = <?= $session->duration_minutes * 60 ?>; // detik
			var questionTimeLeft = 0; // untuk timer per soal
			var bookmarkMap = <?= json_encode($bookmark_map) ?>;
			var enableTimePerQuestion = $('#enable_time_per_question').val() == 1;
			var timePerQuestion = parseInt($('#time_per_question').val());

			// Debug: Tampilkan jumlah soal di console
			// console.log("Jumlah soal:", totalQuestions);
			// console.log("Data soal:", questions);

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
					questionHtml += '<div>';
					questionHtml += '<div class="alert alert-info"><strong>Soal Grup</strong> ' + questionData.question_text + '</div>';
					
					// Tambahkan gambar soal jika ada
					if (questionData.question_image) {
						questionHtml += '<div class="mb-3"><img src="' + BASE_URL + questionData.question_image + '" class="img-fluid rounded" style="max-width: 100%; height: auto;" alt="Gambar Soal Grup" /></div>';
					}
					questionHtml += '</div>';
				} 
				// Jika soal ini bagian dari grup tapi bukan utama
				else if (questionData.group_id !== null && !questionData.is_group_main) {
					questionHtml += '<div class="question-group-member mb-3">';
					questionHtml += '<div class="alert alert-light border"><strong>Soal Lanjutan:</strong> ' + questionData.question_text + '</div>';
					
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

						var checked = (answerMap[questions[currentIndex].question_id] && answerMap[questions[currentIndex].question_id].answer === letter) ? 'checked' : '';
						var isSelected = (answerMap[questions[currentIndex].question_id] && answerMap[questions[currentIndex].question_id].answer === letter) ? 'selected' : '';
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
					if(answerMap[questions[currentIndex].question_id] && 
					   answerMap[questions[currentIndex].question_id].hasOwnProperty('answer_text')) {
						userEssayAnswer = answerMap[questions[currentIndex].question_id].answer_text;
					}
					
					optionsHtml += `
						<div class="essay-answer-area">
							<label for="essay-answer-${questions[currentIndex].question_id}">Jawaban Esai:</label>
							<textarea 
								id="essay-answer-${questions[currentIndex].question_id}" 
								class="form-control essay-answer-textarea" 
								rows="6" 
								placeholder="Tulis jawaban esai Anda di sini...">${userEssayAnswer}</textarea>
							<button class="btn btn-primary save-essay-btn mt-2" data-question-id="${questions[currentIndex].question_id}">Simpan Jawaban</button>
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
				
				// Mulai timer per soal jika diaktifkan
				if (enableTimePerQuestion) {
					startQuestionTimer();
				}
			}

			// Add event handler for essay answers
			$(document).on('click', '.save-essay-btn', function() {
				var questionId = $(this).data('question-id');
				var answerText = $('#essay-answer-' + questionId).val();
				
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
							updateGrid();
							showNotification('Jawaban esai disimpan!', 'success');
						} else {
							showNotification('Gagal menyimpan jawaban esai!', 'removed');
						}
					},
					error: function() {
						showNotification('Gagal menyimpan jawaban esai!', 'removed');
					}
				});
			});

			function updateGrid() {
				// console.log('updateGrid');
				var gridHtml = '';
				for (var i = 0; i < totalQuestions; i++) {
					var qid = questions[i].question_id;
					var statusClass = '';
					if (answerMap[qid]) {
						if (answerMap[qid].is_unsure == 1) {
							statusClass = 'unsure';
						} else if (answerMap[qid].answer) {
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


			// Timer
			function startTimer() {
				timerInterval = setInterval(function() {
					timeLeft--;
					if (timeLeft <= 0) {
						clearInterval(timerInterval);
						alert('Waktu habis!');
						window.location.href = '<?= base_url("user_tryout/submit_session/") ?>' + userTryoutId + '/' + sessionId;
					}
					var hours = Math.floor(timeLeft / 3600);
					var minutes = Math.floor((timeLeft % 3600) / 60);
					var seconds = timeLeft % 60;
					$('#timer').text(
						(hours < 10 ? '0' + hours : hours) + ':' +
						(minutes < 10 ? '0' + minutes : minutes) + ':' +
						(seconds < 10 ? '0' + seconds : seconds)
					);
				}, 1000);
			}
			startTimer();

			// Fungsi untuk menghitung mundur per soal
			function startQuestionTimer() {
				if (!enableTimePerQuestion) return;
				
				// Gunakan waktu dari soal saat ini, atau waktu global jika soal tidak punya waktu spesifik
				var currentQuestion = questions[currentIndex];
				var timeLimit = currentQuestion.time_limit || timePerQuestion;
				
				// Jika waktu limit adalah 0, tidak gunakan timer
				if (timeLimit <= 0) {
					$('#question-timer').hide();
					return;
				}
				
				// Reset waktu soal
				questionTimeLeft = timeLimit;
				$('#question-timer').show();
				updateQuestionTimerDisplay();
				
				// Hentikan timer sebelumnya jika ada
				if (questionTimerInterval) {
					clearInterval(questionTimerInterval);
				}
				
				questionTimerInterval = setInterval(function() {
					questionTimeLeft--;
					updateQuestionTimerDisplay();
					
					if (questionTimeLeft <= 0) {
						// Waktu habis, lanjut ke soal berikutnya
						clearInterval(questionTimerInterval);
						moveToNextQuestion();
					}
				}, 1000);
			}
			
			function updateQuestionTimerDisplay() {
				if (!enableTimePerQuestion) return;
				
				var mins = Math.floor(questionTimeLeft / 60);
				var secs = questionTimeLeft % 60;
				var display = mins + ":" + (secs < 10 ? "0" : "") + secs;
				
				$('#question-timer').text('Waktu Soal: ' + display);
				
				// Beri warning saat waktu hampir habis
				if (questionTimeLeft <= 10) {
					$('#question-timer').css('color', 'red');
				} else {
					$('#question-timer').css('color', '#dc3545');
				}
			}
			
			function stopQuestionTimer() {
				if (questionTimerInterval) {
					clearInterval(questionTimerInterval);
				}
			}
			
			function moveToNextQuestion() {
				if (enableTimePerQuestion) {
					// Hentikan timer sebelum pindah soal
					stopQuestionTimer();
				}
				
				if (currentIndex < totalQuestions - 1) {
					currentIndex++;
					renderQuestion(currentIndex);
					$('#btn-prev').prop('disabled', false);
					
					// Mulai timer soal berikutnya
					if (enableTimePerQuestion) {
						startQuestionTimer();
					}
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
			}
			
			// Render soal pertama
			renderQuestion(0);
		});

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
	</script>
</body>

</html>
