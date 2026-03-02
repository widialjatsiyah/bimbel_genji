<div class="modal fade" id="modal-form-question" data-backdrop="static" data-keyboard="false" style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Soal' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
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

                    <div class="form-group">
                        <label required>Teks Soal</label>
                        <textarea name="question_text" class="form-control question-question_text tinymce-init" rows="4" placeholder="Tulis soal di sini..." required></textarea>
                        <i class="form-group__bar"></i>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Pilihan A</label>
                                <input type="text" name="option_a" class="form-control question-option_a" placeholder="Pilihan A" required />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Pilihan B</label>
                                <input type="text" name="option_b" class="form-control question-option_b" placeholder="Pilihan B" required />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Pilihan C</label>
                                <input type="text" name="option_c" class="form-control question-option_c" placeholder="Pilihan C" required />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Pilihan D</label>
                                <input type="text" name="option_d" class="form-control question-option_d" placeholder="Pilihan D" required />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Pilihan E</label>
                                <input type="text" name="option_e" class="form-control question-option_e" placeholder="Pilihan E" required />
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

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text question-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
