<?php
include 'koneksi.php';

$id = $_GET['id'];

// 1. Cari nama file foto dulu supaya bisa dihapus dari folder
$cari_foto = mysqli_query($conn, "SELECT foto FROM pengaduan WHERE id_pengaduan='$id'");
$data = mysqli_fetch_array($cari_foto);

// 2. Hapus file di folder assets/img/
if (file_exists("assets/img/" . $data['foto'])) {
    unlink("assets/img/" . $data['foto']);
}

// 3. Hapus data di database
mysqli_query($conn, "DELETE FROM pengaduan WHERE id_pengaduan='$id'");

header("Location: index.php?pesan=hapus-berhasil");
?>