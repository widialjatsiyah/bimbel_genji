<script type="text/javascript">
    $(document).ready(function() {

        var _key = "";
        var _section = "question";
        var _table = "table-question";
        var _modal = "modal-form-question";
        var _form = "form-question";

        // Initialize DataTables
        if ($("#" + _table)[0]) {
            var table_question = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('question/ajax_get_all/') ?>",
                    type: "get"
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "question_text",
                        render: function(data) {
                            // Potong teks soal jika terlalu panjang
                            return data.length > 50 ? data.substr(0, 50) + '...' : data;
                        }
                    },
                    {
                        data: "subject_name",
                    },
                    {
                        data: "chapter_name",
                        render: function(data) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: "topic_name",
                        render: function(data) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: "difficulty",
                        render: function(data) {
                            if (data == 'mudah') return '<span class="badge badge-success">Mudah</span>';
                            if (data == 'sedang') return '<span class="badge badge-warning">Sedang</span>';
                            if (data == 'sulit') return '<span class="badge badge-danger">Sulit</span>';
                            return data;
                        }
                    },
                    {
                        data: "curriculum",
                    },
                    {
                        data: "correct_option",
                    },
                    {
                        data: "is_active",
                        render: function(data) {
                            return data == '1' ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Tidak Aktif</span>';
                        }
                    },
                    {
                        data: "created_at",
                        render: function(data) {
                            return moment(data).format('Y-m-d H:mm');
                        }
                    },
                    {
                        data: null,
                        className: "center",
                        defaultContent: '<div class="action">' +
                            '<a href="javascript:;" class="btn btn-sm btn-light btn-table-action action-edit" data-toggle="modal" data-target="#' + _modal + '"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;' +
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
                    targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
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
                    title: "Export Soal"
                }, {
                    extend: "print",
                    title: "Export Soal"
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

        // Handle add
        $("#" + _section).on("click", "button." + _section + "-action-add", function(e) {
            e.preventDefault();
            resetForm();
        });

        // Handle edit
        $("#" + _table).on("click", "a.action-edit", function(e) {
            e.preventDefault();
            resetForm();
            var temp = table_question.row($(this).closest('tr')).data();

            _key = temp.id;

            // Set nilai form
            $(`#${_form} .question-subject_id`).val(temp.subject_id).trigger('change');

            // Karena subject berubah, kita perlu menunggu chapter terload dulu sebelum set nilainya
            // Gunakan setTimeout atau trigger manual setelah ajax selesai
            setTimeout(function() {
                $(`#${_form} .question-chapter_id`).val(temp.chapter_id).trigger('change');
            }, 500);

            setTimeout(function() {
                $(`#${_form} .question-topic_id`).val(temp.topic_id).trigger('change');
            }, 800);

            $(`#${_form} .question-difficulty`).val(temp.difficulty);
            $(`#${_form} .question-curriculum`).val(temp.curriculum);
            $(`#${_form} .question-question_text`).val(temp.question_text);
            $(`#${_form} .question-option_a`).val(temp.option_a);
            $(`#${_form} .question-option_b`).val(temp.option_b);
            $(`#${_form} .question-option_c`).val(temp.option_c);
            $(`#${_form} .question-option_d`).val(temp.option_d);
            $(`#${_form} .question-option_e`).val(temp.option_e);
            $(`#${_form} .question-correct_option`).val(temp.correct_option);
            $(`#${_form} .question-explanation`).val(temp.explanation);
            $(`#${_form} .question-video_explanation_url`).val(temp.video_explanation_url);
        });

        // Handle save
        $("#" + _modal + " ." + _section + "-action-save").on("click", function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "<?php echo base_url('question/ajax_save/') ?>" + _key,
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
            var temp = table_question.row($(this).closest('tr')).data();

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
                        url: "<?php echo base_url('question/ajax_delete/') ?>" + temp.id,
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
            $(`#${_form} .question-subject_id`).val('').trigger('change');
            $(`#${_form} .question-chapter_id`).empty().append('<option value=""></option>').trigger('change');
            $(`#${_form} .question-topic_id`).empty().append('<option value=""></option>').trigger('change');
        };

    });
</script>
