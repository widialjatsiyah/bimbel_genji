<script type="text/javascript">
  $(document).ready(function() {

    var _key = "";
    var _section = "layanan";
    var _table = "table-layanan";
    var _modal = "modal-form-layanan";
    var _form = "form-layanan";

    // Initialize DataTables: Index
    if ($("#" + _table)[0]) {
      var table_layanan = $("#" + _table).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "<?php echo base_url('layanan/ajax_get_all/') ?>",
          type: "get"
        },
        columns: [{
            data: null,
            render: function(data, type, row, meta) {
              return meta.row + meta.settings._iDisplayStart + 1;
            }
          },
          {
            data: "nama_layanan",
          },
          {
            data: "keterangan",
            render: function(data, type, row, meta) {
              // Limit keterangan display to prevent overflow
              return data.length > 50 ? data.substring(0, 50) + "..." : data;
            }
          },
          {
            data: "file_imgae",
            render: function(data, type, row, meta) {
              // Display filename with link to full image
              if (data) {
                var fileName = data.split('/').pop();
                return '<a href="<?php echo base_url(); ?>' + data + '" target="_blank">' + 
                       (fileName.length > 20 ? fileName.substring(0, 20) + "..." : fileName) + '</a>';
              } else {
                return '-';
              }
            }
          },
          {
            data: "created_date",
            render: function(data, type, row, meta) {
              return moment(data).format('Y-m-d H:mm:ss');
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
          title: "Export Layanan"
        }, {
          extend: "print",
          title: "Export Layanan"
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

    // Handle data add
    $("#" + _section).on("click", "button." + _section + "-action-add", function(e) {
      e.preventDefault();
      resetForm();
    });

    // Handle data edit
    $("#" + _table).on("click", "a.action-edit", function(e) {
      e.preventDefault();
      resetForm();
      var temp = table_layanan.row($(this).closest('tr')).data();

      // Set key for update params, important!
      _key = temp.id;

      $.each(temp, function(key, item) {
        $(`#${_form} .${_section}-${key}`).val(item).trigger("input").trigger("change");
      });
    });

    // Handle image preview when file is selected
    $(document).on('change', '#' + _form + ' .layanan-file_imgae', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          $('#image-preview-container').show();
          $('#current-image-preview').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
      }
    });

    // Handle data submit
    $("#" + _modal + " ." + _section + "-action-save").on("click", function(e) {
      e.preventDefault();
      
      var form = $("#" + _form)[0];
      var formData = new FormData(form);

      $.ajax({
        type: "post",
        url: "<?php echo base_url('layanan/ajax_save/') ?>" + _key,
        data: formData,
        dataType: "json",
        enctype: "multipart/form-data",
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
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

    // Handle data delete
    $("#" + _table).on("click", "a.action-delete", function(e) {
      e.preventDefault();
      var temp = table_layanan.row($(this).closest('tr')).data();

      swal({
        title: "Anda akan menghapus layanan, lanjutkan?",
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
            url: "<?php echo base_url('layanan/ajax_delete/') ?>" + temp.id,
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

    // Handle form reset
    resetForm = () => {
      _key = "";
      $(`#${_form}`).trigger("reset");
      $('#image-preview-container').hide();
    };

  });
</script>