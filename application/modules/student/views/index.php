<link href="<?php echo base_url('themes/assets/vendor/select2/css/select2.min.css');?>" rel="stylesheet" />
<style>
.select2-container {
    width: 100% !important;
}
</style>

<section id="student">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?= $card_title ?></h4>
            <div class="table-action">
                <button class="btn btn-primary btn--icon-text student-action-add" data-toggle="modal" data-target="#modal-form-student">
                    <i class="zmdi zmdi-plus-circle"></i> Tambah Siswa
                </button>
            </div>
            <?php include_once('form.php') ?>
            <?php include_once('manage_class_modal.php') ?>
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