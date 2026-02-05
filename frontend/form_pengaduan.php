<?php include '../backend/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kirim Laporan - SPPG MBG</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --primary: #6B8E23; --bg: #F8FAFC; }
        body { background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; display: flex; justify-content: center; padding: 40px 20px; }
        .card { background: white; padding: 40px; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); width: 100%; max-width: 650px; }
        .input-group { margin-bottom: 20px; }
        label { display: block; font-weight: 700; font-size: 13px; margin-bottom: 8px; color: #334155; }
        input, textarea { width: 100%; padding: 14px; border: 2px solid #E2E8F0; border-radius: 12px; font-size: 15px; }
        .file-upload { border: 2px dashed #CBD5E1; padding: 20px; text-align: center; border-radius: 15px; cursor: pointer; background: #F1F5F9; }
        #preview { max-width: 100%; height: 150px; object-fit: cover; border-radius: 10px; display: none; margin: 10px auto; }
        .btn-submit { background: var(--primary); color: white; border: none; padding: 16px; width: 100%; border-radius: 50px; font-weight: 800; cursor: pointer; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
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
            <button type="submit" class="btn-submit">KIRIM ADUAN</button>
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