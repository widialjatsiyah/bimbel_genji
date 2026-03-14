<!-- Hero Section with Slider -->
<section id="home" class="p-0">
	<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
		<div class="carousel-inner">
			<?php if (!empty($slides)): $first = true;
				foreach ($slides as $slide): ?>
					<div class="carousel-item <?= $first ? 'active' : '' ?>" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?= base_url($slide->image) ?>'); background-size: cover; background-position: center; height: 80vh;">
						<div class="container h-100">
							<div class="row h-100 align-items-center">
								<div class="col-lg-8 text-white" data-aos="fade-up">
									<h1 class="display-3 fw-bold"><?= $slide->title ?></h1>
									<p class="lead mb-4"><?= $slide->subtitle ?></p>
									<?php if ($slide->button_text): ?>
										<a href="<?= $slide->button_link ?>" class="btn btn-orange btn-lg"><?= $slide->button_text ?></a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				<?php $first = false;
				endforeach;
			else: ?>
				<div class="carousel-item active" style="background: linear-gradient(135deg, #0066cc, #004d99); height: 80vh;">
					<div class="container h-100">
						<div class="row h-100 align-items-center">
							<div class="col-lg-8 text-white" data-aos="fade-up">
								<h1 class="display-3 fw-bold">Selamat Datang di Generasi Jenius</h1>
								<p class="lead mb-4">Platform try out dan latihan soal terpercaya untuk UTBK dan ujian lainnya.</p>
								<a href="#" class="btn btn-orange btn-lg">Mulai Try Out</a>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Previous</span>
		</button>
		<button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Next</span>
		</button>
	</div>
</section>


<section id="layanan" style="background: #f8f9fa;">
	<div class="container">
		<div class="section-title" data-aos="fade-up">
			<h2>Layanan Kami</h2>
			<p>Layanan Terbaik dan unggulan yang kami tawarkan</p>
		</div>
		<div class="row g-4">
			<?php if (!empty($layanan)): $index = 0;
				foreach ($layanan as $service): ?>
					<a href="<?= base_url('home/paket/') . $service->id ?>" class="col-md-3 text-decoration-none text-dark" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
						
							<div class="card h-100 border-0 shadow-sm text-center p-4">
								<div class="feature-icon mb-3">
									<img src="<?= base_url() . $service->file_imgae ?>" alt="<?= $service->nama_layanan ?>" style="width: 150px; height: 150px; object-fit: cover;">
								</div>
								<h4><?= $service->nama_layanan ?></h4>
							</div>
					</a>
				<?php $index++;
				endforeach;
			else: ?>
				<div class="col-12 text-center">Belum ada data.</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<!-- Stats Section -->
<section id="stats" style="background: linear-gradient(135deg, #0066cc, #004d99); color: white; padding: 60px 0;">
	<div class="container">
		<div class="row text-center">
			<div class="col-md-3 col-6 mb-3">
				<h3 class="display-4"><?= $total_students ?></h3>
				<p>Siswa Terdaftar</p>
			</div>
			<div class="col-md-3 col-6 mb-3">
				<h3 class="display-4"><?= $total_tryouts ?></h3>
				<p>Try Out Tersedia</p>
			</div>
			<div class="col-md-3 col-6 mb-3">
				<h3 class="display-4"><?= $total_schools ?></h3>
				<p>Sekolah Mitra</p>
			</div>
			<div class="col-md-3 col-6 mb-3">
				<h3 class="display-4"><?= $total_questions ?></h3>
				<p>Bank Soal</p>
			</div>
		</div>
	</div>
</section>

<!-- Leaderboard Section (jika ada) -->
<?php if (!empty($leaderboard)): ?>
	<section id="leaderboard" class="py-5">
		<div class="container">
			<div class="section-title">
				<h2>Peringkat Try Out: <?= $latest_tryout_title ?></h2>
				<p>Para siswa terbaik pada try out terbaru</p>
			</div>
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Peringkat</th>
									<th>Nama Siswa</th>
									<th>Skor</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($leaderboard as $index => $row): ?>
									<tr>
										<td><?= $index + 1 ?></td>
										<td><?= $row->nama_lengkap ?></td>
										<td><?= $row->total_score ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- Features -->
