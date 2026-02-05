<?php
session_start();
// Cek level admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

/* --- LOGIC TAMBAH / UPDATE USER --- */
if (isset($_POST['simpan'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $role = $_POST['role'];
    $id_user = $_POST['id_user']; // Ambil ID jika ada (untuk edit)

    if (!empty($id_user)) {
        // --- PROSES UPDATE ---
        if (!empty($_POST['password'])) {
            // Jika password diisi, update password juga
            $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $query = "UPDATE users SET username='$user', password='$pass_hash', role='$role' WHERE id_user='$id_user'";
        } else {
            // Jika password kosong, jangan ubah password lama
            $query = "UPDATE users SET username='$user', role='$role' WHERE id_user='$id_user'";
        }
        mysqli_query($conn, $query);
        header("Location: user.php?status=updated");
        exit;
    } else {
        // --- PROSES TAMBAH ---
        $pass = $_POST['password'];
        $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
        $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
        if(mysqli_num_rows($cek) > 0){
            echo "<script>alert('Username sudah ada! Ganti yang lain.');</script>";
        } else {
            $query = "INSERT INTO users (username, password, role) VALUES ('$user', '$pass_hash', '$role')";
            mysqli_query($conn, $query);
            header("Location: user.php?status=success");
            exit;
        }
    }
}

/* --- LOGIC AMBIL DATA EDIT --- */
$edit_data = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id_edit'");
    $edit_data = mysqli_fetch_assoc($res);
}

/* --- LOGIC HAPUS USER --- */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if(isset($_SESSION['id_user']) && $_SESSION['id_user'] == $id) { 
         echo "<script>alert('Tidak bisa menghapus akun sendiri!');window.location='user.php';</script>";
    } else {
        mysqli_query($conn, "DELETE FROM users WHERE id_user='$id'");
        header("Location: user.php?status=deleted");
        exit;
    }
}

$data = mysqli_query($conn, "SELECT * FROM users ORDER BY id_user DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - SPPG MBG</title>
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
        
        input, select {
            width: 100%; padding: 14px 20px; border: 2px solid #F1F5F9; border-radius: 16px;
            font-family: inherit; font-size: 14px; outline: none; transition: 0.3s; background: #F8FAFC;
        }
        input:focus, select:focus { border-color: var(--primary); background: var(--white); box-shadow: 0 0 0 4px rgba(107, 142, 35, 0.1); }

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
        td { padding: 20px; background: #FDFDFD; border-top: 1px solid #F1F5F9; border-bottom: 1px solid #F1F5F9; }
        td:first-child { border-left: 1px solid #F1F5F9; border-top-left-radius: 20px; border-bottom-left-radius: 20px; }
        td:last-child { border-right: 1px solid #F1F5F9; border-top-right-radius: 20px; border-bottom-right-radius: 20px; }

        .badge { padding: 6px 14px; border-radius: 100px; font-weight: 700; font-size: 11px; display: inline-flex; align-items: center; gap: 5px; }
        .badge-admin { background: #FFF7ED; color: #EA580C; }
        .badge-gizi { background: #ECFDF5; color: #10B981; }
        .badge-pengaduan { background: #EFF6FF; color: #3B82F6; }

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
            <a href="user.php" class="active"><i class="fas fa-users-gear"></i> <span>Manajemen User</span></a>
            <a href="tim_sppg.php"><i class="fas fa-user-shield"></i> <span>Tim SPPG</span></a>
        </div>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fas fa-power-off"></i> Keluar Sistem</a>
        </div>
    </div>

    <div class="main-content">
        <header>
            <h2>Akses Pengguna</h2>
            <div style="background: white; padding: 10px 20px; border-radius: 100px; font-weight: 700; box-shadow: 0 4px 6px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-shield-halved" style="color: var(--primary);"></i> Admin System
            </div>
        </header>

        <div class="card">
            <h3><i class="<?= $edit_data ? 'fas fa-user-pen' : 'fas fa-user-plus' ?>"></i> <?= $edit_data ? 'Perbarui Akses Akun' : 'Daftarkan Akun Baru' ?></h3>
            <form method="post">
                <input type="hidden" name="id_user" value="<?= $edit_data['id_user'] ?? '' ?>">
                <div class="form-grid">
                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" name="username" value="<?= $edit_data['username'] ?? '' ?>" placeholder="Contoh: petugas_gizi01" required autocomplete="off">
                    </div>
                    <div class="input-group">
                        <label>Kata Sandi <?= $edit_data ? '<small>(Kosongkan jika tak diubah)</small>' : '' ?></label>
                        <input type="password" name="password" placeholder="<?= $edit_data ? 'Isi untuk ganti password' : 'Minimal 6 karakter' ?>" <?= $edit_data ? '' : 'required' ?>>
                    </div>
                    <div class="input-group">
                        <label>Otoritas Role</label>
                        <select name="role" required>
                            <option value="">-- Pilih Level Akses --</option>
                            <option value="admin" <?= (isset($edit_data) && $edit_data['role'] == 'admin') ? 'selected' : '' ?>>Administrator</option>
                            <option value="petugas_gizi" <?= (isset($edit_data) && $edit_data['role'] == 'petugas_gizi') ? 'selected' : '' ?>>Petugas Gizi</option>
                            <option value="petugas_pengaduan" <?= (isset($edit_data) && $edit_data['role'] == 'petugas_pengaduan') ? 'selected' : '' ?>>Petugas Pengaduan</option>
                        </select>
                    </div>
                </div>
                
                <div style="display: flex; align-items: center;">
                    <button type="submit" name="simpan" class="btn-save">
                        <i class="fas fa-user-check"></i> <?= $edit_data ? 'Simpan Perubahan' : 'Daftarkan Pengguna' ?>
                    </button>
                    <?php if($edit_data): ?>
                        <a href="user.php" class="btn-cancel">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card">
            <h3><i class="fas fa-users-viewfinder"></i> Daftar Akun Terdaftar</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="80">No</th>
                            <th>Username</th>
                            <th>Level Akses</th>
                            <th style="text-align: center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($d = mysqli_fetch_assoc($data)): 
                            $badge_class = 'badge-admin';
                            if($d['role'] == 'petugas_gizi') $badge_class = 'badge-gizi';
                            if($d['role'] == 'petugas_pengaduan') $badge_class = 'badge-pengaduan';
                        ?>
                        <tr>
                            <td style="font-weight: 700; color: var(--text-light);"><?= $no++ ?></td>
                            <td>
                                <div style="font-weight: 800; color: var(--dark-olive);">
                                    <i class="fas fa-user-circle" style="margin-right: 10px; opacity: 0.5;"></i>
                                    <?= htmlspecialchars($d['username']) ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge <?= $badge_class ?>">
                                    <i class="fas fa-circle" style="font-size: 6px;"></i>
                                    <?= strtoupper(str_replace('_',' ',$d['role'])) ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <a href="?edit=<?= $d['id_user'] ?>" class="btn-edit" title="Edit User">
                                    <i class="fas fa-pen-to-square"></i>
                                </a>
                                <a href="?hapus=<?= $d['id_user'] ?>" class="btn-delete" title="Hapus User" onclick="return confirm('Hapus akses untuk user <?= $d['username'] ?>?')">
                                    <i class="fas fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>