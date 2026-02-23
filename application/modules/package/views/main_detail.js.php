<script type="text/javascript">
    $(document).ready(function() {
		var packageId = <?= $package_id ?>;
		// alert('as');
        var currentType = '';

        // Tombol tambah item berdasarkan tipe
        $('.add-item').click(function() {
            currentType = $(this).data('type');
            $('#item-type').val(currentType);
            $('#item-select').empty().trigger('change');
            
            // Load data via AJAX
            $.get('<?= base_url('package/ajax_get_available_items') ?>', {
                package_id: packageId,
                type: currentType
            }, function(data) {
                var options = '<option value=""></option>';
                
                // Check if data is an array before iterating
                if (data && Array.isArray(data)) {
                    $.each(data, function(i, item) {
                        options += '<option value="' + item.id + '">' + item.text + (item.school ? ' (' + item.school + ')' : '') + '</option>';
                    });
                }
                
                $('#item-select').html(options).trigger('change');
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
                success: function(res) {
                    if (res.status) {
                        location.reload();
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
                    success: function(res) {
                        if (res.status) {
                            $('#item-' + id).remove();
                            if ($('#item-list tr').length == 0) {
                                // Jika semua item habis, reload atau tambahkan baris kosong
                                location.reload();
                            }
                        } else {
                            alert(res.data);
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