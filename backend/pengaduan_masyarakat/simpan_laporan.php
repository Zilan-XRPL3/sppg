<?php
session_start();
include '../koneksi.php';

if (isset($_POST['kirim'])) {
    $nama     = $_SESSION['username'];
    $isi      = mysqli_real_escape_string($conn, $_POST['isi_laporan']);
    $tanggal  = date('Y-m-d H:i:s');
    $sekolah  = "Umum"; 
    
    // Olah Foto
    $foto_name = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    $foto_baru = date('YmdHis') . "_" . $foto_name; // Nama file unik

    if (move_uploaded_file($foto_tmp, "../uploads/" . $foto_baru)) {
        // Query sesuai tabel pengaduan kamu
        $query = "INSERT INTO pengaduan (nama, sekolah, isi_pengaduan, tanggal, status, foto) 
                  VALUES ('$nama', '$sekolah', '$isi', '$tanggal', 'pending', '$foto_baru')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Laporan Terkirim!'); window.location='form_pengaduan.php';</script>";
        } else {
            echo "Gagal Simpan: " . mysqli_error($conn);
        }
    }
}
?>