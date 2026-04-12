$(document).ready(function() {
    // Data soal dari server (dikirim dalam bentuk JSON)
    var currentIndex = 0;
    var timerInterval;

    // Define BASE_URL for use in JavaScript
    var BASE_URL = '<?= base_url() ?>';

    // Debug: Tampilkan jumlah soal di console
    console.log("Jumlah soal:", totalQuestions);
    console.log("Data soal:", questions);

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
        
        // Jika soal ini adalah soal pertama dalam grup (urutan 1), tampilkan header grup
        if (questionData.is_group_main) {
            questionHtml += '<div class="question-group-header">';
            questionHtml += '<strong>Soal Cerita</strong>';
            questionHtml += '</div>';
        }
        
        questionHtml += questionData.question_text;
        
        // Tambahkan gambar soal jika ada
        if (questionData.question_image) {
            questionHtml += '<div class="mb-3"><img src="' + BASE_URL + questionData.question_image + '" class="img-fluid" alt="Gambar Soal" /></div>';
        }
        
        $('#question-text').html(questionHtml);
        
        var optionsHtml = '';
        // Check if the question type is multiple choice to show options
        if (questionData.question_type === 'multiple_choice') {
            optionsHtml += '<ul class="options-list">';
            var letters = ['A', 'B', 'C', 'D', 'E'];
            for (var i = 0; i < 5; i++) {
                var letter = letters[i];
                var optContent = questionData['option_' + letter.toLowerCase()];
                
                // Jika tidak ada konten untuk opsi ini, lewati
                if (!optContent) continue;
                
                var checked = (answerMap[q.question_id] && answerMap[q.question_id].answer === letter) ? 'checked' : '';
                var isSelected = (answerMap[q.question_id] && answerMap[q.question_id].answer === letter) ? 'selected' : '';
                
                // Gunakan field option_type untuk menentukan tipe konten
                var optType = questionData.option_type || 'text';
                
                var optionContent = '';
                if (optType === 'image') {
                    optionContent = '<img src="' + BASE_URL + optContent + '" class="img-fluid" style="max-width: 200px; margin-top: 10px;" alt="Gambar Opsi" />';
                } else {
                    optionContent = optContent;
                }
                
                optionsHtml += `
                    <li class="option-item ${isSelected}" data-option="${letter}" data-question-id="${q.question_id}">
                        <input type="radio" name="option" value="${letter}" ${checked}>
                        <span class="option-letter">${letter}</span> ${optionContent}
                    </li>
                `;
            }
            optionsHtml += '</ul>';
        } else if (questionData.question_type === 'essay') {
            // For essay questions, show a text area for answers
            var userEssayAnswer = '';
            if(answerMap[q.question_id] && answerMap[q.question_id].answer_text) {
                userEssayAnswer = answerMap[q.question_id].answer_text;
            }
            
            optionsHtml += `
                <div class="essay-answer-area">
                    <label for="essay-answer-${q.question_id}">Jawaban Esai:</label>
                    <textarea 
                        id="essay-answer-${q.question_id}" 
                        class="form-control essay-answer-textarea" 
                        rows="6" 
                        placeholder="Tulis jawaban esai Anda di sini...">${userEssayAnswer}</textarea>
                    <button class="btn btn-primary save-essay-btn mt-2" data-question-id="${q.question_id}">Simpan Jawaban</button>
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
    }

    function updateGrid() {
        var gridHtml = '';
        for (var i = 0; i < totalQuestions; i++) {
            var qid = questions[i].question_id;
            var statusClass = '';
            if (answerMap[qid]) {
                if (answerMap[qid].is_unsure == 1) {
                    statusClass = 'unsure';
                } else if (answerMap[qid].answer || answerMap[qid].answer_text) {
                    statusClass = 'answered';
                } else {
                    statusClass = 'skipped';
                }
            } else {
                statusClass = 'skipped';
            }
            
            // Tambahkan kelas jika soal di-bookmark
            var isBookmarked = bookmarkMap && bookmarkMap[qid] ? 'bookmarked' : '';
            var currentClass = (i === currentIndex) ? 'current' : '';
            gridHtml += `<div class="question-grid-item ${statusClass} ${isBookmarked} ${currentClass}" data-index="${i}">${i+1}</div>`;
        }
        $('#question-grid').html(gridHtml);
    }
    
    // Add event handler for essay answers
    $(document).on('click', '.save-essay-btn', function() {
        var questionId = $(this).data('question-id');
        var answerText = $('#essay-answer-' + questionId).val();
        
        $.ajax({
            url: BASE_URL + 'user_tryout/ajax_save_essay_answer',
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
            url: BASE_URL + 'user_tryout/ajax_save_answer',
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
            url: BASE_URL + 'user_tryout/ajax_mark_unsure',
            method: 'POST',
            data: {
                user_tryout_id: userTryoutId,
                question_id: qid,
                is_unsure: isUnsure
            },
            success: function(res) {
                if (res.status) {
                    if (!answerMap[qid]) answerMap[qid] = {};
                    answerMap[qid].is_unsure = isUnsure;
                    updateGrid();
                }
            }
        });
    });

    // Tombol bookmark
    $('#btn-bookmark').click(function() {
        var qid = questions[currentIndex].question_id;
        
        $.ajax({
            url: BASE_URL + 'user_tryout/toggle_bookmark',
            method: 'POST',
            data: {
                user_tryout_id: userTryoutId,
                question_id: qid
            },
            success: function(res) {
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
    
    // Fungsi untuk menampilkan notifikasi
    function showNotification(message, type) {
        const notification = $(`
            <div class="notification ${type} show">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'times-circle'}"></i> 
                ${message}
            </div>
        `);
        
        $('#notification-container').append(notification);
        
        // Hapus notifikasi setelah 3 detik
        setTimeout(() => {
            notification.removeClass('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Navigasi next
    $('#btn-next').click(function() {
        if (currentIndex < totalQuestions - 1) {
            currentIndex++;
            renderQuestion(currentIndex);
            $('#btn-prev').prop('disabled', false);
        }
        if (currentIndex === totalQuestions - 1) {
            $('#btn-next').text('Selesai');
        } else {
            $('#btn-next').text('Selanjutnya');
        }
    });

    // Navigasi prev
    $('#btn-prev').click(function() {
        if (currentIndex > 0) {
            currentIndex--;
            renderQuestion(currentIndex);
            $('#btn-next').text('Selanjutnya');
        }
        if (currentIndex === 0) {
            $('#btn-prev').prop('disabled', true);
        }
    });

    // Klik grid navigasi
    $(document).on('click', '.question-grid-item', function() {
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
            window.location.href = BASE_URL + 'user_tryout/submit_session/' + userTryoutId + '/' + sessionId;
        }
    });

    // Timer
    function startTimer() {
        timerInterval = setInterval(function() {
            timeLeft--;
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                alert('Waktu habis!');
                window.location.href = BASE_URL + 'user_tryout/submit_session/' + userTryoutId + '/' + sessionId;
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

    // Render soal pertama
    renderQuestion(0);
});