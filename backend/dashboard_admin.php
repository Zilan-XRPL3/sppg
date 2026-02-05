<?php
session_start();
include 'koneksi.php'; 

// Cek level admin
if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: login.php");
    exit;
}

// Ambil data asli dari database agar angka otomatis berubah
$count_sekolah = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM sekolah"))['total'];
$count_petugas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='petugas_pengaduan'"))['total'];
$count_laporan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan"))['total'];
$count_tim = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tim_sppg"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Premium - SPPG MBG</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6B8E23;
            --dark-olive: #1A2605; /* Diselaraskan dengan sidebar sebelumnya */
            --accent-gold: #B8860B;
            --bg-body: #F4F7FE;
            --white: #FFFFFF;
            --text-main: #2D3748;
            --text-light: #8B949E;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; text-decoration: none; list-style: none; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-body);
            display: flex;
            min-height: 100vh;
            color: var(--text-main);
        }

        /* --- SIDEBAR PREMIUM (Sama dengan Gizi) --- */
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
        .sidebar-menu a:hover { color: white; background: rgba(255,255,255,0.05); transform: translateX(5px); }
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

        /* --- MAIN CONTENT --- */
        .main-content {
            margin-left: 320px;
            width: calc(100% - 320px);
            padding: 50px 40px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 45px;
        }

        header h2 { font-size: 32px; font-weight: 800; color: #0F172A; }

        .user-pill {
            background: var(--white);
            padding: 10px 20px;
            border-radius: 100px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            border: 1px solid #F1F5F9;
        }

        .user-pill i { font-size: 35px; color: var(--primary); }
        .user-pill h4 { font-size: 14px; font-weight: 700; margin:0; }
        .user-pill small { color: #64748B; font-weight: 600; display: block; }

        /* --- CARDS GRID --- */
        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 45px;
        }

        .card-single {
            background: var(--white);
            padding: 30px 25px;
            border-radius: 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            border: 1px solid #F1F5F9;
            transition: 0.3s;
        }

        .card-single:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 30px rgba(0,0,0,0.04);
        }

        .card-single h1 { font-size: 32px; font-weight: 800; color: #1E293B; margin-bottom: 5px; }
        .card-single span { font-size: 13px; font-weight: 700; color: #94A3B8; text-transform: uppercase; }
        
        .icon-box {
            width: 60px; height: 60px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .clr-1 { background: #EEF2FF; color: #4F46E5; }
        .clr-2 { background: #FFF7ED; color: #EA580C; }
        .clr-3 { background: #F0F9FF; color: #0EA5E9; }
        .clr-4 { background: #ECFDF5; color: #10B981; }

        /* --- WELCOME BOARD --- */
        .welcome-board {
            background: var(--white);
            border-radius: 32px;
            padding: 45px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            border: 1px solid #F1F5F9;
            position: relative;
            overflow: hidden;
        }

        .welcome-board h3 { font-size: 24px; font-weight: 800; margin-bottom: 15px; color: #0F172A; }
        .welcome-board p { color: #64748B; line-height: 1.8; font-size: 16px; max-width: 700px; }
        
        .decoration {
            position: absolute;
            right: -50px;
            bottom: -50px;
            font-size: 250px;
            color: rgba(107, 142, 35, 0.05);
            transform: rotate(-15deg);
        }

        @media (max-width: 1200px) {
            .cards { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; width: 100%; padding: 20px; }
            .cards { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-box"><i class="fas fa-leaf"></i></div>
            <div class="logo-text">SPPG <span>MBG</span></div>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard_admin.php" class="active"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></a>
            <a href="sekolah.php"><i class="fas fa-school"></i> <span>Data Sekolah</span></a>
            <a href="user.php"><i class="fas fa-users-gear"></i> <span>Manajemen User</span></a>
            <a href="tim_sppg.php"><i class="fas fa-user-shield"></i> <span>Tim SPPG</span></a>
        </div>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fas fa-power-off"></i> Keluar Sistem</a>
        </div>
    </div>

    <div class="main-content">
        <header>
            <h2>Overview</h2>
            <div class="user-pill">
                <i class="fas fa-user-circle"></i>
                <div>
                    <h4>Admin Central</h4>
                    <small>Super Administrator</small>
                </div>
            </div>
        </header>

        <div class="cards">
            <div class="card-single">
                <div>
                    <h1><?= $count_sekolah ?></h1>
                    <span>Data Sekolah</span>
                </div>
                <div class="icon-box clr-1"><i class="fas fa-school"></i></div>
            </div>

            <div class="card-single">
                <div>
                    <h1><?= $count_petugas ?></h1>
                    <span>Total Petugas</span>
                </div>
                <div class="icon-box clr-2"><i class="fas fa-users-cog"></i></div>
            </div>

            <div class="card-single">
                <div>
                    <h1><?= $count_laporan ?></h1>
                    <span>Laporan Masuk</span>
                </div>
                <div class="icon-box clr-3"><i class="fas fa-clipboard-list"></i></div>
            </div>

            <div class="card-single">
                <div>
                    <h1><?= $count_tim ?></h1>
                    <span>Tim SPPG</span>
                </div>
                <div class="icon-box clr-4"><i class="fas fa-shield-alt"></i></div>
            </div>
        </div>

        <div class="welcome-board">
            <i class="fas fa-leaf decoration"></i>
            <h3>Selamat Datang, Admin! ✨</h3>
            <p>
                Sistem Pelayanan Gizi (SPPG) berjalan dengan optimal. Hari ini terdapat <strong><?= $count_laporan ?> laporan</strong> yang tersimpan dalam sistem. Gunakan panel navigasi di samping untuk mengelola database sekolah, memantau kinerja petugas, atau memperbarui profil tim strategis.
            </p>
            <div style="margin-top: 25px;">
                <a href="sekolah.php" style="color: var(--primary); font-weight: 700; font-size: 14px;">Mulai Kelola Data <i class="fas fa-arrow-right" style="margin-left:5px;"></i></a>
            </div>
        </div>
    </div>

</body>
</html>