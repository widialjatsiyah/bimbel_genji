<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<title><?= $page_title ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="<?= base_url('themes/material_admin/vendors/material-design-iconic-font/css/material-design-iconic-font.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('themes/material_admin/vendors/animate.css/animate.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('themes/material_admin/css/app.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('themes/_public/css/public.main.css') ?>">

	<style>
		.login__block {
			max-width: 520px;
			margin: auto;
		}

		.section-card {
			background: #fff;
			border-radius: 10px;
			padding: 1.5rem;
			margin-bottom: 1.5rem;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
		}

		.section-title {
			font-weight: 600;
			font-size: 16px;
			color: #5c6bc0;
			margin-bottom: 15px;
			display: flex;
			align-items: center;
		}

		.section-title i {
			margin-right: 8px;
		}

		.helper-text {
			font-size: 12px;
			color: #999;
		}

		.password-toggle {
			cursor: pointer;
		}

		.progress {
			height: 6px;
			margin-bottom: 20px;
		}
	</style>

</head>

<body data-ma-theme="<?= $app->theme_color ?>">

	<div class="login mt-5">

		<div class="login__block active">

			<div class="login__block__header text-center">

				<img src="<?= base_url('directory/settings/7f7ac8b44baa13fb617017560d17f87a.jpg') ?>" style="height:60px">

				<br>

				<span style="font-weight:600">Generasi Jenius</span>

			</div>

			<div class="login__block__body">

				<h4 class="text-center mb-1">Daftar Akun Siswa</h4>

				<p class="text-center text-muted mb-4">
					Isi data berikut untuk membuat akun GENJI
				</p>

				<div class="progress">
					<div class="progress-bar bg-primary" style="width:50%"></div>
				</div>

				<form action="<?= base_url('register/do_register') ?>" method="post" id="form-register">

					<input type="hidden"
						name="<?= $this->security->get_csrf_token_name(); ?>"
						value="<?= $this->security->get_csrf_hash(); ?>" />

					<?php if ($this->session->flashdata('error')): ?>

						<div class="alert alert-danger">
							<?= $this->session->flashdata('error') ?>
						</div>

					<?php endif; ?>

					<?= validation_errors('<div class="alert alert-danger">', '</div>') ?>



					<!-- DATA SISWA -->

					<div class="section-card">

						<div class="section-title">
							<i class="zmdi zmdi-account"></i> Data Siswa
						</div>

						<div class="form-group form-group--float">
							<input type="text" name="nama_lengkap" class="form-control" value="<?= set_value('nama_lengkap') ?>" required>
							<label>Nama Lengkap</label>
							<i class="form-group__bar"></i>
						</div>

						<div class="form-group form-group--float">
							<input type="text" name="nis" class="form-control" value="<?= set_value('nis') ?>">
							<label>NIS (Opsional)</label>
							<i class="form-group__bar"></i>
						</div>

						<div class="form-group form-group--float">
							<input type="text" name="asal_sekolah" class="form-control" value="<?= set_value('asal_sekolah') ?>">
							<label>Asal Sekolah</label>
							<i class="form-group__bar"></i>
						</div>

						<div class="form-group form-group--float">

							<select name="pilihan_kampus1" class="form-control">

								<?= $list_universitas ?>

							</select>

							<label>Pilihan Kampus 1</label>
							<i class="form-group__bar"></i>

						</div>

						<div class="form-group form-group--float">

							<select name="pilihan_kampus2" class="form-control">

								<?= $list_universitas ?>

							</select>

							<label>Pilihan Kampus 2</label>
							<i class="form-group__bar"></i>

						</div>

					</div>



					<!-- INFORMASI AKUN -->

					<div class="section-card">

						<div class="section-title">
							<i class="zmdi zmdi-lock"></i> Informasi Akun
						</div>

						<div class="form-group form-group--float">

							<input type="email"
								name="email"
								class="form-control"
								value="<?= set_value('email') ?>"
								required>

							<label>Email</label>

							<i class="form-group__bar"></i>

						</div>



						<div class="form-group form-group--float">

							<input type="text"
								name="username"
								class="form-control"
								value="<?= set_value('username') ?>"
								required>

							<label>Username</label>

							<i class="form-group__bar"></i>

						</div>



						<div class="row">

							<div class="col-md-6">

								<div class="form-group form-group--float">

									<div class="input-group">

										<input type="password"
											name="password"
											id="password"
											class="form-control"
											required>

										<div class="input-group-append">

											<span class="input-group-text password-toggle" data-target="#password">
												<i class="zmdi zmdi-eye"></i>
											</span>

										</div>

									</div>

									<label>Password</label>

									<i class="form-group__bar"></i>

								</div>

							</div>



							<div class="col-md-6">

								<div class="form-group form-group--float">

									<div class="input-group">

										<input type="password"
											name="password_confirm"
											id="password_confirm"
											class="form-control"
											required>

										<div class="input-group-append">

											<span class="input-group-text password-toggle" data-target="#password_confirm">
												<i class="zmdi zmdi-eye"></i>
											</span>

										</div>

									</div>

									<label>Konfirmasi Password</label>

									<i class="form-group__bar"></i>

								</div>

							</div>

						</div>

						<small class="helper-text">
							Gunakan minimal 8 karakter dengan huruf dan angka
						</small>

						<div class="progress mt-2">

							<div id="password-strength-bar" class="progress-bar"></div>

						</div>

					</div>



					<button type="submit" class="btn btn-primary btn-block btn-lg">

						Daftar Sekarang

						<i class="zmdi zmdi-arrow-right"></i>

					</button>

				</form>



				<div class="mt-3 text-center">

					Sudah punya akun?
					<a href="<?= base_url('login') ?>">Login</a>

				</div>

				<div class="mt-2 text-center">

					<a href="<?= base_url() ?>">
						<i class="zmdi zmdi-arrow-left"></i> Kembali ke Beranda
					</a>

				</div>

			</div>

		</div>

	</div>


