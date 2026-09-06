<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= isset($client_keys) ? $client_keys : '' ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {

		

		var _table = "table-my-payment";

		// Initialize DataTables
		if ($("#" + _table)[0]) {
			var table_my_payment = $("#" + _table).DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					url: "<?php echo base_url('my_payment/ajax_get_all/') ?>",
					type: "get"
				},
				columns: [{
						data: null,
						render: function(data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						}
					},
					{
						data: "order_id",
					},
					{
						data: "package_name",
					},
					{
						data: "description",
						render: function(data) {
							return data ? data : '-';
						}
					},
					{
						data: "created_at",
						render: function(data) {
							return data ? data : '-';
						}
					},
					{
						data: "payment_type",
						render: function(data) {
							return data === 'manual' ? 'Manual' : 'Midtrans';
						}
					},
					{
						data: "transaction_status",
						render: function(data) {
							var badge = {
								'pending': 'warning',
								'settlement': 'success',
								'capture': 'success',
								'cancel': 'danger',
								'cancelled': 'danger',
								'expire': 'secondary',
								'expired': 'secondary',
								'deny': 'danger'
							};
							return '<span class="badge bg-' + (badge[data] || 'info') + '">' + (data || '-') + '</span>';
						}
					},
					{
						data: "manual_verification_status",
						render: function(data, type, row) {
							if (row.payment_type !== 'manual') return '-';
							var labels = {pending: 'Menunggu Verifikasi', approved: 'Terverifikasi', rejected: 'Ditolak'};
							return '<span class="badge bg-info">' + (labels[data] || 'Menunggu Verifikasi') + '</span>';
						}
					},
					{
						data: "gross_amount",
						render: function(data) {
							return data ? 'Rp ' + number_format(data, 0, ',', '.') : '-';
						}
					},
					{
						data: null,
						render: function(data, type, row) {
							if (row.payment_type === 'manual') {
								var proofButton = row.manual_proof ? '<button class="btn btn-sm btn-info btn-view-manual-proof" data-order="' + row.order_id + '" data-proof="' + encodeURIComponent(row.manual_proof) + '">Lihat Bukti</button> ' : '';
								var uploadButton = row.manual_verification_status === 'approved' ? '' : '<button class="btn btn-sm btn-success btn-manual-proof" data-order="' + row.order_id + '">' + (row.manual_proof ? 'Upload Ulang' : 'Upload Bukti') + '</button>';
								return proofButton + uploadButton;
							} else if (row.transaction_status == 'pending') {
								return '<button class="btn btn-sm btn-primary btn-pay" data-order="' + row.order_id + '">Lanjutkan Pembayaran</button>';
							} else {
								return '-';
							}
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
					targets: [0, 1, 2, 3, 4, 5, 6]
				}, {
					className: 'tablet',
					targets: [0, 1, 2, 3, 4, 5, 6]
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
					title: "Export Paket Saya"
				}, {
					extend: "print",
					title: "Export Paket Saya"
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

		$(document).on('click', '.btn-pay', function() {
			var orderId = $(this).data('order');
			// Ambil snap token dari server (dari database)
			$.get('<?= base_url('payment/get_snap_token/') ?>' + orderId, function(res) {
				if (res.status) {
					snap.pay(res.snap_token, {
						onSuccess: function(result) {
							window.location.href = '<?= base_url('payment/finish?order_id=') ?>' + orderId;
						},
						onPending: function(result) {
							window.location.href = '<?= base_url('my_payment') ?>';
						},
						onError: function(result) {
								window.location.href = '<?= base_url('my_payment') ?>';
						}
					});
				} else {
					alert(res.message);
				}
			}, 'json');
		});

		$(document).on('click', '.btn-manual-proof', function() {
			var orderId = $(this).data('order');
			$('#manual-payment-order-id').val(orderId);
			$('#manual-payment-order').text(orderId);
			$('#manual-payment-proof').val('');
			$('#manual-payment-note').val('');
			$('#manual-payment-existing-proof').addClass('d-none').empty();
			$('#modal-manual-payment').modal('show');
		});

		$(document).on('click', '.btn-view-manual-proof', function() {
			var rawProof = decodeURIComponent($(this).data('proof'));
			var proofUrl = '<?= base_url('uploads/payment_proofs/') ?>' + rawProof;
			var orderId = $(this).data('order');
			$('#manual-payment-order-id').val(orderId);
			$('#manual-payment-order').text(orderId);
			$('#manual-payment-proof').val('');
			$('#manual-payment-note').val('');
			var extension = String(rawProof).toLowerCase().split('.').pop();
			if (extension === 'jpg' || extension === 'jpeg' || extension === 'png') {
				$('#manual-payment-existing-proof').html('<img src="' + proofUrl + '" alt="Bukti pembayaran" style="max-width:100%; max-height:280px;">').removeClass('d-none');
			} else {
				$('#manual-payment-existing-proof').html('<a target="_blank" href="' + proofUrl + '">Buka file bukti</a>').removeClass('d-none');
			}
			$('#modal-manual-payment').modal('show');
		});

		$('#manual-payment-submit').on('click', function() {
			var fileInput = $('#manual-payment-proof')[0];
			if (!fileInput.files.length) {
				alert('Pilih bukti pembayaran terlebih dahulu.');
				return;
			}
			var formData = new FormData();
			formData.append('payment_proof', fileInput.files[0]);
			formData.append('manual_note', $('#manual-payment-note').val());
			formData.append('<?= $this->security->get_csrf_token_name() ?>', '<?= $this->security->get_csrf_hash() ?>');
			var button = $(this);
			button.prop('disabled', true).text('Mengirim...');
			$.ajax({
				url: '<?= base_url('payment/upload_manual_proof/') ?>' + $('#manual-payment-order-id').val(),
				type: 'post',
				data: formData,
				processData: false,
				contentType: false,
				dataType: 'json',
				success: function(response) {
					if (response.status) {
						$('#modal-manual-payment').modal('hide');
						$('#' + _table).DataTable().ajax.reload(null, false);
					}
					alert(response.data);
				},
				error: function() { alert('Gagal mengirim bukti pembayaran.'); },
				complete: function() { button.prop('disabled', false).text('Kirim Bukti'); }
			});
		});

		// Helper number_format
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
