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
		}

		.install-prompt {
			position: fixed;
			bottom: 0;
			left: 50%;
			transform: translateX(-50%);
			display: flex;
			align-items: center;
			background-color: #fff;
			border-radius: 8px 8px 0 0;
			box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
			padding: 10px 16px;
			z-index: 9999;
			animation: slideUp 0.3s ease-out;
			width: 100vw;
			max-width: 100vw;
			box-sizing: border-box;
		}

		.install-prompt__icon {
			margin-right: 12px;
		}

		.install-prompt__text {
			display: flex;
			flex-direction: column;
			font-size: 14px;
		}

		.install-prompt__text strong {
			font-weight: 600;
			color: #333;
		}

		.install-prompt__text small {
			color: #777;
		}

		.install-prompt__btn {
			margin-left: auto;
			background-color: #4CAF50;
			color: white;
			border: none;
			padding: 6px 16px;
			border-radius: 4px;
			cursor: pointer;
			font-size: 14px;
		}

		.install-prompt__btn:hover {
			background-color: #45a049;
		}

		.install-prompt__close {
			background: transparent;
			border: none;
			font-size: 20px;
			margin-left: 10px;
			cursor: pointer;
			color: #888;
		}

		@keyframes slideUp {
			from {
				transform: translateX(-50%) translateY(100px);
				opacity: 0;
			}

			to {
				transform: translateX(-50%) translateY(0);
				opacity: 1;
			}
		}
	</style>
</head>

<body data-ma-theme="<?php echo $app->theme_color ?>">
	<!-- Loading Indicator -->
	<div class="spinner" style="position: fixed; flex-direction: column; justify-content: center; align-items: center;">
		<h3 style="color: var(--white);">Please wait</h3>
		<div class="lds-hourglass"></div>
	</div>
	<!-- END ## Loading Indicator -->

	<form id="form-login" autocomplete="off">
		<!-- CSRF -->
		<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

		<div class="login">
			<div class="login__block active" style="max-width: 400px;">
				<div class="login__block__header">

					<img src="<?= base_url('directory/settings/7f7ac8b44baa13fb617017560d17f87a.jpg') ?>" style="height:60px;border-radius: 10%;">
					<span style="font-weight: 500;">Generasi Jenius</span>
				</div>

				<div class="login__block__body">
					<div class="form-group form-group--float form-group--centered">
						<input type="text" name="username" class="form-control login-username" style="cursor: text;" readonly onfocus="this.removeAttribute('readonly');" />
						<label>Username</label>
						<i class="form-group__bar"></i>
					</div>

					<div class="form-group form-group--float form-group--centered">
						<input type="password" name="password" class="form-control login-password" style="cursor: text;" readonly onfocus="this.removeAttribute('readonly');" />
						<label>Password</label>
						<i class="form-group__bar"></i>
					</div>

					<button class="btn btn-primary rounded page-action-login">
						Masuk
						<i class="zmdi zmdi-long-arrow-right"></i>
					</button>

					<div class="mt-3 text-center">

					Belum punya akun?
					<a href="<?= base_url('register') ?>">Daftar</a>

				</div>

				<div class="mt-2 text-center">

					<a href="<?= base_url() ?>">
						<i class="zmdi zmdi-arrow-left"></i> Kembali ke Beranda
					</a>

				</div>
				</div>

				<div style="border-top: 1px solid #eceff1; padding: 20px 10px; color: #999;" class="bg-light mt-4">
					&copy; Generasi Jenius <?= date('Y') ?>. All rights reserved.
				</div>
			</div>
		</div>
	</form>
	<div id="installPrompt" class="install-prompt" style="display: none;">
		<div class="install-prompt__icon">
			<img src="<?php echo base_url('themes/_public/img/logo/logo.png') ?>" alt="Logo" width="40" height="40">
		</div>
		<div class="install-prompt__text">
			<strong>Install Aplikasi</strong>
			<small>Tambahkan ke layar utama</small>
		</div>
		<button id="installAppBtn" class="install-prompt__btn">Install</button>
		<button id="installCancelBtn" class="install-prompt__close">&times;</button>
	</div>
	<!-- Javascript -->
	<script src="<?php echo base_url('themes/material_admin/vendors/jquery/jquery.min.js') ?>"></script>
	<script src="<?php echo base_url('themes/material_admin/vendors/popper.js/popper.min.js') ?>"></script>
	<script src="<?php echo base_url('themes/material_admin/vendors/bootstrap/js/bootstrap.min.js') ?>"></script>
	<script src="<?php echo base_url('themes/material_admin/vendors/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>

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


	<script>
		if ('serviceWorker' in navigator) {
			navigator.serviceWorker.register('<?= base_url('themes/_public/js/service-worker.js') ?>')
				.then(reg => console.log('Service Worker registered:', reg))
				.catch(err => console.log('Service Worker registration failed:', err));
		}
	</script>


	<script>
		let deferredPrompt;

		window.addEventListener('beforeinstallprompt', (e) => {
			e.preventDefault();
			deferredPrompt = e;

			const promptDiv = document.getElementById('installPrompt');
			promptDiv.style.display = 'flex';

			document.getElementById('installAppBtn').addEventListener('click', () => {
				if (deferredPrompt) {
					deferredPrompt.prompt(); // Tampilkan prompt install native
					deferredPrompt.userChoice.then((choiceResult) => {
						if (choiceResult.outcome === 'accepted') {
							console.log('User accepted the install prompt');
						} else {
							console.log('User dismissed the install prompt');
						}
						deferredPrompt = null;
						promptDiv.style.display = 'none';
					});
				}
			});

			document.getElementById('installCancelBtn').addEventListener('click', () => {
				promptDiv.style.display = 'none';
				deferredPrompt = null;
			});
		});
	</script>
</body>

</html>
