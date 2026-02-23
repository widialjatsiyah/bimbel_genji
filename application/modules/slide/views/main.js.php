<script type="text/javascript">
    $(document).ready(function() {
        var _key = "";
        var _section = "slide";
        var _table = "table-slide";
        var _modal = "modal-form-slide";
        var _form = "form-slide";

        if ($("#" + _table)[0]) {
            var table_slide = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('slide/ajax_get_all/') ?>",
                    type: "get"
                },
                columns: [
                    { data: null, render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                    { data: "image", render: function(data) { return data ? '<img src="<?= base_url() ?>' + data + '" style="max-height:50px;">' : '-'; } },
                    { data: "title" },
                    { data: "subtitle", render: function(data) { return data ? data : '-'; } },
                    { data: "button_text", render: function(data, type, row) { return data ? data + ' (' + row.button_link + ')' : '-'; } },
                    { data: "order_num" },
                    { data: "is_active", render: function(data) { return data == '1' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Tidak</span>'; } },
                    { data: null, className: "center", defaultContent: '<div class="action"><a href="javascript:;" class="btn btn-sm btn-light action-edit" data-toggle="modal" data-target="#' + _modal + '"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;<a href="javascript:;" class="btn btn-sm btn-danger action-delete"><i class="zmdi zmdi-delete"></i> Hapus</a></div>' }
                ],
                // ... sisanya sama seperti modul sebelumnya
            });
        }

        // Handle add
        $("#" + _section).on("click", "button." + _section + "-action-add", function(e) {
            e.preventDefault();
            resetForm();
        });

        // Handle edit
        $("#" + _table).on("click", "a.action-edit", function(e) {
            e.preventDefault();
            resetForm();
            var temp = table_slide.row($(this).closest('tr')).data();
            _key = temp.id;
            $.each(temp, function(key, item) {
                $(`#${_form} .${_section}-${key}`).val(item).trigger("input").trigger("change");
            });
        });

        // Handle save with file upload
        $("#" + _modal + " ." + _section + "-action-save").on("click", function(e) {
            e.preventDefault();
            var formData = new FormData($("#" + _form)[0]);
            $.ajax({
                type: "post",
                url: "<?php echo base_url('slide/ajax_save/') ?>" + _key,
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
                    }
                }
            });
        });

        // Handle delete
        $("#" + _table).on("click", "a.action-delete", function(e) {
            e.preventDefault();
            var temp = table_slide.row($(this).closest('tr')).data();
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
                        url: "<?php echo base_url('slide/ajax_delete/') ?>" + temp.id,
                        dataType: "json",
                        success: function(response) {
                            if (response.status) {
                                $("#" + _table).DataTable().ajax.reload(null, false);
                                notify(response.data, "success");
                            } else {
                                notify(response.data, "danger");
                            }
                        }
                    });
                }
            });
        });

        resetForm = () => {
            _key = "";
            $(`#${_form}`).trigger("reset");
            $(`#${_form} .slide-is_active`).prop('checked', true);
        };
    });
</script>
