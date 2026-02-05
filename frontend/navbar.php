<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!function_exists('active')) {
    function active($current_page){
        $url = basename($_SERVER['PHP_SELF']);
        if($current_page == $url){ echo 'active'; }
    }
}
?>
<header style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); padding: 20px 0; position: sticky; top: 0; z-index: 1000; border-bottom: 1px solid #f0f0f0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 25px; display: flex; justify-content: space-between; align-items: center;">
        <a href="index.php" style="font-weight: 800; font-size: 24px; color: #4A5D23; text-decoration: none; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-leaf"></i> MBG App
        </a>
        <ul style="display: flex; gap: 8px; list-style: none; background: #F1F5F1; padding: 6px; border-radius: 20px;">
            <li class="nav-item"><a href="index.php" class="<?php active('index.php'); ?>">Beranda</a></li>
            <li class="nav-item"><a href="menu.php" class="<?php active('menu.php'); ?>">Menu Harian</a></li>
            <li class="nav-item"><a href="riwayat.php" class="<?php active('riwayat.php'); ?>">Riwayat</a></li>
            <li class="nav-item"><a href="tim.php" class="<?php active('tim.php'); ?>">Tim SPPG</a></li>
            <li class="nav-item"><a href="pengaduan.php" class="<?php active('pengaduan.php'); ?>">Pengaduan</a></li>
        </ul>
    </div>
</header>

<style>
    .nav-item a { text-decoration: none; padding: 10px 20px; color: #64748B; font-weight: 700; font-size: 14px; border-radius: 15px; transition: 0.3s; display: block; }
    .nav-item a.active, .nav-item a:hover { background: white; color: #4A5D23; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
</style>