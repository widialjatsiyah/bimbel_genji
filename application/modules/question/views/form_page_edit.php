<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Ubah Soal</h4>

                <form id="form-question" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <input type="hidden" name="id" value="<?php echo $question_data->id; ?>" />

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Mata Pelajaran</label>
                                <div class="select">
                                    <select name="subject_id" class="form-control select2 question-subject_id" data-placeholder="Pilih Mata Pelajaran" required>
                                        <?php echo $list_subject; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bab</label>
                                <div class="select">
                                    <select name="chapter_id" class="form-control select2 question-chapter_id" data-placeholder="Pilih Bab (opsional)">
                                        <?php echo $list_chapter; ?>
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
                                        <?php echo $list_topic; ?>
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
                                                <option value="mudah" <?php echo ($question_data->difficulty === 'mudah') ? 'selected' : ''; ?>>Mudah</option>
                                                <option value="sedang" <?php echo ($question_data->difficulty === 'sedang') ? 'selected' : ''; ?>>Sedang</option>
                                                <option value="sulit" <?php echo ($question_data->difficulty === 'sulit') ? 'selected' : ''; ?>>Sulit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label required>Kurikulum</label>
                                        <div class="select">
                                            <select name="curriculum" class="form-control question-curriculum" required>
                                                <option value="Kurikulum Merdeka" <?php echo ($question_data->curriculum === 'Kurikulum Merdeka') ? 'selected' : ''; ?>>Kurikulum Merdeka</option>
                                                <option value="K13" <?php echo ($question_data->curriculum === 'K13') ? 'selected' : ''; ?>>K13</option>
                                                <option value="Lainnya" <?php echo ($question_data->curriculum === 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
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
                                <div class="select">
                                    <select name="option_type" class="form-control question-option_type" id="question_option_type" required>
                                        <option value="text" <?php echo ($question_data->option_type === 'text') ? 'selected' : ''; ?>>Teks</option>
                                        <option value="image" <?php echo ($question_data->option_type === 'image') ? 'selected' : ''; ?>>Gambar</option>
                                    </select>
                                </div>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Jenis Soal</label>
                                <div class="select">
                                    <select name="question_type" class="form-control question-type" id="question_type" required>
                                        <option value="multiple_choice" <?php echo ($question_data->question_type === 'multiple_choice') ? 'selected' : ''; ?>>Pilihan Ganda</option>
                                        <option value="essay" <?php echo ($question_data->question_type === 'essay') ? 'selected' : ''; ?>>Esai</option>
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
                                <input type="text" name="group_id" class="form-control question-group_id" placeholder="ID Grup (opsional)" value="<?php echo htmlspecialchars($question_data->group_id); ?>" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Urutan dalam Grup</label>
                                <input type="number" name="group_order" class="form-control question-group_order" min="1" value="<?php echo htmlspecialchars($question_data->group_order ?: 1); ?>" placeholder="Nomor urut dalam grup" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Soal Utama Grup</label>
                                <div class="select">
                                    <select name="is_group_main" class="form-control question-is_group_main">
                                        <option value="0" <?php echo (!$question_data->is_group_main) ? 'selected' : ''; ?>>Biasa</option>
                                        <option value="1" <?php echo ($question_data->is_group_main) ? 'selected' : ''; ?>>Utama (Memiliki Narasi)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label required>Teks Soal</label>
                        <textarea id="question_text" name="question_text" class="form-control question-question_text tinymce-init" rows="4" placeholder="Tulis soal di sini..." required><?php echo htmlspecialchars($question_data->question_text); ?></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <!-- Gambar Soal -->
                    <div class="form-group">
                        <label>Gambar Soal</label>
                        <input type="file" name="question_image_file" class="form-control question-image" accept="image/*" />
                        <input type="hidden" name="question_image" class="question-image-hidden" value="<?php echo htmlspecialchars($question_data->question_image); ?>" />
                        <div id="question_image_preview" class="mt-2">
                            <?php if($question_data->question_image): ?>
                                <img src="<?php echo base_url($question_data->question_image); ?>" style="max-width: 200px; max-height: 200px;" />
                            <?php endif; ?>
                        </div>
                        <i class="form-group__bar"></i>
                    </div>

                    <!-- Bagian Pilihan Ganda -->
                    <div id="multiple-choice-section">
                        <div class="row" id="option-a-container">
                            <div class="col-md-6 option-text-input">
                                <div class="form-group">
                                    <label class="option-label" required>Pilihan A</label>
                                    <input type="text" name="option_a" class="form-control question-option_a" placeholder="Pilihan A" value="<?php echo htmlspecialchars($question_data->option_a); ?>" />
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                            <div class="col-md-6 option-image-input">
                                <div class="form-group">
                                    <label class="option-image-label">Gambar Pilihan A</label>
                                    <input type="file" name="option_a_image_file" class="form-control" accept="image/*" />
                                    <input type="hidden" name="option_a_image" class="option_a_image-hidden" value="<?php echo htmlspecialchars($question_data->option_a); ?>" />
                                    <div id="option_a_image_preview" class="mt-2">
                                        <?php if($question_data->option_a): ?>
                                            <img src="<?php echo base_url($question_data->option_a); ?>" style="max-width: 200px; max-height: 200px;" />
                                        <?php endif; ?>
                                    </div>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="option-b-container">
                            <div class="col-md-6 option-text-input">
                                <div class="form-group">
                                    <label class="option-label" required>Pilihan B</label>
                                    <input type="text" name="option_b" class="form-control question-option_b" placeholder="Pilihan B" value="<?php echo htmlspecialchars($question_data->option_b); ?>" />
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                            <div class="col-md-6 option-image-input">
                                <div class="form-group">
                                    <label class="option-image-label">Gambar Pilihan B</label>
                                    <input type="file" name="option_b_image_file" class="form-control" accept="image/*" />
                                    <input type="hidden" name="option_b_image" class="option_b_image-hidden" value="<?php echo htmlspecialchars($question_data->option_b); ?>" />
                                    <div id="option_b_image_preview" class="mt-2">
                                        <?php if($question_data->option_b): ?>
                                            <img src="<?php echo base_url($question_data->option_b); ?>" style="max-width: 200px; max-height: 200px;" />
                                        <?php endif; ?>
                                    </div>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="option-c-container">
                            <div class="col-md-6 option-text-input">
                                <div class="form-group">
                                    <label class="option-label" required>Pilihan C</label>
                                    <input type="text" name="option_c" class="form-control question-option_c" placeholder="Pilihan C" value="<?php echo htmlspecialchars($question_data->option_c); ?>" />
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                            <div class="col-md-6 option-image-input">
                                <div class="form-group">
                                    <label class="option-image-label">Gambar Pilihan C</label>
                                    <input type="file" name="option_c_image_file" class="form-control" accept="image/*" />
                                    <input type="hidden" name="option_c_image" class="option_c_image-hidden" value="<?php echo htmlspecialchars($question_data->option_c); ?>" />
                                    <div id="option_c_image_preview" class="mt-2">
                                        <?php if($question_data->option_c): ?>
                                            <img src="<?php echo base_url($question_data->option_c); ?>" style="max-width: 200px; max-height: 200px;" />
                                        <?php endif; ?>
                                    </div>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="option-d-container">
                            <div class="col-md-6 option-text-input">
                                <div class="form-group">
                                    <label class="option-label" required>Pilihan D</label>
                                    <input type="text" name="option_d" class="form-control question-option_d" placeholder="Pilihan D" value="<?php echo htmlspecialchars($question_data->option_d); ?>" />
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                            <div class="col-md-6 option-image-input">
                                <div class="form-group">
                                    <label class="option-image-label">Gambar Pilihan D</label>
                                    <input type="file" name="option_d_image_file" class="form-control" accept="image/*" />
                                    <input type="hidden" name="option_d_image" class="option_d_image-hidden" value="<?php echo htmlspecialchars($question_data->option_d); ?>" />
                                    <div id="option_d_image_preview" class="mt-2">
                                        <?php if($question_data->option_d): ?>
                                            <img src="<?php echo base_url($question_data->option_d); ?>" style="max-width: 200px; max-height: 200px;" />
                                        <?php endif; ?>
                                    </div>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="option-e-container">
                            <div class="col-md-6 option-text-input">
                                <div class="form-group">
                                    <label class="option-label" required>Pilihan E</label>
                                    <input type="text" name="option_e" class="form-control question-option_e" placeholder="Pilihan E" value="<?php echo htmlspecialchars($question_data->option_e); ?>" />
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                            <div class="col-md-6 option-image-input">
                                <div class="form-group">
                                    <label class="option-image-label">Gambar Pilihan E</label>
                                    <input type="file" name="option_e_image_file" class="form-control" accept="image/*" />
                                    <input type="hidden" name="option_e_image" class="option_e_image-hidden" value="<?php echo htmlspecialchars($question_data->option_e); ?>" />
                                    <div id="option_e_image_preview" class="mt-2">
                                        <?php if($question_data->option_e): ?>
                                            <img src="<?php echo base_url($question_data->option_e); ?>" style="max-width: 200px; max-height: 200px;" />
                                        <?php endif; ?>
                                    </div>
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
                                            <option value="A" <?php echo ($question_data->correct_option === 'A') ? 'selected' : ''; ?>>A</option>
                                            <option value="B" <?php echo ($question_data->correct_option === 'B') ? 'selected' : ''; ?>>B</option>
                                            <option value="C" <?php echo ($question_data->correct_option === 'C') ? 'selected' : ''; ?>>C</option>
                                            <option value="D" <?php echo ($question_data->correct_option === 'D') ? 'selected' : ''; ?>>D</option>
                                            <option value="E" <?php echo ($question_data->correct_option === 'E') ? 'selected' : ''; ?>>E</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Esai -->
                    <div id="essay-section">
                        <div class="form-group">
                            <label>Kata Kunci Jawaban Esai</label>
                            <textarea id="expected_keywords_simple" class="form-control" rows="3" placeholder="Masukkan kata kunci satu per baris, format: kata kunci=skor (misalnya: pendidikan=2)"></textarea>
                            <small class="form-text text-muted">Masukkan kata kunci satu per baris. Format: kata kunci=skor (misalnya: pendidikan=2). Kosongkan jika ingin menetapkan dalam format JSON.</small>
                        </div>
                        <div class="form-group">
                            <label>Kata Kunci Jawaban Esai (Format JSON)</label>
                            <textarea id="expected_keywords" name="expected_keywords" class="form-control" rows="3" placeholder='Atau masukkan dalam format JSON: [{"word":"kata kunci 1","score":1},{"word":"kata kunci 2","score":2}]'><?php echo htmlspecialchars($question_data->expected_keywords); ?></textarea>
                            <small class="form-text text-muted">Gunakan format ini jika ingin lebih presisi: [{"word":"kata kunci 1","score":1},{"word":"kata kunci 2","score":2}]</small>
                        </div>
                        <div class="form-group">
                            <label>Minimal Cocok Kata Kunci</label>
                            <input type="number" name="min_keyword_matches" class="form-control" value="<?php echo htmlspecialchars($question_data->min_keyword_matches ?: 1); ?>" min="0" />
                            <small class="form-text text-muted">Jumlah minimal kata kunci yang harus muncul dalam jawaban</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Pembahasan</label>
                        <textarea name="explanation" class="form-control question-explanation" rows="3" placeholder="Penjelasan jawaban (opsional)"><?php echo htmlspecialchars($question_data->explanation); ?></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-group">
                        <label>URL Video Pembahasan</label>
                        <input type="url" name="video_explanation_url" class="form-control question-video_explanation_url" placeholder="https://www.youtube.com/embed/xxx (opsional)" value="<?php echo htmlspecialchars($question_data->video_explanation_url); ?>" />
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-success btn--icon-text question-action-save">
                            <i class="zmdi zmdi-save"></i> Simpan
                        </button>
                        <a href="<?php echo base_url('question') ?>" class="btn btn-light btn--icon-text">
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
