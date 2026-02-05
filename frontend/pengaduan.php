<?php include '../backend/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Aspirasi - SPPG MBG</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root { 
            --primary: #6B8E23; 
            --primary-light: #F7FEE7;
            --dark: #1A2605; 
            --text-main: #334155;
            --text-muted: #64748B;
            --bg-gradient: radial-gradient(circle at top right, #F1F5F9, #FFFFFF);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-gradient); color: var(--text-main); min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 850px; margin: 0 auto; }

        /* Tombol Kembali */
        .btn-back { 
            display: inline-flex; align-items: center; gap: 10px;
            text-decoration: none; color: var(--dark); font-weight: 700; font-size: 14px;
            padding: 10px 20px; border-radius: 14px; background: white;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: 0.3s; margin-bottom: 30px;
        }
        .btn-back:hover { transform: translateX(-5px); background: var(--primary); color: white; }

        /* Card Utama */
        .main-card {
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5); border-radius: 35px;
            padding: 45px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.06); margin-bottom: 50px;
        }

        .header-section { text-align: center; margin-bottom: 40px; }
        .header-section h1 { font-size: 34px; font-weight: 800; color: var(--dark); letter-spacing: -1px; }

        /* Form Styling */
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .input-box label { display: block; font-size: 12px; font-weight: 800; color: var(--dark); margin-bottom: 8px; text-transform: uppercase; }
        input, textarea { width: 100%; padding: 16px 20px; border-radius: 18px; border: 2px solid #F1F5F9; background: #F8FAFC; font-size: 15px; transition: 0.3s; }
        input:focus, textarea:focus { border-color: var(--primary); outline: none; background: white; }

        /* Area Upload Opsional */
        .upload-area {
            position: relative; width: 100%; padding: 30px;
            background: #F8FAFC; border: 2px dashed #CBD5E1;
            border-radius: 25px; text-align: center; transition: 0.3s; cursor: pointer;
            display: flex; flex-direction: column; align-items: center; gap: 5px;
        }
        .upload-area:hover { border-color: var(--primary); background: var(--primary-light); }
        .upload-area i { font-size: 35px; color: var(--primary); }
        .hidden-input { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
        
        #preview-img-input { max-width: 120px; border-radius: 15px; display: none; margin-bottom: 10px; border: 3px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

        .btn-submit {
            width: 100%; background: var(--primary); color: white; padding: 20px;
            border: none; border-radius: 50px; font-size: 16px; font-weight: 800;
            cursor: pointer; transition: 0.4s; margin-top: 20px;
        }
        .btn-submit:hover { background: var(--dark); transform: translateY(-3px); }

        /* Riwayat Aduan */
        .history-item { background: white; border-radius: 25px; padding: 25px; margin-bottom: 20px; border-left: 6px solid var(--primary); box-shadow: 0 10px 20px rgba(0,0,0,0.03); }
        .badge { float: right; padding: 6px 14px; border-radius: 50px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .pending { background: #FFF7ED; color: #C2410C; }
        .selesai { background: #F0FDF4; color: #15803D; }

        .response-box { margin-top: 15px; padding: 15px; background: var(--primary-light); border-radius: 15px; border-left: 4px solid var(--primary); }

        /* Modal Preview */
        #modalV { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.95); z-index: 10000; align-items: center; justify-content: center; flex-direction: column; padding: 20px; }
        #modalV img { max-width: 90%; max-height: 80vh; border-radius: 20px; }
    </style>
</head>
<body>

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