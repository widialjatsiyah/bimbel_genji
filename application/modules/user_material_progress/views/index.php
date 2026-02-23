<section id="user_material_progress">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="row mb-3">
                <div class="col-md-4">
                    <select class="form-control select2" id="filter-user">
                        <option value="">-- Semua Siswa --</option>
                        <?php
                        $users = $this->UserModel->getAll(['role' => 'student'], 'nama_lengkap', 'asc');
                        foreach ($users as $u) {
                            echo '<option value="'.$u->id.'">'.$u->nama_lengkap.'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" id="btn-filter">Filter</button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="table-user_material_progress" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Siswa</th>
                            <th>Materi</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th>Progres (%)</th>
                            <th>Terakhir Akses</th>
                            <th>Selesai Pada</th>
                            <th width="100" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>
