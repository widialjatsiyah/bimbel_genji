                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <i class="form-group__bar"></i>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="button" class="btn btn-success btn--icon-text question-group-action-save">
            <i class="zmdi zmdi-save"></i> Simpan Grup
        </button>
        <a href="<?= base_url('question_group') ?>" class="btn btn-light btn--icon-text">
            Batal
        </a>
    </div>
</form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Load all questions that are not in any group
    if ($('.question-ids').length) {
        $.ajax({
            url: '<?= base_url('question_group/ajax_get_questions_not_in_group') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var selectElement = $('.question-ids');
                
                $.each(response, function(index, question) {
                    // Only add if not already selected
                    if (!selectElement.find('option[value="' + question.id + '"]').length) {
                        var option = $('<option value="' + question.id + '">' + question.question_text.substring(0, 80) + (question.question_text.length > 80 ? '...' : '') + '</option>');
                        selectElement.append(option);
                    }
                });
                
                // Reinitialize Select2
                selectElement.trigger('change.select2');
            }
        });
    }
    
    // Initialize Select2 for multi-select
    $('.select2').select2();
    
    // Handle save
    $(".question-group-action-save").on("click", function(e) {
        e.preventDefault();
        
        var formData = {
            group_id: $('input[name="group_id"]').val(),
            main_question_id: $('select[name="main_question_id"]').val(),
            question_ids: $('select[name="question_ids[]"]').val()
        };
        
        $.ajax({
            type: "post",
            url: "<?= base_url('question_group/ajax_create_group') ?>",
            data: formData,
            success: function(response) {
                var response = JSON.parse(response);
                if (response.status === true) {
                    notify(response.data, "success");
                    // Redirect to question group list after successful save
                    setTimeout(function() {
                        window.location.href = "<?= base_url('question_group') ?>";
                    }, 1000);
                } else {
                    notify(response.data, "danger");
                }
            },
            error: function(xhr, status, error) {
                console.log("Error: ", xhr.responseText);
                notify("Terjadi kesalahan saat menyimpan data", "danger");
            }
        });
    });
});

function notify(message, type) {
    // Simple notification function
    var alertDiv = $('<div class="alert alert-' + (type === 'success' ? 'success' : 'danger') + ' alert-dismissible fade show" role="alert">' +
        message + 
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button></div>');
    
    // Insert at top of body or form
    $('#form-question-group').before(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(function() {
        alertDiv.alert('close');
    }, 5000);
}
</script>