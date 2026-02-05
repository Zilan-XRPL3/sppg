<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petugas_pengaduan') {
    header("Location: login.php"); exit;
}

// Logika Statistik yang lebih fleksibel
$query_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan"));
$query_pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan WHERE status = 'pending'"));
$query_proses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan WHERE status = 'proses'"));
$query_selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan WHERE status = 'selesai'"));

$query_recent = mysqli_query($conn, "SELECT * FROM pengaduan ORDER BY id DESC LIMIT 1");
$recent = mysqli_fetch_assoc($query_recent);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPPG Center - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6B8E23;
            --dark: #1A2605;
            --bg: #F4F7FE;
            --white: #FFFFFF;
            --text-main: #2D3748;
            --text-muted: #8B949E;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body { background: var(--bg); color: var(--text-main); display: flex; min-height: 100vh; }

        /* SIDEBAR PREMIUM */
        .sidebar { 
            width: 280px; background: var(--dark); color: white; position: fixed; 
            height: calc(100vh - 40px); margin: 20px; border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2); display: flex; flex-direction: column; overflow: hidden;
        }

        .sidebar-logo { padding: 40px 30px; display: flex; align-items: center; gap: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .logo-box { width: 45px; height: 45px; background: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 8px 15px rgba(107, 142, 35, 0.4); }
        .logo-text { font-size: 18px; font-weight: 800; letter-spacing: -0.5px; }
        .logo-text span { color: var(--primary); display: block; font-size: 12px; letter-spacing: 2px; }

        .sidebar-menu { padding: 30px 20px; flex-grow: 1; }
        .sidebar-menu a { 
            display: flex; align-items: center; padding: 14px 20px; color: rgba(255,255,255,0.5); 
            text-decoration: none; font-weight: 600; border-radius: 18px; margin-bottom: 10px; transition: 0.3s; 
        }
        .sidebar-menu a i { font-size: 18px; width: 25px; margin-right: 12px; }
        .sidebar-menu a:hover { color: white; background: rgba(255,255,255,0.05); }
        .sidebar-menu a.active { background: var(--primary); color: white; box-shadow: 0 10px 20px rgba(107, 142, 35, 0.3); }

        .sidebar-footer { padding: 20px; border-top: 1px solid rgba(255,255,255,0.05); }
        .btn-logout { 
            width: 100%; padding: 14px; border-radius: 15px; background: rgba(255, 71, 87, 0.1); 
            color: #FF4757; text-decoration: none; display: flex; align-items: center; justify-content: center; 
            gap: 10px; font-weight: 700; font-size: 14px; transition: 0.3s; 
        }
        .btn-logout:hover { background: #FF4757; color: white; }

        /* MAIN CONTENT */
        .main { margin-left: 320px; width: calc(100% - 320px); padding: 60px 50px; }
        
        .header { margin-bottom: 40px; display: flex; justify-content: space-between; align-items: flex-end; }
        .header h1 { font-size: 32px; font-weight: 800; color: var(--dark); letter-spacing: -1px; }
        .header p { color: var(--text-muted); font-weight: 500; margin-top: 5px; }

        /* STATS GRID */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px; margin-bottom: 40px; }
        
        .stat-card { 
            background: var(--white); padding: 25px; border-radius: 28px; 
            border: 1px solid rgba(226, 232, 240, 0.8); transition: all 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }

        .icon-circle { 
            width: 50px; height: 50px; border-radius: 15px; display: flex; 
            align-items: center; justify-content: center; font-size: 20px; margin-bottom: 20px; 
        }
        
        .bg-all { background: #F0F4FF; color: #4361EE; }
        .bg-wait { background: #FFF9E6; color: #F59E0B; }
        .bg-work { background: #E6F6FF; color: #0EA5E9; }
        .bg-done { background: #EEF9F1; color: #10B981; }

        .stat-card span { font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
        .stat-card h2 { font-size: 30px; font-weight: 800; color: var(--dark); margin-top: 5px; }

        /* RECENT BOX */
        .section-title { font-size: 20px; font-weight: 800; color: var(--dark); margin-bottom: 25px; display: flex; align-items: center; gap: 12px; }
        .section-title i { color: var(--primary); }

        .recent-card { 
            background: var(--white); padding: 30px; border-radius: 30px; 
            border: 1px solid rgba(226, 232, 240, 0.8); display: flex; align-items: center; gap: 30px;
        }

        .recent-img { width: 120px; height: 120px; border-radius: 20px; object-fit: cover; }
        
        .recent-info { flex-grow: 1; }
        .recent-info h4 { font-size: 18px; font-weight: 800; color: var(--dark); margin-bottom: 8px; }
        .recent-info p { color: #64748B; font-size: 14px; line-height: 1.6; max-width: 500px; }

        .btn-go { 
            padding: 16px 30px; background: var(--primary); color: white; 
            text-decoration: none; border-radius: 18px; font-weight: 800; 
            font-size: 14px; transition: 0.3s; box-shadow: 0 10px 20px rgba(107, 142, 35, 0.2);
        }
        .btn-go:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(107, 142, 35, 0.3); }

        @media (max-width: 1100px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .recent-card { flex-direction: column; text-align: center; }
            .recent-info p { margin: 0 auto 20px; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-box"><i class="fas fa-leaf"></i></div>
            <div class="logo-text">SPPG <span>CENTER</span></div>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard_pengaduan.php" class="active"><i class="fas fa-chart-pie"></i> Dashboard</a>
            <a href="pengaduan.php"><i class="fas fa-envelope-open-text"></i> Kotak Masuk</a>
            <a href="#"><i class="fas fa-check-double"></i> Selesai</a>
        </div>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fas fa-power-off"></i> Keluar Sistem</a>
        </div>
    </div>

    <div class="main">
        <div class="header">
            <div>
                <h1>Halo, Petugas! 👋</h1>
                <p>Pantau statistik dan laporan masuk hari ini.</p>
            </div>
            <div style="background: white; padding: 12px 20px; border-radius: 18px; font-weight: 700; font-size: 14px; border: 1px solid #E2E8F0;">
                <i class="far fa-calendar-alt" style="color: var(--primary); margin-right: 8px;"></i> <?= date('d F Y') ?>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon-circle bg-all"><i class="fas fa-layer-group"></i></div>
                <span>Total Laporan</span>
                <h2><?= $query_total['total'] ?></h2>
            </div>
            <div class="stat-card">
                <div class="icon-circle bg-wait"><i class="fas fa-clock"></i></div>
                <span>Menunggu</span>
                <h2><?= $query_pending['total'] ?></h2>
            </div>
            <div class="stat-card">
                <div class="icon-circle bg-work"><i class="fas fa-spinner"></i></div>
                <span>Diproses</span>
                <h2><?= $query_proses['total'] ?></h2>
            </div>
            <div class="stat-card">
                <div class="icon-circle bg-done"><i class="fas fa-check-circle"></i></div>
                <span>Telah Selesai</span>
                <h2><?= $query_selesai['total'] ?></h2>
            </div>
        </div>

        <h3 class="section-title"><i class="fas fa-bolt"></i> Laporan Terbaru</h3>
        <?php if($recent): ?>
        <div class="recent-card">
            <?php if(!empty($recent['foto']) && file_exists("uploads/" . $recent['foto'])): ?>
                <img src="uploads/<?= $recent['foto'] ?>" class="recent-img">
            <?php else: ?>
                <img src="https://placehold.co/200?text=No+Photo" class="recent-img">
            <?php endif; ?>
            <div class="recent-info">
                <h4><?= $recent['nama'] ?></h4>
                <p>"<?= (strlen($recent['isi_pengaduan']) > 150) ? substr($recent['isi_pengaduan'], 0, 150) . '...' : $recent['isi_pengaduan'] ?>"</p>
            </div>
            <a href="pengaduan.php" class="btn-go">Tindak Lanjut <i class="fas fa-arrow-right" style="margin-left: 10px;"></i></a>
        </div>
        <?php else: ?>
            <div style="background: white; padding: 50px; border-radius: 30px; text-align: center; color: #94A3B8;">
                <i class="fas fa-coffee fa-3x" style="margin-bottom: 20px; opacity: 0.3;"></i>
                <p>Belum ada aktivitas laporan masuk hari ini.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>