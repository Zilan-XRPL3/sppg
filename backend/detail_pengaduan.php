<?php
include 'koneksi.php';
$id = $_GET['id'];

if (isset($_POST['update_laporan'])) {
    $status = $_POST['status'];
    $respon = mysqli_real_escape_string($conn, $_POST['tindak_lanjut']);
    mysqli_query($conn, "UPDATE pengaduan SET status='$status', tindak_lanjut='$respon' WHERE id='$id'");
    header("Location: detail_pengaduan.php?id=$id&pesan=berhasil");
}

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengaduan WHERE id = '$id'"));
$st_color = ($data['status'] == 'selesai') ? '#10B981' : (($data['status'] == 'proses') ? '#3B82F6' : '#F59E0B');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rincian #<?= $data['no_tiket'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --primary: #6B8E23; --bg: #F8FAFC; }
        body { background: var(--bg); font-family: 'Plus Jakarta Sans'; padding: 40px; }
        .wrapper { display: grid; grid-template-columns: 1.2fr 1fr; gap: 30px; max-width: 1100px; margin: auto; }
        .card { background: white; padding: 35px; border-radius: 24px; border: 1px solid #E2E8F0; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .status-pill { padding: 6px 16px; border-radius: 50px; color: white; font-size: 11px; font-weight: 800; }
        .img-box { margin-top: 20px; border-radius: 20px; overflow: hidden; border: 1px solid #E2E8F0; }
        .img-box img { width: 100%; display: block; }
        .btn-save { width: 100%; padding: 16px; background: var(--primary); color: white; border: none; border-radius: 16px; font-weight: 800; cursor: pointer; }
        .btn-delete { display: block; text-align: center; margin-top: 25px; color: #EF4444; text-decoration: none; font-weight: 800; font-size: 13px; border-top: 1px dashed #E2E8F0; padding-top: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <a href="pengaduan.php" style="text-decoration:none; color:#64748B; font-weight:700;">← Kembali</a>
            <span class="status-pill" style="background:<?= $st_color ?>"><?= $data['status'] ?></span>
        </div>
        <span style="font-size:11px; font-weight:800; color:#94A3B8;">TIKET #<?= $data['no_tiket'] ?></span>
        <h2 style="font-weight:800; margin-bottom:20px;"><?= $data['nama'] ?></h2>
        <div style="background:#F8FAFC; padding:20px; border-radius:16px; margin-bottom:20px;"><?= $data['isi_pengaduan'] ?></div>
        <div class="img-box">
            <?php if(!empty($data['foto']) && file_exists("uploads/".$data['foto'])): ?>
                <img src="uploads/<?= $data['foto'] ?>">
            <?php else: ?>
                <p style="padding:60px; color:#94A3B8; text-align:center;">Tidak ada foto lampiran</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="card">
        <h3 style="margin-bottom:25px;"><i class="fas fa-reply-all" style="color:var(--primary);"></i> Respon Petugas</h3>
        <form method="POST">
            <label style="font-size:11px; font-weight:800; color:#94A3B8; display:block; margin-bottom:8px;">STATUS</label>
            <select name="status" style="width:100%; padding:14px; border-radius:12px; margin-bottom:20px; background:#F8FAFC; border:1px solid #E2E8F0;">
                <option value="pending" <?= $data['status']=='pending'?'selected':'' ?>>🟡 Pending</option>
                <option value="proses" <?= $data['status']=='proses'?'selected':'' ?>>🔵 Proses</option>
                <option value="selesai" <?= $data['status']=='selesai'?'selected':'' ?>>🟢 Selesai</option>
            </select>
            <label style="font-size:11px; font-weight:800; color:#94A3B8; display:block; margin-bottom:8px;">TANGGAPAN</label>
            <textarea name="tindak_lanjut" rows="6" style="width:100%; padding:14px; border-radius:12px; margin-bottom:20px; background:#F8FAFC; border:1px solid #E2E8F0;"><?= $data['tindak_lanjut'] ?></textarea>
            <button type="submit" name="update_laporan" class="btn-save">Simpan Perubahan</button>
        </form>
        <a href="javascript:void(0)" onclick="confirmDelete(<?= $data['id'] ?>)" class="btn-delete"><i class="fas fa-trash-alt"></i> HAPUS LAPORAN PERMANEN</a>
    </div>
</div>
<script>
function confirmDelete(id) {
    Swal.fire({ title: 'Hapus Laporan?', text: "Data akan hilang permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#EF4444' }).then((r) => {
        if(r.isConfirmed) window.location.href='hapus_pengaduan.php?id='+id;
    });
}
</script>
</body>
</html>