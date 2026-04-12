<script type="text/javascript">
    $(document).ready(function() {
        var _key = "";
        var _section = "bookmark";
        var _table = "table-bookmark";
        var _modal = "modal-form-bookmark";
        var _form = "form-bookmark";

        if ($("#" + _table)[0]) {
            var table_bookmark = $("#" + _table).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('bookmark/ajax_get_all/') ?>",
                    type: "get"
                },
                columns: [
                  {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: "tryout_name", render: function(data) { return data ? data : '-'; } },
                    { data: "question_text", render: function(data) { return data ? data : '-'; } },
                    { data: "created_at", render: function(data) { return data ? data : '-'; } },
                   {
                        data: null,
						
                        className: "center",
                        render : function(data, type, row) {
							return `<div class="action">` +
								   `<a href="<?= base_url('bookmark/question/${row.question_id}') ?>" class="btn btn-sm btn-info" title="Lihat Soal">
									   <i class="zmdi zmdi-eye"></i> Lihat
								   </a>&nbsp;` +
								   `<a href="javascript:;" class="btn btn-sm btn-danger btn-table-action action-delete" title="Hapus Bookmark">
									   <i class="zmdi zmdi-delete"></i> Hapus
								   </a>` +
								   `</div>`;
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
                    targets: [0, 1, 2, 3, 4]
                }, {
                    className: 'tablet',
                    targets: [0, 1, 2, 3,4 ]
                }, {
                    className: 'mobile',
                    targets: [0, 1, 2]
                }, {
                    responsivePriority: 2,
                    targets: -1
                }],
                order: [1, 'asc'],
                language: {
                    searchPlaceholder: "Cari...",
                    sProcessing: '<div style="text-align: center;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
                },
                sDom: '<"dataTables_ct"<"dataTables__top"fb>rt<"dataTables__bottom"ip><"clear">',
            });
        }

        // Handle remove bookmark
          $("#" + _table).on("click", "a.action-delete", function(e) {
            e.preventDefault();
            var temp = table_bookmark.row($(this).closest('tr')).data();

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
                        url: "<?php echo base_url('bookmark/ajax_delete/') ?>" + temp.id,
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
