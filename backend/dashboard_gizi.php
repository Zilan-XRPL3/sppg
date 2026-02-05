<?php
session_start();
// Pastikan hanya petugas gizi yang bisa masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petugas_gizi') {
    header("Location: login.php"); exit;
}
include 'koneksi.php';

// Ambil statistik total menu
$query_menu = mysqli_query($conn, "SELECT COUNT(*) as total FROM menu_harian");
$data_menu = mysqli_fetch_assoc($query_menu);

// Ambil menu terbaru untuk ditampilkan di dashboard
$query_recent = mysqli_query($conn, "SELECT * FROM menu_harian ORDER BY tanggal DESC LIMIT 1");
$recent = mysqli_fetch_assoc($query_recent);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gizi - SPPG MBG</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6B8E23;
            --dark-olive: #1A2605; /* Diselaraskan dengan --dark pengaduan */
            --white: #FFFFFF;
            --bg: #F4F7FE;
            --accent: #D9E8B5;
            --text-main: #2D3748;
            --text-light: #8B949E;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; text-decoration: none; list-style: none; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-main); display: flex; min-height: 100vh; }

        /* SIDEBAR PREMIUM (Identik dengan Pengaduan) */
        .sidebar { 
            width: 280px; background: var(--dark-olive); color: white; position: fixed; 
            height: calc(100vh - 40px); margin: 20px; border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2); display: flex; flex-direction: column; overflow: hidden;
            z-index: 100;
        }

        .sidebar-logo { padding: 40px 30px; display: flex; align-items: center; gap: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .logo-box { 
            width: 45px; height: 45px; background: var(--primary); border-radius: 12px; 
            display: flex; align-items: center; justify-content: center; font-size: 20px; 
            box-shadow: 0 8px 15px rgba(107, 142, 35, 0.4); 
        }
        .logo-text { font-size: 18px; font-weight: 800; letter-spacing: -0.5px; color: white; }
        .logo-text span { color: var(--primary); display: block; font-size: 12px; letter-spacing: 2px; }

        .sidebar-menu { padding: 30px 20px; flex-grow: 1; }
        .sidebar-menu a { 
            display: flex; align-items: center; padding: 14px 20px; color: rgba(255,255,255,0.5); 
            text-decoration: none; font-weight: 600; border-radius: 18px; margin-bottom: 10px; transition: 0.3s; 
        }
        .sidebar-menu a i { font-size: 18px; width: 25px; margin-right: 12px; text-align: center; }
        .sidebar-menu a:hover { color: white; background: rgba(255,255,255,0.05); }
        .sidebar-menu a.active { 
            background: var(--primary); color: white; 
            box-shadow: 0 10px 20px rgba(107, 142, 35, 0.3); 
        }
        
        .sidebar-footer { padding: 20px; border-top: 1px solid rgba(255,255,255,0.05); }
        .btn-logout { 
            width: 100%; padding: 14px; border-radius: 15px; background: rgba(255, 71, 87, 0.1); 
            color: #FF4757; text-decoration: none; display: flex; align-items: center; justify-content: center; 
            gap: 10px; font-weight: 700; font-size: 14px; transition: 0.3s; 
        }
        .btn-logout:hover { background: #FF4757; color: white; }

        /* MAIN CONTENT */
        .main { margin-left: 320px; width: calc(100% - 320px); padding: 60px 50px; }
        .welcome-header { margin-bottom: 40px; }
        .welcome-header h1 { font-size: 32px; font-weight: 800; color: var(--dark-olive); margin-bottom: 8px; letter-spacing: -1px; }
        .welcome-header p { color: var(--text-light); font-size: 16px; }

        /* STATS */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 40px; }
        .card-stat { 
            background: var(--white); border-radius: 30px; padding: 30px; 
            display: flex; align-items: center; gap: 20px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #F1F5F9;
        }
        .icon-box-stat { 
            width: 70px; height: 70px; border-radius: 20px; 
            display: flex; align-items: center; justify-content: center; font-size: 28px;
        }
        .bg-primary-soft { background: #F0F4E8; color: var(--primary); }
        .bg-blue-soft { background: #E0F2FE; color: #0284C7; }

        /* CONTENT CARD */
        .content-card { background: var(--white); border-radius: 35px; padding: 40px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .content-card h3 { font-size: 22px; font-weight: 800; margin-bottom: 30px; color: var(--dark-olive); display: flex; align-items: center; gap: 15px; }

        .recent-menu-wrapper {
            display: grid; grid-template-columns: 220px 1fr; gap: 40px; 
            background: #F8FAFC; padding: 35px; border-radius: 30px; border: 1px solid #F1F5F9;
        }
        .recent-img { width: 100%; height: 220px; object-fit: cover; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        
        .tag-date { 
            display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; 
            background: var(--accent); color: var(--dark-olive); border-radius: 100px; 
            font-size: 13px; font-weight: 700; margin-bottom: 20px; 
        }
        .menu-title { font-size: 32px; font-weight: 800; color: var(--dark-olive); margin-bottom: 15px; }
        .menu-desc { color: var(--text-light); line-height: 1.8; margin-bottom: 30px; font-size: 15px; }

        .btn-action { 
            display: inline-flex; align-items: center; gap: 10px; padding: 16px 30px; 
            background: var(--dark-olive); color: white; border-radius: 18px; 
            font-weight: 700; transition: 0.3s; 
        }
        .btn-action:hover { background: var(--primary); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(107, 142, 35, 0.2); }

        .no-data { text-align: center; padding: 50px; background: #F8FAFC; border-radius: 30px; border: 2px dashed #E2E8F0; }

        @media (max-width: 1024px) {
            .recent-menu-wrapper { grid-template-columns: 1fr; }
            .recent-img { height: 300px; }
        }

        @media (max-width: 768px) {
            .sidebar { 
                display: flex; transform: translateX(-110%); 
                transition: transform 0.3s ease-in-out;
                box-shadow: 10px 0 30px rgba(0,0,0,0.1);
            }
            .sidebar.active { transform: translateX(0); }
            
            .main { margin-left: 0; width: 100%; padding: 20px; padding-top: 80px; }
            .stats-grid { grid-template-columns: 1fr; }
            
            /* Mobile Toggle Button */
            .mobile-toggle { 
                display: flex !important; 
                position: fixed; top: 20px; right: 20px; z-index: 1001; 
                background: var(--dark-olive); color: white; width: 45px; height: 45px; 
                border-radius: 12px; align-items: center; justify-content: center; 
                box-shadow: 0 4px 15px rgba(0,0,0,0.2); font-size: 20px; cursor: pointer;
            }
        }
        .mobile-toggle { display: none; }
    </style>
</head>
<body>

    <!-- Mobile Toggle Button -->
    <div class="mobile-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">
        <i class="fas fa-bars"></i>
    </div>

    <div class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-box"><i class="fas fa-apple-whole"></i></div>
            <div class="logo-text">SPPG <span>GIZI</span></div>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard_gizi.php" class="active"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></a>
            <a href="menu_harian.php"><i class="fas fa-utensils"></i> <span>Menu Harian</span></a>
            <a href="nilai_gizi.php"><i class="fas fa-chart-simple"></i> <span>Nilai Gizi</span></a>
        </div>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fas fa-power-off"></i> Keluar Sistem</a>
        </div>
    </div>

    <div class="main">
        <div class="welcome-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>Halo, Ahli Gizi! 👋</h1>
                    <p>Pantau kualitas nutrisi dan sajikan menu terbaik untuk hari ini.</p>
                </div>
                <div style="background: white; padding: 12px 20px; border-radius: 18px; font-weight: 800; border: 1px solid #E2E8F0; font-size: 14px; color: var(--dark-olive);">
                    <i class="far fa-calendar-alt" style="color: var(--primary); margin-right: 10px;"></i> 
                    <span id="live-date">Memuat...</span>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="card-stat">
                <div class="icon-box-stat bg-primary-soft"><i class="fas fa-bowl-rice"></i></div>
                <div>
                    <p style="color: var(--text-light); font-size: 14px; font-weight: 600; margin-bottom: 5px;">Koleksi Menu</p>
                    <h2 style="font-size: 28px; font-weight: 800;"><?= $data_menu['total'] ?> <span style="font-size: 14px; color: var(--text-light); font-weight: 500;">Sajian</span></h2>
                </div>
            </div>
            <div class="card-stat">
                <div class="icon-box-stat bg-blue-soft"><i class="fas fa-check-double"></i></div>
                <div>
                    <p style="color: var(--text-light); font-size: 14px; font-weight: 600; margin-bottom: 5px;">Status Sistem</p>
                    <h2 style="font-size: 24px; font-weight: 800; color: #0284C7;">Optimal</h2>
                </div>
            </div>
        </div>

        <div class="content-card">
            <h3><i class="fas fa-calendar-day" style="color: var(--primary);"></i> Sajian Terbaru</h3>
            
            <?php if ($recent): ?>
                <div class="recent-menu-wrapper">
                    <img src="uploads/<?= $recent['foto'] ?>" class="recent-img" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&q=80&w=500'">
                    <div>
                        <div class="tag-date">
                            <i class="fas fa-clock"></i> <?= date('d F Y', strtotime($recent['tanggal'])) ?>
                        </div>
                        <h2 class="menu-title"><?= $recent['nama_menu'] ?></h2>
                        <p class="menu-desc">
                            <?= $recent['deskripsi'] ?: 'Belum ada deskripsi mendalam mengenai komposisi bahan makanan untuk sajian menu hari ini.' ?>
                        </p>
                        <a href="menu_harian.php" class="btn-action">
                            <i class="fas fa-pen-to-square"></i> Kelola Koleksi Menu
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-folder-open" style="font-size: 50px; color: #CBD5E1; margin-bottom: 20px; display: block;"></i>
                    <p style="color: var(--text-light); font-weight: 600; margin-bottom: 20px;">Belum ada menu yang direncanakan.</p>
                    <a href="menu_harian.php" class="btn-action">Mulai Input Menu</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function updateLiveDate() {
            const months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            const now = new Date();
            const day = String(now.getDate()).padStart(2, '0');
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            document.getElementById('live-date').innerText = day + " " + month + " " + year;
        }
        updateLiveDate();
    </script>
</body>
</html>