<?php
include 'koneksi.php';

$id     = $_POST['id_pengaduan'];
$status = $_POST['status']; // Ambil dari <select> di form

$query = mysqli_query($conn, "UPDATE pengaduan SET status='$status' WHERE id_pengaduan='$id'");

if ($query) {
    header("Location: index.php?pesan=update-berhasil");
} else {
    echo "Gagal update status";
}
?>