<script type="text/javascript">
    $(document).ready(function() {

        var _table = "table-myrecommendations";

        // Initialize DataTables
        if ($("#" + _table)[0]) {
            var table_myrecommendations = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('myrecommendations/ajax_get_all/') ?>",
                    type: "get"
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "recommendation_text",
                    },
                    {
                        data: "type",
                        render: function(data) {
                            var labels = {
                                'remedial': 'Remedial',
                                'latihan': 'Latihan',
                                'motivasi': 'Motivasi',
                                'spiritual': 'Spiritual'
                            };
                            return labels[data] || data;
                        }
                    },
                    {
                        data: "is_read",
                        render: function(data) {
                            return data == '1' ? 
                                '<span class="badge bg-success">Sudah Dibaca</span>' : 
                                '<span class="badge bg-warning">Belum Dibaca</span>';
                        }
                    },
                    {
                        data: "created_at",
                        render: function(data) {
                            return moment(data).format('DD MMM YYYY HH:mm');
                        }
                    },
                    {
                        data: null,
                        className: "center",
                        render: function(data, type, row) {
                            if (row.is_read == '1') {
                                return '<span class="text-muted">-</span>';
                            } else {
                                return '<button class="btn btn-sm btn-success mark-read" data-id="' + row.id + '">Tandai Dibaca</button>';
                            }
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
                    targets: [0, 1, 2, 3]
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
                    title: "Export Rekomendasi"
                }, {
                    extend: "print",
                    title: "Export Rekomendasi"
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

        // Mark single read
        $(document).on('click', '.mark-read', function() {
            var id = $(this).data('id');
            var btn = $(this);
            $.ajax({
                url: '<?= base_url('myrecommendations/ajax_mark_read/') ?>' + id,
                type: 'post',
                success: function(res) {
                    if (res.status) {
                        $("#" + _table).DataTable().ajax.reload(null, false);
                        notify('Rekomendasi ditandai dibaca', 'success');
                    }
                }
            });
        });

        // Mark all read
        $('.mark-all-read').click(function() {
            $.ajax({
                url: '<?= base_url('myrecommendations/ajax_mark_all_read') ?>',
                type: 'post',
                success: function(res) {
                    if (res.status) {
                        $("#" + _table).DataTable().ajax.reload(null, false);
                        notify('Semua rekomendasi ditandai dibaca', 'success');
                    }
                }
            });
        });
    });
</script>
