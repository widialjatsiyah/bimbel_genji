                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Tipe Opsi</label>
                                <select name="option_type" class="form-control" id="option_type">
                                    <option value="text">Teks</option>
                                    <option value="image">Gambar</option>
                                </select>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Pilihan A</label>
                                <input type="text" name="option_a" class="form-control question-option_a" placeholder="Isi pilihan A (teks atau path gambar)" required />
                                <div class="option-a-upload" style="display:none; margin-top: 10px;">
                                    <label>Unggah Gambar A</label>
                                    <input type="file" name="option_a_file" class="form-control" accept="image/*" />
                                    <small class="text-muted">Gunakan ini untuk mengunggah gambar pilihan A</small>
                                </div>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Pilihan B</label>
                                <input type="text" name="option_b" class="form-control question-option_b" placeholder="Isi pilihan B (teks atau path gambar)" required />
                                <div class="option-b-upload" style="display:none; margin-top: 10px;">
                                    <label>Unggah Gambar B</label>
                                    <input type="file" name="option_b_file" class="form-control" accept="image/*" />
                                    <small class="text-muted">Gunakan ini untuk mengunggah gambar pilihan B</small>
                                </div>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Pilihan C</label>
                                <input type="text" name="option_c" class="form-control question-option_c" placeholder="Isi pilihan C (teks atau path gambar)" required />
                                <div class="option-c-upload" style="display:none; margin-top: 10px;">
                                    <label>Unggah Gambar C</label>
                                    <input type="file" name="option_c_file" class="form-control" accept="image/*" />
                                    <small class="text-muted">Gunakan ini untuk mengunggah gambar pilihan C</small>
                                </div>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Pilihan D</label>
                                <input type="text" name="option_d" class="form-control question-option_d" placeholder="Isi pilihan D (teks atau path gambar)" required />
                                <div class="option-d-upload" style="display:none; margin-top: 10px;">
                                    <label>Unggah Gambar D</label>
                                    <input type="file" name="option_d_file" class="form-control" accept="image/*" />
                                    <small class="text-muted">Gunakan ini untuk mengunggah gambar pilihan D</small>
                                </div>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Pilihan E</label>
                                <input type="text" name="option_e" class="form-control question-option_e" placeholder="Isi pilihan E (teks atau path gambar)" required />
                                <div class="option-e-upload" style="display:none; margin-top: 10px;">
                                    <label>Unggah Gambar E</label>
                                    <input type="file" name="option_e_file" class="form-control" accept="image/*" />
                                    <small class="text-muted">Gunakan ini untuk mengunggah gambar pilihan E</small>
                                </div>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            function toggleOptionFields() {
                                var optionType = $('#option_type').val();
                                
                                if(optionType === 'image') {
                                    $('.option-a-upload, .option-b-upload, .option-c-upload, .option-d-upload, .option-e-upload').show();
                                } else {
                                    $('.option-a-upload, .option-b-upload, .option-c-upload, .option-d-upload, .option-e-upload').hide();
                                }
                            }
                            
                            // Initial check
                            toggleOptionFields();
                            
                            // On change
                            $('#option_type').change(function() {
                                toggleOptionFields();
                            });
                        });
                    </script>