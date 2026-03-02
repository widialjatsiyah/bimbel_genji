main.js.php
<script type="text/javascript">
    $(document).ready(function() {
        // Variabel global
        var _key = "";
        var _table = "table-student";
        var _modal = "modal-form-student";
        var _modalClass = "modal-manage-class";
        var _form = "form-student";

        // Inisialisasi Select2
        $('.select2').select2();

        // ============================================================
        // DataTables Server-side untuk daftar siswa
        // ============================================================
        if ($("#" + _table)[0]) {
            var table_student = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('student/ajax_get_all/') ?>",
                    type: "get"
                },
                columns: [
                    { 
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: "nama_lengkap" },
                    { data: "email" },
                    { data: "username" },
                    { 
                        data: "unit", 
                        render: function(data) { 
                            return data ? data : '-'; 
                        } 
                    },
                    
                    {
                        data: null,
                        className: "center",
                        render: function(data, type, row) {
                            return '<div class="action">' +
                                '<a href="javascript:;" class="btn btn-sm btn-light action-edit" data-id="' + row.id + '"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;' +
                                '<a href="javascript:;" class="btn btn-sm btn-info action-manage-class" data-id="' + row.id + '" data-name="' + row.nama_lengkap + '"><i class="zmdi zmdi-group"></i> Kelas</a>&nbsp;' +
                                '<a href="javascript:;" class="btn btn-sm btn-danger action-delete" data-id="' + row.id + '"><i class="zmdi zmdi-delete"></i> Hapus</a>' +
                                '</div>';
                        }
                    }
                ],
                autoWidth: false,
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
                columnDefs: [
                    { className: 'desktop', targets: [0,1,2,3,4,5] },
                    { className: 'tablet', targets: [0,1,2,3,4] },
                    { className: 'mobile', targets: [0,1] },
                    { responsivePriority: 2, targets: -1 }
                ],
                pageLength: 15,
                language: {
                    searchPlaceholder: "Cari...",
                    sProcessing: '<div style="text-align: center;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
                },
                sDom: '<"dataTables_ct"><"dataTables__top"fb>rt<"dataTables__bottom"ip><"clear">',
                buttons: [
                    { extend: "excelHtml5", title: "Daftar Siswa" },
                    { extend: "print", title: "Daftar Siswa" }
                ],
                initComplete: function(a, b) {
                    $(this).closest(".dataTables_wrapper").find(".dataTables__top").prepend(
                        '<div class="dataTables_buttons hidden-sm-down actions">' +
                        '<span class="actions__item zmdi zmdi-refresh" data-table-action="reload" title="Reload" />' +
                        '</div>'
                    );
                }
            });

            // Reload manual
            $("body").on("click", "[data-table-action=reload]", function(e) {
                e.preventDefault();
                table_student.ajax.reload(null, false);
            });

            // Animasi search
            $(".dataTables_filter input[type=search]").focus(function() {
                $(this).closest(".dataTables_filter").addClass("dataTables_filter--toggled");
            }).blur(function() {
                $(this).closest(".dataTables_filter").removeClass("dataTables_filter--toggled");
            });
        }

        // ============================================================
        // Tombol Tambah Siswa
        // ============================================================
        $("#student").on("click", ".student-action-add", function(e) {
            e.preventDefault();
            resetForm();
            $("#" + _modal).modal("show");
        });

        // ============================================================
        // Tombol Edit Siswa
        // ============================================================
        $(document).on("click", ".action-edit", function() {
            var id = $(this).data("id");
            _key = id;

            $.get("<?= base_url('student/ajax_get_detail/') ?>" + id, function(data) {
                if (data) {
                    $("#form-" + _section + " .student-username").val(data.username);
                    $("#form-" + _section + " .student-email").val(data.email);
                    $("#form-" + _section + " .student-nama_lengkap").val(data.nama_lengkap);
                    $("#form-" + _section + " .student-unit").val(data.unit).trigger('change');
                    $("#form-" + _section + " .student-sub_unit").val(data.sub_unit).trigger('change');
                    $("#form-" + _section + " .student-is_active").prop('checked', data.is_active == '1');
                }
                $("#" + _modal).modal("show");
            }, "json");
        });

        // ============================================================
        // Simpan Siswa (Tambah/Edit)
        // ============================================================
        $("#" + _modal + " .student-action-save").on("click", function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "<?php echo base_url('student/ajax_save/') ?>" + _key,
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
                    }
                }
            });
        });

        // ============================================================
        // Hapus Siswa
        // ============================================================
        $(document).on("click", ".action-delete", function(e) {
            e.preventDefault();
            var id = $(this).data("id");

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
                        url: "<?php echo base_url('student/ajax_delete/') ?>" + id,
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

        // ============================================================
        // MANAJEMEN KELAS (Multiple Class)
        // ============================================================
        var currentStudentId = null;
        var currentStudentName = "";

        // Buka modal kelola kelas
        $(document).on("click", ".action-manage-class", function() {
            currentStudentId = $(this).data("id");
            currentStudentName = $(this).data("name");
            $("#student-name-display").text(currentStudentName);
            loadStudentClasses(currentStudentId);
            loadAvailableClasses(currentStudentId);
            $("#" + _modalClass).modal("show");
        });

        // Load kelas yang sudah dimiliki siswa
        function loadStudentClasses(studentId) {
            $.get("<?= base_url('student/ajax_get_student_classes/') ?>" + studentId, function(data) {
                var html = '';
                if (data.length === 0) {
                    html = '<tr><td colspan="3" class="text-center">Belum ada kelas.</td></tr>';
                } else {
                    $.each(data, function(i, item) {
                        html += '<tr>' +
                            '<td>' + (i+1) + '</td>' +
                            '<td>' + item.class_name + ' (' + (item.school_name || '-') + ')</td>' +
                            '<td><button class="btn btn-sm btn-danger remove-class" data-id="' + item.id + '">Hapus</button></td>' +
                            '</tr>';
                    });
                }
                $("#student-class-list tbody").html(html);
            }, "json");
        }

        // Load kelas yang tersedia (belum diikuti)
        function loadAvailableClasses(studentId) {
            $.get("<?= base_url('student/ajax_get_available_classes/') ?>" + studentId, function(data) {
                var options = '<option value=""></option>';
                $.each(data, function(i, item) {
                    options += '<option value="' + item.id + '">' + item.text + ' (' + (item.school || '-') + ')</option>';
                });
                $("#available-classes").html(options).select2({
                    placeholder: "Pilih kelas...",
                    allowClear: true
                });
            }, "json");
        }

        // Tambah kelas ke siswa
        $("#btn-add-class").click(function() {
            var classId = $("#available-classes").val();
            if (!classId) {
                alert("Pilih kelas terlebih dahulu.");
                return;
            }

            $.ajax({
                url: "<?= base_url('student/ajax_add_class') ?>",
                type: "post",
                data: {
                    student_id: currentStudentId,
                    class_id: classId
                },
                success: function(res) {
                    if (res.status) {
                        loadStudentClasses(currentStudentId);
                        loadAvailableClasses(currentStudentId);
                        notify(res.data, "success");
                    } else {
                        notify(res.data, "danger");
                    }
                }
            });
        });

        // Hapus kelas dari siswa
        $(document).on("click", ".remove-class", function() {
            var id = $(this).data("id");
            swal({
                title: "Hapus kelas ini?",
                text: "Siswa tidak akan bisa mengakses kelas dan konten terkait.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Batal",
                closeOnConfirm: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "<?= base_url('student/ajax_remove_class/') ?>" + id,
                        type: "post",
                        dataType: "json",
                        success: function(res) {
                            if (res.status) {
                                loadStudentClasses(currentStudentId);
                                loadAvailableClasses(currentStudentId);
                                notify(res.data, "success");
                            } else {
                                notify(res.data, "danger");
                            }
                        }
                    });
                }
            });
        });

        // ============================================================
        // Reset form
        // ============================================================
        resetForm = () => {
            _key = "";
            $("#" + _form).trigger("reset");
            $("#" + _form + " .student-is_active").prop('checked', true);
        };

        // ============================================================
        // Notifikasi
        // ============================================================
        function notify(message, type) {
            if (typeof $.notify === 'function') {
                $.notify({ message: message }, { type: type });
            } else {
                alert(message);
            }
        }
    });
</script>