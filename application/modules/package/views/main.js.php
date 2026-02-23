<script type="text/javascript">
	$(document).ready(function() {

		var _key = "";
		var _section = "package";
		var _table = "table-package";
		var _modal = "modal-form-package";
		var _form = "form-package";

		// Initialize DataTables
		if ($("#" + _table)[0]) {
			var table_package = $("#" + _table).DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					url: "<?php echo base_url('package/ajax_get_all/') ?>",
					type: "get"
				},
				columns: [{
						data: null,
						render: function(data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						}
					},
					{
						data: "name",
					},
					{
						data: "description",
						render: function(data) {
							return data ? data : '-';
						}
					},
					{
						data: "price",
						render: function(data) {
							return data ? 'Rp ' + number_format(data, 0, ',', '.') : '-';
						}
					},
					{
						data: "duration_days",
						render: function(data) {
							if (data == 0) return 'Tidak terbatas';
							return data + ' hari';
						}
					},
					{
						data: "features_display",
						render: function(data) {
							return data ? data : '-';
						}
					},
					{
						data: "is_active",
						render: function(data) {
							return data == '1' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Tidak Aktif</span>';
						}
					},
					{
						data: null,
						render: function(data, type, row, meta) {
							return '<div class="action">' +
							'<a href="javascript:;" class="btn btn-sm btn-light btn-table-action action-edit" data-toggle="modal" data-target="#' + _modal + '"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;' +
								'<a href="<?= base_url('package/detail/') ?>' + row.id + '" class="btn btn-sm btn-info"><i class="zmdi zmdi-view-list"></i> Item</a>&nbsp;' +
								'<a href="javascript:;" class="btn btn-sm btn-danger btn-table-action action-delete"><i class="zmdi zmdi-delete"></i> Hapus</a>' +
								'</div>'
						},
						className: "center",

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
					targets: [0, 1, 2, 3, 4]
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
					title: "Export Paket"
				}, {
					extend: "print",
					title: "Export Paket"
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
			var temp = table_package.row($(this).closest('tr')).data();

			_key = temp.id;

			// Decode features dari JSON ke teks biasa (dengan baris baru)
			var featuresText = '';
			if (temp.features) {
				try {
					var features = JSON.parse(temp.features);
					if (Array.isArray(features)) {
						featuresText = features.join('\n');
					}
				} catch (e) {}
			}

			$(`#${_form} .package-name`).val(temp.name);
			$(`#${_form} .package-description`).val(temp.description);
			$(`#${_form} .package-price`).val(number_format(temp.price, 0, ',', '.'));
			$(`#${_form} .package-duration_days`).val(temp.duration_days);
			$(`#${_form} .package-features`).val(featuresText);
			$(`#${_form} .package-is_active`).prop('checked', temp.is_active == '1');
		});

		// Handle save
		$("#" + _modal + " ." + _section + "-action-save").on("click", function(e) {
			e.preventDefault();
			$.ajax({
				type: "post",
				url: "<?php echo base_url('package/ajax_save/') ?>" + _key,
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
			var temp = table_package.row($(this).closest('tr')).data();

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
						url: "<?php echo base_url('package/ajax_delete/') ?>" + temp.id,
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
			$(`#${_form} .package-is_active`).prop('checked', true);
		};

		// Format number untuk input harga
		$('.mask-money').on('input', function() {
			var value = $(this).val().replace(/[^0-9]/g, '');
			if (value) {
				$(this).val(number_format(parseInt(value), 0, ',', '.'));
			} else {
				$(this).val('');
			}
		});

		function number_format(number, decimals, dec_point, thousands_sep) {
			number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
			var n = !isFinite(+number) ? 0 : +number,
				prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
				sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
				dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
				s = '',
				toFixedFix = function(n, prec) {
					var k = Math.pow(10, prec);
					return '' + (Math.round(n * k) / k).toFixed(prec);
				};
			s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
			if (s[0].length > 3) {
				s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
			}
			if ((s[1] || '').length < prec) {
				s[1] = s[1] || '';
				s[1] += new Array(prec - s[1].length + 1).join('0');
			}
			return s.join(dec);
		}
	});
</script>
