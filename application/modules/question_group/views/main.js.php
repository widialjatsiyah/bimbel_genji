<script type="text/javascript">
    $(document).ready(function() {

        var _key = "";
        var _section = "question_group";
        var _table = "table-question-group";
        var _modal = "modal-form-question-group";
        var _form = "form-question-group";

        // Initialize DataTables
        if ($("#" + _table)[0]) {
            var table_question_group = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?= base_url('question_group/ajax_get_all/') ?>",
                    type: "get"
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "id",
                        render: function(data) {
                            return data;
                        }
                    },
                    {
                        data: "question_count",
                        render: function(data) {
                            return data;
                        }
                    },
                    {
                        data: "main_question",
                        render: function(data) {
                            return data ? data : '-';
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
                        render: function(data, type, row) {
                            return '<div class="action">' +
                                '<a href="<?= base_url("question_group/form/") ?>' + row.id + '" class="btn btn-sm btn-light btn-table-action" target="_blank"><i class="zmdi zmdi-edit"></i> Edit</a>&nbsp;' +
                                '<a href="javascript:;" class="btn btn-sm btn-danger btn-table-action action-delete"><i class="zmdi zmdi-delete"></i> Hapus</a>' +
                                '</div>';
                        }
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
                    targets: [0, 1, 2, 3, 4, 5]
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
                    title: "Export Grup Soal",
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                }, {
                    extend: "print",
                    title: "Export Grup Soal",
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
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

        // Handle delete
        $("#" + _table).on("click", "a.action-delete", function(e) {
            e.preventDefault();
            var temp = table_question_group.row($(this).closest('tr')).data();

            swal({
                title: "Anda akan menghapus grup soal, lanjutkan?",
                text: "Soal dalam grup akan dilepas dari grup, bukan dihapus!",
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
                        url: "<?= base_url('question_group/ajax_remove_from_group/') ?>",
                        data: {
                            group_id: temp.id,
                            question_ids: [] // Empty array means remove all from this group
                        },
                        success: function(response) {
                            var response = JSON.parse(response);
                            if (response.status) {
                                $("#" + _table).DataTable().ajax.reload(null, false);
                                notify(response.data, "success");
                            } else {
                                notify(response.data, "danger");
                            };
                        },
                        error: function(xhr, status, error) {
                            notify("Terjadi kesalahan saat menghapus grup", "danger");
                        }
                    });
                };
            });
        });

        resetForm = () => {
            _key = "";
            $(`#${_form}`).trigger("reset");
        };

    });

</script>