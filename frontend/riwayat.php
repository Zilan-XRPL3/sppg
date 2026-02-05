<?php 
// 1. Pastikan koneksi benar. Jika file ini di folder 'frontend', maka keluar satu tingkat.
include '../backend/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Menu - MBG</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #4A5D23; --bg: #F8FAF7; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); }
        .container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        
        /* List Item Styling */
        .list-item { 
            background: white; 
            padding: 20px; 
            border-radius: 25px; 
            margin-bottom: 15px; 
            display: flex; 
            align-items: center; 
            gap: 20px; 
            border: 1px solid #eee; 
            transition: 0.3s; 
            text-decoration: none; 
            color: inherit;
        }
        .list-item:hover { background: #f0f4f0; transform: translateX(5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        
        /* Image Styling */
        .list-img { 
            width: 90px; 
            height: 90px; 
            border-radius: 20px; 
            object-fit: cover; 
            background: #f0f0f0;
        }

        .content-info h4 { font-weight: 800; font-size: 18px; color: #2D3748; }
        .content-info p { font-size: 13px; color: #718096; margin-top: 4px; }

        /* Kalori Badge */
        .kalori-badge { 
            background: #FFF9E6; 
            color: #D97706; 
            padding: 5px 12px; 
            border-radius: 10px; 
            font-size: 12px; 
            font-weight: 800; 
            display: inline-block; 
            margin-top: 10px; 
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h2 style="font-weight:800; margin-bottom:30px; color: #2D3E10;">📅 Riwayat Konsumsi Menu</h2>
        
        <?php 
        // 2. QUERY JOIN: Mengambil data menu sekaligus kalori dari tabel nilai_gizi
        $sql = "SELECT m.id_menu, m.nama_menu, m.tanggal, m.foto, g.kalori 
                FROM menu_harian m 
                LEFT JOIN nilai_gizi g ON m.id_menu = g.id_menu 
                ORDER BY m.tanggal DESC";
        
        $query = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($query) > 0) {
            while($m = mysqli_fetch_assoc($query)): 
                // 3. PENGECEKAN PATH GAMBAR
                // Kita coba arahkan ke folder uploads yang ada di folder petugas
                $nama_foto = $m['foto'];
                $path_foto = "../backend/uploads/" . $nama_foto; 
        ?>
        <a href="detail_gizi.php?id=<?= $m['id_menu'] ?>" class="list-item">
            <img src="<?= $path_foto ?>" class="list-img" onerror="this.src='https://placehold.co/200?text=No+Photo'">
            
            <div class="content-info" style="flex-grow: 1;">
                <h4><?= $m['nama_menu'] ?></h4>
                <p><i class="far fa-calendar-alt"></i> <?= date('l, d F Y', strtotime($m['tanggal'])) ?></p>
                
                <div class="kalori-badge">
                    <i class="fas fa-fire"></i> <?= ($m['kalori'] ?? '0') ?> kcal
                </div>
            </div>
            
            <i class="fas fa-chevron-right" style="color: #CBD5E1;"></i>
        </a>
        <?php 
            endwhile; 
        } else {
            echo "<p style='text-align:center; color:#718096;'>Belum ada riwayat menu.</p>";
        }
        ?>
    </div>
</body>
</html>