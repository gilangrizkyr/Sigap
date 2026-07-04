<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'MPP — Sigap' ?></title>
    <!-- Base styling -->
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Public theme styles (Red gradient, clean styles, layouts) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/public-theme.css') ?>">
    
    <?php if (isset($extra_css)): ?>
    <style>
        <?= $extra_css ?>
    </style>
    <?php endif; ?>
</head>

<body>

    <!-- Red Hero Banner -->
    <div style="position: relative; background: linear-gradient(135deg, #b71c1c 0%, #7f0000 100%); padding: 0 0 70px; color: #ffffff; box-shadow: inset 0 -10px 20px rgba(0,0,0,0.05); overflow: hidden;">

        <!-- Navbar -->
        <?php
        $current_path = service('request')->getUri()->getPath();
        $is_home_active = ($current_path === 'mpp' || $current_path === 'pengaduan/mpp');
        $is_tracking_active = (strpos($current_path, 'mpp/tracking') !== false);
        $is_faq_active = (strpos($current_path, 'mpp/faq') !== false);
        $is_about_active = (strpos($current_path, 'mpp/about') !== false);
        ?>
        <div class="container" style="position: relative; z-index: 10;">
            <nav class="navbar">
                <a href="<?= base_url('mpp') ?>" class="nav-logo">
                    <div class="logo-icon-wrapper">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <span class="logo-brand">SIGAP</span>
                </a>
                <button type="button" class="menu-toggle" aria-label="Toggle Menu" onclick="toggleMobileMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <ul class="nav-links" id="navLinks">
                    <li><a href="<?= base_url('mpp') ?>" class="nav-link <?= $is_home_active ? 'active' : '' ?>">Home</a></li>
                    <li><a href="<?= base_url('mpp/tracking') ?>" class="nav-link <?= $is_tracking_active ? 'active' : '' ?>">Lacak Aduan</a></li>
                    <li><a href="<?= base_url('mpp/faq') ?>" class="nav-link <?= $is_faq_active ? 'active' : '' ?>">FAQ</a></li>
                    <li><a href="<?= base_url('mpp/about') ?>" class="nav-link <?= $is_about_active ? 'active' : '' ?>">Tentang</a></li>
                </ul>
            </nav>
        </div>

        <!-- Dot Pattern Overlay -->
        <div style="position: absolute; inset: 0; background-image: radial-gradient(rgba(255, 255, 255, 0.12) 1.5px, transparent 1.5px); background-size: 24px 24px; opacity: 0.7; pointer-events: none; z-index: 1;"></div>

        <!-- Glow Blobs -->
        <div style="position: absolute; top: -20%; left: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(245, 158, 11, 0.22) 0%, rgba(245, 158, 11, 0) 70%); filter: blur(50px); pointer-events: none; border-radius: 50%; z-index: 1;"></div>
        <div style="position: absolute; bottom: -10%; right: 10%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(236, 72, 153, 0.15) 0%, rgba(236, 72, 153, 0) 70%); filter: blur(60px); pointer-events: none; border-radius: 50%; z-index: 1;"></div>
        <div style="position: absolute; top: 20%; right: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(6, 182, 212, 0.12) 0%, rgba(6, 182, 212, 0) 70%); filter: blur(40px); pointer-events: none; border-radius: 50%; z-index: 1;"></div>

        <!-- Page Content -->
        <div class="container" style="position: relative; z-index: 2; padding-top: 20px;">
            <?= $hero_content ?? '' ?>
        </div>    </div>

    <!-- Below-the-fold content -->
    <?php if (!empty($body_content)): ?>
    <div style="position: relative; background: #faf8f8; overflow: hidden;">
        <!-- Soft background glow blobs -->
        <div style="position: absolute; top: 0; left: -5%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(183, 28, 28, 0.03) 0%, transparent 70%); filter: blur(60px); pointer-events: none; border-radius: 50%; z-index: 1;"></div>
        <div style="position: absolute; bottom: 5%; right: -5%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(183, 28, 28, 0.03) 0%, transparent 70%); filter: blur(60px); pointer-events: none; border-radius: 50%; z-index: 1;"></div>
        <div style="position: relative; z-index: 2;">
            <?= $body_content ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Load shared scripts -->
    <script src="<?= base_url('assets/js/complaint.js') ?>"></script>
    
    <?= $extra_scripts ?? '' ?>

    <footer class="modern-footer">
        <!-- Glow effects -->
        <div class="footer-bg-glow"></div>
        <div class="footer-bg-glow-2"></div>
        
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand-section">
                    <div class="footer-brand">
                        <div class="logo-icon-wrapper" style="width: 32px; height: 32px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-comments" style="color: #ef4444; font-size: 13px;"></i>
                        </div>
                        <span style="font-weight: 800; color: #ffffff; font-size: 19px; letter-spacing: 0.05em;">SIGAP</span>
                    </div>
                    <p style="margin: 0 0 20px; color: #9ca3af; font-size: 13.5px; line-height: 1.7;">
                        Portal aspirasi dan pengaduan online terintegrasi. Sampaikan masukan atau aduan Anda secara aman, transparan, dan akuntabel guna meningkatkan pelayanan publik.
                    </p>
                </div>
                <div>
                    <h4 class="footer-title">Tautan Cepat</h4>
                    <ul class="footer-links">
                        <li><a href="<?= base_url('/') ?>"><i class="fa-solid fa-chevron-right"></i>Beranda</a></li>
                        <li><a href="<?= base_url('tracking') ?>"><i class="fa-solid fa-chevron-right"></i>Lacak Laporan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">Hubungi Kami</h4>
                    <ul class="footer-info">
                        <!-- <li>
                            <i class="fa-solid fa-location-dot"></i>
                            <span>Gedung Mal Pelayanan Publik (MPP)<br>Lantai Utama, Area Informasi</span>
                        </li> -->
                        <li>
                            <i class="fa-solid fa-envelope"></i>
                            <span>support@sigap.go.id</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-clock"></i>
                            <span>Senin - Jumat: 08:00 - 15:00 WIB</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div>
                    &copy; <?= date('Y') ?> <strong>Sigap</strong>. Semua Hak Cipta Dilindungi.
                </div>
                <div class="footer-socials">
                    <a href="#" class="footer-social-btn" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="footer-social-btn" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" class="footer-social-btn" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
