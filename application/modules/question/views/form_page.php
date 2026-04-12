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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label required>Pilihan A</label>
                                    <input type="text" name="option_a" class="form-control question-option_a" placeholder="Pilihan A" required />
                                    <!-- Gambar Pilihan A -->
                                    <input type="file" name="option_a_image_file" class="form-control mt-2" accept="image/*" />
                                    <input type="hidden" name="option_a_image" class="option_a_image-hidden" />
                                    <div id="option_a_image_preview" class="mt-2"></div>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label required>Pilihan B</label>
                                    <input type="text" name="option_b" class="form-control question-option_b" placeholder="Pilihan B" required />
                                    <!-- Gambar Pilihan B -->
                                    <input type="file" name="option_b_image_file" class="form-control mt-2" accept="image/*" />
                                    <input type="hidden" name="option_b_image" class="option_b_image-hidden" />
                                    <div id="option_b_image_preview" class="mt-2"></div>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label required>Pilihan C</label>
                                    <input type="text" name="option_c" class="form-control question-option_c" placeholder="Pilihan C" required />
                                    <!-- Gambar Pilihan C -->
                                    <input type="file" name="option_c_image_file" class="form-control mt-2" accept="image/*" />
                                    <input type="hidden" name="option_c_image" class="option_c_image-hidden" />
                                    <div id="option_c_image_preview" class="mt-2"></div>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label required>Pilihan D</label>
                                    <input type="text" name="option_d" class="form-control question-option_d" placeholder="Pilihan D" required />
                                    <!-- Gambar Pilihan D -->
                                    <input type="file" name="option_d_image_file" class="form-control mt-2" accept="image/*" />
                                    <input type="hidden" name="option_d_image" class="option_d_image-hidden" />
                                    <div id="option_d_image_preview" class="mt-2"></div>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label required>Pilihan E</label>
                                    <input type="text" name="option_e" class="form-control question-option_e" placeholder="Pilihan E" required />
                                    <!-- Gambar Pilihan E -->
                                    <input type="file" name="option_e_image_file" class="form-control mt-2" accept="image/*" />
                                    <input type="hidden" name="option_e_image" class="option_e_image-hidden" />
                                    <div id="option_e_image_preview" class="mt-2"></div>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const questionTypeSelect = document.getElementById('question_type');
        const mcSection = document.getElementById('multiple-choice-section');
        const essaySection = document.getElementById('essay-section');
        
        questionTypeSelect.addEventListener('change', function() {
            if (this.value === 'essay') {
                mcSection.style.display = 'none';
                essaySection.style.display = 'block';
            } else {
                mcSection.style.display = 'block';
                essaySection.style.display = 'none';
            }
        });
    });
</script>
