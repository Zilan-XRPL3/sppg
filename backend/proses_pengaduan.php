<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $sekolah = mysqli_real_escape_string($conn, $_POST['sekolah']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi_pengaduan']);
    $tanggal = date('Y-m-d H:i:s');
    
    // Generate Tiket MBG-2026-XXXX
    $query_tkt = mysqli_query($conn, "SELECT MAX(id) as max_id FROM pengaduan");
    $data_tkt = mysqli_fetch_array($query_tkt);
    $next_id = ($data_tkt['max_id'] ?? 0) + 1;
    $no_tiket = "MBG-" . date('Y') . "-" . str_pad($next_id, 4, "0", STR_PAD_LEFT);

    // Proses Upload Foto
    $nama_foto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }

        $ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
        $nama_foto = "IMG_" . time() . "." . $ext;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $nama_foto);
    }

    $query = "INSERT INTO pengaduan (no_tiket, nama, sekolah, isi_pengaduan, tanggal, status, foto) 
              VALUES ('$no_tiket', '$nama', '$sekolah', '$isi', '$tanggal', 'pending', '$nama_foto')";

    if (mysqli_query($conn, $query)) {
        // REDIRECT KE pengaduan.php (Bukan form_pengaduan.php)
        header("Location: ../frontend/pengaduan.php?status=sukses&tiket=" . $no_tiket);
        exit();
    } else {
        die("Error: " . mysqli_error($conn));
    }
}
?>