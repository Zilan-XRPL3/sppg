<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - SPPG Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #6B8E23; --dark: #1A2605; --bg: #F4F7FE; --white: #FFFFFF; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: #2D3748; }
        
        nav { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); padding: 20px 5%; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1000; border-bottom: 1px solid rgba(226, 232, 240, 0.5); }
        .logo { display: flex; align-items: center; gap: 12px; text-decoration: none; color: var(--dark); font-weight: 800; }
        .logo-icon { width: 35px; height: 35px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; }

        .content-container { max-width: 1200px; margin: 60px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 1.5fr; gap: 40px; }
        
        .card { background: var(--white); padding: 35px; border-radius: 30px; border: 1px solid rgba(226, 232, 240, 0.8); margin-bottom: 25px; }
        .icon-box { width: 50px; height: 50px; background: #F0F9EB; color: var(--primary); border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 20px; }
        
        .social-links { display: flex; flex-direction: column; gap: 15px; margin-top: 20px; }
        .btn-sosmed { display: flex; align-items: center; gap: 12px; padding: 12px 20px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 14px; transition: 0.3s; }
        .btn-ig { background: #FDE7F3; color: #D1105A; }
        .btn-tt { background: #E2E8F0; color: #000000; }
        .btn-maps { background: var(--dark); color: white; margin-top: 15px; justify-content: center; }

        .map-wrapper { background: var(--white); border-radius: 40px; padding: 12px; border: 1px solid rgba(226, 232, 240, 0.8); height: 600px; overflow: hidden; }
        iframe { width: 100%; height: 100%; border-radius: 28px; border: none; }
    </style>
</head>
<body>
    <nav>
        <a href="index.php" class="logo"><div class="logo-icon"><i class="fas fa-leaf"></i></div> SPPG CENTER</a>
    </nav>

    <div class="content-container">
        <div>
            <div class="card">
                <div class="icon-box"><i class="fas fa-share-nodes"></i></div>
                <h3>Media Sosial</h3>
                <p style="color: #64748B; font-size: 14px; margin-bottom: 15px;">Ikuti aktivitas terbaru kami di platform favorit Anda.</p>
                <div class="social-links">
                    <a href="https://instagram.com/sppg_cimurah" class="btn-sosmed btn-ig" target="_blank">
                        <i class="fab fa-instagram"></i> @sppg_cimurah
                    </a>
                    <a href="https://tiktok.com/@sppg.cimurah" class="btn-sosmed btn-tt" target="_blank">
                        <i class="fab fa-tiktok"></i> @sppg.cimurah
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="icon-box"><i class="fas fa-location-dot"></i></div>
                <h3>Alamat Kantor</h3>
                <p style="color: #64748B; font-size: 14px;">Kawasan Tugu Muda, Kota Semarang, Jawa Tengah.</p>
                <a href="http://googleusercontent.com/maps.google.com/9" class="btn-sosmed btn-maps" target="_blank">
                    <i class="fas fa-location-arrow"></i> Petunjuk Arah
                </a>
            </div>
        </div>

        <div class="map-wrapper">
            <iframe src="https://maps.google.com0" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</body>
</html>