<section id="features" style="background: #f8f9fa;">
	<div class="container">
		<div class="section-title" data-aos="fade-up">
			<h2>Keunggulan Kami</h2>
			<p>Mengapa memilih Generasi Jenius?</p>
		</div>
		<div class="row g-4">
			<?php if (!empty($features)): $index = 0;
				foreach ($features as $feature): ?>
					<div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
						<div class="card h-100 border-0 shadow-sm text-center p-4">
							<div class="feature-icon mb-3">
								<i class="<?= $feature->icon ?: 'fas fa-star' ?>" style="font-size: 3rem; color: #ff9933;"></i>
							</div>
							<h4><?= $feature->title ?></h4>
							<p class="text-muted"><?= $feature->description ?></p>
						</div>
					</div>
				<?php $index++;
				endforeach;
			else: ?>
				<div class="col-12 text-center">Belum ada data.</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<!-- Packages -->
<section id="packages">
	<div class="container">
		<div class="section-title" data-aos="fade-up">
			<h2>Pilih Paket Belajar</h2>
			<p>Sesuaikan dengan kebutuhan Anda</p>
		</div>
		<div class="row g-4">
			<?php if (!empty($packages)): foreach ($packages as $package): ?>
					<div class="col-md-4" data-aos="flip-left">
						<div class="card h-100 border-0 shadow-lg package-card">
							<div class="card-body text-center p-5">
								<h5 class="card-title text-primary fw-bold"><?= $package->name ?></h5>
								<h2 class="my-4" style="color: #ff9933;">Rp <?= number_format($package->price, 0, ',', '.') ?></h2>
								<p class="card-text"><?= $package->description ?></p>
								<ul class="list-unstyled mt-4 mb-4">
									<?php
									$features = json_decode($package->features);
									if ($features): foreach ($features as $f): ?>
											<li><i class="fas fa-check text-success me-2"></i> <?= $f ?></li>
									<?php endforeach;
									endif; ?>
								</ul>
								<a href="<?= base_url('select_package') ?>" class="btn btn-orange w-100">Pilih Paket</a>
							</div>
						</div>
					</div>
				<?php endforeach;
			else: ?>
				<div class="col-12 text-center">Belum ada paket.</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<!-- FAQ -->
<section id="faq" style="background: #f8f9fa;">
	<div class="container">
		<div class="section-title" data-aos="fade-up">
			<h2>Pertanyaan Umum</h2>
			<p>Yang sering ditanyakan</p>
		</div>
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<div class="accordion" id="accordionFaq">
					<?php if (!empty($faqs)): $i = 0;
						foreach ($faqs as $faq): ?>
							<div class="accordion-item border-0 mb-3 shadow-sm">
								<h2 class="accordion-header" id="heading<?= $i ?>">
									<button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>" style="background: white; border-radius: 10px;">
										<?= $faq->question ?>
									</button>
								</h2>
								<div id="collapse<?= $i ?>" class="accordion-collapse collapse <?= $i == 0 ? 'show' : '' ?>" data-bs-parent="#accordionFaq">
									<div class="accordion-body">
										<?= $faq->answer ?>
									</div>
								</div>
							</div>
						<?php $i++;
						endforeach;
					else: ?>
						<p class="text-center">Belum ada FAQ.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Testimonials -->
<section id="testimonials">
	<div class="container">
		<div class="section-title" data-aos="fade-up">
			<h2>Apa Kata Mereka</h2>
			<p>Testimoni dari pengguna kami</p>
		</div>
		<div class="row">
			<?php if (!empty($testimonials)): foreach ($testimonials as $testi): ?>
					<div class="col-md-4 mb-4" data-aos="fade-up">
						<div class="card h-100 border-0 shadow-sm">
							<div class="card-body p-4">
								<div class="d-flex align-items-center mb-3">
									<img src="<?= $testi->photo ? base_url($testi->photo) : 'https://via.placeholder.com/60' ?>" class="rounded-circle me-3" width="60" height="60" alt="<?= $testi->name ?>">
									<div>
										<h6 class="mb-0"><?= $testi->name ?></h6>
										<small class="text-muted"><?= $testi->position ?>, <?= $testi->company ?></small>
									</div>
								</div>
								<p class="text-muted fst-italic">"<?= $testi->content ?>"</p>
								<div class="text-warning">
									<?php for ($j = 1; $j <= 5; $j++): ?>
										<i class="fas fa-star <?= $j <= $testi->rating ? 'text-warning' : 'text-muted' ?>"></i>
									<?php endfor; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach;
			else: ?>
				<div class="col-12 text-center">Belum ada testimoni.</div>
			<?php endif; ?>
		</div>
	</div>
</section>
