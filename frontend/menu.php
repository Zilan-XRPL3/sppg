<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Sesuaikan jalur ke koneksi.php (asumsi menu.php ada di folder frontend)
include '../backend/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Sehat - MBG App</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --primary: #4A5D23; 
            --primary-soft: #E8F0E5;
            --bg: #FDFDFD; 
            --text: #232931; 
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: var(--text); padding-bottom: 50px; }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 25px; }

        /* HEADER */
        .page-header { padding: 60px 0 40px; }
        .page-header h2 { font-size: 36px; font-weight: 800; letter-spacing: -1px; color: var(--primary); }
        .page-header p { color: #64748B; font-weight: 600; margin-top: 5px; }

        /* GRID */
        .grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); 
            gap: 30px; 
        }

        /* CARD APPLE STYLE */
        .card { 
            background: white; 
            border-radius: 45px; 
            overflow: hidden; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.03); 
            border: 1px solid #F0F4F2; 
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            cursor: pointer;
            position: relative;
        }

        .card:hover { 
            transform: translateY(-12px); 
            box-shadow: 0 25px 50px rgba(74, 93, 35, 0.12); 
        }

        .img-wrapper { position: relative; width: 100%; height: 260px; overflow: hidden; background: #f0f0f0; }
        .card img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .card:hover img { transform: scale(1.1); }

        .badge-date {
            position: absolute; top: 20px; right: 20px;
            background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);
            padding: 8px 16px; border-radius: 50px;
            font-size: 11px; font-weight: 800; color: var(--primary);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .card-body { padding: 30px; }
        .category { 
            display: inline-block; background: var(--primary-soft); 
            color: var(--primary); padding: 5px 12px; border-radius: 10px;
            font-size: 10px; font-weight: 800; text-transform: uppercase; margin-bottom: 15px;
        }

        .card-body h3 { font-size: 22px; font-weight: 800; margin-bottom: 10px; color: #1e293b; }
        
        .nutrition-info { display: flex; gap: 15px; margin-top: 20px; padding-top: 20px; border-top: 1px dashed #eee; }
        .nut-item { font-size: 12px; color: #64748B; font-weight: 700; display: flex; align-items: center; gap: 5px; }
        .nut-item i { color: var(--primary); }

        .empty-state { text-align: center; padding: 100px 0; grid-column: 1 / -1; }
        .empty-state i { font-size: 60px; color: #cbd5e1; margin-bottom: 20px; }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container">
        <header class="page-header">
            <h2>Menu Gizi Sehat</h2>
            <p>Sajian nutrisi harian untuk mendukung kesehatan warga.</p>
        </header>

        <div class="grid">
            <?php 
            // Query JOIN untuk mendapatkan info gizi langsung di kartu
            $query = mysqli_query($conn, "SELECT m.*, g.kalori, g.protein 
                                          FROM menu_harian m 
                                          LEFT JOIN nilai_gizi g ON m.id_menu = g.id_menu 
                                          ORDER BY m.tanggal DESC");
            
            if(mysqli_num_rows($query) > 0):
                while($m = mysqli_fetch_assoc($query)): 
                    // JALUR FOTO: mengarah ke folder uploads di backend
                    $foto_path = "../backend/uploads/" . $m['foto'];
            ?>
            <div class="card" onclick="location.href='detail_gizi.php?id=<?= $m['id_menu'] ?>'">
                <div class="img-wrapper">
                    <img src="<?= $foto_path ?>?t=<?= time() ?>" 
                         onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1000&auto=format&fit=crop'">
                    
                    <div class="badge-date">
                        <i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($m['tanggal'])) ?>
                    </div>
                </div>
                
                <div class="card-body">
                    <span class="category">Menu Terpilih</span>
                    <h3><?= htmlspecialchars($m['nama_menu']) ?></h3>
                    
                    <div class="nutrition-info">
                        <div class="nut-item"><i class="fas fa-fire"></i> <?= $m['kalori'] ?? '0' ?> kcal</div>
                        <div class="nut-item"><i class="fas fa-egg"></i> <?= $m['protein'] ?? '0' ?>g Pro</div>
                        <div class="nut-item" style="margin-left: auto; color: var(--primary);">
                            Lihat <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endwhile; 
            else:
            ?>
            <div class="empty-state">
                <i class="fas fa-utensils"></i>
                <h3>Belum ada menu di daftar</h3>
                <p>Silakan kembali lagi nanti untuk melihat update menu terbaru.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>