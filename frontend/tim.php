<?php include '../backend/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tim Ahli SPPG - MBG</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="page-tim">

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="header-section">
        <h2>Mengenal Tim Ahli SPPG</h2>
        <p>Para profesional di balik layanan gizi terbaik untuk Anda.</p>
    </div>

    <div class="grid-tim">
        <?php 
        $query = mysqli_query($conn, "SELECT * FROM tim_sppg ORDER BY id_tim ASC");
        while($t = mysqli_fetch_assoc($query)): 
            $nama = $t['nama_petugas']; // Pakai nama_petugas
            $jabatan = $t['jabatan'];   // Pakai jabatan
            $inisial = strtoupper(substr($nama, 0, 2)); // Ambil 2 huruf depan
        ?>
        <div class="card-tim">
            <div class="avatar-circle"><?= $inisial ?></div>
            <span class="badge-jabatan"><?= $jabatan ?></span>
            <h3 class="nama-petugas"><?= $nama ?></h3>
            <p class="sub-text">Tim Pelaksana Pelayanan Gizi</p>
            <a href="https://wa.me/<?= $t['no_hp'] ?>" class="contact-btn">
                <i class="fab fa-whatsapp"></i> Hubungi Petugas
            </a>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>