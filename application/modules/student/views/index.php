<link href="<?php echo base_url('themes/assets/vendor/select2/css/select2.min.css');?>" rel="stylesheet" />
<style>
.select2-container {
    width: 100% !important;
}
</style>
<?php $card_title = isset($card_title) ? $card_title : 'Manajemen Siswa'; ?>

<section id="student">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?= $card_title ?></h4>
            <div class="table-action">
                <button class="btn btn-primary btn--icon-text student-action-add" data-toggle="modal" data-target="#modal-form-student">
                    <i class="zmdi zmdi-plus-circle"></i> Tambah Siswa
                </button>
                <button class="btn btn-success btn--icon-text" id="btn-import-excel">
                    <i class="zmdi zmdi-upload"></i> Import Excel
                </button>
                <a href="<?= base_url('student/download_template') ?>" class="btn btn-light btn--icon-text" id="btn-download-template">
                    <i class="zmdi zmdi-download"></i> Download Template
                </a>
            </div>
            <?php include_once('form.php') ?>
            <?php include_once('manage_class_modal.php') ?>
            <!-- Modal Import Excel -->
            <div class="modal fade" id="modal-import-student" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Import Siswa dari Excel</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <small>Format kolom: <b>A</b>=Nama Lengkap, <b>B</b>=Email, <b>C</b>=Username, <b>D</b>=Password (default 123456 jika kosong), <b>E</b>=Sekolah/Unit, <b>F</b>=Sub Sekolah, <b>G</b>=Aktif (1/0). Baris 1 header.</small>
                            </div>
                            <div class="form-group">
                                <label>Pilih File Excel (.xlsx/.xls)</label>
                                <input type="file" id="import_file" name="import_file" class="form-control" accept=".xlsx,.xls">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btn-do-import">Import</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="table-student" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Sekolah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url('themes/assets/vendor/select2/js/select2.full.min.js');?>"></script>
