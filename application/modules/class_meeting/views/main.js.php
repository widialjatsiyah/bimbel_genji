<script type="text/javascript">
	$(document).ready(function() {
		var _key = "";
		var _section = "meeting";
		var _table = "table-meeting";
		var _modal = "modal-form-meeting";
		var _form = "form-meeting";
		var class_id = <?= $class_id ?>;

		// Initialize DataTables
		if ($("#" + _table)[0]) {
			var table_meeting = $("#" + _table).DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					url: "<?php echo base_url('class_meeting/ajax_get_all/') ?>" + class_id,
					type: "get"
				},
				columns: [{
						data: null,
						render: function(data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						}
					},
					{
						data: "title",
					},
					{
						data: "description",
						render: function(data) {
							return data ? data.substr(0, 50) + '...' : '-';
						}
					},
					{
						data: "date",
						render: function(data) {
							return data ? data : '-';
						}
					},
					{
						data: "start_time",
						render: function(data) {
							return data ? data : '-';
						}
					},
					{
						data: "end_time",
						render: function(data) {
							return data ? data : '-';
						}
					},
					{
						data: "meeting_link",
						render: function(data) {
							return data ? '<a href="' + data + '" target="_blank">Link</a>' : '-';
						}
					},
					{
						data: "order_num",
					},
					{
						data: null,
						className: "center",
						render: function(data, type, row, meta) {
						var btn = '';	
						btn += '<div class="action">';
						btn +='<a href="javascript:;" class="btn btn-sm btn-light btn-table-action action-edit" data-toggle="modal" data-target="#' + _modal + '"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;' +
								'<a href="javascript:;" class="btn btn-sm btn-danger btn-table-action action-delete"><i class="zmdi zmdi-delete"></i> Hapus</a>';
							btn +=`<a href="<?= base_url('meeting_material/index/') ?>${row.id}" class="btn btn-sm btn-info">
								<i class="zmdi zmdi-file"></i>Materi
							</a>`;
							btn += `<a href="<?= base_url('meeting_quiz/index/') ?>${row.id}" class="btn btn-sm btn-primary">
								<i class="zmdi zmdi-file"></i> Quiz
							</a>`;
							btn += '</div>';
							return btn;
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
					targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
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
					title: "Export Pertemuan"
				}, {
					extend: "print",
					title: "Export Pertemuan"
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
			}).blur(function() {
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

		// Handle add
		$("#" + _section).on("click", "button." + _section + "-action-add", function(e) {
			e.preventDefault();
			resetForm();
		});

		// Handle edit
		$("#" + _table).on("click", "a.action-edit", function(e) {
			e.preventDefault();
			resetForm();
			var temp = table_meeting.row($(this).closest('tr')).data();
			_key = temp.id;
			$(`#${_form} .meeting-title`).val(temp.title);
			$(`#${_form} .meeting-description`).val(temp.description);
			$(`#${_form} .meeting-date`).val(temp.date);
			$(`#${_form} .meeting-start_time`).val(temp.start_time);
			$(`#${_form} .meeting-end_time`).val(temp.end_time);
			$(`#${_form} .meeting-meeting_link`).val(temp.meeting_link);
			$(`#${_form} .meeting-order_num`).val(temp.order_num);
		});

		// Handle save
		$("#" + _modal + " ." + _section + "-action-save").on("click", function(e) {
			e.preventDefault();
			$.ajax({
				type: "post",
				url: "<?php echo base_url('class_meeting/ajax_save/') ?>" + class_id + "/" + _key,
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

		// Handle delete
		$("#" + _table).on("click", "a.action-delete", function(e) {
			e.preventDefault();
			var temp = table_meeting.row($(this).closest('tr')).data();
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
						url: "<?php echo base_url('class_meeting/ajax_delete/') ?>" + class_id + "/" + temp.id,
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
		};

		// Initialize flatpickr jika perlu
		if (typeof flatpickr !== 'undefined') {
			flatpickr(".flatpickr-date", {
				dateFormat: "Y-m-d"
			});
			flatpickr(".flatpickr-time", {
				enableTime: true,
				noCalendar: true,
				dateFormat: "H:i",
				time_24hr: true
			});
		}
	});
</script>
