<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?php if (isset($layanan_id) && $layanan_id):
			$service = $this->LayananModel->getDetail(['id' => $layanan_id]);
			echo $service ? 'Paket - ' . $service->nama_layanan : 'Daftar Paket';
		else: ?>
			Daftar Paket
		<?php endif; ?> | Generasi Jenius
	</title>

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<!-- Custom CSS -->
	<style>
		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			background-color: #f8f9fa;
		}

		.header-bg {
			background: linear-gradient(135deg, #0066cc, #004d99);
			color: white;
		}

		.package-card {
			transition: transform 0.3s, box-shadow 0.3s;
			border-radius: 15px;
			overflow: hidden;
			border: none;
			height: 100%;
		}

		.package-card:hover {
			transform: translateY(-10px);
			box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
		}

		.btn-orange {
			background-color: #ff9933;
			border-color: #ff9933;
			color: white;
		}

		.btn-orange:hover {
			background-color: #e68a00;
			border-color: #e68a00;
			color: white;
		}

		.service-filter {
			cursor: pointer;
			transition: all 0.3s;
		}

		.service-filter:hover {
			transform: scale(1.05);
		}

		.service-filter.active {
			border: 3px solid #0066cc;
			border-radius: 10px;
		}

		.section-title {
			position: relative;
			padding-bottom: 15px;
			margin-bottom: 30px;
			text-align: center;
		}

		.section-title:after {
			content: '';
			position: absolute;
			bottom: 0;
			left: 50%;
			transform: translateX(-50%);
			width: 80px;
			height: 3px;
			background: #0066cc;
		}
	</style>
</head>

<body>
	<!-- Header -->
	<header class="header-bg py-4">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-3">
					<h1 class="h4 mb-0">
						<a class="navbar-brand" href="<?= base_url() ?>">
							<?php if (!empty($settings['site_logo'])): ?>
								<img src="<?= base_url($settings['site_logo']) ?>" alt="Logo" style="border-radius: 10px; width : 100px; height: auto;">
							<?php else: ?>
								<span style="font-size: 1.5rem; font-weight: 700;"><?= $settings['site_title'] ?? 'Generasi Jenius' ?></span>
							<?php endif; ?>
						</a>
					</h1>
				</div>
				<div class="col-md-6 text-center">
					<h2 class="h4 mb-0">
						<?php if (isset($layanan_id) && $layanan_id):
							$service = $this->LayananModel->getDetail(['id' => $layanan_id]);
							echo $service ? $service->nama_layanan : 'Semua Paket';
						else: ?>
							Semua Paket
						<?php endif; ?>
					</h2>
				</div>
				<div class="col-md-3 text-end">
					<a href="<?= base_url() ?>" class="btn btn-outline-light btn-sm">Beranda</a>
				</div>
			</div>
		</div>
	</header>

	<!-- Service Filter Section -->
	<?php if (!isset($layanan_id) || !$layanan_id): ?>
		<section id="services" class="py-5 bg-white">
			<div class="container">
				<div class="section-title">
					<h2>Pilih Layanan</h2>
					<p>Pilih layanan yang sesuai dengan kebutuhan Anda</p>
				</div>
				<div class="row g-4">
					<?php 
					// var_dump($layanan);
					if (!empty($layanan)):
						$index = 0;
						foreach ($layanan as $service): ?>
							<div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>">
								<a href="<?= base_url('home/paket/') . $service->id ?>" class="text-decoration-none">
									<div class="card h-100 border-0 shadow-sm text-center service-filter p-3">
										<div class="feature-icon mb-2">
											<img src="<?= base_url() . $service->file_imgae ?>"
												alt="<?= $service->nama_layanan ?>"
												style="width: 100%; height: auto; object-fit: cover; border-radius: 50%; margin: 0 auto;">
										</div>
										<h6 class="mb-0"><?= $service->nama_layanan ?></h6>
									</div>
								</a>
							</div>
						<?php $index++;
						endforeach;
					else: ?>
						<div class="col-12 text-center">
							<p class="text-muted">Belum ada layanan tersedia</p>
						</div>
					<?php endif; ?>

					<!-- Option to show all packages -->
					<div class="col-md-3 col-6" data-aos="fade-up">
						<a href="<?= base_url('home/paket') ?>" class="text-decoration-none">
							<div class="card h-100 border-0 shadow-sm text-center service-filter p-3 <?= !isset($layanan_id) || !$layanan_id ? 'active' : '' ?>">
								<div class="feature-icon mb-2">
									<i class="fas fa-th-large text-primary" style="font-size: 3rem;"></i>
								</div>
								<h6 class="mb-0">Semua Paket</h6>
							</div>
						</a>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- Packages Section -->
	<section id="packages" class="py-5">
		<div class="container">
			<div class="section-title">
				<h2>
					<?php if (isset($layanan_id) && $layanan_id):
						$service = $this->LayananModel->getDetail(['id' => $layanan_id]);
						echo $service ? 'Paket untuk ' . htmlspecialchars($service->nama_layanan) : 'Paket Tersedia';
					else: ?>
						Semua Paket Tersedia
					<?php endif; ?>
				</h2>
				<p><?php if (isset($layanan_id) && $layanan_id): ?>
						Paket-paket yang tersedia untuk <?= htmlspecialchars($service->nama_layanan) ?>
					<?php else: ?>
						Pilih paket belajar yang sesuai dengan kebutuhan Anda
					<?php endif; ?></p>
			</div>

			<?php if (!empty($packages)): ?>
				<div class="row g-4">
					<?php foreach ($packages as $package): ?>
						<div class="col-lg-4 col-md-6" data-aos="fade-up">
							<div class="card package-card">
								<div class="card-body p-5 text-center">
									<h4 class="card-title text-primary fw-bold"><?= htmlspecialchars($package->name) ?></h4>

									<?php if ($package->price > 0): ?>
										<h2 class="my-4" style="color: #ff9933;">
											Rp <?= number_format($package->price, 0, ',', '.') ?>
										</h2>
									<?php else: ?>
										<h2 class="my-4 text-success">Gratis</h2>
									<?php endif; ?>

									<p class="card-text text-muted"><?= htmlspecialchars($package->description) ?></p>

									<ul class="list-unstyled mt-4 text-start">
										<?php
										$features = json_decode($package->features);
										if ($features && is_array($features)):
											foreach ($features as $f): ?>
												<li class="mb-2">
													<i class="fas fa-check text-success me-2"></i>
													<?= htmlspecialchars($f) ?>
												</li>
											<?php endforeach;
										else: ?>
											<li class="text-muted">Tidak ada fitur terdaftar</li>
										<?php endif; ?>
									</ul>

									<div class="mt-4">
										<?php if ($package->price > 0): ?>
											<a href="<?= base_url('select_package') ?>" class="btn btn-orange w-100 py-2">
												<i class="fas fa-shopping-cart me-2"></i>Beli Sekarang
											</a>
										<?php else: ?>
											<a href="<?= base_url('select_package') ?>" class="btn btn-success w-100 py-2">
												<i class="fas fa-download me-2"></i>Ambil Sekarang
											</a>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php else: ?>
				<div class="text-center py-5">
					<div class="mb-4">
						<i class="fas fa-box-open fa-3x text-muted"></i>
					</div>
					<h5 class="text-muted">Tidak Ada Paket Tersedia</h5>
					<p class="text-muted">
						<?php if (isset($layanan_id) && $layanan_id): ?>
							Saat ini belum tersedia paket untuk layanan ini.
						<?php else: ?>
							Tidak ditemukan paket yang tersedia saat ini.
						<?php endif; ?>
					</p>
					<a href="<?= base_url('home/paket') ?>" class="btn btn-outline-primary">Lihat Semua Paket</a>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<!-- Footer -->
	<footer class="bg-dark text-white text-center py-4">
		<div class="container">
			<p class="mb-0">&copy; <?= date('Y') ?> Generasi Jenius. Hak Cipta Dilindungi.</p>
		</div>
	</footer>

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

	<!-- AOS Animation Library -->
	<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
	<script>
		AOS.init({
			duration: 1000,
			once: true
		});
	</script>
</body>

</html>
