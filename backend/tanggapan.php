<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['role'])) { header("Location: login.php"); exit(); }

$id = $_GET['id'];

// Logika Pintar: Cek apakah kolomnya bernama id atau id_pengaduan
$cek_kolom = mysqli_query($conn, "SHOW COLUMNS FROM pengaduan LIKE 'id_pengaduan'");
$kolom_id = (mysqli_num_rows($cek_kolom) > 0) ? "id_pengaduan" : "id";

// Ambil data detail pengaduan
$query = mysqli_query($conn, "SELECT * FROM pengaduan WHERE $kolom_id = '$id'");
$data = mysqli_fetch_assoc($query);

// Logika Simpan Tanggapan & Update Status
if (isset($_POST['submit_tanggapan'])) {
    $status_baru = $_POST['status'];
    // Update status di tabel pengaduan
    mysqli_query($conn, "UPDATE pengaduan SET status='$status_baru' WHERE $kolom_id='$id'");
    
    echo "<script>alert('Tanggapan berhasil dikirim!'); window.location='dashboard_pengaduan.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respon Pengaduan - SPPG</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --bg: #F6F0D7; --olive: #89986D; --white: #FFF; --sage: #9CAB84; }
        body { background: var(--bg); font-family: 'Nunito', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .container { background: white; width: 100%; max-width: 500px; padding: 40px; border-radius: 30px; box-shadow: 0 15px 40px rgba(0,0,0,0.1); }
        h2 { color: var(--olive); font-weight: 800; text-align: center; }
        .detail-box { background: #f9fdf4; padding: 20px; border-radius: 15px; border-left: 5px solid var(--sage); margin: 20px 0; }
        label { font-weight: 700; color: var(--olive); display: block; margin-bottom: 8px; }
        select, textarea { width: 100%; padding: 12px; border-radius: 12px; border: 2px solid #EEE; outline: none; font-family: 'Nunito'; margin-bottom: 20px; box-sizing: border-box;}
        .btn-send { width: 100%; padding: 15px; background: var(--olive); color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s; }
        .btn-send:hover { background: var(--sage); transform: translateY(-2px); }
        .btn-back { display: block; text-align: center; margin-top: 15px; color: #999; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-reply-all"></i> Respon Laporan</h2>
        
        <div class="detail-box">
            <small>Isi Pengaduan:</small>
            <p>"<?= $data['isi_pengaduan']; ?>..."</p>
        </div>

        <form method="POST">
            <label>Update Status:</label>
            <select name="status">
                <option value="pending" <?= ($data['status']=='pending')?'selected':'' ?>>PENDING (Menunggu)</option>
                <option value="proses" <?= ($data['status']=='proses')?'selected':'' ?>>PROSES (Dikerjakan)</option>
                <option value="selesai" <?= ($data['status']=='selesai')?'selected':'' ?>>SELESAI (Tuntas)</option>
            </select>

            <label>Tulis Tanggapan Resmi:</label>
            <textarea name="tanggapan" rows="4" placeholder="Ketik jawaban untuk pelapor di sini..." required></textarea>

            <button type="submit" name="submit_tanggapan" class="btn-send">
                KIRIM & UPDATE STATUS
            </button>
        </form>
        <a href="dashboard_pengaduan.php" class="btn-back">Kembali ke Dashboard</a>
    </div>
</body>
</html>