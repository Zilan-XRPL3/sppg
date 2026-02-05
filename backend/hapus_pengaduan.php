<?php
include 'koneksi.php';
$id = $_GET['id'];
$d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT foto FROM pengaduan WHERE id='$id'"));
if(!empty($d['foto']) && file_exists("uploads/".$d['foto'])) unlink("uploads/".$d['foto']);
mysqli_query($conn, "DELETE FROM pengaduan WHERE id='$id'");
header("Location: pengaduan.php?status=deleted");
?>