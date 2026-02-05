<?php
ob_start(); 
session_start();
include 'koneksi.php'; // Pastikan file koneksi.php ada di folder yang sama dengan login.php ini
$error = "";

if (isset($_POST['login'])) {
    $u = mysqli_real_escape_string($conn, $_POST['username']);
    $p = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$u'");
    if (mysqli_num_rows($query) === 1) {
        $data = mysqli_fetch_assoc($query);
        
        if (password_verify($p, $data['password'])) {
            $_SESSION['username'] = $data['username'];
            $_SESSION['role']     = $data['role'];
            $_SESSION['id_user']  = $data['id_user']; 

            if ($data['role'] == 'admin') {
                header("Location: dashboard_admin.php");
            } elseif ($data['role'] == 'petugas_gizi') {
                header("Location: dashboard_gizi.php");
            } elseif ($data['role'] == 'petugas_pengaduan') {
                header("Location: dashboard_pengaduan.php");
            } elseif ($data['role'] == 'masyarakat') {
                header("Location: pengaduan_masyarakat/form_pengaduan.php");
            }
            exit();
        } else { 
            $error = "Akses Ditolak! Password tidak sesuai."; 
        }
    } else { 
        $error = "Akses Ditolak! Akun tidak terdaftar."; 
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Verifikasi | MBG App</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-dark: #2D3A15; --primary-light: #8BA84C; --bg-soft: #F4F7F2; --white: #FFFFFF; }
        body { background: var(--bg-soft); font-family: 'Plus Jakarta Sans', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; overflow: hidden; }
        .circle { position: absolute; z-index: -1; border-radius: 50%; background: linear-gradient(135deg, #E2EAD3 0%, #D4DFC7 100%); filter: blur(50px); }
        .c1 { width: 300px; height: 300px; top: -100px; right: -50px; }
        .c2 { width: 250px; height: 250px; bottom: -50px; left: -80px; }
        .login-card { background: var(--white); padding: 50px 40px; border-radius: 40px; box-shadow: 0 25px 50px rgba(45, 58, 21, 0.08); width: 100%; max-width: 380px; text-align: center; border: 1px solid rgba(255,255,255,0.8); position: relative; z-index: 10; box-sizing: border-box; }
        .icon-box { width: 70px; height: 70px; background: var(--primary-dark); color: white; border-radius: 22px; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; font-size: 28px; box-shadow: 0 10px 20px rgba(45, 58, 21, 0.2); }
        h2 { color: var(--primary-dark); font-weight: 800; margin: 0 0 8px 0; font-size: 26px; }
        p.subtitle { font-size: 14px; color: #64748B; margin-bottom: 30px; }
        .input-group { position: relative; margin-bottom: 15px; text-align: left; }
        .input-group i { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #A0AEC0; transition: 0.3s; }
        input { width: 100%; padding: 18px 20px 18px 50px; border: 2px solid #F0F4EF; border-radius: 20px; box-sizing: border-box; outline: none; background: #F9FBF8; font-family: inherit; font-size: 14px; transition: 0.3s; color: var(--primary-dark); }
        input:focus { border-color: var(--primary-light); background: #fff; }
        button { width: 100%; padding: 18px; background: var(--primary-dark); color: white; border: none; border-radius: 20px; font-weight: 800; font-size: 15px; cursor: pointer; transition: 0.4s; margin-top: 15px; letter-spacing: 1px; }
        button:hover { background: var(--primary-light); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(139, 168, 76, 0.3); }
        .btn-home { display: inline-block; margin-top: 20px; color: var(--primary-light); text-decoration: none; font-size: 14px; font-weight: 700; transition: 0.3s; }
        .btn-home:hover { color: var(--primary-dark); transform: translateX(-3px); }
        .err { background: #FFF5F5; color: #C53030; padding: 12px; border-radius: 15px; font-size: 13px; margin-bottom: 20px; font-weight: 700; border: 1px solid #FED7D7; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .copyright { margin-top: 25px; padding-top: 20px; border-top: 1px solid #F0F4EF; font-size: 12px; color: #A0AEC0; font-weight: 600; }
    </style>
</head>
<body>
    <div class="circle c1"></div>
    <div class="circle c2"></div>

    <div class="login-card">
        <div class="icon-box">
            <i class="fas fa-fingerprint"></i>
        </div>
        <h2>Verifikasi Akses</h2>
        <p class="subtitle">Silakan login ke Portal SPPG MBG</p>

        <?php if($error): ?>
            <div class="err">
                <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required autocomplete="off">
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login">LOG IN SYSTEM</button>
        </form>

        <a href="../frontend/index.php" class="btn-home">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>

        <div class="copyright">
            &copy; 2026 MBG Digital Team
        </div>
    </div>
</body>
</html>