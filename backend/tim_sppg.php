<?php
session_start();
// 1. Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

/* --- LOGIC SIMPAN (TAMBAH / EDIT DATA) --- */
if (isset($_POST['simpan'])) {
    $id      = $_POST['id_tim']; 
    $nama    = mysqli_real_escape_string($conn, $_POST['nama_petugas']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $no_hp   = mysqli_real_escape_string($conn, $_POST['no_hp']);

    if ($id == "") {
        $query = "INSERT INTO tim_sppg (nama_petugas, jabatan, no_hp) VALUES ('$nama', '$jabatan', '$no_hp')";
    } else {
        $query = "UPDATE tim_sppg SET nama_petugas='$nama', jabatan='$jabatan', no_hp='$no_hp' WHERE id_tim='$id'";
    }

    if(mysqli_query($conn, $query)){
        header("Location: tim_sppg.php?status=success");
        exit;
    }
}

/* --- LOGIC HAPUS DATA --- */
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM tim_sppg WHERE id_tim='$id'");
    header("Location: tim_sppg.php?status=deleted");
    exit;
}

/* --- LOGIC AMBIL DATA UNTUK DIEDIT --- */
$edit = null;
if (isset($_GET['edit'])) {
    $id_edit = mysqli_real_escape_string($conn, $_GET['edit']);
    $q_edit = mysqli_query($conn, "SELECT * FROM tim_sppg WHERE id_tim='$id_edit'");
    if(mysqli_num_rows($q_edit) > 0){
        $edit = mysqli_fetch_assoc($q_edit);
    }
}

