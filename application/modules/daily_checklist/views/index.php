<section id="daily_checklist">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>Checklist Hari Ini: <?= date('d F Y') ?></h5>
                            <form id="form-checklist">
                                <!-- CSRF -->
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                <input type="hidden" name="date" value="<?= date('Y-m-d') ?>" />

                                <div class="form-group">
                                    <label>Ibadah</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="shalat_subuh" id="shalat_subuh" value="1" <?= isset($today_data) && json_decode($today_data->checklist_data)->shalat_subuh ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="shalat_subuh">Shalat Subuh</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="shalat_dzuhur" id="shalat_dzuhur" value="1" <?= isset($today_data) && json_decode($today_data->checklist_data)->shalat_dzuhur ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="shalat_dzuhur">Shalat Dzuhur</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="shalat_ashar" id="shalat_ashar" value="1" <?= isset($today_data) && json_decode($today_data->checklist_data)->shalat_ashar ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="shalat_ashar">Shalat Ashar</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="shalat_maghrib" id="shalat_maghrib" value="1" <?= isset($today_data) && json_decode($today_data->checklist_data)->shalat_maghrib ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="shalat_maghrib">Shalat Maghrib</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="shalat_isya" id="shalat_isya" value="1" <?= isset($today_data) && json_decode($today_data->checklist_data)->shalat_isya ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="shalat_isya">Shalat Isya</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="tilawah" id="tilawah" value="1" <?= isset($today_data) && json_decode($today_data->checklist_data)->tilawah ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="tilawah">Tilawah (Membaca Al-Qur'an)</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Aktivitas Lain</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="olahraga" id="olahraga" value="1" <?= isset($today_data) && json_decode($today_data->checklist_data)->olahraga ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="olahraga">Olahraga</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="membaca_buku" id="membaca_buku" value="1" <?= isset($today_data) && json_decode($today_data->checklist_data)->membaca_buku ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="membaca_buku">Membaca Buku (non-pelajaran)</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Durasi Belajar (menit)</label>
                                    <input type="number" name="belajar_menit" class="form-control" min="0" value="<?= isset($today_data) ? json_decode($today_data->checklist_data)->belajar_menit : '' ?>" placeholder="Misal: 120">
                                </div>

                                <div class="form-group">
                                    <label>Mood Hari Ini (1-10)</label>
                                    <select name="mood_rating" class="form-control">
                                        <option value="">Pilih</option>
                                        <?php for ($i=1; $i<=10; $i++): ?>
                                        <option value="<?= $i ?>" <?= isset($today_data) && $today_data->mood_rating == $i ? 'selected' : '' ?>><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Catatan / Refleksi</label>
                                    <textarea name="notes" class="form-control" rows="3"><?= isset($today_data) ? $today_data->notes : '' ?></textarea>
                                </div>

                                <button type="button" class="btn btn-primary" id="btn-save-checklist">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>Riwayat Checklist</h5>
                            <div id="history-container">
                                <!-- akan diisi JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
