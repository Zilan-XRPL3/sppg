<?php
ob_start();
session_start();
// Proteksi: Hanya petugas gizi
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petugas_gizi') {
    header("Location: login.php"); exit;
}
include 'koneksi.php';

// --- LOGIKA HAPUS ---
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM nilai_gizi WHERE id_menu='$id'");
    $res = mysqli_query($conn, "SELECT foto FROM menu_harian WHERE id_menu='$id'");
    $row = mysqli_fetch_assoc($res);
    if($row['foto'] && file_exists("uploads/".$row['foto'])) {
        unlink("uploads/".$row['foto']);
    }
    mysqli_query($conn, "DELETE FROM menu_harian WHERE id_menu='$id'");
    header("Location: menu_harian.php?status=deleted"); exit;
}

// --- LOGIKA SIMPAN (Insert) ---
if (isset($_POST['simpan'])) {
    $nama_menu = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $tanggal   = $_POST['tanggal'];
    $kalori    = $_POST['kalori'];
    $protein   = $_POST['protein'];
    $karbo     = $_POST['karbohidrat'];
    $lemak     = $_POST['lemak'];
    $serat     = $_POST['serat'];
    $id_user   = $_SESSION['id_user'] ?? 12;

    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nama_baru = "menu_" . round(microtime(true)) . "." . $ext;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $nama_baru)) {
            $q1 = "INSERT INTO menu_harian (tanggal, nama_menu, id_user, foto) VALUES ('$tanggal', '$nama_menu', '$id_user', '$nama_baru')";
            if(mysqli_query($conn, $q1)) {
                $last_id = mysqli_insert_id($conn);
                mysqli_query($conn, "INSERT INTO nilai_gizi (id_menu, kalori, protein, lemak, karbohidrat, serat) 
                                     VALUES ('$last_id', '$kalori', '$protein', '$lemak', '$karbo', '$serat')");
            }
        }
    }
    header("Location: menu_harian.php?status=success"); exit;
}

// --- LOGIKA UPDATE ---
if (isset($_POST['update'])) {
    $id_menu   = $_POST['id_menu'];
    $nama_menu = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $tanggal   = $_POST['tanggal'];
    $kalori    = $_POST['kalori'];
    $protein   = $_POST['protein'];
    $karbo     = $_POST['karbohidrat'];
    $lemak     = $_POST['lemak'];
    $serat     = $_POST['serat'];

    $query_update = "UPDATE menu_harian SET nama_menu='$nama_menu', tanggal='$tanggal' ";
    
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nama_baru = "menu_" . round(microtime(true)) . "." . $ext;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $nama_baru)) {
            $old = mysqli_query($conn, "SELECT foto FROM menu_harian WHERE id_menu='$id_menu'");
            $old_data = mysqli_fetch_assoc($old);
            if($old_data['foto'] && file_exists("uploads/".$old_data['foto'])) unlink("uploads/".$old_data['foto']);
            $query_update .= ", foto='$nama_baru' ";
        }
    }
    $query_update .= " WHERE id_menu='$id_menu'";
    
    if(mysqli_query($conn, $query_update)) {
        mysqli_query($conn, "UPDATE nilai_gizi SET kalori='$kalori', protein='$protein', lemak='$lemak', karbohidrat='$karbo', serat='$serat' WHERE id_menu='$id_menu'");
    }
    header("Location: menu_harian.php?status=updated"); exit;
}

