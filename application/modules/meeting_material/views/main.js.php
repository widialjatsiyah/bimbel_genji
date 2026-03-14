<script type="text/javascript">
    $(document).ready(function() {
        var meeting_id = <?= $meeting_id ?>;

        // Load daftar materi yang sudah ada
        loadMaterials();

        // Inisialisasi Select2 untuk pilihan materi
        $('#material-select').select2({
            ajax: {
                url: '<?= base_url('meeting_material/ajax_get_available_materials/') ?>' + meeting_id,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            placeholder: 'Cari materi...',
            minimumInputLength: 2
        });

        // Tambah materi
        $('#btn-add-material').click(function() {
            var materialId = $('#material-select').val();
            var orderNum = $('input[name="order_num"]').val();
            if (!materialId) {
                alert('Pilih materi terlebih dahulu');
                return;
            }

            $.ajax({
                url: '<?= base_url('meeting_material/ajax_add') ?>',
                type: 'post',
                data: {
                    meeting_id: meeting_id,
                    material_id: materialId,
                    order_num: orderNum
                },
                success: function(res) {
                    if (res.status) {
                        $('#material-select').val(null).trigger('change');
                        $('input[name="order_num"]').val('');
                        loadMaterials();
                        notify(res.data, 'success');
                    } else {
                        notify(res.data, 'danger');
                    }
                }
            });
        });

        // Hapus materi
        $(document).on('click', '.btn-remove', function() {
            var id = $(this).data('id');
            swal({
                title: "Hapus materi dari pertemuan?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Batal",
                closeOnConfirm: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '<?= base_url('meeting_material/ajax_remove/') ?>' + id,
                        type: 'post',
                        dataType: 'json',
                        success: function(res) {
                            if (res.status) {
                                loadMaterials();
                                notify(res.data, 'success');
                            } else {
                                notify(res.data, 'danger');
                            }
                        }
                    });
                }
            });
        });

        function loadMaterials() {
            $.get('<?= base_url('meeting_material/ajax_get_all/') ?>' + meeting_id, function(data) {
                var tbody = $('#table-materials tbody');
                tbody.empty();
                if (data.data.length === 0) {
                    tbody.append('<tr><td colspan="5" class="text-center">Belum ada materi</td></tr>');
                } else {
                    $.each(data.data, function(i, item) {
                        var row = '<tr>' +
                            '<td>' + (i+1) + '</td>' +
                            '<td>' + item.title + '</td>' +
                            '<td>' + item.type + '</td>' +
                            '<td><a href="' + item.url + '" target="_blank">Lihat</a></td>' +
                            '<td><button class="btn btn-sm btn-danger btn-remove" data-id="' + item.id + '">Hapus</button></td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                }
            }, 'json');
        }
    });
</script>
