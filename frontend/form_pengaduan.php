<?php include '../backend/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kirim Laporan - SPPG MBG</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="page-form-pengaduan">
    <div class="card-form">
        <h2 style="text-align:center; margin-bottom:30px;">Sampaikan Laporan</h2>
        <form action="../backend/proses_pengaduan.php" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label>Nama Anda</label>
                <input type="text" name="nama" required>
            </div>
            <div class="input-group">
                <label>Sekolah / Instansi</label>
                <input type="text" name="sekolah" required>
            </div>
            <div class="input-group">
                <label>Isi Laporan</label>
                <textarea name="isi_pengaduan" rows="4" required></textarea>
            </div>
            <div class="input-group">
                <label>Foto Bukti</label>
                <input type="file" name="foto" id="foto" hidden onchange="showPreview(event)">
                <div class="file-upload" onclick="document.getElementById('foto').click()">
                    <img id="preview">
                    <p id="text-foto"><i class="fas fa-camera"></i> Klik untuk Upload Foto</p>
                </div>
            </div>
            <button type="submit" class="btn-submit-form">KIRIM ADUAN</button>
            <a href="daftar_aduan.php" style="display:block; text-align:center; margin-top:15px; color:#64748B; text-decoration:none; font-weight:600;">Lihat Aduan Publik</a>
        </form>
    </div>

    <script>
        function showPreview(event) {
            if(event.target.files.length > 0) {
                let src = URL.createObjectURL(event.target.files[0]);
                let preview = document.getElementById("preview");
                preview.src = src;
                preview.style.display = "block";
                document.getElementById("text-foto").innerText = event.target.files[0].name;
            }
        }
    </script>

    <?php if(isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
    <script>
        Swal.fire('Terkirim!', 'No Tiket: <?= $_GET['tiket'] ?>', 'success');
    </script>
    <?php endif; ?>
</body>
</html>