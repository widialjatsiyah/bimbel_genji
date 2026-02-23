<script type="text/javascript">
    $(document).ready(function() {

        var _key = "";
        var _section = "team";
        var _table = "table-team";
        var _modal = "modal-form-team";
        var _form = "form-team";

        if ($("#" + _table)[0]) {
            var table_team = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('team/ajax_get_all/') ?>",
                    type: "get"
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "photo",
                        render: function(data) {
                            if (data) {
                                return '<img src="<?= base_url() ?>' + data + '" style="max-height:50px; max-width:50px; border-radius:50%;">';
                            }
                            return '-';
                        }
                    },
                    {
                        data: "name",
                    },
                    {
                        data: "position",
                        render: function(data) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: "order_num",
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
                    targets: [0, 1, 2, 3, 4, 5, 6]
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
                    title: "Export Tim"
                }, {
                    extend: "print",
                    title: "Export Tim"
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

        $("#" + _section).on("click", "button." + _section + "-action-add", function(e) {
            e.preventDefault();
            resetForm();
        });

        $("#" + _table).on("click", "a.action-edit", function(e) {
            e.preventDefault();
            resetForm();
            var temp = table_team.row($(this).closest('tr')).data();

            _key = temp.id;

            $(`#${_form} .team-name`).val(temp.name);
            $(`#${_form} .team-position`).val(temp.position);
            $(`#${_form} .team-bio`).val(temp.bio);
            $(`#${_form} .team-social_facebook`).val(temp.social_facebook);
            $(`#${_form} .team-social_twitter`).val(temp.social_twitter);
            $(`#${_form} .team-social_instagram`).val(temp.social_instagram);
            $(`#${_form} .team-social_linkedin`).val(temp.social_linkedin);
            $(`#${_form} .team-order_num`).val(temp.order_num);
            $(`#${_form} .team-is_active`).prop('checked', temp.is_active == '1');
        });

        $("#" + _modal + " ." + _section + "-action-save").on("click", function(e) {
            e.preventDefault();
            var formData = new FormData($("#" + _form)[0]);
            $.ajax({
                type: "post",
                url: "<?php echo base_url('team/ajax_save/') ?>" + _key,
                data: formData,
                processData: false,
                contentType: false,
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

        $("#" + _table).on("click", "a.action-delete", function(e) {
            e.preventDefault();
            var temp = table_team.row($(this).closest('tr')).data();

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
                        url: "<?php echo base_url('team/ajax_delete/') ?>" + temp.id,
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
            $(`#${_form} .team-is_active`).prop('checked', true);
        };
    });
</script>
