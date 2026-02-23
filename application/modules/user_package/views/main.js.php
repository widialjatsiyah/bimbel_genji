<script type="text/javascript">
    $(document).ready(function() {

        var _key = "";
        var _section = "user_package";
        var _table = "table-user_package";
        var _modal = "modal-form-user_package";
        var _form = "form-user_package";

        // Initialize DataTables
        if ($("#" + _table)[0]) {
            var table_user_package = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('user_package/ajax_get_all/') ?>",
                    type: "get"
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
                        data: "package_name",
                    },
                    {
                        data: "start_date",
                    },
                    {
                        data: "end_date",
                    },
                    {
                        data: "status",
                        render: function(data) {
                            var badge = {
                                'active': 'bg-success',
                                'expired': 'bg-secondary',
                                'cancelled': 'bg-danger'
                            };
                            return '<span class="badge ' + (badge[data] || 'bg-info') + '">' + data + '</span>';
                        }
                    },
                    {
                        data: "payment_status",
                        render: function(data) {
                            var badge = {
                                'paid': 'bg-success',
                                'pending': 'bg-warning',
                                'failed': 'bg-danger'
                            };
                            return '<span class="badge ' + (badge[data] || 'bg-info') + '">' + data + '</span>';
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
                    targets: [0, 1, 2, 3, 4, 5, 6, 7]
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
                    title: "Export Paket Pengguna"
                }, {
                    extend: "print",
                    title: "Export Paket Pengguna"
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
            var temp = table_user_package.row($(this).closest('tr')).data();

            _key = temp.id;

            $(`#${_form} .user_package-user_id`).val(temp.user_id).trigger('change');
            $(`#${_form} .user_package-package_id`).val(temp.package_id).trigger('change');
            $(`#${_form} .user_package-start_date`).val(temp.start_date);
            $(`#${_form} .user_package-end_date`).val(temp.end_date);
            $(`#${_form} .user_package-status`).val(temp.status);
            $(`#${_form} .user_package-payment_status`).val(temp.payment_status);
        });

        // Handle save
        $("#" + _modal + " ." + _section + "-action-save").on("click", function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "<?php echo base_url('user_package/ajax_save/') ?>" + _key,
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
            var temp = table_user_package.row($(this).closest('tr')).data();

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
                        url: "<?php echo base_url('user_package/ajax_delete/') ?>" + temp.id,
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
            $(`#${_form} .user_package-user_id`).val('').trigger('change');
            $(`#${_form} .user_package-package_id`).val('').trigger('change');
            $(`#${_form} .user_package-status`).val('active');
            $(`#${_form} .user_package-payment_status`).val('pending');
        };

    });
</script>
