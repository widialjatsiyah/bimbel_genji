<script>
var base_url = '<?= base_url() ?>';
$(document).ready(function() {
    // Array untuk menyimpan soal yang dipilih
    var selectedQuestions = [];
    var allAvailableQuestions = [];

    // Initialize form
    init_list();

    // Handle data submit
    $("body").on("click", ".question_group-action-save", function(e) {
        e.preventDefault();
        
        // Update hidden field with selected question IDs
        $('#hidden_question_ids').val(selectedQuestions.map(q => q.id).join(','));
        
        var formData = {
            group_id: $('input[name="group_id"]').val(),
            main_question_id: $('select[name="main_question_id"]').val(),
            question_ids: selectedQuestions.map(q => q.id)
        };
        
        $.ajax({
            type: "post",
            url: base_url + 'question_group/ajax_create_group/' + ($('input[name="group_id"]').val() || ''),
            data: formData,
            success: function(response) {
                var response = JSON.parse(response);
                if (response.status === true) {
                    notify(response.data, "success");
                    // Redirect back to main page after successful save
                    setTimeout(function() {
                        window.location.href = base_url + 'question_group';
                    }, 1500);
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

    // Handle data cancel
    $("body").on("click", ".question_group-action-cancel", function(e) {
        e.preventDefault();
        window.location.href = base_url + 'question_group';
    });
});

function init_list() {
    // Load all questions that are not in any group
    $.ajax({
        url: base_url + 'question_group/ajax_get_questions_not_in_group',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            allAvailableQuestions = response;
            selectedQuestions = [];
            
            updateAvailableQuestionsTable();
            updateSelectedQuestionsTable();
            
            // Clear main question selection
            var mainQuestionSelect = $('#main_question_id');
            mainQuestionSelect.empty();
            mainQuestionSelect.append($('<option value="">Pilih Soal Utama</option>'));
            
            // Add all available questions to main question selection
            $.each(allAvailableQuestions, function(index, question) {
                var option = $('<option value="' + question.id + '">' + 
                               question.question_text.substring(0, 80) + 
                               (question.question_text.length > 80 ? '...' : '') + 
                               '</option>');
                mainQuestionSelect.append(option);
            });
            
            // Reinitialize Select2
            mainQuestionSelect.trigger('change.select2');
        },
        error: function(xhr, status, error) {
            console.log("Error loading questions: ", error);
        }
    });
}

function init_list_for_edit(groupId) {
    // Fetch current questions in the group
    $.ajax({
        url: base_url + 'question_group/ajax_get_questions_in_group/' + groupId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // Separate selected questions from available questions
            selectedQuestions = [];
            var selectedIds = [];
            
            $.each(response, function(index, question) {
                selectedQuestions.push(question);
                selectedIds.push(question.id);
                
                // Check if this is the main question
                if(question.is_group_main == 1) {
                    $('#main_question_id').val(question.id);
                }
            });
            
            // Get all other questions that are not in this group
            $.ajax({
                url: base_url + 'question_group/ajax_get_questions_not_in_group',
                type: 'GET',
                dataType: 'json',
                success: function(allQuestions) {
                    // Filter out questions that are already selected
                    allAvailableQuestions = allQuestions.filter(q => !selectedIds.includes(q.id));
                    
                    updateAvailableQuestionsTable();
                    updateSelectedQuestionsTable();
                    
                    // Populate main question select
                    var mainQuestionSelect = $('#main_question_id');
                    mainQuestionSelect.empty();
                    mainQuestionSelect.append($('<option value="">Pilih Soal Utama</option>'));
                    
                    // Add all questions (both available and selected) to main question selection
                    var allQuestionsForSelect = allQuestions.concat(selectedQuestions);
                    $.each(allQuestionsForSelect, function(index, question) {
                        var option = $('<option value="' + question.id + '">' + 
                                       question.question_text.substring(0, 80) + 
                                       (question.question_text.length > 80 ? '...' : '') + 
                                       '</option>');
                        mainQuestionSelect.append(option);
                    });
                    
                    // Reinitialize Select2
                    mainQuestionSelect.trigger('change.select2');
                    
                    // Set the group ID in the hidden input
                    $('input[name="group_id"]').val(groupId);
                },
                error: function(xhr, status, error) {
                    console.log("Error loading all questions: ", error);
                }
            });
        },
        error: function(xhr, status, error) {
            console.log("Error loading questions for edit: ", error);
            
            // Fallback: still call init_list to populate with non-grouped questions
            init_list();
        }
    });
}

function updateAvailableQuestionsTable() {
    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable('#table-available-questions')) {
        $('#table-available-questions').DataTable().destroy();
    }
    
    var table = $('#table-available-questions').DataTable({
        destroy: true,
        data: allAvailableQuestions,
        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {
                data: 'question_text',
                render: function(data, type, row) {
                    // Create a preview of the question text with limited height and scroll
                    var preview = data.length > 100 ? data.substring(0, 100) + '...' : data;
                    return '<div class="question-preview" style="max-height: 80px; overflow-y: auto; white-space: normal;">' + 
                           preview + 
                           (data.length > 100 ? 
                               '<span class="show-full" style="color: blue; cursor: pointer; text-decoration: underline;" data-full-text="' + 
                               data.replace(/"/g, '&quot;') + '"> Lihat Selengkapnya</span>' : '') + 
                           '</div>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return '<button type="button" class="btn btn-sm btn-primary add-question-btn" data-id="' + row.id + '">Tambah</button>';
                }
            }
        ],
        paging: true,
        pageLength: 10,
        language: {
            searchPlaceholder: "Cari...",
            sProcessing: '<div style="text-align: center;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>',
            paginate: {
                next: ">",
                previous: "<"
            }
        },
        createdRow: function(row, data, dataIndex) {
            $(row).attr('data-id', data.id);
        }
    });
    
    // Event listener for showing full text
    $('#table-available-questions tbody').off('click', '.show-full').on('click', '.show-full', function() {
        var fullText = $(this).data('full-text');
        alert(fullText); // Could be replaced with a modal or tooltip
    });
    
    // Event handler untuk tombol tambah
    $('#table-available-questions tbody').off('click', '.add-question-btn').on('click', '.add-question-btn', function() {
        var questionId = parseInt($(this).data('id'));
        var question = allAvailableQuestions.find(q => q.id === questionId);
        
        if (question && !selectedQuestions.some(sq => sq.id === questionId)) {
            selectedQuestions.push(question);
            
            // Remove from available questions
            allAvailableQuestions = allAvailableQuestions.filter(q => q.id !== questionId);
            
            // Update tables
            updateAvailableQuestionsTable();
            updateSelectedQuestionsTable();
            
            // Update main question select
            var mainQuestionSelect = $('#main_question_id');
            var option = $('<option value="' + question.id + '">' + 
                           question.question_text.substring(0, 80) + 
                           (question.question_text.length > 80 ? '...' : '') + 
                           '</option>');
            mainQuestionSelect.append(option);
        }
    });
}

function updateSelectedQuestionsTable() {
    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable('#table-selected-questions')) {
        $('#table-selected-questions').DataTable().destroy();
    }
    
    var table = $('#table-selected-questions').DataTable({
        destroy: true,
        data: selectedQuestions,
        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {
                data: 'question_text',
                render: function(data, type, row) {
                    // Create a preview of the question text with limited height and scroll
                    var preview = data.length > 100 ? data.substring(0, 100) + '...' : data;
                    return '<div class="question-preview" style="max-height: 80px; overflow-y: auto; white-space: normal;">' + 
                           preview + 
                           (data.length > 100 ? 
                               '<span class="show-full" style="color: blue; cursor: pointer; text-decoration: underline;" data-full-text="' + 
                               data.replace(/"/g, '&quot;') + '"> Lihat Selengkapnya</span>' : '') + 
                           '</div>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return '<button type="button" class="btn btn-sm btn-danger remove-question-btn" data-id="' + row.id + '">Hapus</button>';
                }
            }
        ],
        paging: true,
        pageLength: 10,
        language: {
            searchPlaceholder: "Cari...",
            sProcessing: '<div style="text-align: center;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>',
            paginate: {
                next: ">",
                previous: "<"
            }
        },
        createdRow: function(row, data, dataIndex) {
            $(row).attr('data-id', data.id);
        }
    });
    
    // Event listener for showing full text
    $('#table-selected-questions tbody').off('click', '.show-full').on('click', '.show-full', function() {
        var fullText = $(this).data('full-text');
        alert(fullText); // Could be replaced with a modal or tooltip
    });
    
    // Event handler untuk tombol hapus
    $('#table-selected-questions tbody').off('click', '.remove-question-btn').on('click', '.remove-question-btn', function() {
        var questionId = parseInt($(this).data('id'));
        
        // Remove from selected questions
        var removedQuestion = selectedQuestions.find(q => q.id === questionId);
        selectedQuestions = selectedQuestions.filter(q => q.id !== questionId);
        
        // Add back to available questions
        if (removedQuestion) {
            allAvailableQuestions.push(removedQuestion);
        }
        
        // Update tables
        updateAvailableQuestionsTable();
        updateSelectedQuestionsTable();
        
        // Update main question select
        $('#main_question_id option[value="' + questionId + '"]').remove();
    });
}

function notify(message, type) {
    // Simple notification function
    var alertDiv = $('<div class="alert alert-' + (type === 'success' ? 'success' : 'danger') + ' alert-dismissible fade show" role="alert">' +
        message + 
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button></div>');
    
    // Insert at top of form container
    $('.form-container').prepend(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(function() {
        alertDiv.alert('close');
    }, 5000);
}

// Initialize edit mode if group ID is provided
if (typeof groupData !== 'undefined' && groupData && groupData.group_data) {
    $(document).ready(function() {
        init_list_for_edit(groupData.group_data.group_id);
    });
}
</script>
