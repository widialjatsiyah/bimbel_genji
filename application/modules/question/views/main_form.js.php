<script type="text/javascript">
    $(document).ready(function() {
        var _key = "<?php echo $this->uri->segment(3); ?>"; // Get ID from URL segment
        var _section = "question";
        var _form = "form-question";
        
        // Define base_url for AJAX requests
        var base_url = '<?php echo base_url(); ?>';

        // Fungsi untuk menangani preview gambar
        function previewImage(input, previewContainer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    previewContainer.html('<img src="' + e.target.result + '" style="max-width: 200px; max-height: 200px;" />');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Event listener untuk file input gambar soal
        $('.question-image').on('change', function() {
            var previewContainer = $('#question_image_preview');
            previewImage(this, previewContainer);
            
            // Simpan nama file ke hidden input
            if (this.files && this.files[0]) {
                var formData = new FormData();
                formData.append('image', this.files[0]);
                
                // Upload gambar dan simpan path-nya
                $.ajax({
                    url: '<?php echo base_url("question/upload_image") ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var res = JSON.parse(response);
                        if(res.status) {
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

        // Fungsi untuk menangani event listener file input gambar pilihan
        function setupImageUploadHandlers(optionLetter) {
            var inputName = 'option_' + optionLetter.toLowerCase() + '_image_file';
            var hiddenClass = '.option_' + optionLetter.toLowerCase() + '_image-hidden';
            var previewId = '#option_' + optionLetter.toLowerCase() + '_image_preview';

            $('[name="' + inputName + '"]').on('change', function() {
                var previewContainer = $(previewId);
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
                            if(res.status) {
                                $(hiddenClass).val(res.path);
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
        }

        // Event listener untuk file input gambar pilihan A-E
        var optionLetters = ['A', 'B', 'C', 'D', 'E'];
        $.each(optionLetters, function(i, letter) {
            setupImageUploadHandlers(letter);
        });

        // Populate form with data if editing
        if (_key) {
            $.ajax({
                url: "<?php echo base_url('question/api_get_question/') ?>" + _key,
                type: "GET",
                success: function(response) {
                    var data = response;
                    
                    // Set nilai form
                    $('.question-subject_id').val(data.subject_id).trigger('change');

                    // Setelah subject dipilih, tunggu chapter dan topic diload
                    setTimeout(function() {
                        $('.question-chapter_id').val(data.chapter_id).trigger('change');
                        
                        setTimeout(function() {
                            $('.question-topic_id').val(data.topic_id).trigger('change');
                            
                            // Setelah semua dropdown selesai dimuat, masukkan nilai lainnya
                            setTimeout(function() {
                                // Set jenis soal
                                $('.question-type').val(data.question_type).trigger('change');
                                
                                // Set tipe opsi
                                $('.question-option_type').val(data.option_type || 'text').trigger('change');
                                
                                // Set group fields
                                $('.question-group_id').val(data.group_id);
                                $('.question-group_order').val(data.group_order || 1);
                                $('.question-is_group_main').val(data.is_group_main || 0);
                                
                                // Update TinyMCE jika ada
                                if (typeof tinymce !== 'undefined' && tinymce.get('question_text')) {
                                    tinymce.get('question_text').setContent(data.question_text);
                                } else {
                                    $('.question-question_text').val(data.question_text);
                                }
                                
                                // Set gambar soal jika ada
                                if(data.question_image) {
                                    $('.question-image-hidden').val(data.question_image);
                                    $('#question_image_preview').html('<img src="<?php echo base_url() ?>' + data.question_image + '" style="max-width: 200px; max-height: 200px;" />');
                                }
                                
                                // Jika soal pilihan ganda
                                if(data.question_type === 'multiple_choice') {
                                    $('.question-option_a').val(data.option_a);
                                    // Set gambar pilihan A jika ada
                                    handleOptionImage('a', data.option_a_image);
                                    
                                    $('.question-option_b').val(data.option_b);
                                    // Set gambar pilihan B jika ada
                                    handleOptionImage('b', data.option_b_image);
                                    
                                    $('.question-option_c').val(data.option_c);
                                    // Set gambar pilihan C jika ada
                                    handleOptionImage('c', data.option_c_image);
                                    
                                    $('.question-option_d').val(data.option_d);
                                    // Set gambar pilihan D jika ada
                                    handleOptionImage('d', data.option_d_image);
                                    
                                    $('.question-option_e').val(data.option_e);
                                    // Set gambar pilihan E jika ada
                                    handleOptionImage('e', data.option_e_image);
                                    
                                    $('.question-correct_option').val(data.correct_option);
                                } 
                                // Jika soal esai
                                else if(data.question_type === 'essay') {
                                    if(data.expected_keywords) {
                                        $('#expected_keywords').val(data.expected_keywords);
                                    }
                                    if(data.min_keyword_matches) {
                                        $('input[name="min_keyword_matches"]').val(data.min_keyword_matches);
                                    }
                                }
                                
                                $('.question-explanation').val(data.explanation);
                                $('.question-video_explanation_url').val(data.video_explanation_url);
                            }, 300);
                        }, 300);
                    }, 300);
                },
                error: function() {
                    notify('Gagal memuat data soal', 'danger');
                }
            });
        }

		
                // Fungsi untuk menangani gambar pilihan saat edit
                function handleOptionImage(optionLetter, imagePath) {
                    if(imagePath) {
                        $('.option_' + optionLetter + '_image-hidden').val(imagePath);
                        $('#option_' + optionLetter + '_image_preview').html('<img src="<?php echo base_url() ?>' + imagePath + '" style="max-width: 200px; max-height: 200px;" />');
                    }
                }

        // Chained dropdown: Subject -> Chapter
        $('.question-subject_id').on('change', function() {
            var subject_id = $(this).val();
            var $chapter = $('.question-chapter_id');
            var $topic = $('.question-topic_id');

            // Reset chapter & topic
            $chapter.empty().append('<option value=""></option>').trigger('change');
            $topic.empty().append('<option value=""></option>').trigger('change');

            if (subject_id) {
                $.ajax({
                    url: '<?php echo base_url("question/ajax_get_chapters") ?>',
                    type: 'get',
                    data: { subject_id: subject_id },
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(i, item) {
                            $chapter.append('<option value="' + item.id + '">' + item.name + '</option>');
                        });
                        $chapter.trigger('change');
                    }
                });
            }
        });

        // Chained dropdown: Chapter -> Topic
        $('.question-chapter_id').on('change', function() {
            var chapter_id = $(this).val();
            var $topic = $('.question-topic_id');

            // Reset topic
            $topic.empty().append('<option value=""></option>').trigger('change');

            if (chapter_id) {
                $.ajax({
                    url: '<?php echo base_url("question/ajax_get_topics") ?>',
                    type: 'get',
                    data: { chapter_id: chapter_id },
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(i, item) {
                            $topic.append('<option value="' + item.id + '">' + item.name + '</option>');
                        });
                        $topic.trigger('change');
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
            
            if (optionType === 'text') {
                // Sembunyikan input file gambar dan tampilkan input teks
                $('.row[id*="option-"]').find('.col-md-6:last-child').hide(); // Sembunyikan kolom gambar
                $('.row[id*="option-"]').find('.col-md-6:first-child').show(); // Tampilkan kolom teks
                $('.row[id*="option-"]').find('.col-md-6:first-child input').prop('required', true); // Wajibkan teks
            } else if (optionType === 'image') {
                // Sembunyikan input teks dan tampilkan input file gambar
                $('.row[id*="option-"]').find('.col-md-6:first-child').hide(); // Sembunyikan kolom teks
                $('.row[id*="option-"]').find('.col-md-6:last-child').show(); // Tampilkan kolom gambar
                $('.row[id*="option-"]').find('.col-md-6:first-child input').prop('required', false); // Hilangkan wajib teks
            }
        });

        // Handle data add
        $("#" + _section).on("click", "button." + _section + "-action-add", function(e) {
            e.preventDefault();
            resetForm();
        });

        // Handle data import button
        $("#" + _section).on("click", "button." + _section + "-action-import", function(e) {
            e.preventDefault();
            openImportModal();
        });

        // Function to open import modal
        function openImportModal() {
            // Create and show import modal with subject selection
            var modalHtml = `
            <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">Impor Soal dari Excel</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <p><strong>Petunjuk:</strong></p>
                                <ul>
                                    <li>Unduh template Excel terlebih dahulu</li>
                                    <li>Isi data soal sesuai kolom yang ditentukan</li>
                                    <li>Pastikan kolom wajib diisi tidak kosong</li>
                                </ul>
                                <button type="button" class="btn btn-outline-primary" onclick="downloadTemplate()">Unduh Template Excel</button>
                            </div>
                            
                            <form id="importForm">
                                <div class="form-group">
                                    <label for="subjectSelection">Pilih Mata Pelajaran:</label>
                                    <select class="form-control select2" id="subjectSelection" name="subject_id" required>
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="importFile">Pilih File Excel:</label>
                                    <input type="file" class="form-control" id="importFile" name="import_file" accept=".xlsx,.xls" required>
                                    <small class="form-text text-muted">Format file harus .xlsx atau .xls</small>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" id="startImportBtn">Mulai Impor</button>
                        </div>
                    </div>
                </div>
            </div>`;

            // Add modal to body if not exists
            if ($('#importModal').length === 0) {
                $('body').append(modalHtml);
                
                // Load subjects into select
                $.ajax({
                    url: base_url + 'question/ajax_get_subjects',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var select = $('#subjectSelection');
                        response.forEach(function(subject) {
                            select.append('<option value="' + subject.id + '">' + subject.name + '</option>');
                        });
                        
                        // Initialize select2
                        select.select2();
                    },
                    error: function(xhr, status, error) {
                        console.error('Gagal memuat mata pelajaran:', error);
                        showNotification('Gagal memuat daftar mata pelajaran', 'error');
                    }
                });
            }

            // Show modal
            $('#importModal').modal('show');
        }

        // Function to download template
        function downloadTemplate() {
            window.location.href = base_url + 'question/download_template';
        }

        // Handle import button click
        $(document).on('click', '#startImportBtn', function() {
            var subjectId = $('#subjectSelection').val();
            var fileInput = $('#importFile')[0];
            
            if (!subjectId) {
                showNotification('Silakan pilih mata pelajaran', 'warning');
                return;
            }
            
            if (!fileInput.files[0]) {
                showNotification('Silakan pilih file Excel', 'warning');
                return;
            }
            
            var formData = new FormData();
            formData.append('import_file', fileInput.files[0]);
            formData.append('subject_id', subjectId);
            
            // Disable button during import
            $('#startImportBtn').prop('disabled', true).text('Mengimpor...');
            
            $.ajax({
                url: base_url + 'question/import_from_excel',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var res = JSON.parse(response);
                    
                    if (res.status) {
                        showNotification(res.data, 'success');
                        $('#importModal').modal('hide');
                        // Reload the table
                        $('#' + _table).DataTable().ajax.reload(null, false);
                    } else {
                        showNotification(res.data, 'error');
                    }
                    
                    // Re-enable button
                    $('#startImportBtn').prop('disabled', false).text('Mulai Impor');
                },
                error: function(xhr, status, error) {
                    console.error('Import error:', error);
                    showNotification('Terjadi kesalahan saat mengimpor: ' + error, 'error');
                    
                    // Re-enable button
                    $('#startImportBtn').prop('disabled', false).text('Mulai Impor');
                }
            });
        });

        // Function to show notification
        function showNotification(message, type) {
            // Using standard Bootstrap alert
            var alertClass = 'alert-';
            switch(type) {
                case 'success':
                    alertClass += 'success';
                    break;
                case 'error':
                    alertClass += 'danger';
                    break;
                case 'warning':
                    alertClass += 'warning';
                    break;
                default:
                    alertClass += 'info';
            }
            
            var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show position-fixed" style="z-index: 9999; top: 20px; right: 20px;" role="alert">' +
                message +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span>' +
                '</button>' +
            '</div>';
            
            $('body').append(alertHtml);
            
            // Auto remove after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
        }

        // Handle data edit
        $("#" + _section).on("click", "button." + _section + "-action-edit", function(e) {
            e.preventDefault();
            var n = $(this).closest("tr").find('td:first').text() - 1;
            var record = dt.rows(n).data()[0];

            // Set current data
            currentId = record.id;

            // Load data to form
            loadFormData(currentId);
        });

        // Handle save
        $("." + _section + "-action-save").on("click", function(e) {
            e.preventDefault();
            
            // Jika menggunakan TinyMCE, pastikan konten disimpan ke textarea sebelum submit
            if (typeof tinymce !== 'undefined' && tinymce.editors.length > 0) {
                tinymce.triggerSave();
            }
            
            // Gunakan FormData untuk mengirim data dan file
            var formData = new FormData($(`#${_form}`)[0]);
            
            $.ajax({
                type: "post",
                url: "<?php echo base_url('question/ajax_save/') ?>" + _key,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('.spinner').show();
                },
                success: function(response) {
                    var response = JSON.parse(response);
                    if (response.status === true) {
						notify(response.data, 'success');
                        // Redirect to question list after successful save
						setTimeout(function() {
							window.location.href = "<?php echo base_url('question') ?>";
						}, 1000);
                    } else {
                        notify(response.data, 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", xhr.responseText);
                    notify('Terjadi kesalahan saat menyimpan data', 'danger');
                },
                complete: function() {
                    $('.spinner').hide();
                }
            });
        });

        // Initialize TinyMCE if available
        if (typeof tinymce !== 'undefined') {
            setTimeout(function() {
                $('.tinymce-init').each(function() {
                    if (!$(this).hasClass('tinymce-initialized')) {
                        $(this).addClass('tinymce-initialized');
                        // Initialize TinyMCE for the textarea
                        tinymce.init({
                            selector: '#' + this.id,
                            plugins: 'advlist autolink lists link image charmap print preview anchor',
                            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | help',
                            height: 300
                        });
                    }
                });
            }, 500);
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
                        
                        keywords.push({"word": word, "score": score});
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
        
        // Panggil handler tipe soal dan tipe opsi untuk menyesuaikan tampilan saat halaman dimuat
        if (_key) {
            var currentType = $('.question-type').val();
            var currentOptionType = $('.question-option_type').val();
            
            if (currentType === 'essay') {
                $('#multiple-choice-section').hide();
                $('#essay-section').show();
            } else {
                $('#multiple-choice-section').show();
                $('#essay-section').hide();
            }
            
            // Trigger change untuk menyesuaikan tampilan opsi
            setTimeout(function() {
                $('.question-option_type').trigger('change');
            }, 500);
        } else {
            // Jika mode tambah baru, trigger change untuk tipe opsi default
            setTimeout(function() {
                $('.question-option_type').trigger('change');
            }, 500);
        }
    });

    // Handler untuk mengganti tampilan berdasarkan jenis soal
    $(document).ready(function() {
        const questionTypeSelect = document.getElementById('question_type');
        const mcSection = document.getElementById('multiple-choice-section');
        const essaySection = document.getElementById('essay-section');
          
        if(questionTypeSelect) {
            questionTypeSelect.addEventListener('change', function() {
                if (this.value === 'essay') {
                    mcSection.style.display = 'none';
                    essaySection.style.display = 'block';
                } else {
                    mcSection.style.display = 'block';
                    essaySection.style.display = 'none';
                }
            });
        }
    });
</script>