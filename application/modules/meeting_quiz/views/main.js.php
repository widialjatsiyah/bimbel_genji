<script type="text/javascript">
    $(document).ready(function() {
        var meeting_id = <?= $meeting_id ?>;

        loadQuizzes();

        $('#quiz-select').select2({
            ajax: {
                url: '<?= base_url('meeting_quiz/ajax_get_available_quizzes/') ?>' + meeting_id,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return { results: data };
                },
                cache: true
            },
            placeholder: 'Cari quiz...',
            minimumInputLength: 2
        });

        $('#btn-add-quiz').click(function() {
            var quizId = $('#quiz-select').val();
            var orderNum = $('input[name="order_num"]').val();
            if (!quizId) {
                alert('Pilih quiz terlebih dahulu');
                return;
            }

            $.ajax({
                url: '<?= base_url('meeting_quiz/ajax_add') ?>',
                type: 'post',
                data: {
                    meeting_id: meeting_id,
                    quiz_id: quizId,
                    order_num: orderNum
                },
                success: function(res) {
                    if (res.status) {
                        $('#quiz-select').val(null).trigger('change');
                        $('input[name="order_num"]').val('');
                        loadQuizzes();
                        notify(res.data, 'success');
                    } else {
                        notify(res.data, 'danger');
                    }
                }
            });
        });

        $(document).on('click', '.btn-remove', function() {
            var id = $(this).data('id');
            swal({
                title: "Hapus quiz dari pertemuan?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Batal",
                closeOnConfirm: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '<?= base_url('meeting_quiz/ajax_remove/') ?>' + id,
                        type: 'post',
                        dataType: 'json',
                        success: function(res) {
                            if (res.status) {
                                loadQuizzes();
                                notify(res.data, 'success');
                            } else {
                                notify(res.data, 'danger');
                            }
                        }
                    });
                }
            });
        });

        function loadQuizzes() {
            $.get('<?= base_url('meeting_quiz/ajax_get_all/') ?>' + meeting_id, function(data) {
                var tbody = $('#table-quizzes tbody');
                tbody.empty();
                if (data.data.length === 0) {
                    tbody.append('<tr><td colspan="5" class="text-center">Belum ada quiz</td></tr>');
                } else {
                    $.each(data.data, function(i, item) {
                        var row = '<tr>' +
                            '<td>' + (i+1) + '</td>' +
                            '<td>' + item.title + '</td>' +
                            '<td>' + (item.description ? item.description : '-') + '</td>' +
                            '<td>' + (item.total_duration ? item.total_duration : '-') + '</td>' +
                            '<td><button class="btn btn-sm btn-danger btn-remove" data-id="' + item.id + '">Hapus</button></td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                }
            }, 'json');
        }
    });
</script>
