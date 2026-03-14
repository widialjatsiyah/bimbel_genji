<script type="text/javascript">
    $(document).ready(function() {

        var _key = "";
        var _section = "schedule";
        var _table = "table-schedule";
        var _modal = "modal-form-schedule";
        var _form = "form-schedule";

        // Load tryouts untuk dropdown
        function loadTryouts() {
            $.get('<?= base_url('tryout_schedule/ajax_get_tryouts') ?>', function(data) {
                var options = '<option value=""></option>';
                $.each(data, function(i, item) {
                    options += '<option value="' + item.id + '">' + item.title + '</option>';
                });
                $('.schedule-tryout_id').html(options);
            }, 'json');
        }

        // Load kelas untuk dropdown
        function loadClasses() {
            $.get('<?= base_url('tryout_schedule/ajax_get_classes') ?>', function(data) {
                var options = '<option value=""></option>';
                $.each(data, function(i, item) {
                    options += '<option value="' + item.id + '">' + item.name + '</option>';
                });
                $('.schedule-class_id').html(options);
            }, 'json');
        }

        // Initialize DataTables
        if ($("#" + _table)[0]) {
            var table_schedule = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('tryout_schedule/ajax_get_all/') ?>",
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
                        render: function(data) { return data ? data : '-'; }
                    },
                    {
                        data: "class_name",
                        render: function(data) { return data ? data : '-'; }
                    },
                    {
                        data: "start_time",
                        render: function(data) { return data ? data : '-'; }
                    },
                    {
                        data: "end_time",
                        render: function(data) { return data ? data : '-'; }
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
                    targets: [0,1,2,3,4,5,6]
                }, {
                    className: 'tablet',
                    targets: [0,1,2,3,4]
                }, {
                    className: 'mobile',
                    targets: [0,1]
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
                    title: "Export Jadwal"
                }, {
                    extend: "print",
                    title: "Export Jadwal"
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
            loadTryouts();
            loadClasses();
        });

        // Handle edit
        $("#" + _table).on("click", "a.action-edit", function(e) {
            e.preventDefault();
            resetForm();
            var temp = table_schedule.row($(this).closest('tr')).data();

            _key = temp.id;

            // Load dropdown terlebih dahulu, lalu set value
            loadTryouts();
            loadClasses();

            // Setelah dropdown terisi, set value (perlu sedikit delay atau gunakan callback)
            setTimeout(function() {
                $(`#${_form} .schedule-tryout_id`).val(temp.tryout_id).trigger('change');
                $(`#${_form} .schedule-class_id`).val(temp.class_id).trigger('change');
                $(`#${_form} .schedule-start_time`).val(temp.start_time);
                $(`#${_form} .schedule-end_time`).val(temp.end_time);
                $(`#${_form} .schedule-is_active`).prop('checked', temp.is_active == '1');
            }, 500); // delay 500ms, atau gunakan promise
        });

        // Handle save
        $("#" + _modal + " ." + _section + "-action-save").on("click", function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "<?php echo base_url('tryout_schedule/ajax_save/') ?>" + _key,
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
            var temp = table_schedule.row($(this).closest('tr')).data();

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
                        url: "<?php echo base_url('tryout_schedule/ajax_delete/') ?>" + temp.id,
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
            $(`#${_form} .schedule-is_active`).prop('checked', true);
            $('.schedule-tryout_id').empty();
            $('.schedule-class_id').empty();
        };

        // Flatpickr untuk datetime
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr-datetime", {
                enableTime: true,
                dateFormat: "Y-m-d H:i:S",
                time_24hr: true
            });
        }
    });
</script>
