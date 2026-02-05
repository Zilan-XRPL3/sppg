<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!function_exists('active')) {
    function active($current_page){
        $url = basename($_SERVER['PHP_SELF']);
        if($current_page == $url){ echo 'active'; }
    }
}
?>
<header class="navbar-header">
    <div class="navbar-inner">
        <a href="index.php" class="navbar-brand">
            <i class="fas fa-leaf"></i> MBG App
        </a>
        
        <div class="navbar-toggle" onclick="document.querySelector('.navbar-menu').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </div>

        <ul class="navbar-menu">
            <li class="nav-item"><a href="index.php" class="<?php active('index.php'); ?>">Beranda</a></li>
            <li class="nav-item"><a href="menu.php" class="<?php active('menu.php'); ?>">Menu Harian</a></li>
            <li class="nav-item"><a href="riwayat.php" class="<?php active('riwayat.php'); ?>">Riwayat</a></li>
            <li class="nav-item"><a href="tim.php" class="<?php active('tim.php'); ?>">Tim SPPG</a></li>
            <li class="nav-item"><a href="pengaduan.php" class="<?php active('pengaduan.php'); ?>">Pengaduan</a></li>
        </ul>
    </div>
</header>