$query = "SELECT m.*, g.kalori, g.protein, g.lemak, g.karbohidrat, g.serat 
          FROM menu_harian m 
          LEFT JOIN nilai_gizi g ON m.id_menu = g.id_menu 
          ORDER BY m.tanggal DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - SPPG Gizi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6B8E23; --dark-olive: #1A2605; --white: #FFFFFF;
            --bg: #F4F7FE; --accent: #D9E8B5; --text-main: #2D3748; --text-light: #8B949E;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; text-decoration: none; list-style: none; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-main); display: flex; min-height: 100vh; }

        /* SIDEBAR PREMIUM (Identik dengan Pengaduan) */
        .sidebar { 
            width: 280px; background: var(--dark-olive); color: white; position: fixed; 
            height: calc(100vh - 40px); margin: 20px; border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2); display: flex; flex-direction: column; overflow: hidden;
            z-index: 100;
        }

        .sidebar-logo { padding: 40px 30px; display: flex; align-items: center; gap: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .logo-box { 
            width: 45px; height: 45px; background: var(--primary); border-radius: 12px; 
            display: flex; align-items: center; justify-content: center; font-size: 20px; 
            box-shadow: 0 8px 15px rgba(107, 142, 35, 0.4); 
        }
        .logo-text { font-size: 18px; font-weight: 800; letter-spacing: -0.5px; color: white; }
        .logo-text span { color: var(--primary); display: block; font-size: 12px; letter-spacing: 2px; }

        .sidebar-menu { padding: 30px 20px; flex-grow: 1; }
        .sidebar-menu a { 
            display: flex; align-items: center; padding: 14px 20px; color: rgba(255,255,255,0.5); 
            text-decoration: none; font-weight: 600; border-radius: 18px; margin-bottom: 10px; transition: 0.3s; 
        }
        .sidebar-menu a i { font-size: 18px; width: 25px; margin-right: 12px; text-align: center; }
        .sidebar-menu a:hover { color: white; background: rgba(255,255,255,0.05); }
        .sidebar-menu a.active { 
            background: var(--primary); color: white; 
            box-shadow: 0 10px 20px rgba(107, 142, 35, 0.3); 
        }
        
        .sidebar-footer { padding: 20px; border-top: 1px solid rgba(255,255,255,0.05); }
        .btn-logout { 
            width: 100%; padding: 14px; border-radius: 15px; background: rgba(255, 71, 87, 0.1); 
            color: #FF4757; text-decoration: none; display: flex; align-items: center; justify-content: center; 
            gap: 10px; font-weight: 700; font-size: 14px; transition: 0.3s; 
        }
        .btn-logout:hover { background: #FF4757; color: white; }

        /* Main Content */
        .main { margin-left: 320px; width: calc(100% - 320px); padding: 50px 40px; }
        .welcome-header { margin-bottom: 40px; }
        .welcome-header h1 { font-size: 32px; font-weight: 800; color: var(--dark-olive); margin-bottom: 8px; }

        .grid-container { display: grid; grid-template-columns: 400px 1fr; gap: 30px; align-items: start; }

        /* Card Form */
        .card-form { background: white; border-radius: 30px; padding: 35px; border: 1px solid #F1F5F9; position: sticky; top: 50px; }
        .card-form h3 { margin-bottom: 25px; color: var(--dark-olive); font-weight: 800; display: flex; align-items: center; gap: 10px; }
        
        label { display: block; font-size: 13px; font-weight: 700; color: var(--text-light); margin-bottom: 8px; margin-top: 15px; }
        input[type="text"], input[type="date"], input[type="number"] {
            width: 100%; padding: 14px; border-radius: 14px; border: 2px solid #F1F5F9; 
            background: #F8FAFC; outline: none; transition: 0.3s; font-size: 14px;
        }
        input:focus { border-color: var(--primary); background: white; }

        /* Custom File Input */
        .file-input-wrapper {
            position: relative; width: 100%; height: 55px; background: #F8FAFC;
            border: 2px dashed #CBD5E1; border-radius: 14px; display: flex;
            align-items: center; justify-content: center; transition: 0.3s; cursor: pointer;
        }
        .file-input-wrapper:hover { border-color: var(--primary); background: #F1F5F9; }
        .file-input-wrapper input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
        .placeholder-text { font-size: 13px; font-weight: 600; color: var(--text-light); display: flex; align-items: center; gap: 10px; }
        .placeholder-text i { color: var(--primary); font-size: 18px; }
        
        .gizi-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 10px; }
        .btn-save { 
            width: 100%; background: var(--dark-olive); color: white; border: none; padding: 18px; 
            border-radius: 18px; font-weight: 700; margin-top: 25px; cursor: pointer; transition: 0.3s;
        }
        .btn-save:hover { background: var(--primary); transform: translateY(-3px); }

        /* Menu List */
        .list-wrapper { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .menu-card {
            background: var(--white); border-radius: 28px; overflow: hidden;
            border: 1px solid #F1F5F9; transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .menu-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        .card-img-container { position: relative; height: 180px; overflow: hidden; }
        .menu-card img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .card-overlay-date {
            position: absolute; top: 15px; left: 15px; background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px); padding: 6px 14px; border-radius: 12px;
            font-size: 11px; font-weight: 800; color: var(--dark-olive);
        }

        .card-content { padding: 20px; }
        .card-content h4 { font-size: 18px; font-weight: 800; color: var(--dark-olive); margin-bottom: 12px; }
        .mini-gizi-info { display: flex; gap: 10px; margin-bottom: 20px; }
        .gizi-tag { background: #F8FAFC; padding: 5px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; color: var(--primary); }

        /* Action Buttons */
        .card-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #F1F5F9; padding-top: 15px; }
        .btn-detail-view { color: var(--primary); font-weight: 700; font-size: 13px; cursor: pointer; }
        .action-btns { display: flex; gap: 10px; }
        .btn-action-circle {
            width: 38px; height: 38px; border-radius: 12px; display: flex; 
            align-items: center; justify-content: center; transition: 0.3s; border: none; cursor: pointer;
        }
        .btn-edit { background: #F0F9FF; color: #0EA5E9; }
        .btn-edit:hover { background: #0EA5E9; color: white; transform: rotate(-8deg) scale(1.1); }
        .btn-delete-circle { background: #FFF1F1; color: #FF5E5E; }
        .btn-delete-circle:hover { background: #FF5E5E; color: white; transform: rotate(8deg) scale(1.1); }

        /* Modal */
        .modal { display: none; position: fixed; inset: 0; background: rgba(30, 41, 59, 0.8); z-index: 200; align-items: center; justify-content: center; backdrop-filter: blur(8px); }
        .modal-content { background: white; width: 450px; border-radius: 35px; overflow: hidden; animation: zoom 0.3s; max-height: 90vh; overflow-y: auto; }
        @keyframes zoom { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .modal-body { padding: 30px; }
        .gizi-badge-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 20px; }
        .badge-item { background: #F8FAFC; padding: 12px; border-radius: 16px; border: 1px solid #F1F5F9; text-align: center; }
        .badge-item small { font-size: 10px; color: var(--text-light); display: block; margin-bottom: 4px; font-weight: 700; text-transform: uppercase; }
        .badge-item b { font-size: 14px; color: var(--primary); }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-box"><i class="fas fa-apple-whole"></i></div>
            <div class="logo-text">SPPG <span>GIZI</span></div>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard_gizi.php"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></a>
            <a href="menu_harian.php" class="active"><i class="fas fa-utensils"></i> <span>Menu Harian</span></a>
            <a href="nilai_gizi.php"><i class="fas fa-chart-simple"></i> <span>Nilai Gizi</span></a>
        </div>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fas fa-power-off"></i> Keluar Sistem</a>
        </div>
    </div>

    <div class="main">
        <div class="welcome-header" style="display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <h1>Kelola Menu Harian</h1>
                <p style="color: var(--text-light);">Atur jadwal makan dan pantau kandungan gizi setiap sajian.</p>
            </div>
            <div style="background: white; padding: 12px 20px; border-radius: 18px; font-weight: 800; border: 1px solid #E2E8F0; font-size: 14px; color: var(--dark-olive);">
                <i class="far fa-calendar-alt" style="color: var(--primary); margin-right: 10px;"></i> 
                <span id="live-date">Memuat...</span>
            </div>
        </div>

        <div class="grid-container">
            <div class="card-form">
                <h3><i class="fas fa-plus-circle" style="color: var(--primary);"></i> Tambah Menu</h3>
                <form method="POST" enctype="multipart/form-data">
                    <label>Judul Sajian</label>
                    <input type="text" name="nama_menu" placeholder="Contoh: Nasi Merah Ayam Bakar" required>
                    
                    <label>Tanggal Rencana</label>
                    <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required>
                    
                    <label>Foto Masakan</label>
                    <div class="file-input-wrapper">
                        <div class="placeholder-text">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span id="txt-add">Pilih Foto Sajian</span>
                        </div>
                        <input type="file" name="foto" required onchange="updateFileName(this, 'txt-add')">
                    </div>

                    <label>Kandungan Gizi (Angka)</label>
                    <div class="gizi-grid">
                        <input type="number" step="0.01" name="kalori" placeholder="Kalori" required>
                        <input type="number" step="0.01" name="protein" placeholder="Protein" required>
                        <input type="number" step="0.01" name="karbohidrat" placeholder="Karbo" required>
                        <input type="number" step="0.01" name="lemak" placeholder="Lemak" required>
                        <input type="number" step="0.01" name="serat" placeholder="Serat" required>
                    </div>
                    <button type="submit" name="simpan" class="btn-save">Simpan Sajian</button>
                </form>
            </div>

            <div class="list-wrapper">
                <?php while($row = mysqli_fetch_assoc($result)): 
                    $img = (!empty($row['foto']) && file_exists("uploads/".$row['foto'])) ? "uploads/".$row['foto'] : "https://via.placeholder.com/400x300";
                ?>
                <div class="menu-card">
                    <div class="card-img-container">
                        <div class="card-overlay-date">
                            <i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($row['tanggal'])) ?>
                        </div>
                        <img src="<?= $img ?>?v=<?= time() ?>" alt="<?= $row['nama_menu'] ?>">
                    </div>
                    <div class="card-content">
                        <h4><?= $row['nama_menu'] ?></h4>
                        <div class="mini-gizi-info">
                            <span class="gizi-tag"><i class="fas fa-fire"></i> <?= $row['kalori'] ?> kcal</span>
                            <span class="gizi-tag"><i class="fas fa-egg"></i> <?= $row['protein'] ?>g</span>
                        </div>
                        <div class="card-footer">
                            <span class="btn-detail-view" onclick="openModal('<?= $row['nama_menu'] ?>', '<?= $img ?>', '<?= $row['kalori'] ?>', '<?= $row['protein'] ?>', '<?= $row['karbohidrat'] ?>', '<?= $row['lemak'] ?>', '<?= $row['serat'] ?>')">
                                Detail <i class="fas fa-arrow-right"></i>
                            </span>
                            <div class="action-btns">
                                <button class="btn-action-circle btn-edit" onclick="openEditModal(<?= htmlspecialchars(json_encode($row)) ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?hapus=<?= $row['id_menu'] ?>" class="btn-action-circle btn-delete-circle" onclick="return confirm('Hapus menu ini?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <div id="m-modal" class="modal">
        <div class="modal-content">
            <img id="m-img" src="" style="width: 100%; height: 250px; object-fit: cover;">
            <div class="modal-body" style="text-align: center;">
                <h2 id="m-nama" style="font-weight: 800; color: var(--dark-olive); margin-bottom:10px;"></h2>
                <div class="gizi-badge-grid">
                    <div class="badge-item"><small>Kalori</small><b id="m-kal"></b></div>
                    <div class="badge-item"><small>Protein</small><b id="m-pro"></b></div>
                    <div class="badge-item"><small>Karbo</small><b id="m-kar"></b></div>
                    <div class="badge-item"><small>Lemak</small><b id="m-lem"></b></div>
                    <div class="badge-item"><small>Serat</small><b id="m-ser"></b></div>
                </div>
                <button onclick="closeModal('m-modal')" class="btn-save" style="background: #F1F5F9; color: var(--text-main); margin-top: 25px;">Tutup</button>
            </div>
        </div>
    </div>

    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <div class="modal-body">
                <h3 style="color: var(--dark-olive); margin-bottom: 20px;"><i class="fas fa-edit"></i> Edit Sajian</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_menu" id="e-id">
                    <label>Judul Sajian</label>
                    <input type="text" name="nama_menu" id="e-nama" required>
                    <label>Tanggal Rencana</label>
                    <input type="date" name="tanggal" id="e-tgl" required>
                    <label>Foto Baru (Opsional)</label>
                    <div class="file-input-wrapper">
                        <div class="placeholder-text">
                            <i class="fas fa-image"></i>
                            <span id="txt-edit">Ganti Foto Sajian</span>
                        </div>
                        <input type="file" name="foto" onchange="updateFileName(this, 'txt-edit')">
                    </div>
                    <div class="gizi-grid">
                        <div><label>Kalori</label><input type="number" step="0.01" name="kalori" id="e-kal" required></div>
                        <div><label>Protein</label><input type="number" step="0.01" name="protein" id="e-pro" required></div>
                        <div><label>Karbo</label><input type="number" step="0.01" name="karbohidrat" id="e-kar" required></div>
                        <div><label>Lemak</label><input type="number" step="0.01" name="lemak" id="e-lem" required></div>
                        <div><label>Serat</label><input type="number" step="0.01" name="serat" id="e-ser" required></div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="button" onclick="closeModal('edit-modal')" class="btn-save" style="background: #F1F5F9; color: var(--text-main); margin-top:0;">Batal</button>
                        <button type="submit" name="update" class="btn-save" style="margin-top:0;">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Real-time Date
        function updateLiveDate() {
            const months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            const now = new Date();
            document.getElementById('live-date').innerText = String(now.getDate()).padStart(2, '0') + " " + months[now.getMonth()] + " " + now.getFullYear();
        }
        updateLiveDate();

        function updateFileName(input, targetId) {
            const fileName = input.files[0] ? input.files[0].name : "Pilih Foto Sajian";
            document.getElementById(targetId).innerText = fileName;
        }

        function openModal(nama, img, kal, pro, kar, lem, ser) {
            document.getElementById('m-nama').innerText = nama;
            document.getElementById('m-img').src = img;
            document.getElementById('m-kal').innerText = kal + ' kcal';
            document.getElementById('m-pro').innerText = pro + 'g';
            document.getElementById('m-kar').innerText = kar + 'g';
            document.getElementById('m-lem').innerText = lem + 'g';
            document.getElementById('m-ser').innerText = ser + 'g';
            document.getElementById('m-modal').style.display = 'flex';
        }

        function openEditModal(data) {
            document.getElementById('e-id').value = data.id_menu;
            document.getElementById('e-nama').value = data.nama_menu;
            document.getElementById('e-tgl').value = data.tanggal;
            document.getElementById('e-kal').value = data.kalori;
            document.getElementById('e-pro').value = data.protein;
            document.getElementById('e-kar').value = data.karbohidrat;
            document.getElementById('e-lem').value = data.lemak;
            document.getElementById('e-ser').value = data.serat;
            document.getElementById('edit-modal').style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>