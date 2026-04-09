
<script type="text/javascript">
    $(document).ready(function() {
        var _key = "<?php echo $this->uri->segment(3); ?>"; // Get ID from URL segment
        var _section = "question";
        var _form = "form-question";

	// tinymce.init({
	// selector: "textarea.tinymce-math",
	// plugins: [
	// "eqneditor advlist autolink lists link image charmap print preview anchor",
	// "searchreplace visualblocks code fullscreen",
	// "insertdatetime media table contextmenu paste" ],
	// toolbar: "undo redo | eqneditor link image | styleselect | bold italic | bullist numlist outdent indent	",
	// selector : "textarea"  
	// });

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

        // Event listener untuk file input gambar pilihan A
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
                        if(res.status) {
                            $('.option_a_image-hidden').val(res.path);
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

        // Event listener untuk file input gambar pilihan B
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
                        if(res.status) {
                            $('.option_b_image-hidden').val(res.path);
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

        // Event listener untuk file input gambar pilihan C
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
                        if(res.status) {
                            $('.option_c_image-hidden').val(res.path);
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

        // Event listener untuk file input gambar pilihan D
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
                        if(res.status) {
                            $('.option_d_image-hidden').val(res.path);
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

        // Event listener untuk file input gambar pilihan E
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
                        if(res.status) {
                            $('.option_e_image-hidden').val(res.path);
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

        // Populate form with data if editing
        if (_key) {
            $.ajax({
                url: "<?php echo base_url('api/get/question/') ?>" + _key,
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
                                    if(data.option_a_image) {
                                        $('.option_a_image-hidden').val(data.option_a_image);
                                        $('#option_a_image_preview').html('<img src="<?php echo base_url() ?>' + data.option_a_image + '" style="max-width: 200px; max-height: 200px;" />');
                                    }
                                    
                                    $('.question-option_b').val(data.option_b);
                                    // Set gambar pilihan B jika ada
                                    if(data.option_b_image) {
                                        $('.option_b_image-hidden').val(data.option_b_image);
                                        $('#option_b_image_preview').html('<img src="<?php echo base_url() ?>' + data.option_b_image + '" style="max-width: 200px; max-height: 200px;" />');
                                    }
                                    
                                    $('.question-option_c').val(data.option_c);
                                    // Set gambar pilihan C jika ada
                                    if(data.option_c_image) {
                                        $('.option_c_image-hidden').val(data.option_c_image);
                                        $('#option_c_image_preview').html('<img src="<?php echo base_url() ?>' + data.option_c_image + '" style="max-width: 200px; max-height: 200px;" />');
                                    }
                                    
                                    $('.question-option_d').val(data.option_d);
                                    // Set gambar pilihan D jika ada
                                    if(data.option_d_image) {
                                        $('.option_d_image-hidden').val(data.option_d_image);
                                        $('#option_d_image_preview').html('<img src="<?php echo base_url() ?>' + data.option_d_image + '" style="max-width: 200px; max-height: 200px;" />');
                                    }
                                    
                                    $('.question-option_e').val(data.option_e);
                                    // Set gambar pilihan E jika ada
                                    if(data.option_e_image) {
                                        $('.option_e_image-hidden').val(data.option_e_image);
                                        $('#option_e_image_preview').html('<img src="<?php echo base_url() ?>' + data.option_e_image + '" style="max-width: 200px; max-height: 200px;" />');
                                    }
                                    
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
    });
</script>
