<?php include '../backend/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MBG App | Healthy & Modern</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #4A5D23; --secondary: #8BA84C; --bg: #F4F7F2; --white: #ffffff; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: #2D3A15; overflow-x: hidden; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 25px; }

        /* HERO - FULL GAYA GLOOMY GREEN */
        .hero-wrapper { padding: 30px 0; }
        .hero-main { 
            background: linear-gradient(135deg, #2D3A15 0%, #4A5D23 100%);
            border-radius: 60px; padding: 80px; color: white;
            display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 40px; align-items: center;
            position: relative; overflow: hidden; box-shadow: 0 30px 60px rgba(45, 58, 21, 0.25);
        }
        .hero-text h1 { font-size: 64px; font-weight: 800; line-height: 1; letter-spacing: -2px; margin-bottom: 25px; }
        .hero-text p { font-size: 18px; opacity: 0.8; margin-bottom: 40px; max-width: 450px; }

        /* PORTAL LOGIN GAYA FLOATING */
        .portal-box {
            background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(20px);
            padding: 35px; border-radius: 40px; border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center; width: 320px;
        }
        .btn-login {
            display: block; background: #8BA84C; color: white; padding: 18px;
            border-radius: 20px; text-decoration: none; font-weight: 800; transition: 0.3s;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .btn-login:hover { transform: scale(1.05); background: white; color: var(--primary); }

        /* INFO CARDS - APPLE STYLE */
        .info-section { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: -60px; margin-bottom: 80px; }
        .info-card { 
            background: white; padding: 50px 40px; border-radius: 50px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.03); border: 1px solid #EBF0E9; transition: 0.4s;
        }
        .info-card:hover { transform: translateY(-15px); box-shadow: 0 30px 60px rgba(74, 93, 35, 0.1); }
        .info-card i { font-size: 32px; color: var(--primary); margin-bottom: 25px; display: block; }
        .info-card h3 { font-size: 24px; font-weight: 800; margin-bottom: 15px; }

        /* MAPS SECTION - MEGA WIDE */
        .maps-title { text-align: center; margin-bottom: 40px; font-size: 32px; font-weight: 800; }
        .map-wrapper { 
            background: white; padding: 20px; border-radius: 60px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05); margin-bottom: 80px;
        }
        .map-iframe { width: 100%; height: 500px; border-radius: 45px; overflow: hidden; border: none; }

        /* SOSMED - PILLS STYLE */
        .sosmed-container { display: flex; justify-content: center; gap: 20px; padding-bottom: 100px; }
        .sosmed-pill { 
            background: white; padding: 20px 40px; border-radius: 100px;
            display: flex; align-items: center; gap: 15px; text-decoration: none;
            color: var(--primary); font-weight: 800; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: 0.3s; border: 1px solid #eee;
        }
        .sosmed-pill:hover { background: var(--primary); color: white; transform: translateY(-5px); }

        @media (max-width: 992px) { .hero-main, .info-section { grid-template-columns: 1fr; } .hero-main { padding: 40px; text-align: center; } .portal-box { width: 100%; } }
    </style>
</head>
<body>

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