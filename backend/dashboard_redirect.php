<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$role = $_SESSION['role'];

if ($role === 'admin') {
    header("Location: dashboard_admin.php");
} elseif ($role === 'petugas_gizi') {
    header("Location: dashboard_gizi.php");
} elseif ($role === 'petugas_pengaduan') {
    header("Location: dashboard_pengaduan.php");
} else {
    header("Location: ../frontend/index.php");
}
exit();