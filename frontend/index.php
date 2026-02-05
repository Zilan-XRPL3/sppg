<?php include '../backend/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MBG App | Healthy & Modern</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="page-home">

    <?php include 'navbar.php'; ?>

    <div class="container">
        <section class="hero-wrapper">
            <div class="hero-main">
                <div class="hero-text">
                    <h1>Cimurah Sehat,<br>Garut Hebat.</h1>
                    <p>Program pemenuhan gizi masyarakat berkualitas oleh Yayasan Forsalima Sejahtera Desa Cimurah.</p>
                    <div style="display:flex; gap:15px;">
                         <div style="background:rgba(255,255,255,0.1); padding:15px 25px; border-radius:20px; border:1px solid rgba(255,255,255,0.1);">
                            <span style="display:block; font-size:24px; font-weight:800;">100%</span>
                            <span style="font-size:12px; opacity:0.7;">Bahan Lokal</span>
                         </div>
                         <div style="background:rgba(255,255,255,0.1); padding:15px 25px; border-radius:20px; border:1px solid rgba(255,255,255,0.1);">
                            <span style="display:block; font-size:24px; font-weight:800;">Setiap</span>
                            <span style="font-size:12px; opacity:0.7;">Hari Kerja</span>
                         </div>
                    </div>
                </div>

                <div class="portal-box">
                    <i class="fas fa-user-shield" style="font-size: 40px; margin-bottom: 20px; opacity: 0.9;"></i>
                    <h3 style="margin-bottom: 10px; font-weight: 800;">Portal Petugas</h3>
                    <p style="font-size: 13px; opacity: 0.8; margin-bottom: 25px;">Akses khusus pengelolaan data gizi dan laporan warga.</p>
                    
                    <a href="login.php" class="btn-login">Masuk Sekarang</a>
                </div>
            </div>
        </section>

        <section class="info-section">
            <div class="info-card">
                <i class="fas fa-leaf"></i>
                <h3>Gizi Alami</h3>
                <p style="color:#64748B; font-size:15px;">Menggunakan bahan baku segar langsung dari petani lokal Garut setiap harinya.</p>
            </div>
            <div class="info-card">
                <i class="fas fa-utensils"></i>
                <h3>Menu Variatif</h3>
                <p style="color:#64748B; font-size:15px;">Daftar menu yang disusun oleh ahli gizi untuk memenuhi kebutuhan kalori harian.</p>
            </div>
            <div class="info-card">
                <i class="fas fa-heart"></i>
                <h3>Amanah</h3>
                <p style="color:#64748B; font-size:15px;">Dikelola secara transparan dan profesional oleh tim Forsalima Sejahtera.</p>
            </div>
        </section>

        <h2 class="maps-title">Lokasi Dapur Produksi</h2>
        <div class="map-wrapper">
            <iframe class="map-iframe" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.468846394593!2d107.925!3d-7.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMTInMDAuMCJTIDEwN8KwNTUnMzAuMCJF!5e0!3m2!1sid!2sid!4v1700000000000" allowfullscreen="" loading="lazy"></iframe>
        </div>

        <div class="sosmed-container">
            <a href="https://instagram.com/sppg_cimurah" class="sosmed-pill">
                <i class="fab fa-instagram" style="font-size:24px;"></i> @sppg_cimurah
            </a>
            <a href="https://tiktok.com/@sppg.cimurah" class="sosmed-pill">
                <i class="fab fa-tiktok" style="font-size:24px;"></i> @sppg.cimurah
            </a>
            <a href="https://facebook.com/sppgcimurah" class="sosmed-pill">
                <i class="fab fa-facebook" style="font-size:24px;"></i> @sppg cimurah
            </a>
        </div>

        <footer style="text-align:center; padding-bottom:50px; opacity:0.5; font-weight:700; font-size:13px;">
            © 2026 FORSALIMA SEJAHTERA FOUNDATION. ALL RIGHTS RESERVED.
        </footer>
    </div>

</body>
</html>