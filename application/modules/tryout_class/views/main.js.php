<script type="text/javascript">
    $(document).ready(function() {
        var _key = "";
        var _section = "tryout_class";
        var _table = "table-tryout_class";
        var _modal = "modal-form-tryout_class";
        var _form = "form-tryout_class";

        if ($("#" + _table)[0]) {
            var table = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: "<?php echo base_url('tryout_class/ajax_get_all/') ?>",
                columns: [
                    { data: null, render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                    { data: "tryout_title" },
                    { data: "class_name" },
                    { data: "start_time", render: function(d) { return d ? d : '-' } },
                    { data: "end_time", render: function(d) { return d ? d : '-' } },
                    { data: "is_active", render: function(d) { return d == '1' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Tidak</span>'; } },
                    { data: "created_at", render: function(d) { return moment(d).format('Y-MM-D H:mm'); } },
                    { data: null, className: "center", defaultContent: '<div class="action">' +
                        '<a href="javascript:;" class="btn btn-sm btn-light action-edit" data-toggle="modal" data-target="#'+_modal+'"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;' +
                        '<a href="javascript:;" class="btn btn-sm btn-danger action-delete"><i class="zmdi zmdi-delete"></i> Hapus</a>' +
                        '</div>' }
                ],
                autoWidth: !1,
                responsive: true,
                pageLength: 15,
                language: { searchPlaceholder: "Cari...", sProcessing: '<div style="text-align: center;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>' },
                sDom: '<"dataTables_ct"><"dataTables__top"fb>rt<"dataTables__bottom"ip><"clear">',
                buttons: [{ extend: "excelHtml5", title: "Export Jadwal" }, { extend: "print", title: "Export Jadwal" }],
                initComplete: function() {
                    $(this).closest(".dataTables_wrapper").find(".dataTables__top").prepend(
                        '<div class="dataTables_buttons hidden-sm-down actions">' +
                        '<span class="actions__item zmdi zmdi-refresh" data-table-action="reload" title="Reload" /></div>'
                    );
                }
            });

            $("body").on("click", "[data-table-action='reload']", function() { $("#" + _table).DataTable().ajax.reload(null, false); });
        }

        $("#" + _section).on("click", "button." + _section + "-action-add", function() { resetForm(); });

        $("#" + _table).on("click", "a.action-edit", function() {
            resetForm();
            var temp = table.row($(this).closest('tr')).data();
            _key = temp.id;
            $(".tryout_class-tryout_id").val(temp.tryout_id).trigger('change');
            $(".tryout_class-class_id").val(temp.class_id).trigger('change');
            $(".tryout_class-start_time").val(temp.start_time);
            $(".tryout_class-end_time").val(temp.end_time);
            $(".tryout_class-is_active").prop('checked', temp.is_active == '1');
        });

        $("#" + _modal + " ." + _section + "-action-save").on("click", function() {
            $.ajax({
                type: "post",
                url: "<?php echo base_url('tryout_class/ajax_save/') ?>" + _key,
                data: $("#" + _form).serialize(),
                success: function(res) {
                    res = JSON.parse(res);
                    if (res.status) {
                        resetForm();
                        $("#" + _modal).modal("hide");
                        $("#" + _table).DataTable().ajax.reload(null, false);
                        notify(res.data, "success");
                    } else notify(res.data, "danger");
                }
            });
        });

        $("#" + _table).on("click", "a.action-delete", function() {
            var temp = table.row($(this).closest('tr')).data();
            swal({
                title: "Hapus jadwal?",
                text: "Data akan dihapus permanen.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "delete",
                        url: "<?php echo base_url('tryout_class/ajax_delete/') ?>" + temp.id,
                        dataType: "json",
                        success: function(res) {
                            if (res.status) {
                                $("#" + _table).DataTable().ajax.reload(null, false);
                                notify(res.data, "success");
                            } else notify(res.data, "danger");
                        }
                    });
                }
            });
        });

        function resetForm() {
            _key = "";
            $("#" + _form).trigger("reset");
            $(".tryout_class-tryout_id, .tryout_class-class_id").val('').trigger('change');
            $(".tryout_class-is_active").prop('checked', true);
        }

        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr-datetime", { enableTime: true, dateFormat: "Y-m-d H:i:S", time_24hr: true });
        }
    });
</script>
