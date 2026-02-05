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
    $id     = $_POST['id_sekolah'];
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_sekolah']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah_siswa']);

    if ($id == "") {
        $query = "INSERT INTO sekolah (nama_sekolah, alamat, jumlah_siswa) VALUES ('$nama', '$alamat', '$jumlah')";
    } else {
        $query = "UPDATE sekolah SET nama_sekolah='$nama', alamat='$alamat', jumlah_siswa='$jumlah' WHERE id_sekolah='$id'";
    }

    if(mysqli_query($conn, $query)){
        header("Location: sekolah.php?status=sukses");
        exit;
    }
}

/* --- LOGIC HAPUS DATA --- */
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM sekolah WHERE id_sekolah='$id'");
    header("Location: sekolah.php?status=terhapus");
    exit;
}

/* --- LOGIC AMBIL DATA UNTUK DIEDIT --- */
$edit = null;
if (isset($_GET['edit'])) {
    $id_edit = mysqli_real_escape_string($conn, $_GET['edit']);
    $q_edit = mysqli_query($conn, "SELECT * FROM sekolah WHERE id_sekolah='$id_edit'");
    if(mysqli_num_rows($q_edit) > 0){
        $edit = mysqli_fetch_assoc($q_edit);
    }
}

$data = mysqli_query($conn, "SELECT * FROM sekolah ORDER BY id_sekolah DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sekolah - SPPG MBG</title>
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
        }

        * { margin: 0; padding: 0; box-sizing: border-box; text-decoration: none; list-style: none; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-body);
            display: flex;
            min-height: 100vh;
            color: var(--text-main);
        }

        /* --- SIDEBAR PREMIUM (Identik dengan Dashboard) --- */
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
            margin-bottom: 40px;
        }

        header h2 { font-size: 30px; font-weight: 800; }

        /* --- CARDS & FORMS --- */
        .card {
            background: var(--white);
            border-radius: 32px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid #F1F5F9;
            margin-bottom: 30px;
        }

        .card h3 { 
            font-size: 20px; 
            font-weight: 800; 
            margin-bottom: 25px; 
            display: flex; 
            align-items: center; 
            gap: 12px;
            color: #1A2605;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .input-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--text-light);
            text-transform: uppercase;
        }

        input {
            width: 100%;
            padding: 14px 20px;
            border: 2px solid #F1F5F9;
            border-radius: 16px;
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
            background: #F8FAFC;
        }

        input:focus { border-color: var(--primary); background: var(--white); box-shadow: 0 0 0 4px rgba(107, 142, 35, 0.1); }

        .btn-save {
            background: var(--primary);
            color: white;
            border: none;
            padding: 16px 30px;
            border-radius: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-save:hover { background: #557219; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(107, 142, 35, 0.2); }

        /* --- TABLE STYLE --- */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        th { padding: 15px 20px; text-align: left; font-size: 12px; text-transform: uppercase; color: var(--text-light); letter-spacing: 1px; }
        
        tbody tr { transition: 0.3s; }
        td { 
            padding: 20px; 
            background: #FDFDFD; 
            border-top: 1px solid #F1F5F9;
            border-bottom: 1px solid #F1F5F9;
        }
        td:first-child { border-left: 1px solid #F1F5F9; border-top-left-radius: 20px; border-bottom-left-radius: 20px; }
        td:last-child { border-right: 1px solid #F1F5F9; border-top-right-radius: 20px; border-bottom-right-radius: 20px; }

        tbody tr:hover td { background: #F8FAFC; transform: scale(1.01); }

        .badge {
            background: #ECFDF5;
            color: #10B981;
            padding: 6px 14px;
            border-radius: 100px;
            font-weight: 700;
            font-size: 12px;
        }

        /* --- ACTIONS --- */
        .btn-edit { color: #F59E0B; background: #FFFBEB; padding: 10px; border-radius: 12px; margin-right: 8px; display: inline-block; }
        .btn-delete { color: #EF4444; background: #FEF2F2; padding: 10px; border-radius: 12px; display: inline-block; }
        .btn-edit:hover, .btn-delete:hover { filter: brightness(0.9); }

        @media (max-width: 1024px) { .form-grid { grid-template-columns: 1fr; } }
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; width: 100%; padding: 20px; }
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
            <a href="dashboard_admin.php"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></a>
            <a href="sekolah.php" class="active"><i class="fas fa-school"></i> <span>Data Sekolah</span></a>
            <a href="user.php"><i class="fas fa-users-gear"></i> <span>Manajemen User</span></a>
            <a href="tim_sppg.php"><i class="fas fa-user-shield"></i> <span>Tim SPPG</span></a>
        </div>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fas fa-power-off"></i> Keluar Sistem</a>
        </div>
    </div>

    <div class="main-content">
        <header>
            <h2>Database Sekolah</h2>
            <div style="background: white; padding: 10px 20px; border-radius: 100px; font-weight: 700; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                <i class="fas fa-user-circle" style="color: var(--primary);"></i> Admin
            </div>
        </header>

        <div class="card">
            <h3>
                <i class="fas <?= $edit ? 'fa-edit' : 'fa-plus-circle' ?>"></i> 
                <?= $edit ? 'Perbarui Informasi Sekolah' : 'Registrasi Sekolah Baru' ?>
            </h3>
            <form method="post">
                <input type="hidden" name="id_sekolah" value="<?= $edit['id_sekolah'] ?? '' ?>">

                <div class="form-grid">
                    <div class="input-group">
                        <label>Nama Sekolah</label>
                        <input type="text" name="nama_sekolah" placeholder="Masukkan nama sekolah..." required value="<?= $edit['nama_sekolah'] ?? '' ?>">
                    </div>
                    <div class="input-group">
                        <label>Alamat Lokasi</label>
                        <input type="text" name="alamat" placeholder="Nama jalan, nomor, dsb..." required value="<?= $edit['alamat'] ?? '' ?>">
                    </div>
                    <div class="input-group">
                        <label>Target Penerima (Siswa)</label>
                        <input type="number" name="jumlah_siswa" placeholder="0" required value="<?= $edit['jumlah_siswa'] ?? '' ?>">
                    </div>
                </div>
                
                <div style="display:flex; gap:15px; align-items:center;">
                    <button type="submit" name="simpan" class="btn-save">
                        <i class="fas fa-save"></i> <?= $edit ? 'Simpan Perubahan' : 'Daftarkan Sekolah' ?>
                    </button>

                    <?php if($edit): ?>
                        <a href="sekolah.php" style="margin-top:25px; color:var(--text-light); font-weight: 600; font-size:14px;">
                            <i class="fas fa-times"></i> Batalkan
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card">
            <h3><i class="fas fa-table"></i> List Sekolah Terdaftar</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Detail Sekolah</th>
                            <th>Alamat Lengkap</th>
                            <th>Kapasitas</th>
                            <th style="text-align: center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if(mysqli_num_rows($data) > 0){
                            while($d = mysqli_fetch_assoc($data)): 
                        ?>
                        <tr>
                            <td width="50" style="font-weight: 700; color: var(--text-light);"><?= $no++ ?></td>
                            <td>
                                <div style="font-weight:800; color:#1A2605; font-size: 15px;">
                                    <?= htmlspecialchars($d['nama_sekolah']) ?>
                                </div>
                            </td>
                            <td style="color: var(--text-light); font-size: 14px;">
                                <i class="fas fa-map-marker-alt" style="margin-right: 5px; font-size: 12px;"></i> 
                                <?= htmlspecialchars($d['alamat']) ?>
                            </td>
                            <td>
                                <span class="badge"><?= $d['jumlah_siswa'] ?> Siswa</span>
                            </td>
                            <td style="text-align: center;">
                                <a href="?edit=<?= $d['id_sekolah'] ?>" class="btn-edit" title="Edit Data">
                                    <i class="fas fa-pen-to-square"></i>
                                </a>
                                <a href="?hapus=<?= $d['id_sekolah'] ?>" class="btn-delete" title="Hapus Data" onclick="return confirm('Hapus sekolah ini dari database?')">
                                    <i class="fas fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center; color:var(--text-light); padding:40px;'>Belum ada data sekolah yang tersimpan.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>