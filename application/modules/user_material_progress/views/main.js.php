<script type="text/javascript">
    $(document).ready(function() {

        var _section = "user_material_progress";
        var _table = "table-user_material_progress";
        var _tableInstance;

        function loadTable(userFilter = '') {
            if (_tableInstance) {
                _tableInstance.ajax.reload();
                return;
            }

            _tableInstance = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('user_material_progress/ajax_get_all/') ?>",
                    type: "get",
                    data: function(d) {
                        d.user_id = userFilter;
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "user_name",
                    },
                    {
                        data: "material_title",
                    },
                    {
                        data: "material_type",
                    },
                    {
                        data: "status",
                        render: function(data) {
                            var badge = {
                                'not_started': 'bg-secondary',
                                'in_progress': 'bg-warning',
                                'completed': 'bg-success'
                            };
                            return '<span class="badge ' + (badge[data] || 'bg-info') + '">' + data + '</span>';
                        }
                    },
                    {
                        data: "progress_percent",
                        render: function(data) {
                            return data + '%';
                        }
                    },
                    {
                        data: "last_accessed",
                        render: function(data) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: "completed_at",
                        render: function(data) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: null,
                        className: "center",
                        defaultContent: '<a href="javascript:;" class="btn btn-sm btn-danger btn-table-action action-delete"><i class="zmdi zmdi-delete"></i> Hapus</a>'
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
                    title: "Export Progres Siswa"
                }, {
                    extend: "print",
                    title: "Export Progres Siswa"
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
        }

        loadTable();

        // Filter
        $('#btn-filter').click(function() {
            var userId = $('#filter-user').val();
            if (_tableInstance) {
                _tableInstance.ajax.url("<?php echo base_url('user_material_progress/ajax_get_all/') ?>?user_id=" + userId).load();
            } else {
                loadTable(userId);
            }
        });

        // Handle delete
        $("#" + _table).on("click", "a.action-delete", function(e) {
            e.preventDefault();
            var data = _tableInstance.row($(this).closest('tr')).data();

            swal({
                title: "Anda akan menghapus data progres, lanjutkan?",
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
                        url: "<?php echo base_url('user_material_progress/ajax_delete/') ?>" + data.id,
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

    });
</script>
