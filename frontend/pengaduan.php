<?php include '../backend/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Aspirasi - SPPG MBG</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="page-pengaduan">

<div id="modalV">
    <img id="modalImg" src="">
    <button onclick="closeM()" style="margin-top:20px; padding:15px 40px; border-radius:50px; border:none; cursor:pointer; font-weight:800;">TUTUP</button>
</div>

<div class="container">
    <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>

    <div class="main-card">
        <div class="header-section">
            <h1>Layanan Aspirasi</h1>
            <p>Suara Anda adalah langkah menuju perubahan lebih baik.</p>
        </div>

        <form action="../backend/proses_pengaduan.php" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="input-box"><label>Nama Lengkap</label><input type="text" name="nama" placeholder="Nama Anda..." required></div>
                <div class="input-box"><label>Instansi / Sekolah</label><input type="text" name="sekolah" placeholder="Nama instansi..." required></div>
            </div>
            <div class="input-box"><label>Detail Laporan</label><textarea name="isi_pengaduan" rows="4" placeholder="Tulis aspirasi Anda di sini..." required></textarea></div>
            
            <div class="input-box">
                <label>Foto Bukti (Opsional)</label>
                <div class="upload-area" id="drop-zone">
                    <input type="file" name="foto" id="foto-input" accept="image/*" class="hidden-input" onchange="previewFile(event)">
                    <div id="up-placeholder">
                        <i class="fas fa-camera"></i>
                        <p><b>Klik jika ingin melampirkan foto</b></p>
                        <span style="font-size: 11px; color: #94A3B8;">Boleh dikosongkan jika tidak ada</span>
                    </div>
                    <img id="preview-img-input" src="">
                    <span id="file-name-info" style="font-size:12px; font-weight:700; color:var(--primary);"></span>
                </div>
            </div>

            <button type="submit" class="btn-submit">KIRIM ASPIRASI</button>
        </form>
    </div>

    <h2 style="font-weight:800; margin-bottom:20px; color:var(--dark);"><i class="fas fa-history"></i> Riwayat Aspirasi Terbaru</h2>

    <?php
    $sql = mysqli_query($conn, "SELECT * FROM pengaduan ORDER BY id DESC LIMIT 5");
    while($row = mysqli_fetch_array($sql)):
        $st = ($row['status'] == 'selesai') ? 'selesai' : 'pending';
    ?>
    <div class="history-item">
        <span class="badge <?= $st ?>"><?= $row['status'] ?></span>
        <span style="color:var(--primary); font-weight:800; font-size:12px;"><?= $row['no_tiket'] ?></span>
        <p style="font-weight:600; font-size:16px; margin:10px 0;">"<?= htmlspecialchars($row['isi_pengaduan']) ?>"</p>
        
        <div style="font-size:12px; color:var(--text-muted); font-weight:600;">
            <i class="fas fa-university"></i> <?= htmlspecialchars($row['sekolah']) ?> | 
            <i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($row['tanggal'])) ?>
            <?php if(!empty($row['foto'])): ?>
                | <a href="javascript:void(0)" onclick="openM('../uploads/<?= $row['foto'] ?>')" style="color:var(--primary); text-decoration:none; font-weight:800;">Lihat Foto</a>
            <?php endif; ?>
        </div>

        <?php if(!empty($row['tindak_lanjut'])): ?>
        <div class="response-box">
            <strong style="font-size:11px; color:var(--primary); display:block; margin-bottom:5px;">TANGGAPAN ADMIN:</strong>
            <p style="font-size:14px; color:var(--text-main);"><?= htmlspecialchars($row['tindak_lanjut']) ?></p>
        </div>
        <?php endif; ?>
    </div>
    <?php endwhile; ?>
</div>

<script>
    function previewFile(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = () => {
                document.getElementById('preview-img-input').src = reader.result;
                document.getElementById('preview-img-input').style.display = 'block';
                document.getElementById('up-placeholder').style.display = 'none';
                document.getElementById('file-name-info').innerText = "Terpilih: " + file.name;
            }
            reader.readAsDataURL(file);
        }
    }
    function openM(s) { document.getElementById('modalImg').src = s; document.getElementById('modalV').style.display = 'flex'; }
    function closeM() { document.getElementById('modalV').style.display = 'none'; }

    // NOTIFIKASI SUKSES DENGAN NOMOR TIKET
    <?php if(isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
    Swal.fire({ 
        title: 'Laporan Terkirim!', 
        html: `
            <p style="margin-bottom:15px; color:#64748B;">Laporan Anda telah kami terima.</p>
            <div style="background:#F7FEE7; padding:20px; border-radius:15px; border:2px dashed #6B8E23;">
                <span style="font-size:11px; font-weight:800; color:#6B8E23; text-transform:uppercase;">Nomor Tiket:</span>
                <h2 style="font-size:28px; font-weight:800; color:#1A2605; margin:5px 0;"><?= htmlspecialchars($_GET['tiket']) ?></h2>
            </div>
            <p style="margin-top:15px; font-size:12px; color:#94A3B8;">Catat tiket ini untuk pengecekan status.</p>
        `,
        icon: 'success', 
        confirmButtonColor: '#6B8E23',
        confirmButtonText: 'Oke, Saya Catat!',
        borderRadius: '25px'
    }).then(() => {
        window.history.replaceState({}, document.title, "pengaduan.php");
    });
    <?php endif; ?>
</script>

</body>
</html>