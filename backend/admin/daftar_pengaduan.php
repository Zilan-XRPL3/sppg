<?php
session_start();
include '../koneksi.php';

// Proteksi akses
if (!isset($_SESSION['role']) || $_SESSION['role'] == 'masyarakat') {
    header("Location: ../login.php");
    exit();
}

$query = mysqli_query($conn, "SELECT * FROM pengaduan ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Laporan - SPPG MBG</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --cream: #F6F0D7;
            --olive: #89986D;
            --sage: #9CAB84;
            --white: #FFFFFF;
            --text: #444;
        }

        body {
            background-color: var(--cream);
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 20px;
            color: var(--text);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h2 {
            color: var(--olive);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Grid System agar tidak gede banget */
        .report-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .report-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .report-card:hover {
            transform: translateY(-8px);
        }

        /* Ukuran Gambar Dikunci */
        .image-box {
            width: 100%;
            height: 180px;
            background: #eee;
            position: relative;
        }

        .image-box img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Gambar tetap proporsional */
        }

        .status-tag {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            background: var(--olive);
            color: white;
        }

        .card-content {
            padding: 20px;
            flex-grow: 1;
        }

        .card-content h3 {
            margin: 0 0 10px;
            font-size: 18px;
            color: var(--olive);
        }

        .card-content p {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .card-footer {
            padding: 15px 20px;
            background: #fafafa;
            border-top: 1px solid #f1f1f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #999;
        }

        .btn-detail {
            display: block;
            text-align: center;
            background: var(--olive);
            color: white;
            text-decoration: none;
            padding: 10px;
            font-weight: 700;
            transition: background 0.3s;
        }

        .btn-detail:hover {
            background: var(--sage);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2><i class="fas fa-list-check"></i> Monitoring Pengaduan</h2>
    </div>

    <div class="report-grid">
        <?php while($row = mysqli_fetch_assoc($query)) : ?>
        <div class="report-card">
            <div class="image-box">
                <span class="status-tag"><?= $row['status'] ?></span>
                <img src="../uploads/<?= $row['foto'] ?>" onerror="this.src='https://placehold.co/400x200?text=Foto+Tidak+Ditemukan'">
            </div>
            
            <div class="card-content">
                <h3><?= $row['nama'] ?></h3>
                <p><?= htmlspecialchars($row['isi_pengaduan']) ?></p>
            </div>

            <div class="card-footer">
                <span><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($row['tanggal'])) ?></span>
                <span><i class="fas fa-school"></i> <?= $row['sekolah'] ?></span>
            </div>
            
            <a href="detail_pengaduan.php?id=<?= $row['id'] ?>" class="btn-detail">Kelola Laporan</a>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>