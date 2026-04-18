<script type="text/javascript">
    $(document).ready(function() {

        // Fungsi untuk memperbarui tampilan bintang berdasarkan nilai yang dipilih
        function updateStarRating(value) {
            $('.star').each(function(index) {
                if (index < value) {
                    $(this).addClass('rated');
                } else {
                    $(this).removeClass('rated');
                }
            });
        }

        // Event handler ketika pengguna memilih nilai dari dropdown
        $('#mood-rating-select').on('change', function() {
            var selectedValue = parseInt($(this).val());
            if (!isNaN(selectedValue)) {
                updateStarRating(selectedValue);
            } else {
                updateStarRating(0);
            }
        });

        // Event handler ketika pengguna mengklik bintang
        $('.star').on('click', function() {
            var starValue = parseInt($(this).data('value'));
            $('#mood-rating-select').val(starValue);
            updateStarRating(starValue);
        });

        // Event handler untuk hover pada bintang
        $('.star').hover(
            function() {
                // Hover in - highlight bintang sampai yang diklik
                var hoverValue = parseInt($(this).data('value'));
                $('.star').each(function(index) {
                    if (index < hoverValue) {
                        $(this).css('color', '#e0a800');
                    }
                });
            },
            function() {
                // Hover out - kembalikan ke tampilan berdasarkan nilai yang dipilih
                var currentValue = parseInt($('#mood-rating-select').val()) || 0;
                $('.star').each(function(index) {
                    if (index < currentValue) {
                        $(this).css('color', '#ffc107');
                    } else {
                        $(this).css('color', '#ddd');
                    }
                });
            }
        );

        // Load riwayat
        loadHistory();

        $('#btn-save-checklist').click(function() {
            $.ajaxSetup({
                beforeSend: function(xhr, settings) {
                    // Ambil token CSRF terbaru dari form sebelum mengirim permintaan
                    var tokenName = $('input[name="<?=$this->security->get_csrf_token_name();?>"]').attr('name');
                    var tokenValue = $('input[name="<?=$this->security->get_csrf_token_name();?>"]').val();
                    
                    // Tambahkan token ke data yang dikirim jika belum ada
                    if (!settings.data.includes(tokenName)) {
                        settings.data += '&' + tokenName + '=' + encodeURIComponent(tokenValue);
                    }
                }
            });
            
            $.ajax({
                url: '<?= base_url('daily_checklist/ajax_save') ?>',
                type: 'post',
                data: $('#form-checklist').serialize(),
                success: function(response) {
					response = JSON.parse(response);
                    if (response.status) {
                        notify(response.data, 'success');
                        loadHistory();
                        
                        // Update CSRF token jika disediakan dalam response
                        if(response.csrf_hash) {
                            $('input[name="<?=$this->security->get_csrf_token_name();?>"]').val(response.csrf_hash);
                        }
                    } else {
                        notify(response.data, 'danger');
                        
                        // Update CSRF token jika disediakan dalam response
                        if(response.csrf_hash) {
                            $('input[name="<?=$this->security->get_csrf_token_name();?>"]').val(response.csrf_hash);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    notify('Terjadi kesalahan saat menyimpan data: ' + error, 'danger');
                }
            });
        });

        function loadHistory() {
            $.get('<?= base_url('daily_checklist/ajax_get_history') ?>', function(data) {
                var html = '<table class="table table-sm">';
                html += '<thead><tr><th>Tanggal</th><th>Ibadah</th><th>Belajar</th><th>Mood</th></tr></thead><tbody>';
                $.each(data, function(i, item) {
                    var checklist = JSON.parse(item.checklist_data);
                    var ibadah = (checklist.shalat_subuh ? 'Subuh ' : '') +
                                 (checklist.shalat_dzuhur ? 'Dzuhur ' : '') +
                                 (checklist.shalat_ashar ? 'Ashar ' : '') +
                                 (checklist.shalat_maghrib ? 'Maghrib ' : '') +
                                 (checklist.shalat_isya ? 'Isya ' : '') +
                                 (checklist.tilawah ? 'Tilawah' : '');
                    
                    // Format mood rating menjadi bintang
                    var moodDisplay = formatMoodRating(item.mood_rating);
                    
                    html += '<tr>';
                    html += '<td>' + item.date + '</td>';
                    html += '<td>' + (ibadah || '-') + '</td>';
                    html += '<td>' + (checklist.belajar_menit || 0) + ' menit</td>';
                    html += '<td>' + moodDisplay + '</td>';
                    html += '</tr>';
                });
                html += '</tbody></table>';
                $('#history-container').html(html);
            }, 'json');
        }
        
        // Fungsi untuk memformat tampilan mood rating di tabel histori
        function formatMoodRating(moodValue) {
            if (!moodValue) {
                return '-';
            }
            
            var moodInt = parseInt(moodValue);
            var moodClass = '';
            
            // Tentukan kelas CSS berdasarkan tingkat mood
            if (moodInt >= 8) {
                moodClass = 'mood-excellent';
            } else if (moodInt >= 6) {
                moodClass = 'mood-good';
            } else if (moodInt >= 4) {
                moodClass = 'mood-average';
            } else if (moodInt >= 2) {
                moodClass = 'mood-below-average';
            } else {
                moodClass = 'mood-poor';
            }
            
            // Buat tampilan bintang
            var stars = '';
            for (var i = 1; i <= 10; i++) {
                if (i <= moodInt) {
                    stars += '<span class="star rated" style="font-size: 12px;">&#9733;</span>';
                } else {
                    stars += '<span class="star" style="font-size: 12px; color: #ddd;">&#9733;</span>';
                }
            }
            
            return '<span class="' + moodClass + '">' + moodInt + '/10</span><br>' + stars;
        }
    });
    // Tambahkan CSS untuk bintang rating
    $('<style>.star { cursor: pointer; color: #ddd; transition: color 0.2s; } .star.rated { color: #ffc107; } .star:hover { color: #e0a800; } .mood-excellent { color: #28a745; } .mood-good { color: #17a2b8; } .mood-average { color: #ffc107; } .mood-below-average { color: #fd7e14; } .mood-poor { color: #dc3545; }</style>').appendTo('head');
</script>
