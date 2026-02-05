<?php
session_start();
include 'koneksi.php';

$nama     = $_POST['nama'];
$sekolah  = $_POST['sekolah'];
$isi      = $_POST['isi_pengaduan'];
$tanggal  = date("Y-m-d H:i:s");

$query = mysqli_query($koneksi,
    "INSERT INTO pengaduan (nama,sekolah,isi_pengaduan,tanggal)
     VALUES ('$nama','$sekolah','$isi','$tanggal')"
);

if ($query) {
    echo "Pengaduan berhasil dikirim <br>";
    echo "<a href='../backend/dashboard.php'>Kembali ke Dashboard</a>";
} else {
    echo "Gagal menyimpan pengaduan";
}
?>
