<script type="text/javascript">
	$(document).ready(function() {
		var packageId = <?= $package_id ?>;
		// alert('as');
		var currentType = '';
		// console.log('Package ID:', packageId);
		// Tombol tambah item berdasarkan tipe
		$('.add-item').click(function() {
			// alert('as');
			currentType = $(this).data('type');
			$('#item-type').val(currentType);
			$('#item-select').empty().trigger('change');

			$.ajax({
				url: '<?= base_url('package/ajax_get_available_items') ?>',
				type: 'GET',
				dataType: 'json', // WAJIB
				data: {
					package_id: packageId,
					type: currentType
				},
				success: function(data) {

					var options = '<option value=""></option>';

					if (Array.isArray(data)) {
						$.each(data, function(i, item) {
							options += '<option value="' + item.id + '">' + item.text + '</option>';
						});
					}

					$('#item-select').html(options).trigger('change');
				}
			});
			$('#modal-add-item').modal('show');
		});

		// Tombol simpan tambah item
		$('#btn-add-item').click(function() {
			var itemId = $('#item-select').val();
			if (!itemId) {
				alert('Pilih item terlebih dahulu');
				return;
			}
			$.ajax({
				url: '<?= base_url('package/ajax_add_item') ?>',
				type: 'post',
				data: {
					package_id: packageId,
					type: currentType,
					item_id: itemId
				},
				dataType: 'json',
				success: function(res) {
					if (res.status) {
						notify(res.data, 'success');
						setTimeout(
							function() {
								location.reload();
							}, 1000
						)
						// location.reload();
					} else {
						alert(res.data);
					}
				}
			});
		});

		// Tombol hapus item
		$('.remove-item').click(function() {
			var id = $(this).data('id');
			if (confirm('Hapus item ini dari paket?')) {
				$.ajax({
					url: '<?= base_url('package/ajax_remove_item/') ?>' + id,
					type: 'post',
					dataType: 'json',
					success: function(res) {
						if (res.status) {
							notify(res.data, 'success');
						setTimeout(
							function() {
								location.reload();
							}, 1000
						)
							$('#item-' + id).remove();
						} else {
							notify(res.data, 'error');
						}
					}
				});
			}
		});

		// Inisialisasi select2 untuk modal
		$('#item-select').select2({
			placeholder: 'Pilih item...',
			width: '100%'
		});
	});
</script>
