<section id="myrecommendations">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-action">
                <div class="buttons">
                    <button class="btn btn--raised btn-success btn--icon-text mark-all-read">
                        <i class="zmdi zmdi-check-all"></i> Tandai Semua Dibaca
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="table-myrecommendations" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Rekomendasi</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</section>
