<script type="text/javascript">
	
$(document).ready(function() {
    // Handle "Tambah Banyak Soal" button click
    $(".tryout_question-action-add-many").on("click", function(e) {
        e.preventDefault();
        // Trigger the same add functionality
        $(".tryout_question-action-add").click();
    });
});

	$(document).ready(function() {
    // Hide/show start order input based on radio selection
    $('input[name="ordering_method"]').on('change', function() {
        if ($(this).val() === 'sequential') {
            $('#start-order-input').show();
        } else {
            $('#start-order-input').hide();
        }
    });
    
    // Initialize multi-select
    $('.select2-multiple').select2({
        width: '100%',
        placeholder: $(this).attr('data-placeholder'),
        allowClear: true
    });
});

    $(document).ready(function() {

        var _key = "";
        var _section = "tryout_question";
        var _table = "table-tryout_question";
        var _modal = "modal-form-tryout_question";
        var _form = "form-tryout_question";
        var selectedRows = [];

        // Initialize DataTables
        if ($("#" + _table)[0]) {
            var table_tryout_question = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('tryout_question/ajax_get_all/') ?>",
                    type: "get"
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false
                    },
                    {
                        data: "tryout_title",
                    },
                    {
                        data: "session_name",
                    },
                    {
                        data: "session_order",
                    },
                    {
                        data: "question_text",
                        render: function(data) {
                            // Potong teks soal jika terlalu panjang
                            return data ? (data.length > 50 ? data.substr(0, 50) + '...' : data) : '-';
                        }
                    },
                    {
                        data: "subject_name",
                        render: function(data) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: "question_order",
                    },
                    {
                        data: "points",
                        render: function(data) {
                            return data ? parseFloat(data).toFixed(2) : '0.00';
                        }
                    },
                    {
                        data: null,
                        className: "center",
                        defaultContent: '<div class="action">' +
                            '<label class="custom-control custom-checkbox checkbox--charlie m-0">' +
                            '<input type="checkbox" class="custom-control-input tryout-question-checkbox" value="">' +
                            '<span class="custom-control-indicator"></span>' +
                            '</label>' +
                            '<a href="javascript:;" class="btn btn-sm btn-light btn-table-action action-edit ml-1" data-toggle="modal" data-target="#' + _modal + '"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;' +
                            '<a href="javascript:;" class="btn btn-sm btn-danger btn-table-action action-delete ml-1"><i class="zmdi zmdi-delete"></i> Hapus</a>' +
                            '</div>',
                        orderable: false
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
                    targets: [0, 1, 2, 3, 4, 5, 6, 7]
                }, {
                    className: 'tablet',
                    targets: [0, 1, 2, 3, 4]
                }, {
                    className: 'mobile',
                    targets: [0, 1, 2]
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
                    title: "Export Soal Try Out"
                }, {
                    extend: "print",
                    title: "Export Soal Try Out"
                }],
                initComplete: function(a, b) {
                    $(this).closest(".dataTables_wrapper").find(".dataTables__top").prepend(
                        '<div class="dataTables_buttons hidden-sm-down actions">' +
                        '<span class="actions__item zmdi zmdi-refresh" data-table-action="reload" title="Reload" />' +
                        '<button class="btn btn--raised btn-danger btn--icon-text ml-1" id="bulk-delete-btn" style="display:none;">' +
                        '<i class="zmdi zmdi-delete"></i> Hapus Terpilih' +
                        '</button>' +
                        '</div>'
                    );
                },
            });

            // Event handler for checkboxes
            $("#" + _table).on('click', '.tryout-question-checkbox', function() {
                var row = $(this).closest('tr');
                var data = table_tryout_question.row(row).data();
                
                if(this.checked) {
                    selectedRows.push(data.id);
                } else {
                    var index = selectedRows.indexOf(data.id);
                    if (index !== -1) {
                        selectedRows.splice(index, 1);
                    }
                }
                
                // Update bulk delete button visibility
                if(selectedRows.length > 0) {
                    $('#bulk-delete-btn').show();
                } else {
                    $('#bulk-delete-btn').hide();
                }
            });
            
            // Bulk delete button handler
            $('#bulk-delete-btn').on('click', function() {
                if(selectedRows.length === 0) {
                    notify('Tidak ada data yang dipilih untuk dihapus.', 'warning');
                    return;
                }
                
                swal({
                    title: "Anda akan menghapus " + selectedRows.length + " data, lanjutkan?",
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
                            type: "post",
                            url: "<?php echo base_url('tryout_question/ajax_bulk_delete') ?>",
                            data: {
                                ids: selectedRows,
                                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.status) {
                                    selectedRows = [];
                                    $('#bulk-delete-btn').hide();
                                    $("#" + _table).DataTable().ajax.reload(null, false);
                                    notify(response.data, "success");
                                } else {
                                    notify(response.data, "danger");
                                }
                            },
                            error: function() {
                                notify("Terjadi kesalahan saat menghapus data", "danger");
                            }
                        });
                    }
                });
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

        // Handle add
        $("#" + _section).on("click", "button." + _section + "-action-add", function(e) {
            e.preventDefault();
            resetForm();
        });

        // Handle edit - for single item editing
        $("#" + _table).on("click", "a.action-edit", function(e) {
            e.preventDefault();
            resetForm();
            var temp = table_tryout_question.row($(this).closest('tr')).data();

            _key = temp.id;

            $(`#${_form} .tryout_question-tryout_session_id`).val(temp.tryout_session_id).trigger('change');
            // For editing single item, we'll use single select
            $(`#${_form} .tryout_question-question_ids`).val([temp.question_id]).trigger('change');
            $(`#${_form} .tryout_question-question_order`).val(temp.question_order);
            
            // Show single editing fields
            $(`#${_form} [name='ordering_method']`).prop('disabled', true);
            $('#start-order-input').hide();
        });

        // Handle save
        $("#" + _modal + " ." + _section + "-action-save").on("click", function(e) {
            e.preventDefault();
            
            // Validate form
            var tryoutSessionId = $(`#${_form} [name='tryout_session_id']`).val();
            var questionId = $(`#${_form} [name='question_id']`).val();
            var points = $(`#${_form} [name='points']`).val();
            
            if (!tryoutSessionId) {
                notify('Silakan pilih sesi try out terlebih dahulu.', 'danger');
                return;
            }
            
            if (!questionId) {
                notify('Silakan pilih sebuah soal.', 'danger');
                return;
            }
            
            if (!points || isNaN(points) || parseFloat(points) <= 0) {
                notify('Silakan masukkan nilai poin yang valid (angka positif).', 'danger');
                return;
            }
            
            // Prepare form data for submission
            var formData = $("#" + _form).serialize();
            
            $.ajax({
                type: "post",
                url: "<?php echo base_url('tryout_question/ajax_save/') ?>" + _key,
                data: formData,
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
                },
                error: function(xhr, status, error) {
                    notify('Terjadi kesalahan saat menyimpan data: ' + xhr.responseText, "danger");
                }
            });
        });

        // Handle delete
        $("#" + _table).on("click", "a.action-delete", function(e) {
            e.preventDefault();
            var temp = table_tryout_question.row($(this).closest('tr')).data();

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
                        url: "<?php echo base_url('tryout_question/ajax_delete/') ?>" + temp.id,
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
            $(`#${_form} .tryout_question-tryout_session_id`).val('').trigger('change');
            $(`#${_form} .tryout_question-question_id`).val('').trigger('change');
            $(`#${_form} .tryout_question-points`).val('1.00');
        };

    });
</script>
