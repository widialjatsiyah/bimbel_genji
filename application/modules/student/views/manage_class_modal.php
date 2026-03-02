<!-- Modal untuk manajemen kelas -->
<div class="modal fade" id="modal-manage-class" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manajemen Kelas untuk <span id="student-name-display"></span></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Kelas Terdaftar</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="student-class-list">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="70%">Nama Kelas</th>
                                        <th width="25%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Isi tabel akan dimuat melalui AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Tambahkan Kelas Baru</h6>
                        <div class="form-group">
                            <label>Pilih Kelas:</label>
                            <select id="available-classes" class="form-control select2" style="width: 100%;">
                                <option value="">-- Pilih Kelas --</option>
                                <!-- Opsi akan dimuat melalui AJAX -->
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="button" id="btn-add-class" class="btn btn-primary">Tambahkan ke Kelas</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>