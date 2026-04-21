<script type="text/javascript">
	$(document).ready(function() {

		var _key = "";
		var _section = "question";
		var _table = "table-question";
		var _modal = "modal-form-question";
		var _form = "form-question";
		
		// Define base_url for AJAX requests
		var base_url = '<?php echo base_url(); ?>';

		// Fungsi untuk menangani preview gambar
		function previewImage(input, previewContainer) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function(e) {
					previewContainer.html('<img src="' + e.target.result + '" style="max-width: 200px; max-height: 200px;" />');
				}

				reader.readAsDataURL(input.files[0]);
			}
		}

		// Event listener untuk file input gambar soal
		$('.question-image').on('change', function() {
			var previewContainer = $('#question_image_preview');
			previewImage(this, previewContainer);

			// Simpan nama file ke hidden input
			if (this.files && this.files[0]) {
				var formData = new FormData();
				formData.append('image', this.files[0]);

				// Upload gambar dan simpan path-nya
				$.ajax({
					url: '<?php echo base_url("question/upload_image") ?>',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(response) {
						var res = JSON.parse(response);
						if (res.status) {
							$('.question-image-hidden').val(res.path);
						} else {
							notify('Gagal mengupload gambar: ' + res.error, 'danger');
						}
					},
					error: function() {
						notify('Terjadi kesalahan saat upload gambar', 'danger');
					}
				});
			}
		});

		// Event listener untuk file input gambar pilihan A
		$('[name="option_a_image_file"]').on('change', function() {
			var previewContainer = $('#option_a_image_preview');
			previewImage(this, previewContainer);

			if (this.files && this.files[0]) {
				var formData = new FormData();
				formData.append('image', this.files[0]);

				$.ajax({
					url: '<?php echo base_url("question/upload_image") ?>',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(response) {
						var res = JSON.parse(response);
						if (res.status) {
							$('.option_a_image-hidden').val(res.path);
						} else {
							notify('Gagal mengupload gambar: ' + res.error, 'danger');
						}
					},
					error: function() {
						notify('Terjadi kesalahan saat upload gambar', 'danger');
					}
				});
			}
		});

		// Event listener untuk file input gambar pilihan B
		$('[name="option_b_image_file"]').on('change', function() {
			var previewContainer = $('#option_b_image_preview');
			previewImage(this, previewContainer);

			if (this.files && this.files[0]) {
				var formData = new FormData();
				formData.append('image', this.files[0]);

				$.ajax({
					url: '<?php echo base_url("question/upload_image") ?>',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(response) {
						var res = JSON.parse(response);
						if (res.status) {
							$('.option_b_image-hidden').val(res.path);
						} else {
							notify('Gagal mengupload gambar: ' + res.error, 'danger');
						}
					},
					error: function() {
						notify('Terjadi kesalahan saat upload gambar', 'danger');
					}
				});
			}
		});

		// Event listener untuk file input gambar pilihan C
		$('[name="option_c_image_file"]').on('change', function() {
			var previewContainer = $('#option_c_image_preview');
			previewImage(this, previewContainer);

			if (this.files && this.files[0]) {
				var formData = new FormData();
				formData.append('image', this.files[0]);

				$.ajax({
					url: '<?php echo base_url("question/upload_image") ?>',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(response) {
						var res = JSON.parse(response);
						if (res.status) {
							$('.option_c_image-hidden').val(res.path);
						} else {
							notify('Gagal mengupload gambar: ' + res.error, 'danger');
						}
					},
					error: function() {
						notify('Terjadi kesalahan saat upload gambar', 'danger');
					}
				});
			}
		});

		// Event listener untuk file input gambar pilihan D
		$('[name="option_d_image_file"]').on('change', function() {
			var previewContainer = $('#option_d_image_preview');
			previewImage(this, previewContainer);

			if (this.files && this.files[0]) {
				var formData = new FormData();
				formData.append('image', this.files[0]);

				$.ajax({
					url: '<?php echo base_url("question/upload_image") ?>',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(response) {
						var res = JSON.parse(response);
						if (res.status) {
							$('.option_d_image-hidden').val(res.path);
						} else {
							notify('Gagal mengupload gambar: ' + res.error, 'danger');
						}
					},
					error: function() {
						notify('Terjadi kesalahan saat upload gambar', 'danger');
					}
				});
			}
		});

		// Event listener untuk file input gambar pilihan E
		$('[name="option_e_image_file"]').on('change', function() {
			var previewContainer = $('#option_e_image_preview');
			previewImage(this, previewContainer);

			if (this.files && this.files[0]) {
				var formData = new FormData();
				formData.append('image', this.files[0]);

				$.ajax({
					url: '<?php echo base_url("question/upload_image") ?>',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(response) {
						var res = JSON.parse(response);
						if (res.status) {
							$('.option_e_image-hidden').val(res.path);
						} else {
							notify('Gagal mengupload gambar: ' + res.error, 'danger');
						}
					},
					error: function() {
						notify('Terjadi kesalahan saat upload gambar', 'danger');
					}
				});
			}
		});

		// Handle data add
		$("#" + _section).on("click", "button." + _section + "-action-add", function(e) {
			e.preventDefault();
			resetForm();
		});

		// Handle data import button
		$("#" + _section).on("click", "button." + _section + "-action-import", function(e) {
			e.preventDefault();
			// Open import modal
			$('#modal-form-question-import').modal('show');
		});

		// Handle download template button
		$("#" + _section).on("click", "button.question-import-download-template", function(e) {
			e.preventDefault();
			downloadTemplate();
		});

		// Function to download template
		function downloadTemplate() {
			window.location.href = base_url + 'question/download_template';
		}

		// Handle import button click
		$(document).on('click', '.question-import-action-save', function() {
			var subjectId = $('.question-import-subject_id').val();
			var fileInput = $('.question-import-file')[0];
			
			if (!subjectId) {
				showNotification('Silakan pilih mata pelajaran', 'warning');
				return;
			}
			
			if (!fileInput.files[0]) {
				showNotification('Silakan pilih file Excel', 'warning');
				return;
			}
			
			var formData = new FormData($('#form-question-import')[0]);
			
			// Disable button during import
			$('.question-import-action-save').prop('disabled', true).html('<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Mengimpor...');
			
			$.ajax({
				url: base_url + 'question/import_from_excel',
				method: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					var res = JSON.parse(response);
					
					if (res.status) {
						showNotification(res.data, 'success');
						$('#modal-form-question-import').modal('hide');
						// Reload the table
						$('#' + _table).DataTable().ajax.reload(null, false);
					} else {
						showNotification(res.data, 'error');
					}
					
					// Re-enable button
					$('.question-import-action-save').prop('disabled', false).html('<i class="zmdi zmdi-upload"></i> Mulai Impor');
				},
				error: function(xhr, status, error) {
					console.error('Import error:', error);
					showNotification('Terjadi kesalahan saat mengimpor: ' + error, 'error');
					
					// Re-enable button
					$('.question-import-action-save').prop('disabled', false).html('<i class="zmdi zmdi-upload"></i> Mulai Impor');
				}
			});
		});

		// Function to show notification
		function showNotification(message, type) {
			// Using standard Bootstrap alert
			var alertClass = 'alert-';
			switch(type) {
				case 'success':
					alertClass += 'success';
					break;
				case 'error':
					alertClass += 'danger';
					break;
				case 'warning':
					alertClass += 'warning';
					break;
				default:
					alertClass += 'info';
			}
			
			var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show position-fixed" style="z-index: 9999; top: 20px; right: 20px;" role="alert">' +
				message +
				'<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
					'<span aria-hidden="true">&times;</span>' +
				'</button>' +
			'</div>';
			
			$('body').append(alertHtml);
			
			// Auto remove after 5 seconds
			setTimeout(function() {
				$('.alert').fadeOut('slow', function() {
					$(this).remove();
				});
			}, 5000);
		}

		// Handle data edit
		$("#" + _section).on("click", "button." + _section + "-action-edit", function(e) {
			e.preventDefault();
			var n = $(this).closest("tr").find('td:first').text() - 1;
			var record = dt.rows(n).data()[0];

			// Set current data
			currentId = record.id;

			// Load data to form
			loadFormData(currentId);
		});

		// Initialize DataTable
		
		// Initialize DataTables
		if ($("#" + _table)[0]) {
			var table_question = $("#" + _table).DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					url: "<?php echo base_url('question/ajax_get_all/') ?>",
					type: "get"
				},
				columns: [{
						data: null,
						render: function(data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						}
					},
					{
						data: "question_text",
						render: function(data, type, row) {
							if (data === null) {
								return `<img src="<?php echo base_url('/') ?>${row.question_image}" style="max-width: 100px; max-height: 100px;" />`;
							} else {
								// return data;
								return data.length > 400 ? data.substr(0, 400) + '...' : data;
							};
						}
					},
					{
						data: "subject_name",
					},
					{
						data: "chapter_name",
						render: function(data) {
							return data ? data : '-';
						}
					},
					{
						data: "topic_name",
						render: function(data) {
							return data ? data : '-';
						}
					},
					{
						data: "difficulty",
						render: function(data) {
							if (data == 'mudah') return '<span class="badge badge-success">Mudah</span>';
							if (data == 'sedang') return '<span class="badge badge-warning">Sedang</span>';
							if (data == 'sulit') return '<span class="badge badge-danger">Sulit</span>';
							return data;
						}
					},
					{
						data: "question_type",
						render: function(data) {
							if (data == 'multiple_choice') return '<span class="badge badge-primary">Pilihan Ganda</span>';
							if (data == 'essay') return '<span class="badge badge-info">Essay</span>';
							// return data ? data : '-';
						}
					},
					{
						data: null,
						render: function(data, type, row) {
							if (row.correct_option) {
								return row.correct_option.toUpperCase();
							} else if (row.question_type == 'essay') {
								var keywords = JSON.parse(row.expected_keywords);
								var key = '';
								keywords.forEach(item => {
									key += '<span class="badge badge-success">'+item.word+' ('+item.score+')</span> ';
								});
								return key;
							} else {
								return '-';
							}
						}
					},
					{
						data: "is_active",
						render: function(data) {
							return data == '1' ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Tidak Aktif</span>';
						}
					},
					{
						data: "created_at",
						render: function(data) {
							return moment(data).format('Y-m-d H:mm');
						}
					},
					{
						data: null,
						className: "center",
						render: function(data, type, row) {
							return '<div class="action">' +
								'<a href="<?php echo base_url("question/form_edit/") ?>' + row.id + '" class="btn btn-sm btn-light btn-table-action" target="_blank"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;' +
								'<a href="javascript:;" class="btn btn-sm btn-danger btn-table-action action-delete"><i class="zmdi zmdi-delete"></i> Hapus</a>' +
								'</div>';
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
					targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
				}, {
					className: 'tablet',
					targets: [0, 1, 2, 3, 4, 5, 6, 7]
				}, {
					className: 'mobile',
					targets: [0, 1, 6]
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
					title: "Export Soal",
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
					}
				}, {
					extend: "print",
					title: "Export Soal",
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
					}
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

		// Handle data delete
		$("#" + _table).on("click", "button." + _section + "-action-delete", function(e) {
			e.preventDefault();
			var n = $(this).closest("tr").find('td:first').text() - 1;
			var record = dt.rows(n).data()[0];

			confirmDelete(record.id, record.question_text);
		});

		// Confirm delete
		function confirmDelete(id, name) {
			bootbox.confirm("Apakah Anda yakin ingin menghapus soal '" + name + "' ?", function(result) {
				if (result) {
					$.ajax({
						url: "<?php echo base_url('question/ajax_delete/') ?>" + id,
						type: "POST",
						success: function(response) {
							var response = JSON.parse(response);
							if (response.status === true) {
								notify(response.data, 'success');
								dt.ajax.reload();
							} else {
								notify(response.data, 'danger');
							}
						}
					});
				}
			});
		}

		// Reset form
		function resetForm() {
			// Clear all inputs
			$("#" + _form)[0].reset();

			// Reset TinyMCE if exists
			if (typeof tinymce !== 'undefined' && tinymce.editors.length > 0) {
				tinymce.editors[0].setContent('');
			}

			// Reset image previews
			$('.image-preview').empty();
		}
	});
</script>