$data = mysqli_query($conn, "SELECT * FROM tim_sppg ORDER BY id_tim DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tim SPPG - Manajemen Pelaksana</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6B8E23;
            --dark-olive: #1A2605;
            --bg-body: #F4F7FE;
            --white: #FFFFFF;
            --text-main: #2D3748;
            --text-light: #8B949E;
            --accent: #D9E8B5;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; text-decoration: none; list-style: none; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-body);
            display: flex;
            min-height: 100vh;
            color: var(--text-main);
        }

        /* --- SIDEBAR PREMIUM --- */
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
        .main-content { margin-left: 320px; width: calc(100% - 320px); padding: 50px 40px; }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        header h2 { font-size: 30px; font-weight: 800; }

        .card {
            background: var(--white); border-radius: 32px; padding: 35px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #F1F5F9; margin-bottom: 30px;
        }
        .card h3 { 
            font-size: 20px; font-weight: 800; margin-bottom: 25px; 
            display: flex; align-items: center; gap: 12px; color: var(--dark-olive);
        }
        
        .form-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .input-group label { display: block; font-size: 13px; font-weight: 700; margin-bottom: 10px; color: var(--text-light); text-transform: uppercase; }
        
        input {
            width: 100%; padding: 14px 20px; border: 2px solid #F1F5F9; border-radius: 16px;
            font-family: inherit; font-size: 14px; outline: none; transition: 0.3s; background: #F8FAFC;
        }
        input:focus { border-color: var(--primary); background: var(--white); box-shadow: 0 0 0 4px rgba(107, 142, 35, 0.1); }

        .btn-save {
            background: var(--primary); color: white; border: none; padding: 16px 30px;
            border-radius: 16px; font-weight: 700; cursor: pointer; transition: 0.3s;
            display: inline-flex; align-items: center; gap: 10px; margin-top: 25px;
        }
        .btn-save:hover { background: #557219; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(107, 142, 35, 0.2); }
        
        .btn-cancel {
            display: inline-block; padding: 16px 30px; border-radius: 16px; font-weight: 700; 
            background: #F1F5F9; color: #64748B; margin-top: 25px; margin-left: 10px;
        }

        /* --- TABLE STYLE --- */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        th { padding: 15px 20px; text-align: left; font-size: 12px; text-transform: uppercase; color: var(--text-light); letter-spacing: 1px; }
        td { padding: 20px; background: #FDFDFD; border-top: 1px solid #F1F5F9; border-bottom: 1px solid #F1F5F9; vertical-align: middle; }
        td:first-child { border-left: 1px solid #F1F5F9; border-top-left-radius: 20px; border-bottom-left-radius: 20px; }
        td:last-child { border-right: 1px solid #F1F5F9; border-top-right-radius: 20px; border-bottom-right-radius: 20px; }

        .jabatan-text { font-weight: 600; color: var(--primary); font-size: 14px; }
        .contact-pill { background: #F0F4E8; color: var(--dark-olive); padding: 8px 16px; border-radius: 100px; font-weight: 700; font-size: 12px; display: inline-flex; align-items: center; gap: 8px; }

        .btn-edit { color: #F59E0B; background: #FFFBEB; padding: 10px; border-radius: 12px; margin-right: 8px; display: inline-block; }
        .btn-delete { color: #EF4444; background: #FEF2F2; padding: 10px; border-radius: 12px; display: inline-block; }
        .btn-edit:hover, .btn-delete:hover { filter: brightness(0.9); }

        @media (max-width: 1024px) { .form-grid { grid-template-columns: 1fr; } }

        @media (max-width: 768px) {
            .sidebar { 
                display: flex; transform: translateX(-110%); 
                transition: transform 0.3s ease-in-out;
                box-shadow: 10px 0 30px rgba(0,0,0,0.1);
            }
            .sidebar.active { transform: translateX(0); }
            
            .main-content { margin-left: 0; width: 100%; padding: 20px; padding-top: 80px; }
            
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
            <div class="logo-box"><i class="fas fa-leaf"></i></div>
            <div class="logo-text">SPPG <span>MBG</span></div>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard_admin.php"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></a>
            <a href="sekolah.php"><i class="fas fa-school"></i> <span>Data Sekolah</span></a>
            <a href="user.php"><i class="fas fa-users-gear"></i> <span>Manajemen User</span></a>
            <a href="tim_sppg.php" class="active"><i class="fas fa-user-shield"></i> <span>Tim SPPG</span></a>
        </div>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fas fa-power-off"></i> Keluar Sistem</a>
        </div>
    </div>

    <div class="main-content">
        <header>
            <h2>Struktur Tim Pelaksana</h2>
            <div style="background: white; padding: 10px 20px; border-radius: 100px; font-weight: 700; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                <i class="fas fa-circle-check" style="color: var(--primary);"></i> Sistem Aktif
            </div>
        </header>

        <div class="card">
            <h3>
                <i class="fas <?= $edit ? 'fa-user-pen' : 'fa-user-plus' ?>"></i> 
                <?= $edit ? 'Perbarui Profil Anggota' : 'Daftarkan Anggota Tim Baru' ?>
            </h3>
            <form method="post">
                <input type="hidden" name="id_tim" value="<?= $edit['id_tim'] ?? '' ?>">
                
                <div class="form-grid">
                    <div class="input-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_petugas" placeholder="Nama Petugas" required value="<?= $edit['nama_petugas'] ?? '' ?>">
                    </div>
                    <div class="input-group">
                        <label>Jabatan / Divisi</label>
                        <input type="text" name="jabatan" placeholder="Contoh: Koordinator Lapangan" required value="<?= $edit['jabatan'] ?? '' ?>">
                    </div>
                    <div class="input-group">
                        <label>Kontak WhatsApp</label>
                        <input type="text" name="no_hp" placeholder="08xxxx" required value="<?= $edit['no_hp'] ?? '' ?>">
                    </div>
                </div>

                <div style="display:flex; align-items:center;">
                    <button type="submit" name="simpan" class="btn-save">
                        <i class="fas fa-cloud-arrow-up"></i> <?= $edit ? 'Simpan Perubahan' : 'Simpan Anggota' ?>
                    </button>
                    
                    <?php if($edit): ?>
                        <a href="tim_sppg.php" class="btn-cancel">
                            <i class="fas fa-rotate-left"></i> Batal
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card">
            <h3><i class="fas fa-id-card-clip"></i> Direktori Anggota Tim</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="70">No</th>
                            <th>Nama Lengkap</th>
                            <th>Posisi</th>
                            <th>Informasi Kontak</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if(mysqli_num_rows($data) > 0){
                            while($d = mysqli_fetch_assoc($data)): 
                        ?>
                        <tr>
                            <td style="font-weight: 700; color: var(--text-light);"><?= $no++ ?></td>
                            <td>
                                <div style="font-weight:800; color:var(--dark-olive); font-size: 15px;">
                                    <?= htmlspecialchars($d['nama_petugas']) ?>
                                </div>
                            </td>
                            <td class="jabatan-text">
                                <i class="fas fa-briefcase" style="font-size: 12px; margin-right: 5px; opacity: 0.7;"></i>
                                <?= htmlspecialchars($d['jabatan']) ?>
                            </td>
                            <td>
                                <div class="contact-pill">
                                    <i class="fab fa-whatsapp"></i> 
                                    <?= htmlspecialchars($d['no_hp']) ?>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <a href="?edit=<?= $d['id_tim'] ?>" class="btn-edit" title="Edit">
                                    <i class="fas fa-pen-to-square"></i>
                                </a>
                                <a href="?hapus=<?= $d['id_tim'] ?>" class="btn-delete" title="Hapus" onclick="return confirm('Hapus data <?= $d['nama_petugas'] ?>?')">
                                    <i class="fas fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center; padding:40px; color:var(--text-light);'>Data tim masih kosong.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>