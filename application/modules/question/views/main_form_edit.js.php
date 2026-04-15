<script type="text/javascript">
	$(document).ready(function() {
		var _key = "<?php echo $question_data->id; ?>"; // Question ID
		var _section = "question";
		var _form = "form-question";


		// Preview functions
		function previewImage(input, previewContainer) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function(e) {
					previewContainer.html('<img src="' + e.target.result + '" style="max-width: 200px; max-height: 200px;" />');
				}

				reader.readAsDataURL(input.files[0]);
			}
		}

		// Question image upload
		$('.question-image').on('change', function() {
			var previewContainer = $('#question_image_preview');
			previewImage(this, previewContainer);

			if (this.files && this.files[0]) {
				var formData = new FormData();
				formData.append('image', this.files[0]);

				$.ajax({
					url: '<?php echo base_url("question/upload_image") ?>',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(response) {
						var res = JSON.parse(response);
						if (res.status) {
							$('.question-image-hidden').val(res.path);
						} else {
							notify('Gagal mengupload gambar: ' + res.error, 'danger');
						}
					},
					error: function() {
						notify('Terjadi kesalahan saat upload gambar', 'danger');
					}
				});
			}
		});

		// Option image uploads
		$('[name="option_a_image_file"]').on('change', function() {
			var previewContainer = $('#option_a_image_preview');
			previewImage(this, previewContainer);

			if (this.files && this.files[0]) {
				var formData = new FormData();
				formData.append('image', this.files[0]);

				$.ajax({
					url: '<?php echo base_url("question/upload_image") ?>',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(response) {
						var res = JSON.parse(response);
						if (res.status) {
							$('.option_a_image-hidden').val(res.path);
						} else {
							notify('Gagal mengupload gambar opsi A: ' + res.error, 'danger');
						}
					},
					error: function() {
						notify('Terjadi kesalahan saat upload gambar opsi A', 'danger');
					}
				});
			}
		});

		$('[name="option_b_image_file"]').on('change', function() {
			var previewContainer = $('#option_b_image_preview');
			previewImage(this, previewContainer);

			if (this.files && this.files[0]) {
				var formData = new FormData();
				formData.append('image', this.files[0]);

				$.ajax({
					url: '<?php echo base_url("question/upload_image") ?>',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(response) {
						var res = JSON.parse(response);
						if (res.status) {
							$('.option_b_image-hidden').val(res.path);
						} else {
							notify('Gagal mengupload gambar opsi B: ' + res.error, 'danger');
						}
					},
					error: function() {
						notify('Terjadi kesalahan saat upload gambar opsi B', 'danger');
					}
				});
			}
		});

		$('[name="option_c_image_file"]').on('change', function() {
			var previewContainer = $('#option_c_image_preview');
			previewImage(this, previewContainer);

			if (this.files && this.files[0]) {
				var formData = new FormData();
				formData.append('image', this.files[0]);

				$.ajax({
					url: '<?php echo base_url("question/upload_image") ?>',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(response) {
						var res = JSON.parse(response);
						if (res.status) {
							$('.option_c_image-hidden').val(res.path);
						} else {
							notify('Gagal mengupload gambar opsi C: ' + res.error, 'danger');
						}
					},
					error: function() {
						notify('Terjadi kesalahan saat upload gambar opsi C', 'danger');
					}
				});
			}
		});

		$('[name="option_d_image_file"]').on('change', function() {
			var previewContainer = $('#option_d_image_preview');
			previewImage(this, previewContainer);

			if (this.files && this.files[0]) {
				var formData = new FormData();
				formData.append('image', this.files[0]);

				$.ajax({
					url: '<?php echo base_url("question/upload_image") ?>',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(response) {
						var res = JSON.parse(response);
						if (res.status) {
							$('.option_d_image-hidden').val(res.path);
						} else {
							notify('Gagal mengupload gambar opsi D: ' + res.error, 'danger');
						}
					},
					error: function() {
						notify('Terjadi kesalahan saat upload gambar opsi D', 'danger');
					}
				});
			}
		});

		$('[name="option_e_image_file"]').on('change', function() {
			var previewContainer = $('#option_e_image_preview');
			previewImage(this, previewContainer);

			if (this.files && this.files[0]) {
				var formData = new FormData();
				formData.append('image', this.files[0]);

				$.ajax({
					url: '<?php echo base_url("question/upload_image") ?>',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(response) {
						var res = JSON.parse(response);
						if (res.status) {
							$('.option_e_image-hidden').val(res.path);
						} else {
							notify('Gagal mengupload gambar opsi E: ' + res.error, 'danger');
						}
					},
					error: function() {
						notify('Terjadi kesalahan saat upload gambar opsi E', 'danger');
					}
				});
			}
		});

		// Handle change of question type
		$('.question-type').on('change', function() {
			var type = $(this).val();
			if (type === 'essay') {
				$('#multiple-choice-section').hide();
				$('#essay-section').show();
			} else {
				$('#multiple-choice-section').show();
				$('#essay-section').hide();
			}
		});

		// Handle change of option type
		$('.question-option_type').on('change', function() {
			var optionType = $(this).val();
			var questionType = $('.question-type').val();
			if (questionType === 'multiple_choice') {
				if (optionType === 'text') {
					// Show text inputs, hide image inputs
					$('#multiple-choice-section .row[id*="option-"] .col-md-6:nth-child(1)').show();
					$('#multiple-choice-section .row[id*="option-"] .col-md-6:nth-child(2)').hide();
					$('#multiple-choice-section input[type="text"][name^="option_"]').prop('required', true);
					$('#multiple-choice-section input[type="file"][name*="_image_file"]').prop('required', false);
				} else if (optionType === 'image') {
					// Show image inputs, hide text inputs
					$('#multiple-choice-section .row[id*="option-"] .col-md-6:nth-child(1)').hide();
					$('#multiple-choice-section .row[id*="option-"] .col-md-6:nth-child(2)').show();
					$('#multiple-choice-section input[type="text"][name^="option_"]').prop('required', false);
					$('#multiple-choice-section input[type="file"][name*="_image_file"]').prop('required', true);
				}
			}
		});

		// Chained dropdown: Subject -> Chapter
		var subjectChanging = false;
		$('.question-subject_id').on('change', function() {
			if (subjectChanging) return; // Prevent multiple triggers
			subjectChanging = true;

			var subject_id = $(this).val();
			var $chapter = $('.question-chapter_id');
			var $topic = $('.question-topic_id');

			// Reset chapter & topic
			$chapter.html('<option value="">Pilih Bab</option>');
			$topic.html('<option value="">Pilih Topik</option>');

			if (subject_id) {
				$.ajax({
					url: '<?php echo base_url("question/get_chapters_by_subject") ?>',
					type: 'POST',
					data: {
						subject_id: subject_id,
						'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
					},
					success: function(response) {
						var data = JSON.parse(response);
						if (data.status) {
							$.each(data.chapters, function(key, value) {
								$chapter.append('<option value="' + value.id + '">' + value.name + '</option>');
							});
						}
						subjectChanging = false;
					},
					error: function() {
						subjectChanging = false;
					}
				});
			} else {
				subjectChanging = false;
			}
		});

		// Chained dropdown: Chapter -> Topic
		var chapterChanging = false;
		$('.question-chapter_id').on('change', function() {
			if (chapterChanging) return; // Prevent multiple triggers
			chapterChanging = true;

			var chapter_id = $(this).val();
			var $topic = $('.question-topic_id');

			// Reset topic
			$topic.html('<option value="">Pilih Topik</option>');

			if (chapter_id) {
				$.ajax({
					url: '<?php echo base_url("question/get_topics_by_chapter") ?>',
					type: 'POST',
					data: {
						chapter_id: chapter_id,
						'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
					},
					success: function(response) {
						var data = JSON.parse(response);
						if (data.status) {
							$.each(data.topics, function(key, value) {
								$topic.append('<option value="' + value.id + '">' + value.name + '</option>');
							});
						}
						chapterChanging = false;
					},
					error: function() {
						chapterChanging = false;
					}
				});
			} else {
				chapterChanging = false;
			}
		});

		// Handle save
		$(".question-action-save").on("click", function(e) {
			e.preventDefault();

			// If using TinyMCE, save content to textarea before submit
			if (typeof tinymce !== 'undefined' && tinymce.editors.length > 0) {
				tinymce.triggerSave();
			}

			var formData = new FormData(document.getElementById(_form));

			$.ajax({
				url: '<?php echo base_url("question/ajax_save") ?>',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					var data = JSON.parse(response);
					if (data.status) {
						notify('Soal berhasil diperbarui!', 'success');
						setTimeout(function() {
							window.location.href = '<?php echo base_url("question") ?>';
						}, 2000);
					} else {
						notify('Gagal memperbarui soal: ' + data.message, 'danger');
					}
				},
				error: function() {
					notify('Terjadi kesalahan saat memperbarui soal', 'danger');
				}
			});
		});

		// Initialize form display state manually (without triggering events)
		var currentQuestionType = "<?php echo $question_data->question_type; ?>";
		var currentOptionType = "<?php echo $question_data->option_type ?: 'text'; ?>";

		// Set display for question type
		if (currentQuestionType === 'essay') {
			$('#multiple-choice-section').hide();
			$('#essay-section').show();
		} else {
			$('#multiple-choice-section').show();
			$('#essay-section').hide();
		}

		// Set display for option type
		if (currentOptionType === 'text') {
			$('#multiple-choice-section .row[id*="option-"] .col-md-6:nth-child(1)').show();
			$('#multiple-choice-section .row[id*="option-"] .col-md-6:nth-child(2)').hide();
		} else if (currentOptionType === 'image') {
			$('#multiple-choice-section .row[id*="option-"] .col-md-6:nth-child(1)').hide();
			$('#multiple-choice-section .row[id*="option-"] .col-md-6:nth-child(2)').show();
		}

		
		// Handler untuk mengkonversi input sederhana ke format JSON
		$('#expected_keywords_simple').on('input', function() {
			var simpleInput = $(this).val();
			if (simpleInput.trim() !== '') {
				var lines = simpleInput.split('\n');
				var keywords = [];

				for (var i = 0; i < lines.length; i++) {
					var line = lines[i].trim();
					if (line) {
						var parts = line.split('=');
						var word = parts[0].trim();
						var score = 1; // default score

						if (parts.length > 1) {
							score = parseInt(parts[1]) || 1;
						}

						keywords.push({
							"word": word,
							"score": score
						});
					}
				}

				var jsonString = JSON.stringify(keywords);
				$('#expected_keywords').val(jsonString);
			}
		});

		// Handler untuk mengisi input sederhana dari JSON jika ada
		$('#expected_keywords').on('input', function() {
			var jsonInput = $(this).val();
			if (jsonInput.trim() !== '') {
				try {
					var jsonObj = JSON.parse(jsonInput);
					if (Array.isArray(jsonObj)) {
						var simpleLines = [];
						for (var i = 0; i < jsonObj.length; i++) {
							simpleLines.push(jsonObj[i].word + '=' + jsonObj[i].score);
						}
						$('#expected_keywords_simple').val(simpleLines.join('\n'));
					}
				} catch (e) {
					// Jika bukan format JSON yang valid, abaikan
				}
			} else {
				$('#expected_keywords_simple').val('');
			}
		});

		$("#expected_keywords_simple").val(jsonToWordScoreString($('#expected_keywords').val())); // Initialize simple input on page load

	});

	function jsonToWordScoreString(jsonString) {
			try {
				let data = JSON.parse(jsonString);

				if (!Array.isArray(data)) return '';

				let result = data.map(item => {
					if (item.word !== undefined && item.score !== undefined) {
						return item.word + '=' + item.score;
					}
					return '';
				});

				return result.join('\n');

			} catch (e) {
				console.error('Format JSON salah:', e);
				return '';
			}
		}
</script>
