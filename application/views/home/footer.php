<!-- Footer -->
<footer id="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="mb-4"><?= $settings['site_title'] ?? 'Generasi Jenius' ?></h5>
                <p><?= $settings['about_us'] ?? 'Kami adalah platform pendidikan yang berkomitmen membantu siswa meraih prestasi terbaik.' ?></p>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="mb-4">Kontak</h5>
                <p><i class="fas fa-envelope me-2"></i> <?= $settings['contact_email'] ?? '-' ?></p>
                <p><i class="fas fa-phone me-2"></i> <?= $settings['contact_phone'] ?? '-' ?></p>
                <p><i class="fas fa-map-marker-alt me-2"></i> <?= $settings['address'] ?? '-' ?></p>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="mb-4">Ikuti Kami</h5>
                <div class="social-icons">
                    <?php if (!empty($settings['facebook_url'])): ?><a href="<?= $settings['facebook_url'] ?>"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                    <?php if (!empty($settings['twitter_url'])): ?><a href="<?= $settings['twitter_url'] ?>"><i class="fab fa-twitter"></i></a><?php endif; ?>
                    <?php if (!empty($settings['instagram_url'])): ?><a href="<?= $settings['instagram_url'] ?>"><i class="fab fa-instagram"></i></a><?php endif; ?>
                    <?php if (!empty($settings['youtube_url'])): ?><a href="<?= $settings['youtube_url'] ?>"><i class="fab fa-youtube"></i></a><?php endif; ?>
                </div>
            </div>
        </div>
        <div class="copyright">
            <?= $settings['footer_copyright'] ?? '© 2025 Generasi Jenius. All rights reserved.' ?>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });
</script>
<!-- Custom JS -->
<script>
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>
</body>
</html>
