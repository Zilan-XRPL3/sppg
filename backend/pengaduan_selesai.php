<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petugas_pengaduan') { 
    header("Location: login.php"); exit; 
}

$query = mysqli_query($conn, "SELECT * FROM pengaduan WHERE status = 'selesai' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Selesai - SPPG Center</title>
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
            --success: #10B981;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: var(--text-main); display: flex; min-height: 100vh; }

        /* SIDEBAR PREMIUM (Identik dengan Dashboard) */
        .sidebar { 
            width: 280px; background: var(--dark); color: white; position: fixed; 
            height: calc(100vh - 40px); margin: 20px; border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2); display: flex; flex-direction: column; overflow: hidden;
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
        .header { margin-bottom: 40px; display: flex; justify-content: space-between; align-items: flex-end; }
        .header h1 { font-size: 32px; font-weight: 800; color: var(--dark); letter-spacing: -1px; }

        /* GRID & CARDS */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 25px; }
        .card { 
            background: var(--white); padding: 30px; border-radius: 30px; 
            border: 1px solid rgba(226, 232, 240, 0.8); transition: 0.3s; 
            display: flex; flex-direction: column; 
        }
        .card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
        
        .status-badge { 
            align-self: flex-start; padding: 6px 15px; border-radius: 12px; 
            font-size: 11px; font-weight: 800; text-transform: uppercase; 
            margin-bottom: 20px; background: #EEF9F1; color: var(--success); 
        }

        .card h3 { font-size: 20px; font-weight: 800; color: var(--dark); margin-bottom: 12px; }
        .info-row { display: flex; align-items: center; gap: 12px; color: var(--text-muted); font-size: 13px; font-weight: 600; margin-bottom: 18px; }
        .info-row i { color: var(--success); width: 16px; text-align: center; }

        .card p { color: #64748B; font-size: 14px; line-height: 1.7; margin-bottom: 25px; }
        
        .btn-view { 
            margin-top: auto; padding: 15px; background: #EEF9F1; color: var(--success); 
            text-decoration: none; border-radius: 16px; text-align: center; 
            font-weight: 700; border: 1px solid #D1FAE5; transition: 0.3s;
        }
        .btn-view:hover { background: var(--success); color: white; }
        .btn-view:hover { background: var(--success); color: white; }
        
        @media (max-width: 768px) {
            .sidebar { 
                display: flex; transform: translateX(-110%); 
                transition: transform 0.3s ease-in-out;
                box-shadow: 10px 0 30px rgba(0,0,0,0.1);
            }
            .sidebar.active { transform: translateX(0); }
            
            .main { margin-left: 0; width: 100%; padding: 20px; padding-top: 80px; }
            .grid { grid-template-columns: 1fr; }
            
            /* Mobile Toggle Button */
            .mobile-toggle { 
                display: flex !important; 
                position: fixed; top: 20px; right: 20px; z-index: 1001; 
                background: var(--dark); color: white; width: 45px; height: 45px; 
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
            <div class="logo-box"><i class="fas fa-leaf"></i></div>
            <div class="logo-text">SPPG <span>CENTER</span></div>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard_pengaduan.php"><i class="fas fa-chart-pie"></i> Dashboard</a>
            <a href="pengaduan.php"><i class="fas fa-envelope-open-text"></i> Kotak Masuk</a>
            <a href="pengaduan_selesai.php" class="active"><i class="fas fa-check-double"></i> Selesai</a>
        </div>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fas fa-power-off"></i> Keluar Sistem</a>
        </div>
    </div>

    <div class="main">
        <div class="header">
            <div>
                <h1>Laporan Selesai</h1>
                <p style="color: var(--text-muted); margin-top: 5px;">Daftar pengaduan yang telah berhasil diselesaikan.</p>
            </div>
            <div style="background: white; padding: 12px 20px; border-radius: 18px; font-weight: 700; border: 1px solid #E2E8F0; font-size: 14px;">
                <i class="fas fa-archive" style="color: var(--primary); margin-right: 10px;"></i> <?= mysqli_num_rows($query) ?> Laporan Tersimpan
            </div>
        </div>

        <div class="grid">
            <?php while($row = mysqli_fetch_assoc($query)): ?>
            <div class="card">
                <div class="status-badge"><i class="fas fa-check-circle"></i> Selesai</div>
                <span style="font-size: 11px; font-weight: 800; color: var(--primary); margin-bottom: 8px; display: block; letter-spacing: 1px;">#<?= $row['no_tiket'] ?></span>
                <h3><?= htmlspecialchars($row['nama']) ?></h3>
                
                <div class="info-row">
                    <i class="fas fa-school"></i> 
                    <span><?= htmlspecialchars($row['sekolah']) ?></span>
                </div>
                
                <p><?= (strlen($row['isi_pengaduan']) > 120) ? substr(htmlspecialchars($row['isi_pengaduan']), 0, 120) . '...' : htmlspecialchars($row['isi_pengaduan']) ?></p>
                
                <a href="detail_pengaduan.php?id=<?= $row['id'] ?>" class="btn-view">
                    Lihat Detail Arsip <i class="fas fa-arrow-right" style="font-size: 10px; margin-left: 8px;"></i>
                </a>
            </div>
            <?php endwhile; ?>

            <?php if(mysqli_num_rows($query) == 0): ?>
            <div style="grid-column: 1 / -1; background: white; padding: 60px; border-radius: 30px; text-align: center; color: #94A3B8; border: 1px dashed #E2E8F0;">
                <i class="fas fa-folder-open fa-3x" style="margin-bottom: 20px; opacity: 0.3;"></i>
                <p>Belum ada laporan yang diselesaikan.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>