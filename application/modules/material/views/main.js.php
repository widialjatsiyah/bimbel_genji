<script type="text/javascript">
    $(document).ready(function() {

        var _key = "";
        var _section = "material";
        var _table = "table-material";
        var _modal = "modal-form-material";
        var _form = "form-material";

        // Initialize DataTables
        if ($("#" + _table)[0]) {
            var table_material = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('material/ajax_get_all/') ?>",
                    type: "get"
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "title",
                    },
                    {
                        data: "type",
                        render: function(data) {
                            var icons = {
                                'video': '🎥',
                                'pdf': '📄',
                                'modul': '📘'
                            };
                            return (icons[data] || '') + ' ' + data;
                        }
                    },
                    {
                        data: "url",
                        render: function(data) {
                            return '<a href="' + data + '" target="_blank">Link</a>';
                        }
                    },
                    {
                        data: "subject_name",
                        render: function(data) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: "chapter_name",
                        render: function(data) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: "duration_seconds",
                        render: function(data) {
                            if (!data) return '-';
                            var hours = Math.floor(data / 3600);
                            var minutes = Math.floor((data % 3600) / 60);
                            var seconds = data % 60;
                            return (hours ? hours + 'j ' : '') + (minutes ? minutes + 'm ' : '') + seconds + 'd';
                        }
                    },
                    {
                        data: "is_active",
                        render: function(data) {
                            return data == '1' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Tidak</span>';
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
                    targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                }, {
                    className: 'tablet',
                    targets: [0, 1, 2, 3, 4]
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
                    title: "Export Materi"
                }, {
                    extend: "print",
                    title: "Export Materi"
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

        // Handle add
        $("#" + _section).on("click", "button." + _section + "-action-add", function(e) {
            e.preventDefault();
            resetForm();
        });

        // Handle edit
        $("#" + _table).on("click", "a.action-edit", function(e) {
            e.preventDefault();
            resetForm();
            var temp = table_material.row($(this).closest('tr')).data();

            _key = temp.id;

            $(`#${_form} .material-title`).val(temp.title);
            $(`#${_form} .material-type`).val(temp.type);
            $(`#${_form} .material-url`).val(temp.url);
            $(`#${_form} .material-subject_id`).val(temp.subject_id).trigger('change');
            $(`#${_form} .material-chapter_id`).val(temp.chapter_id).trigger('change');
            $(`#${_form} .material-description`).val(temp.description);
            $(`#${_form} .material-duration_seconds`).val(temp.duration_seconds);
            $(`#${_form} .material-is_active`).prop('checked', temp.is_active == '1');
        });

        // Handle save
        $("#" + _modal + " ." + _section + "-action-save").on("click", function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "<?php echo base_url('material/ajax_save/') ?>" + _key,
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
            var temp = table_material.row($(this).closest('tr')).data();

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
                        url: "<?php echo base_url('material/ajax_delete/') ?>" + temp.id,
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
            $(`#${_form} .material-subject_id`).val('').trigger('change');
            $(`#${_form} .material-chapter_id`).val('').trigger('change');
            $(`#${_form} .material-is_active`).prop('checked', true);
        };

        // Optional: dynamic chapter based on subject
        $('.material-subject_id').change(function() {
            var subject_id = $(this).val();
            if (subject_id) {
                $.get('<?= base_url("material/ajax_get_chapters") ?>', { subject_id: subject_id }, function(chapters) {
                    var $chapter = $('.material-chapter_id');
                    $chapter.empty().append('<option value=""></option>');
                    $.each(chapters, function(i, ch) {
                        $chapter.append('<option value="' + ch.id + '">' + ch.name + '</option>');
                    });
                    $chapter.trigger('change');
                }, 'json');
            } else {
                $('.material-chapter_id').empty().append('<option value=""></option>').trigger('change');
            }
        });
    });
</script>