<script src="<?= base_url('themes/material_admin/vendors/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('themes/material_admin/vendors/bootstrap/js/bootstrap.min.js') ?>"></script>



<script>

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

    // Toggle password visibility
    $(document).ready(function() {
      $('#togglePassword').click(function() {
        var passwordInput = $('.login-password');
        var icon = $(this);
        
        if (passwordInput.attr('type') === 'password') {
          passwordInput.attr('type', 'text');
          icon.removeClass('zmdi-eye').addClass('zmdi-eye-off');
          icon.attr('title', 'Hide password');
        } else {
          passwordInput.attr('type', 'password');
          icon.removeClass('zmdi-eye-off').addClass('zmdi-eye');
          icon.attr('title', 'Show password');
        }
      });
    });
		
$('.password-toggle').click(function(){

var input = $($(this).data('target'));

if(input.attr('type') == 'password'){
input.attr('type','text');
}else{
input.attr('type','password');
}

});



$('#password').on('input',function(){

var password=$(this).val();

var score=0;

if(password.length>7) score+=25;
if(/[A-Z]/.test(password)) score+=25;
if(/[0-9]/.test(password)) score+=25;
if(/[^A-Za-z0-9]/.test(password)) score+=25;

$('#password-strength-bar').css('width',score+'%');

if(score<50){
$('#password-strength-bar').css('background','#e74c3c');
}
else if(score<75){
$('#password-strength-bar').css('background','#f1c40f');
}
else{
$('#password-strength-bar').css('background','#2ecc71');
}

});



$('#password_confirm').on('keyup',function(){

if($('#password').val()!= $(this).val()){

$(this).css('border-color','#e74c3c');

}else{

$(this).css('border-color','#2ecc71');

}

});

</script>

</body>
</html>

<script type="text/javascript">
  $(document).ready(function() {

    var _form = "form-register";

    // Handle ajax start
    $(document).ajaxStart(function() {
      $(".spinner").css("display", "flex");
    });

    // Handle ajax stop
    $(document).ajaxStop(function() {
      $(".spinner").hide();
    });

    // Handle data submit
    $("#" + _form + " .page-action-register").on("click", function(e) {
      e.preventDefault();

      var form = $("#" + _form)[0];
      var data = new FormData(form);

      $.ajax({
        type: "post",
        url: "<?php echo base_url('register/ajax_submit/') ?>",
        data: data,
        dataType: "json",
        enctype: "multipart/form-data",
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
          if (response.status === true) {
            notify(response.data, "success");
            window.location.href = "<?php echo base_url('select_package') ?>";
          } else {
            notify(response.data, "danger");
          };
        }
      });
      return false;
    });

  });
</script>
