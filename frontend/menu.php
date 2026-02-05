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
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="page-menu">

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