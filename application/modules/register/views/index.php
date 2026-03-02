<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
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
	<link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url('themes/_public/') ?>img/favicon/android-icon-192x192.png">
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

	<!-- App styles -->
	<link rel="stylesheet" href="<?php echo base_url('themes/material_admin/css/app.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('themes/_public/css/material-effect.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('themes/_public/css/public.main.css') ?>">

	<style type="text/css">
		.login__block {
			margin: 1rem;
			max-width: 500px;
		}

		.login__block__body {
			padding: 2rem;
		}

		.form-group--float {
			margin-bottom: 1.5rem;
		}

		.account-section {
			background-color: #f8f9fa;
			padding: 1.2rem;
			border-radius: 8px;
			margin: 1.5rem 0;
			border-left: 4px solid #667eea;
		}

		.account-section h5 {
			margin-bottom: 1rem;
			color: #667eea;
			display: flex;
			align-items: center;
		}

		.account-section h5 i {
			margin-right: 8px;
		}
	</style>
</head>

<body data-ma-theme="<?php echo $app->theme_color ?>">
	<!-- Loading Indicator -->
	<div class="spinner" style="position: fixed; flex-direction: column; justify-content: center; align-items: center;">
		<h3 style="color: var(--white);">Please wait</h3>
		<div class="lds-hourglass"></div>
	</div>

	<form action="<?= base_url('register/do_register') ?>" method="post" id="form-register" autocomplete="off">
		<!-- CSRF -->
		<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

		<div class="login">
			<div class="login__block active">
				<div class="login__block__header">
					<img src="<?php echo base_url('directory/settings/7f7ac8b44baa13fb617017560d17f87a.jpg') ?>" />
					<span style="font-weight: 500;">Generasi Jenius</span>
				</div>

				<div class="login__block__body">
					<h4 class="text-center mb-4">Daftar Akun Siswa</h4>

					<form action="<?= base_url('register/do_register') ?>" method="post" id="form-register" autocomplete="off">
						<!-- CSRF -->
						<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

						<?php if ($this->session->flashdata('error')): ?>
							<div class="alert alert-danger alert-dismissible">
								<?= $this->session->flashdata('error') ?>
								<button type="button" class="close" data-dismiss="alert">&times;</button>
							</div>
						<?php endif; ?>

						<?= validation_errors('<div class="alert alert-danger">', '</div>') ?>

						<div class="form-group form-group--float form-group--centered">
							<input type="text" name="nama_lengkap" class="form-control" value="<?= set_value('nama_lengkap') ?>" required>
							<label>Nama Lengkap</label>
							<i class="form-group__bar"></i>
						</div>

						<div class="form-group form-group--float form-group--centered">
							<input type="text" name="nis" class="form-control" value="<?= set_value('nis') ?>">
							<label>NIS (opsional)</label>
							<i class="form-group__bar"></i>
						</div>

						<div class="form-group form-group--float form-group--centered">
							<input type="text" name="asal_sekolah" class="form-control" value="<?= set_value('asal_sekolah') ?>">
							<label>Asal Sekolah (opsional)</label>
							<i class="form-group__bar"></i>
						</div>

						<div class="form-group form-group--float form-group--centered">
							<input type="text" name="pilihan_kampus1" class="form-control" value="<?= set_value('pilihan_kampus1') ?>">
							<label>Pilihan Kampus 1 (opsional)</label>
							<i class="form-group__bar"></i>
						</div>

						<div class="form-group form-group--float form-group--centered">
							<input type="text" name="pilihan_kampus2" class="form-control" value="<?= set_value('pilihan_kampus2') ?>">
							<label>Pilihan Kampus 2 (opsional)</label>
							<i class="form-group__bar"></i>
						</div>

						<div class="form-group form-group--float form-group--centered">
							<input type="email" name="email" class="form-control" value="<?= set_value('email') ?>" required>
							<label>Email</label>
							<i class="form-group__bar"></i>
						</div>

						<!-- Card specifically for username and password -->
						<div class="password-card">
							<h5 class="font-weight-bold text-primary mb-4">
								<i class="fas fa-user-lock mr-2"></i>Informasi Akun
							</h5>

							<div class="form-group">
								<input type="text" name="username" class="form-control" value="<?= set_value('username') ?>" required>
								<label>Username</label>
								<i class="form-group__bar"></i>
							</div>

							<div class="form-group row">
								<div class="col-sm-6 mb-3 mb-sm-0">
									<input type="password" name="password" id="password" class="form-control" required>
									<label>Password</label>
									<i class="form-group__bar"></i>
								</div>
								<div class="col-sm-6">
									<input type="password" name="password_confirm" id="password_confirm" class="form-control" required>
									<label>Konfirmasi Password</label>
									<i class="form-group__bar"></i>
									<div class="mt-2">

									</div>

								</div>
								 <small class="text-muted">Gunakan minimal 8 karakter dengan kombinasi huruf besar, huruf kecil, dan angka</small>
							</div>

							<!-- Password strength indicator -->
							<!-- <div class="mt-3">
              <div class="d-flex justify-content-between">
                <small class="text-muted">Kekuatan Password:</small>
                <small class="text-muted" id="strength-text">Belum diisi</small>
              </div>
              <div class="progress" style="height: 8px;">
                <div class="progress-bar" id="password-strength-bar" role="progressbar" style="width: 0%; background-color: #e74a3b;"></div>
              </div>
            </div> -->
						</div>

						<button type="submit" class="btn btn-primary btn-block">
							Daftar
							<i class="zmdi zmdi-long-arrow-right"></i>
						</button>

					</form>

					<div class="mt-3 text-center">
						Sudah punya akun? <a href="<?= base_url('login') ?>">Login</a>
					</div>
					<div class="mt-2 text-center">
						<a href="<?= base_url() ?>"><i class="zmdi zmdi-long-arrow-left"></i> Kembali ke Beranda</a>
					</div>
				</div>
			</div>
		</div>
		</div>
		</div>
		</div>
		</div>

		</div>

		<div style="border-top: 1px solid #eceff1; padding: 20px 10px; color: #999;" class="bg-light mt-4">
			&copy; Bimbel Genji
		</div>
		</div>
	</form>

	<!-- Javascript -->
	<script src="<?php echo base_url('themes/material_admin/vendors/jquery/jquery.min.js') ?>"></script>
	<script src="<?php echo base_url('themes/material_admin/vendors/popper.js/popper.min.js') ?>"></script>
	<script src="<?php echo base_url('themes/material_admin/vendors/bootstrap/js/bootstrap.min.js') ?>"></script>
	<script src="<?php echo base_url('themes/material_admin/vendors/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>

	<!-- App functions and actions -->
	<script src="<?php echo base_url('themes/material_admin/js/app.min.js') ?>"></script>
	<script src="<?php echo base_url('themes/_public/js/material-effect.js') ?>"></script>

	<script>
		// Handle CSRF for AJAX
		$.ajaxPrefilter(function(options, originalOptions, jqXHR) {
			if (originalOptions.data instanceof FormData) {
				originalOptions.data.append("<?= $this->security->get_csrf_token_name(); ?>", "<?= $this->security->get_csrf_hash(); ?>");
			}
		});

		// Password strength indicator
		$(document).ready(function() {
			$("#password").on("input", function() {
				var password = $(this).val();
				var strength = calculatePasswordStrength(password);

				$("#password-strength-bar").css("width", strength.value + "%");
				$("#password-strength-bar").css("background-color", strength.color);
				$("#strength-text").text(strength.text);
			});

			// Confirm password match
			$("#confirm_password").on("input", function() {
				var password = $("#password").val();
				var confirmPassword = $(this).val();

				if (confirmPassword === "" || password === confirmPassword) {
					// Passwords match or confirm field is empty
				} else {
					notify("Password dan Konfirmasi Password tidak cocok", "warning");
				}
			});

			function calculatePasswordStrength(password) {
				var score = 0;
				var result = {
					value: 0,
					color: "#e74a3b",
					text: "Lemah"
				};

				if (password.length === 0) {
					return result;
				}

				// Length check
				score += password.length * 4;

				// Uppercase letters
				var upperCases = (password.match(/[A-Z]/g) || []).length;
				score += upperCases * 2;

				// Lowercase letters
				var lowerCases = (password.match(/[a-z]/g) || []).length;
				score += lowerCases * 2;

				// Numbers
				var numbers = (password.match(/[0-9]/g) || []).length;
				score += numbers * 4;

				// Special characters
				var specials = (password.match(/[^0-9a-zA-Z]/g) || []).length;
				score += specials * 6;

				// Calculate final score
				score = Math.min(100, Math.floor(score / 2));

				// Determine color and text based on score
				if (score < 40) {
					result = {
						value: score,
						color: "#e74a3b",
						text: "Lemah"
					}; // red
				} else if (score < 70) {
					result = {
						value: score,
						color: "#f6c23e",
						text: "Sedang"
					}; // yellow
				} else if (score < 90) {
					result = {
						value: score,
						color: "#36b9cc",
						text: "Baik"
					}; // blue
				} else {
					result = {
						value: score,
						color: "#28a745",
						text: "Kuat"
					}; // green
				}

				result.value = score;
				return result;
			}
		});
	</script>

	<?php echo (isset($main_js)) ? $main_js : '' ?>

</body>

</html>
