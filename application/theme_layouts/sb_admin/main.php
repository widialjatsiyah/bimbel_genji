<?php include_once('header.php') ?>
<?php include_once('sidebar.php') ?>

<div id="layoutSidenav_content">
    <main>
        <header class="page-header page-header-dark bg-img-cover pb-10" style="background-color : #0066cc">
            <div class="container-xl px-4">
                <div class="page-header-content <?= ($app->is_mobile) ? 'pt-0' : 'pt-4' ?>">
                    <?php if (!$app->is_mobile) : ?>
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mt-4">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon avatar">
                                        <img class="avatar-img img-fluid" src="<?= base_url('themes/_public/img/logo/genji-logo.jpg') ?>" alt="Logo KAH">
                                    </div>
                                    Bimbel Genji
                                </h1>
                                <div class="page-header-subtitle" style="color: rgba(255, 255, 255, 0.7);">
          Bimbel Generasi Jenius Tasik                     
📚BIMBEL SD, SMP, SMA TASIKMALAYA
📂Intensif UTBK-SNBT
📝Calistung
🗣️English Course
💡Konsultasi Belajar
🏠Home Private
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-fluid px-4 mt-n10">
            {content}
        </div>
    </main>
    <?php include_once('footerCredit.php') ?>
</div>
</div>

<?php include_once('footer.php') ?>
