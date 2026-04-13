<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?= $card_title ?></h4>
                
                <form id="form-question" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Mata Pelajaran</label>
                                <div class="select">
                                    <select name="subject_id" class="form-control select2 question-subject_id" data-placeholder="Pilih Mata Pelajaran" required>
                                        <option value=""></option>
                                        <?= $list_subject ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bab</label>
                                <div class="select">
                                    <select name="chapter_id" class="form-control select2 question-chapter_id" data-placeholder="Pilih Bab (opsional)">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Topik</label>
                                <div class="select">
                                    <select name="topic_id" class="form-control select2 question-topic_id" data-placeholder="Pilih Topik (opsional)">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label required>Tingkat Kesulitan</label>
                                        <div class="select">
                                            <select name="difficulty" class="form-control question-difficulty" required>
                                                <option value="mudah">Mudah</option>
                                                <option value="sedang" selected>Sedang</option>
                                                <option value="sulit">Sulit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label required>Kurikulum</label>
                                        <div class="select">
                                            <select name="curriculum" class="form-control question-curriculum" required>
                                                <option value="Kurikulum Merdeka" selected>Kurikulum Merdeka</option>
                                                <option value="K13">K13</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Tipe Opsi</label>
                                <select name="option_type" class="form-control question-option_type" id="question_option_type">
                                    <option value="text">Teks</option>
                                    <option value="image">Gambar</option>
                                </select>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Jenis Soal</label>
                                <div class="select">
                                    <select name="question_type" class="form-control question-type" id="question_type" required>
                                        <option value="multiple_choice">Pilihan Ganda</option>
                                        <option value="essay">Esai</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Group Settings Section -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Grup Soal</label>
                                <input type="text" name="group_id" class="form-control question-group_id" placeholder="ID Grup (opsional)" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Urutan dalam Grup</label>
                                <input type="number" name="group_order" class="form-control question-group_order" min="1" value="1" placeholder="Nomor urut dalam grup" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Soal Utama Grup</label>
                                <div class="select">
                                    <select name="is_group_main" class="form-control question-is_group_main">
                                        <option value="0" selected>Biasa</option>
                                        <option value="1">Utama (Memiliki Narasi)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label required>Teks Soal</label>
                        <textarea name="question_text" class="form-control question-question_text tinymce-init" rows="4" placeholder="Tulis soal di sini..." required></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <!-- Gambar Soal -->
                    <div class="form-group">
                        <label>Gambar Soal</label>
                        <input type="file" name="question_image_file" class="form-control question-image" accept="image/*" />
                        <input type="hidden" name="question_image" class="question-image-hidden" />
                        <div id="question_image_preview" class="mt-2"></div>
                        <i class="form-group__bar"></i>
                    </div>

                    <!-- Bagian Pilihan Ganda -->
                    <div id="multiple-choice-section">
                        <div class="row" id="option-a-container">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label required>Pilihan A</label>
                                    <input type="text" name="option_a" class="form-control question-option_a" placeholder="Pilihan A" required />
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Pilihan A</label>
                                    <input type="file" name="option_a_image_file" class="form-control mt-2" accept="image/*" />
                                    <input type="hidden" name="option_a_image" class="option_a_image-hidden" />
                                    <div id="option_a_image_preview" class="mt-2"></div>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="option-b-container">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label required>Pilihan B</label>
                                    <input type="text" name="option_b" class="form-control question-option_b" placeholder="Pilihan B" required />
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Pilihan B</label>
                                    <input type="file" name="option_b_image_file" class="form-control mt-2" accept="image/*" />
                                    <input type="hidden" name="option_b_image" class="option_b_image-hidden" />
                                    <div id="option_b_image_preview" class="mt-2"></div>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="option-c-container">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label required>Pilihan C</label>
                                    <input type="text" name="option_c" class="form-control question-option_c" placeholder="Pilihan C" required />
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Pilihan C</label>
                                    <input type="file" name="option_c_image_file" class="form-control mt-2" accept="image/*" />
                                    <input type="hidden" name="option_c_image" class="option_c_image-hidden" />
                                    <div id="option_c_image_preview" class="mt-2"></div>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="option-d-container">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label required>Pilihan D</label>
                                    <input type="text" name="option_d" class="form-control question-option_d" placeholder="Pilihan D" required />
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Pilihan D</label>
                                    <input type="file" name="option_d_image_file" class="form-control mt-2" accept="image/*" />
                                    <input type="hidden" name="option_d_image" class="option_d_image-hidden" />
                                    <div id="option_d_image_preview" class="mt-2"></div>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="option-e-container">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label required>Pilihan E</label>
                                    <input type="text" name="option_e" class="form-control question-option_e" placeholder="Pilihan E" required />
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Pilihan E</label>
                                    <input type="file" name="option_e_image_file" class="form-control mt-2" accept="image/*" />
                                    <input type="hidden" name="option_e_image" class="option_e_image-hidden" />
                                    <div id="option_e_image_preview" class="mt-2"></div>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label required>Jawaban Benar</label>
                                    <div class="select">
                                        <select name="correct_option" class="form-control question-correct_option" required>
                                            <option value="">Pilih</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                            <option value="E">E</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Esai -->
                    <div id="essay-section" style="display:none;">
                        <div class="form-group">
                            <label>Kata Kunci Jawaban Esai</label>
                            <textarea id="expected_keywords" name="expected_keywords" class="form-control" rows="3" placeholder="Masukkan kata kunci yang diharapkan dalam jawaban, satu per baris"></textarea>
                            <small class="form-text text-muted">Gunakan format JSON: [{"word":"kata kunci 1","score":1},{"word":"kata kunci 2","score":2}] atau tulis satu kata kunci per baris</small>
                        </div>
                        <div class="form-group">
                            <label>Minimal Cocok Kata Kunci</label>
                            <input type="number" name="min_keyword_matches" class="form-control" value="1" min="0" />
                            <small class="form-text text-muted">Jumlah minimal kata kunci yang harus muncul dalam jawaban</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Pembahasan</label>
                        <textarea name="explanation" class="form-control question-explanation" rows="3" placeholder="Penjelasan jawaban (opsional)"></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>URL Video Pembahasan</label>
                        <input type="url" name="video_explanation_url" class="form-control question-video_explanation_url" placeholder="https://www.youtube.com/embed/xxx (opsional)" />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-success btn--icon-text question-action-save">
                            <i class="zmdi zmdi-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('question') ?>" class="btn btn-light btn--icon-text">
                            Batal
                        </a>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                        Gambar opsional dan dapat digunakan bersamaan dengan teks atau tanpa teks.
                    </small>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handler untuk mengganti tampilan opsi berdasarkan tipe opsi
    $('#question_option_type').change(function() {
        var optionType = $(this).val();
        
        if (optionType === 'text') {
            // Sembunyikan input file gambar dan tampilkan input teks
            $('.row[id*="option-"]').find('.col-md-6:last-child').hide(); // Sembunyikan kolom gambar
            $('.row[id*="option-"]').find('.col-md-6:first-child').show(); // Tampilkan kolom teks
            $('.row[id*="option-"]').find('.col-md-6:first-child input').prop('required', true); // Wajibkan teks
        } else if (optionType === 'image') {
            // Sembunyikan input teks dan tampilkan input file gambar
            $('.row[id*="option-"]').find('.col-md-6:first-child').hide(); // Sembunyikan kolom teks
            $('.row[id*="option-"]').find('.col-md-6:last-child').show(); // Tampilkan kolom gambar
            $('.row[id*="option-"]').find('.col-md-6:first-child input').prop('required', false); // Hilangkan wajib teks
        }
    });
    
    // Handler untuk mengganti tampilan berdasarkan jenis soal
    $('.question-type').change(function() {
        var type = $(this).val();
        if (type === 'essay') {
            $('#multiple-choice-section').hide();
            $('#essay-section').show();
        } else {
            $('#multiple-choice-section').show();
            $('#essay-section').hide();
        }
    });

    <?php if($question_data): ?>
    // Jika sedang mengedit, isi data dari model
    $('.question-subject_id').val(<?= $question_data->subject_id ?>).trigger('change.select2');
    setTimeout(function() {
        $('.question-chapter_id').val(<?= $question_data->chapter_id ?>).trigger('change.select2');
        setTimeout(function() {
            $('.question-topic_id').val(<?= $question_data->topic_id ?>).trigger('change.select2');
        }, 300);
    }, 300);

    $('.question-difficulty').val('<?= $question_data->difficulty ?>');
    $('.question-curriculum').val('<?= $question_data->curriculum ?>');
    $('.question-type').val('<?= $question_data->question_type ?>').trigger('change');
    $('.question-option_type').val('<?= $question_data->option_type ?>').trigger('change');
    
    // Atur tampilan berdasarkan jenis soal
    if ('<?= $question_data->question_type ?>' === 'essay') {
        $('#multiple-choice-section').hide();
        $('#essay-section').show();
    } else {
        $('#multiple-choice-section').show();
        $('#essay-section').hide();
    }
    
    // Isi teks soal
    $('.question-question_text').val('<?= addslashes($question_data->question_text) ?>');
    if (typeof tinymce !== 'undefined' && tinymce.activeEditor) {
        tinymce.activeEditor.setContent('<?= addslashes($question_data->question_text) ?>');
    } else {
        // Jika TinyMCE belum aktif, atur timeout
        setTimeout(function() {
            if (typeof tinymce !== 'undefined' && tinymce.activeEditor) {
                tinymce.activeEditor.setContent('<?= addslashes($question_data->question_text) ?>');
            } else {
                $('.question-question_text').val('<?= addslashes($question_data->question_text) ?>');
            }
        }, 1000);
    }

    // Isi gambar soal jika ada
    if ('<?= $question_data->question_image ?>') {
        $('.question-image-hidden').val('<?= $question_data->question_image ?>');
        $('#question_image_preview').html('<img src="<?= base_url($question_data->question_image) ?>" style="max-width: 200px; max-height: 200px;" />');
    }

    // Isi data pilihan ganda jika soal bukan essay
    if ('<?= $question_data->question_type ?>' === 'multiple_choice') {
        $('.question-option_a').val('<?= addslashes($question_data->option_a) ?>');
        $('.question-option_b').val('<?= addslashes($question_data->option_b) ?>');
        $('.question-option_c').val('<?= addslashes($question_data->option_c) ?>');
        $('.question-option_d').val('<?= addslashes($question_data->option_d) ?>');
        $('.question-option_e').val('<?= addslashes($question_data->option_e) ?>');
        $('.question-correct_option').val('<?= $question_data->correct_option ?>');
        
        // Tampilkan gambar pilihan jika ada
        if ('<?= $question_data->option_a_image ?>') {
            $('.option_a_image-hidden').val('<?= $question_data->option_a_image ?>');
            $('#option_a_image_preview').html('<img src="<?= base_url($question_data->option_a_image) ?>" style="max-width: 200px; max-height: 200px;" />');
        }
        if ('<?= $question_data->option_b_image ?>') {
            $('.option_b_image-hidden').val('<?= $question_data->option_b_image ?>');
            $('#option_b_image_preview').html('<img src="<?= base_url($question_data->option_b_image) ?>" style="max-width: 200px; max-height: 200px;" />');
        }
        if ('<?= $question_data->option_c_image ?>') {
            $('.option_c_image-hidden').val('<?= $question_data->option_c_image ?>');
            $('#option_c_image_preview').html('<img src="<?= base_url($question_data->option_c_image) ?>" style="max-width: 200px; max-height: 200px;" />');
        }
        if ('<?= $question_data->option_d_image ?>') {
            $('.option_d_image-hidden').val('<?= $question_data->option_d_image ?>');
            $('#option_d_image_preview').html('<img src="<?= base_url($question_data->option_d_image) ?>" style="max-width: 200px; max-height: 200px;" />');
        }
        if ('<?= $question_data->option_e_image ?>') {
            $('.option_e_image-hidden').val('<?= $question_data->option_e_image ?>');
            $('#option_e_image_preview').html('<img src="<?= base_url($question_data->option_e_image) ?>" style="max-width: 200px; max-height: 200px;" />');
        }
    } 
    // Isi data essay jika soal essay
    else if ('<?= $question_data->question_type ?>' === 'essay') {
        if ('<?= $question_data->expected_keywords ?>') {
            $('#expected_keywords').val('<?= addslashes($question_data->expected_keywords) ?>');
        }
        if ('<?= $question_data->min_keyword_matches ?>') {
            $('input[name="min_keyword_matches"]').val('<?= $question_data->min_keyword_matches ?>');
        }
    }

    // Isi pembahasan dan video
    if ('<?= $question_data->explanation ?>') {
        $('.question-explanation').val('<?= addslashes($question_data->explanation) ?>');
    }
    if ('<?= $question_data->video_explanation_url ?>') {
        $('.question-video_explanation_url').val('<?= $question_data->video_explanation_url ?>');
    }
    <?php endif; ?>
    
    // Trigger change untuk menyesuaikan tampilan saat halaman dimuat
    setTimeout(function() {
        $('#question_option_type').trigger('change');
    }, 500);
});
</script>
