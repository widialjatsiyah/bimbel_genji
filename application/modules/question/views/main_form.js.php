
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
                                $('.question-difficulty').val(data.difficulty);
                                $('.question-curriculum').val(data.curriculum);
                                $('.question-question_text').val(data.question_text);
                                $('.question-option_a').val(data.option_a);
                                $('.question-option_b').val(data.option_b);
                                $('.question-option_c').val(data.option_c);
                                $('.question-option_d').val(data.option_d);
                                $('.question-option_e').val(data.option_e);
                                $('.question-correct_option').val(data.correct_option);
                                $('.question-explanation').val(data.explanation);
                                $('.question-video_explanation_url').val(data.video_explanation_url);
                            }, 300);
                        }, 300);
                    }, 300);
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

        // Handle save
        $("." + _section + "-action-save").on("click", function(e) {
            e.preventDefault();
            var formData = $("#" + _form).serialize();
            
            $.ajax({
                type: "post",
                url: "<?php echo base_url('question/ajax_save/') ?>" + _key,
                data: formData,
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
                    console.error("Error:", error);
                    alert("Terjadi kesalahan saat menyimpan data.");
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
                        // Note: This would depend on how TinyMCE is implemented in your app
                    }
                });
            }, 500);
        }
    });
</script>
