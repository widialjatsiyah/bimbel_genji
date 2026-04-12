<script type="text/javascript">
    $(document).ready(function() {

        var _key = "";
        var _section = "tryout_session";
        var _table = "table-tryout_session";
        var _modal = "modal-form-tryout_session";
        var _form = "form-tryout_session";
        var _manageQuestionsModal = "modal-manage-questions";
        var _manageQuestionsForm = "form-manage-questions";
        var _questionsTable = "table-session-questions";

        // Initialize toggle visibility for time_per_question field
        $(document).on('change', 'input[name="enable_time_per_question"]', function() {
            if ($(this).is(':checked')) {
                $('#time_per_question_container').show();
            } else {
                $('#time_per_question_container').hide();
            }
        });
        
        // Trigger change event on page load to set initial state
        $('input[name="enable_time_per_question"]').trigger('change');

        // Initialize DataTables
        if ($("#" + _table)[0]) {
            var table_tryout_session = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('tryout_session/ajax_get_all/') ?>",
                    type: "get"
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "tryout_title",
                    },
                    {
                        data: "name",
                    },
                    {
                        data: "session_order",
                    },
                    {
                        data: "duration_minutes",
                    },
                    {
                        data: "question_count",
                    },
                    {
                        data: "is_random",
                        render: function(data) {
                            return data == 1 ? 'Ya' : 'Tidak';
                        }
                    },
                    {
                        data: "scoring_method",
                        render: function(data) {
                            switch(data) {
                                case 'correct_incorrect':
                                    return 'Benar/Salah';
                                case 'points_per_question':
                                    return 'Poin per Soal';
                                default:
                                    return data;
                            }
                        }
                    },
                    {
                        data: "enable_time_per_question",
                        render: function(data, type, row) {
                            if (data == 1) {
                                return 'Ya';
                            } else {
                                return 'Tidak';
                            }
                        }
                    },
                    {
                        data: "description",
                        render: function(data) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: null,
                        className: "center",
                        defaultContent: '<div class="action">' +
                            '<a href="javascript:;" class="btn btn-sm btn-light btn-table-action action-edit" data-toggle="modal" data-target="#' + _modal + '"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;' +
                            '<a href="javascript:;" class="btn btn-sm btn-warning btn-table-action action-manage-questions" data-toggle="modal" data-target="#' + _manageQuestionsModal + '"><i class="zmdi zmdi-book"></i> Atur Soal</a>&nbsp;' +
                            '<a href="javascript:;" class="btn btn-sm btn-danger btn-table-action action-delete"><i class="zmdi zmdi-delete"></i> Hapus</a>' +
                            '</div>'
                    }
                ],
                autoWidth: !1,
                responsive: {
                    details: {
                        renderer: function(api, rowIdx, columns) {
                            var hideColumn = [];
                            var data = $.map(columns, function(col, i) {
                                return ($.inArray(col.columnIndex, hideColumn)) ?
                                    '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                                    '<td class="dt-details-td">' + col.title + ':' + '</td> ' +
                                    '<td class="dt-details-td">' + col.data + '</td>' +
                                    '</tr>' :
                                    '';
                            }).join('');

                            return data ? $('<table/>').append(data) : false;
                        },
                        type: "inline",
                        target: 'tr',
                    }
                },
                columnDefs: [{
                    className: 'desktop',
                    targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }, {
                    className: 'tablet',
                    targets: [0, 1, 2, 3, 4, 5]
                }, {
                    className: 'mobile',
                    targets: [0, 1]
                }, {
                    responsivePriority: 2,
                    targets: -1
                }],
                pageLength: 15,
                language: {
                    searchPlaceholder: "Cari...",
                    sProcessing: '<div style="text-align: center;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
                },
                sDom: '<"dataTables_ct"><"dataTables__top"fb>rt<"dataTables__bottom"ip><"clear">',
                buttons: [{
                    extend: "excelHtml5",
                    title: "Export Sesi Try Out"
                }, {
                    extend: "print",
                    title: "Export Sesi Try Out"
                }],
                initComplete: function(a, b) {
                    $(this).closest(".dataTables_wrapper").find(".dataTables__top").prepend(
                        '<div class="dataTables_buttons hidden-sm-down actions">' +
                        '<span class="actions__item zmdi zmdi-refresh" data-table-action="reload" title="Reload" />' +
                        '</div>'
                    );
                },
            });

            $(".dataTables_filter input[type=search]").focus(function() {
                $(this).closest(".dataTables_filter").addClass("dataTables_filter--toggled")
            });

            $(".dataTables_filter input[type=search]").blur(function() {
                $(this).closest(".dataTables_filter").removeClass("dataTables_filter--toggled")
            });

            $("body").on("click", "[data-table-action]", function(a) {
                a.preventDefault();
                var b = $(this).data("table-action");
                if ("reload" === b) {
                    $("#" + _table).DataTable().ajax.reload(null, false);
                };
            });
        };

        // Initialize multi-select for questions
        if ($('#question_select').length > 0) {
            $('#question_select').select2({
                width: '100%',
                placeholder: $('#question_select').attr('data-placeholder'),
                allowClear: true,
                ajax: {
                    url: "<?php echo base_url('tryout_session/ajax_get_questions_not_in_session'); ?>",
                    delay: 250,
                    type: 'GET', // Tambahkan tipe request
                    data: function(params) {
                        return {
                            session_id: $('#current_session_id').val(),
                            q: params.term,
                            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                        };
                    },
                    processResults: function(data) {
                        console.log("Data received:", data); // Log untuk debugging
                        
                        // Pastikan data adalah array
                        if (!$.isArray(data)) {
                            console.error("Expected an array but got:", typeof data, data);
                            return { results: [] };
                        }
                        
                        // Proses data yang diterima
                        const results = data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.text || item.question_text || String(item.id)
                            };
                        });
                        
                        console.log("Processed results:", results); // Log hasil pemrosesan
                        return { results: results };
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        console.error("Response text:", xhr.responseText);
                        notify("Terjadi kesalahan saat memuat soal: " + error, "danger");
                    }
                },
                // Tambahkan escapeMarkup untuk mencegah XSS dan memungkinkan HTML jika diperlukan
                escapeMarkup: function(markup) {
                    return markup;
                }
            });
        }

        // Toggle start order input based on radio selection
        $(document).on('change', 'input[name="ordering_method"]', function() {
            if ($(this).val() === 'sequential') {
                $('#start-order-input').show();
            } else {
                $('#start-order-input').hide();
            }
        });

        // Handle add
        $("#" + _section).on("click", "button." + _section + "-action-add", function(e) {
            e.preventDefault();
            resetForm();
        });

        // Handle edit
        $("#" + _table).on("click", "a.action-edit", function(e) {
            e.preventDefault();
            resetForm();
            var temp = table_tryout_session.row($(this).closest('tr')).data();

            _key = temp.id;

            $(`#${_form} .tryout_session-tryout_id`).val(temp.tryout_id).trigger('change');
            $(`#${_form} .tryout_session-name`).val(temp.name);
            $(`#${_form} .tryout_session-session_order`).val(temp.session_order);
            $(`#${_form} .tryout_session-duration_minutes`).val(temp.duration_minutes);
            $(`#${_form} .tryout_session-question_count`).val(temp.question_count);
            $(`#${_form} .tryout_session-description`).val(temp.description);
            
            // Set is_random checkbox
            if(temp.is_random == 1) {
                $(`#${_form} .tryout_session-is_random`).prop('checked', true);
            } else {
                $(`#${_form} .tryout_session-is_random`).prop('checked', false);
            }
            
            // Set scoring method radio button
            $(`#${_form} input[name="scoring_method"][value="${temp.scoring_method}"]`).prop('checked', true);
            
            // Set enable_time_per_question checkbox
            if(temp.enable_time_per_question == 1) {
                $(`#${_form} .tryout_session-enable_time_per_question`).prop('checked', true);
            } else {
                $(`#${_form} .tryout_session-enable_time_per_question`).prop('checked', false);
            }
            
            // Set time_per_question value
            $(`#${_form} .tryout_session-time_per_question`).val(temp.time_per_question);
            
            // Update toggle visibility based on stored value
            if(temp.enable_time_per_question == 1) {
                $('#time_per_question_container').show();
            } else {
                $('#time_per_question_container').hide();
            }
        });

        // Handle manage questions
        $("#" + _table).on("click", "a.action-manage-questions", function(e) {
            e.preventDefault();
            
            var temp = table_tryout_session.row($(this).closest('tr')).data();
            
            // Set current session ID
            $("#current_session_id").val(temp.id);
            $("#session_name_label").text(temp.name + " (" + temp.tryout_title + ")");
            
            // Load existing questions in the session
            loadSessionQuestions(temp.id);
            
            // Clear the question selection
            $("#question_select").val(null).trigger('change');
        });

        // Load questions in session
        function loadSessionQuestions(sessionId) {
			// console.log("Loading questions for session ID:", sessionId); // Debug log
            if ($("#" + _questionsTable).length) {
                // Destroy existing DataTable instance if exists
                if ($.fn.DataTable.isDataTable("#" + _questionsTable)) {
                    $("#" + _questionsTable).DataTable().destroy();
                }
                
                // Initialize DataTable for session questions
                var table_session_questions = $("#" + _questionsTable).DataTable({
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: "<?php echo base_url('tryout_session/ajax_get_questions_by_session/') ?>",
                        type: "get",
                        data: {
                            session_id: sessionId,
                            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                        },
                        dataSrc: ''
                    },
                    columns: [{
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: "question_text",
                            render: function(data) {
                                // Truncate question text if too long
                                return data ? (data.length > 100 ? data.substring(0, 100) + '...' : data) : '-';
                            }
                        },
                        // {
                        //     data: "question_order"
                        // },
                        {
                            data: "points",
                            render: function(data, type, row) {
                                // Create input field for points if scoring method is 'points_per_question'
                                var sessionRow = table_tryout_session.row(function(idx, data, node) {
                                    return data.id == $("#current_session_id").val();
                                }).data();
                                
                                if (sessionRow && sessionRow.scoring_method === 'points_per_question') {
                                    return '<input type="number" step="0.01" min="0" class="form-control input-points" data-session-id="' + $("#current_session_id").val() + '" data-question-id="' + row.question_id + '" value="' + (data || 1.00) + '" style="width: 150px;">';
                                } else {
                                    return data || 1.00;
                                }
                            }
                        },
                        {
                            data: "time_limit",
                            render: function(data, type, row) {
                                // Create input field for time limit
                                return '<input type="number" min="0" class="form-control input-time-limit" data-session-id="' + $("#current_session_id").val() + '" data-question-id="' + row.question_id + '" value="' + (data || 0) + '" style="width: 120px;" placeholder="Detik (0=tanpa batas)"> <small class="text-muted">detik</small>';
                            }
                        },
                        {
                            className: "center",
                            defaultContent: '<div class="action">' +
                                '<a href="javascript:;" class="btn btn-sm btn-danger btn-table-action action-delete-question"><i class="zmdi zmdi-delete"></i></a>' +
                                '</div>'
                        }
                    ],
                    autoWidth: !1,
                    pageLength: 10,
                    language: {
                        searchPlaceholder: "Cari...",
                        sProcessing: '<div style="text-align: center;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
                    },
                    sDom: '<"dataTables_ct"><"dataTables__top"f>rt<"dataTables__bottom"ip><"clear">',
                });
                
                // Handle points input change
                $('#' + _questionsTable).on('change', '.input-points', function() {
                    var sessionId = $(this).data('session-id');
                    var questionId = $(this).data('question-id');
                    var points = parseFloat($(this).val()) || 1.00;
                    
                    $.ajax({
                        type: "post",
                        url: "<?php echo base_url('tryout_session/ajax_update_question_points/') ?>",
                        data: {
                            session_id: sessionId,
                            question_id: questionId,
                            points: points,
                            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status) {
                                notify(response.data, "success");
                            } else {
                                notify(response.data, "danger");
                            }
                        },
                        error: function() {
                            notify("Terjadi kesalahan saat memperbarui poin soal", "danger");
                        }
                    });
                });
                
                // Handle time limit input change
                $('#' + _questionsTable).on('change', '.input-time-limit', function() {
                    var sessionId = $(this).data('session-id');
                    var questionId = $(this).data('question-id');
                    var timeLimit = parseInt($(this).val()) || 0;
                    
                    $.ajax({
                        type: "post",
                        url: "<?php echo base_url('tryout_session/ajax_update_question_time_limit/') ?>",
                        data: {
                            session_id: sessionId,
                            question_id: questionId,
                            time_limit: timeLimit,
                            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status) {
                                notify(response.data, "success");
                            } else {
                                notify(response.data, "danger");
                            }
                        },
                        error: function() {
                            notify("Terjadi kesalahan saat memperbarui batas waktu soal", "danger");
                        }
                    });
                });
                
                // Handle delete question from session
                $("#" + _questionsTable).on("click", "a.action-delete-question", function(e) {
                    e.preventDefault();
                    var row_data = table_session_questions.row($(this).parents('tr')).data();
                    
                    swal({
                        title: "Anda akan menghapus soal dari sesi ini, lanjutkan?",
                        text: "Soal akan dihapus dari sesi ini!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: "Ya",
                        cancelButtonText: "Tidak",
                        closeOnConfirm: false
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "post",
                                url: "<?php echo base_url('tryout_session/ajax_remove_question_from_session/') ?>",
                                data: {
                                    session_id: sessionId,
                                    question_id: row_data.question_id,
                                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                                },
                                dataType: "json",
                                success: function(response) {
                                    if (response.status) {
                                        loadSessionQuestions(sessionId); // Reload the table
                                        notify(response.data, "success");
                                    } else {
                                        notify(response.data, "danger");
                                    }
                                },
                                error: function() {
                                    notify("Terjadi kesalahan saat menghapus soal", "danger");
                                }
                            });
                        }
                    });
                });
            }
        }

        // Handle save questions to session
        $("#" + _manageQuestionsModal + " .manage-questions-action-save").on("click", function(e) {
            e.preventDefault();
            
            var sessionId = $("#current_session_id").val();
            var questionIds = $("#question_select").val();
            var orderingMethod = $('input[name="ordering_method"]:checked').val();
            var startOrder = parseInt($('input[name="start_order"]').val()) || 1;
            
            if (!sessionId) {
                notify('Sesi tidak valid.', 'danger');
                return;
            }
            
            if (!questionIds || questionIds.length === 0) {
                notify('Silakan pilih setidaknya satu soal.', 'danger');
                return;
            }
            
            $.ajax({
                type: "post",
                url: "<?php echo base_url('tryout_session/ajax_add_questions_to_session/') ?>",
                data: {
                    session_id: sessionId,
                    question_ids: questionIds,
                    ordering_method: orderingMethod,
                    start_order: startOrder,
                    default_points: 1.00, // Add default points value
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        // Reset form
                        $("#question_select").val(null).trigger('change');
                        // Reload the questions table
                        loadSessionQuestions(sessionId);
                        notify(response.data, "success");
                    } else {
                        notify(response.data, "danger");
                    }
                },
                error: function(xhr, status, error) {
                    notify('Terjadi kesalahan saat menyimpan soal: ' + xhr.responseText, "danger");
                }
            });
        });

        // Handle save
        $("#" + _modal + " ." + _section + "-action-save").on("click", function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "<?php echo base_url('tryout_session/ajax_save/') ?>" + _key,
                data: $("#" + _form).serialize(),
                success: function(response) {
                    var response = JSON.parse(response);
                    if (response.status === true) {
                        resetForm();
                        $("#" + _modal).modal("hide");
                        $("#" + _table).DataTable().ajax.reload(null, false);
                        notify(response.data, "success");
                    } else {
                        notify(response.data, "danger");
                    };
                }
            });
        });

        // Handle delete
        $("#" + _table).on("click", "a.action-delete", function(e) {
            e.preventDefault();
            var temp = table_tryout_session.row($(this).closest('tr')).data();

            swal({
                title: "Anda akan menghapus data, lanjutkan?",
                text: "Setelah dihapus, data tidak dapat dikembalikan lagi!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
                closeOnConfirm: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "delete",
                        url: "<?php echo base_url('tryout_session/ajax_delete/') ?>" + temp.id,
                        dataType: "json",
                        success: function(response) {
                            if (response.status) {
                                $("#" + _table).DataTable().ajax.reload(null, false);
                                notify(response.data, "success");
                            } else {
                                notify(response.data, "danger");
                            };
                        }
                    });
                };
            });
        });

        resetForm = () => {
            _key = "";
            $(`#${_form}`).trigger("reset");
            $(`#${_form} .tryout_session-tryout_id`).val('').trigger('change');
            $(`#${_form} .tryout_session-is_random`).prop('checked', false);
            $(`#${_form} input[name="scoring_method"][value="correct_incorrect"]`).prop('checked', true);
            $(`#${_form} .tryout_session-enable_time_per_question`).prop('checked', false);
            $('#time_per_question_container').hide();
            $(`#${_form} .tryout_session-time_per_question`).val(60);
        };

    });
</script>
