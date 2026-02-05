<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data hanya yang dikirim dari form
    $nama          = mysqli_real_escape_string($conn, $_POST['nama']);
    $sekolah       = mysqli_real_escape_string($conn, $_POST['sekolah']);
    $isi_pengaduan = mysqli_real_escape_string($conn, $_POST['isi_pengaduan']);
    
    // Data tambahan otomatis sesuai tabel kamu
    $tanggal       = date('Y-m-d H:i:s');
    $status        = 'pending'; 

    // Query ini HANYA pakai kolom: nama, sekolah, isi_pengaduan, tanggal, status
    $query = "INSERT INTO pengaduan (nama, sekolah, isi_pengaduan, tanggal, status) 
              VALUES ('$nama', '$sekolah', '$isi_pengaduan', '$tanggal', '$status')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Alhamdulillah Berhasil! Data sudah masuk.');
                window.location.href='../frontend/pengaduan.php';
              </script>";
    } else {
        die("Gagal simpan: " . mysqli_error($conn));
    }
}
?>