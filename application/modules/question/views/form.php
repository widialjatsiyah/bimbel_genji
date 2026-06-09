<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form id="form-question" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mata Pelajaran *</label>
                                <select class="form-control select2 question-subject_id" name="subject_id" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    <?php foreach($subjects as $subject): ?>
                                        <option value="<?= $subject->id ?>"><?= $subject->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bab *</label>
                                <select class="form-control select2 question-chapter_id" name="chapter_id" required>
                                    <option value="">-- Pilih Bab --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Topik *</label>
                                <select class="form-control select2 question-topic_id" name="topic_id" required>
                                    <option value="">-- Pilih Topik --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Soal *</label>
                                <select class="form-control select2 question-type" name="question_type" id="question_type" required>
                                    <option value="">-- Pilih Jenis Soal --</option>
                                    <option value="multiple_choice">Pilihan Ganda</option>
                                    <option value="essay">Esai</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipe Opsi</label>
                                <select class="form-control select2 question-option_type" name="option_type">
                                    <option value="text">Teks</option>
                                    <option value="image">Gambar</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gambar Soal</label>
                                <input type="file" class="form-control question-image" name="question_image_file" accept="image/*">
                                <input type="hidden" class="question-image-hidden" name="question_image" value="">
                                <div id="question_image_preview"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Group Fields -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Grup ID</label>
                                <input type="number" class="form-control question-group_id" name="group_id" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Urutan Grup</label>
                                <input type="number" class="form-control question-group_order" name="group_order" value="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Soal Utama Grup</label>
                                <select class="form-control question-is_group_main" name="is_group_main">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Soal *</label>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Soal</span>
                            <a href="https://www.codecogs.com/latex/eqneditor.php" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-calculator"></i> Online LaTeX Editor
                            </a>
                        </div>
                        <textarea class="form-control tinymce-init question-question_text" id="question_text" name="question_text" rows="5"></textarea>
                        <small class="form-text text-muted">Gunakan format LaTeX untuk rumus matematika. Contoh: $x = \frac{-b \pm \sqrt{b^2-4ac}}{2a}$</small>
                    </div>

                    <!-- Multiple Choice Section -->
                    <div id="multiple-choice-section">
                        <hr>
                        <h5>Opsi Jawaban</h5>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Opsi Jawaban</span>
                            <a href="https://www.codecogs.com/latex/eqneditor.php" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-calculator"></i> Online LaTeX Editor
                            </a>
                        </div>
                        <div class="row" id="option-a">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Opsi A</label>
                                    <input type="text" class="form-control question-option_a" name="option_a">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Opsi A</label>
                                    <input type="file" class="form-control option_a_image" name="option_a_image_file" accept="image/*">
                                    <input type="hidden" class="option_a_image-hidden" name="option_a_image" value="">
                                    <div id="option_a_image_preview"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="option-b">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Opsi B</label>
                                    <input type="text" class="form-control question-option_b" name="option_b">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Opsi B</label>
                                    <input type="file" class="form-control option_b_image" name="option_b_image_file" accept="image/*">
                                    <input type="hidden" class="option_b_image-hidden" name="option_b_image" value="">
                                    <div id="option_b_image_preview"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="option-c">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Opsi C</label>
                                    <input type="text" class="form-control question-option_c" name="option_c">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Opsi C</label>
                                    <input type="file" class="form-control option_c_image" name="option_c_image_file" accept="image/*">
                                    <input type="hidden" class="option_c_image-hidden" name="option_c_image" value="">
                                    <div id="option_c_image_preview"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="option-d">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Opsi D</label>
                                    <input type="text" class="form-control question-option_d" name="option_d">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Opsi D</label>
                                    <input type="file" class="form-control option_d_image" name="option_d_image_file" accept="image/*">
                                    <input type="hidden" class="option_d_image-hidden" name="option_d_image" value="">
                                    <div id="option_d_image_preview"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="option-e">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Opsi E</label>
                                    <input type="text" class="form-control question-option_e" name="option_e">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Opsi E</label>
                                    <input type="file" class="form-control option_e_image" name="option_e_image_file" accept="image/*">
                                    <input type="hidden" class="option_e_image-hidden" name="option_e_image" value="">
                                    <div id="option_e_image_preview"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jawaban Benar *</label>
                            <select class="form-control select2 question-correct_option" name="correct_option" required>
                                <option value="">-- Pilih Jawaban Benar --</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                            </select>
                        </div>
                    </div>

                    <!-- Essay Section -->
                    <div id="essay-section" style="display: none;">
                        <hr>
                        <h5>Penilaian Esai</h5>
                        <div class="form-group">
                            <label>Kata Kunci yang Diharapkan (JSON)</label>
                            <textarea class="form-control" id="expected_keywords" name="expected_keywords" rows="4" placeholder='Contoh: [{"word":"kata1","score":1},{"word":"kata2","score":2}]'></textarea>
                        </div>
                        <div class="form-group">
                            <label>Kata Kunci yang Diharapkan (Format Sederhana)</label>
                            <textarea class="form-control" id="expected_keywords_simple" rows="4" placeholder="Format: kata_kunci=skor&#10;contoh: evolusi=2&#10;seleksi alam=1"></textarea>
                            <small class="form-text text-muted">Satu baris per kata kunci, format: kata_kunci=skor</small>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Minimum Kata Kunci yang Cocok</label>
                            <input type="number" class="form-control" name="min_keyword_matches" min="0" value="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Penjelasan</label>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Penjelasan</span>
                            <a href="https://www.codecogs.com/latex/eqneditor.php" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-calculator"></i> Online LaTeX Editor
                            </a>
                        </div>
                        <textarea class="form-control tinymce-init question-explanation" name="explanation" rows="5"></textarea>
                        <small class="form-text text-muted">Gunakan format LaTeX untuk rumus matematika. Contoh: $x = \frac{-b \pm \sqrt{b^2-4ac}}{2a}$</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Video Penjelasan URL</label>
                        <input type="text" class="form-control question-video_explanation_url" name="video_explanation_url" placeholder="https://youtube.com/...">
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-success btn-labeled question-action-save">
                            <b><i class="ti-save"></i></b> Simpan
                        </button>
                        <a href="<?= base_url('question') ?>" class="btn btn-inverse btn-labeled">
                            <b><i class="ti-back-left"></i></b> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>