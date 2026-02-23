<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title><?php echo $page_title ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192" href="i<?php echo base_url('themes/_public/') ?>mg/favicon/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url('themes/_public/') ?>img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url('themes/_public/') ?>img/favicon/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('themes/_public/') ?>img/favicon/favicon-16x16.png">
  <link rel="manifest" href="<?php echo base_url('themes/_public/') ?>img/favicon/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="<?php echo base_url('themes/_public/') ?>img/favicon/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">

  <!-- Vendor styles -->
  <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/material-design-iconic-font/css/material-design-iconic-font.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/animate.css/animate.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/select2/css/select2.min.css') ?>">

  <!-- App styles -->
  <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/css/app.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('themes/_public/css/material-effect.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('themes/_public/css/public.main.css') ?>">

  <style type="text/css">
    .login__block {
      text-align: left;
      max-width: 750px;
      margin: 1rem;
    }

    .login__block__header {
      text-align: center;
    }

    .clear {
      height: 1.2rem;
    }
  </style>
</head>

<body data-ma-theme="<?php echo $app->theme_color ?>">
  <form id="form-permintaan" autocomplete="off">
    <!-- CSRF -->
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

    <div class="login">
      <div class="login__block active">
        <div class="login__block__header">
          <img src="<?php echo base_url('themes/_public/img/logo/logo.png') ?>" />
          <span style="font-weight: 500;">IEMED SUPPORT | PT. KAH</span>
          <br>
          <small>Permintaan Perbaikan & Pemeliharaan</small>
        </div>

        <div class="clear"></div>

        <div class="permintaan-form-wrapper" style="display: block;">
          <div class="login__block__body">
            <div class="row">
              <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                  <label required>Unit</label>
                  <div class="select">
                    <select name="unit" class="form-control select2 permintaan-unit" data-placeholder="Select &#8595;" required>
                      <?= $list_unit ?>
                    </select>
                    <i class="form-group__bar"></i>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                  <label required>Sub Unit</label>
                  <div class="select">
                    <select name="sub_unit" class="form-control select2 permintaan-sub_unit" data-placeholder="Select &#8595;" required>
                    </select>
                    <i class="form-group__bar"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                  <label required>Nama Pemohon</label>
                  <input type="text" name="nama_pemohon" class="form-control permintaan-nama_pemohon" placeholder="Nama Pemohon" required>
                  <i class="form-group__bar"></i>
                </div>
              </div>
              <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                  <label required>No. HP Pemohon</label>
                  <input type="text" name="no_hp_pemohon" class="form-control permintaan-no_hp_pemohon mask-number" placeholder="No. HP Pemohon" required>
                  <i class="form-group__bar"></i>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                  <label required>Rekomendasi Atasan (Nama)</label>
                  <input type="text" name="rekomendasi_atasan" class="form-control permintaan-rekomendasi_atasan" placeholder="Rekomendasi Atasan (Nama)" required>
                  <i class="form-group__bar"></i>
                </div>
              </div>
              <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                  <label required>Jenis Kerusakan</label>
                  <div class="select">
                    <select name="jenis_kerusakan" class="form-control select2 permintaan-jenis_kerusakan" data-placeholder="Select &#8595;" required>
                      <?= $list_jenis_kerusakan ?>
                    </select>
                    <i class="form-group__bar"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label required>Keluhan</label>
              <textarea name="keluhan" class="form-control permintaan-keluhan" placeholder="Tulis keluhan Anda disini..." rows="4" required></textarea>
              <i class="form-group__bar"></i>
            </div>
            <div class="form-group bg-warning p-3">
              <i class="text-dark">
                <small>Dengan meng-klik tombol <b>Ajukan</b>, Anda menyetujui Syarat dan Ketentuan (tanya kang Danil) yang berlaku.</small>
              </i>
            </div>
            <div class="text-center">
              <button class="btn btn-primary page-action-permintaan spinner-action-button pl-5 pr-5">
                Ajukan <i class="zmdi zmdi-long-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="permintaan-message" style="display: none;">
          <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Terima Kasih!</h4>
            Pengajuan Anda berhasil dikirim, silahkan tunggu tim internal kami untuk menghubungi Anda.
          </div>
          <div class="text-center pt-5 pb-5">
            <a href="<?= base_url('form') ?>" class="btn btn-primary pl-5 pr-5 pt-3 pb-3">
              <i class="zmdi zmdi-email mr-1"></i>
              Buat Pengajuan Baru?
            </a>
          </div>
        </div>
      </div>
    </div>
  </form>

  <!-- Javascript -->
  <script src="<?php echo base_url('themes/material_admin/vendors/jquery/jquery.min.js') ?>"></script>
  <script src="<?php echo base_url('themes/material_admin/vendors/popper.js/popper.min.js') ?>"></script>
  <script src="<?php echo base_url('themes/material_admin/vendors/bootstrap/js/bootstrap.min.js') ?>"></script>
  <script src="<?php echo base_url('themes/material_admin/vendors/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>
  <script src="<?php echo base_url('themes/material_admin/vendors/select2/js/select2.full.min.js') ?>"></script>

  <!-- App functions and actions -->
  <script src="<?php echo base_url('themes/material_admin/js/app.min.js') ?>"></script>
  <script src="<?php echo base_url('themes/_public/js/material-effect.js') ?>"></script>

  <script type="text/javascript">
    function notify(nMessage, nType) {
      $.notify({
        message: nMessage
      }, {
        type: nType,
        z_index: 9999,
        delay: 2500,
        timer: 500,
        placement: {
          from: "top",
          align: "center"
        },
        template: '<div data-notify="container" class="alert alert-dismissible alert-{0} alert--notify" role="alert">' +
          '<span data-notify="message">{2}</span>' +
          '<button type="button" aria-hidden="true" data-notify="dismiss" class="alert--notify__close">Tutup</button>' +
          '</div>'
      });
    };

    // Handle CSRF
    $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
      if (originalOptions.data instanceof FormData) {
        originalOptions.data.append("<?= $this->security->get_csrf_token_name(); ?>", "<?= $this->security->get_csrf_hash(); ?>");
      };
    });
  </script>

  <?php echo (isset($main_js)) ? $main_js : '' ?>
</body>

</html>