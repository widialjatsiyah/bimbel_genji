<script type="text/javascript">
    $(document).ready(function() {

        var _table = "table-my-progress";

        // Initialize DataTables
        if ($("#" + _table)[0]) {
            var table_my_progress = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('my_progress/ajax_get_all/') ?>",
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
                        data: "status",
                        render: function(data) {
                            var badge = {
                                'not_started': 'secondary',
                                'in_progress': 'warning',
                                'completed': 'success'
                            };
                            return '<span class="badge bg-' + (badge[data] || 'secondary') + '">' + data + '</span>';
                        }
                    },
                    {
                        data: "progress_percent",
                        render: function(data) {
                            return '<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' + data + '%;">' + data + '%</div></div>';
                        }
                    },
                    {
                        data: "last_accessed",
                        render: function(data) {
                            return data ? moment(data).format('DD/MM/YYYY HH:mm') : '-';
                        }
                    },
                    {
                        data: "completed_at",
                        render: function(data) {
                            return data ? moment(data).format('DD/MM/YYYY HH:mm') : '-';
                        }
                    },
                    {
                        data: null,
                        className: "center",
                        render: function(data, type, row) {
                            return '<a href="<?= base_url('material/view/') ?>' + row.material_id + '" class="btn btn-sm btn-primary" target="_blank">Lanjutkan</a>';
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
                    targets: [0, 1, 2, 3, 4, 5, 6, 7]
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
                    title: "Export Progres Belajar"
                }, {
                    extend: "print",
                    title: "Export Progres Belajar"
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
    });
</script>
