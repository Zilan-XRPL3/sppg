<?php
include '../backend/koneksi.php';

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// 1. Query JOIN (Sama seperti sebelumnya agar nilai gizi muncul)
$query = mysqli_query($conn, "SELECT m.*, g.kalori, g.protein, g.karbohidrat, g.lemak, g.serat 
                              FROM menu_harian m 
                              LEFT JOIN nilai_gizi g ON m.id_menu = g.id_menu 
                              WHERE m.id_menu = '$id'");
$data = mysqli_fetch_assoc($query);

// 2. LOGIKA PELACAKAN JALUR (PATH) FOTO
$nama_file = $data['foto'];

// Daftar folder yang mungkin (Sesuaikan dengan nama folder di laptopmu)
$kemungkinan_folder = [
    '../backend/uploads/', 
    '../uploads/', 
    'uploads/', 
    '../asset/img/'
];

$path_final = "https://placehold.co/600x800?text=Foto+Tidak+Ketemu"; // Default jika gagal

foreach ($kemungkinan_folder as $folder) {
    if (!empty($nama_file) && file_exists($folder . $nama_file)) {
        $path_final = $folder . $nama_file;
        break; // Jika ketemu, berhenti mencari
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Menu - MBG</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="page-detail-gizi">

<div class="card-detail">
    <div class="img-section">
        <a href="riwayat.php" class="back-btn-circle"><i class="fas fa-chevron-left"></i></a>
        <img src="<?= $path_final ?>">
    </div>

    <div class="content-section">
        <h1 style="font-weight: 800; margin-bottom: 25px; font-size: 32px;"><?= $data['nama_menu'] ?></h1>

        <div class="gizi-row"><span>Protein</span> <span><?= number_format($data['protein'] ?? 0, 1) ?>g</span></div>
        <div class="gizi-row"><span>Karbohidrat</span> <span><?= number_format($data['karbohidrat'] ?? 0, 1) ?>g</span></div>
        <div class="gizi-row"><span>Lemak</span> <span><?= number_format($data['lemak'] ?? 0, 1) ?>g</span></div>
        <div class="gizi-row"><span>Serat</span> <span><?= number_format($data['serat'] ?? 0, 1) ?>g</span></div>

        <div style="display: flex; align-items: center; gap: 20px; margin-top: 30px;">
            <div class="energy-box">
                <h2 style="font-size: 28px; font-weight: 800;"><?= round($data['kalori'] ?? 0) ?></h2>
                <p style="font-size: 10px; font-weight: 700; opacity: 0.9;">kkal</p>
            </div>
            <p style="font-size: 12px; color: #A0AEC0; font-weight: 600;">Estimasi nilai gizi per porsi saji.</p>
        </div>
    </div>
</div>

</body>
</html>