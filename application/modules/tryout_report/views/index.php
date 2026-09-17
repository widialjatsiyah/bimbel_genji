<section id="tryout_report">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Try Out</label>
                        <select id="filter-tryout" class="form-control tryout_report-filter-tryout">
                            <option value="">-- Semua Try Out --</option>
                            <?php if (!empty($tryouts)): ?>
                                <?php foreach ($tryouts as $t): ?>
                                    <option value="<?= $t->id ?>"><?= htmlspecialchars($t->title) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Sesi</label>
                        <select id="filter-session" class="form-control tryout_report-filter-session">
                            <option value="">-- Semua Sesi --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-end">
                    <button id="btn-export-excel" class="btn btn-success btn--icon-text tryout_report-action-export-excel" style="margin-right: 8px;">
                        <i class="zmdi zmdi-file-excel"></i> Export Excel
                    </button>
                    <button id="btn-export-csv" class="btn btn-secondary btn--icon-text tryout_report-action-export-csv">
                        <i class="zmdi zmdi-download"></i> Export CSV
                    </button>
                </div>
            </div>

            <!-- Statistik ringkasan -->
            <div class="row mb-3" id="summary-row">
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card-mini">
                        <div class="stats-label">Peserta</div>
                        <div class="stats-value" id="stat-participants">0</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card-mini">
                        <div class="stats-label">Total Percobaan</div>
                        <div class="stats-value" id="stat-attempts">0</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card-mini">
                        <div class="stats-label">Rata-rata Skor</div>
                        <div class="stats-value" id="stat-avg">0</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card-mini">
                        <div class="stats-label">Skor Tertinggi</div>
                        <div class="stats-value" id="stat-high">0</div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="table-tryout-report" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="60">Peringkat</th>
                            <th>Nama Lengkap</th>
                            <th>Try Out</th>
                            <th>Sesi</th>
                            <th width="100">Skor</th>
                            <th width="100">Status</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>

<style>
    .stats-card-mini {
        background: #f7f9fc;
        border: 1px solid #e6ebf1;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
    }
    .stats-card-mini .stats-label {
        font-size: 12px;
        color: #6c7a89;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stats-card-mini .stats-value {
        font-size: 24px;
        font-weight: 700;
        color: #0066cc;
        margin-top: 5px;
    }
    .rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        font-weight: 700;
        color: #fff;
    }
    .rank-1 { background: linear-gradient(135deg, #ffd700, #ffb700); }
    .rank-2 { background: linear-gradient(135deg, #c0c0c0, #a0a0a0); }
    .rank-3 { background: linear-gradient(135deg, #cd7f32, #b8722d); }
    .rank-other { background: #6c7a89; }
</style>
