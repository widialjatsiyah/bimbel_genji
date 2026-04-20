<script>
$(document).ready(function() {
    var _key = "";
    var _section = "question_group";
    var _table = "table-question-group";
    var _modal = "modal-form-question-group";
    var _form = "form-question-group";
    var base_url = "<?php echo base_url(); ?>";
    
    // Initialize DataTables: Index
    if ($("#" + _table)[0]) {
        var table_question_group = $("#" + _table).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: base_url + 'question_group/ajax_get_all/',
                type: "get"
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: "id"
                },
                {
                    data: "question_count"
                },
                {
                    data: "main_question"
                },
                {
                    data: "created_at"
                },
                {
                    data: null,
                    className: "center",
                    render: function(data, type, row, meta) {
                        return '<div class="action">' +
                            '<a href="' + base_url + 'question_group/form/' + row.id + '" class="btn btn-sm btn-light btn-table-action"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;' +
                            '<a href="javascript:;" class="btn btn-sm btn-danger btn-table-action action-delete"><i class="zmdi zmdi-delete"></i> Hapus</a>' +
                            '</div>';
                    }
                }
            ],
            autoWidth: !1,
            responsive: {
                details: {
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: "table dt-details"
                    }),
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
                targets: [0, 1, 2]
            }, {
                responsivePriority: 1,
                targets: 0
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
                title: "Export Result"
            }, {
                extend: "print",
                title: "Export Result"
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

    // Handle data delete
    $("#" + _table).on("click", "a.action-delete", function(e) {
        e.preventDefault();
        var temp = table_question_group.row($(this).closest('tr')).data();

        swal({
            title: "Anda akan menghapus grup soal, lanjutkan?",
            text: "Setelah dihapus, semua soal dalam grup ini akan dilepas dari grup!",
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
                    url: base_url + 'question_group/ajax_remove_from_group/',
                    data: {
                        "<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>",
                        group_id: temp.id
                    },
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
});

function notify(message, type) {
    // Simple notification function
    var alertDiv = $('<div class="alert alert-' + (type === 'success' ? 'success' : 'danger') + ' alert-dismissible fade show" role="alert">' +
        message + 
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button></div>');

    // Insert at top of body or form
    $('#question_group .card-body').prepend(alertDiv);

    // Auto remove after 5 seconds
    setTimeout(function() {
        alertDiv.alert('close');
    }, 5000);
}
</script>
