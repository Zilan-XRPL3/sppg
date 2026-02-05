<?php
session_start();

$error = "";

if (isset($_POST['login'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];

    // LOGIKA LOGIN DAMI (Username & Password Statis)
    if ($u === "Admin" && $p === "admin123") {
        $_SESSION['username'] = "Admin";
        $_SESSION['role']     = "admin";
        header("Location: ../backend/dashboard_redirect.php");
        exit();
    } elseif ($u === "petugas_gizi" && $p === "gizi123") {
        $_SESSION['username'] = "Gizi";
        $_SESSION['role']     = "petugas_gizi";
        header("Location: ../backend/dashboard_redirect.php");
        exit();
    } elseif ($u === "petugas_pengaduan" && $p === "aduan123") {
        $_SESSION['username'] = "Aduan";
        $_SESSION['role']     = "petugas_pengaduan";
        header("Location: ../backend/dashboard_redirect.php");
        exit();
    } else {
        $error = "Akses Ditolak! Username atau Password salah.";
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="page-login">
    <div class="circle c1"></div>
    <div class="circle c2"></div>

    <div class="login-card">
        <div class="icon-box">
            <i class="fas fa-fingerprint"></i>
        </div>
        <h2>Verifikasi Akses</h2>
        <p class="subtitle">Silakan login menggunakan akun petugas</p>

        <?php if($error): ?>
            <div class="err"><i class="fas fa-exclamation-triangle"></i> <?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
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
    </div>
</body>
</html>