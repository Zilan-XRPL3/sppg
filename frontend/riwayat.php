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
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="page-riwayat">
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