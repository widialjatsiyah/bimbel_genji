<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($settings['site_title']) ? $settings['site_title'] : 'Generasi Jenius' ?></title>
    <meta name="description" content="<?= isset($settings['meta_description']) ? $settings['meta_description'] : '' ?>">
    <meta name="keywords" content="<?= isset($settings['meta_keywords']) ? $settings['meta_keywords'] : '' ?>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-blue: #0066cc;
            --primary-orange: #ff9933;
            --dark-blue: #004d99;
            --light-orange: #ffb366;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        .navbar {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand img {
            max-height: 50px;
        }
        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s;
        }
        .navbar-nav .nav-link:hover {
            color: var(--primary-orange) !important;
            transform: translateY(-2px);
        }
        .btn-orange {
            background-color: var(--primary-orange);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-orange:hover {
            background-color: var(--light-orange);
            transform: scale(1.05);
        }
        .btn-outline-orange {
            border: 2px solid var(--primary-orange);
            color: var(--primary-orange);
            background: transparent;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-outline-orange:hover {
            background-color: var(--primary-orange);
            color: white;
        }
        section {
            padding: 80px 0;
        }
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-blue);
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
        }
        .section-title h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--primary-orange);
            border-radius: 2px;
        }
        .section-title p {
            color: #666;
            font-size: 1.1rem;
            margin-top: 15px;
        }
        footer {
            background: linear-gradient(135deg, var(--dark-blue) 0%, var(--primary-blue) 100%);
            color: white;
            padding: 60px 0 20px;
        }
        footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s;
        }
        footer a:hover {
            color: var(--primary-orange);
        }
        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 10px;
            transition: all 0.3s;
        }
        .social-icons a:hover {
            background: var(--primary-orange);
            color: white;
            transform: translateY(-3px);
        }
        .copyright {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
            margin-top: 40px;
            text-align: center;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <?php if (!empty($settings['site_logo'])): ?>
                    <img src="<?= base_url($settings['site_logo']) ?>" alt="Logo" style="border-radius: 10px;">
                <?php else: ?>
                    <span style="font-size: 1.5rem; font-weight: 700;"><?= $settings['site_title'] ?? 'Generasi Jenius' ?></span>
                <?php endif; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#home">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
					<li class="nav-item"><a class="nav-link" href="#features">Keunggulan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#packages">Paket</a></li>
                    <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimonials">Testimoni</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
                </ul>
                <div class="d-flex ms-lg-3">
                    <a href="<?= base_url('login') ?>" class="btn btn-outline-orange me-2">Masuk</a>
                    <a href="<?= base_url('register') ?>" class="btn btn-orange">Daftar</a>
                </div>
            </div>
        </div>
    </nav>
