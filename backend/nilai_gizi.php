<?php
session_start();
// Proteksi: Hanya petugas gizi
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petugas_gizi') {
    header("Location: login.php"); exit;
}
include 'koneksi.php';

// --- LOGIKA UPDATE GIZI ---
if (isset($_POST['update_gizi'])) {
    $id_menu = mysqli_real_escape_string($conn, $_POST['id_menu']);
    $kalori  = mysqli_real_escape_string($conn, $_POST['kalori']);
    $protein = mysqli_real_escape_string($conn, $_POST['protein']);
    $karbo   = mysqli_real_escape_string($conn, $_POST['karbohidrat']);
    $lemak   = mysqli_real_escape_string($conn, $_POST['lemak']);
    $serat   = mysqli_real_escape_string($conn, $_POST['serat']);

    $cek = mysqli_query($conn, "SELECT id_menu FROM nilai_gizi WHERE id_menu='$id_menu'");
    
    if (mysqli_num_rows($cek) > 0) {
        $q = "UPDATE nilai_gizi SET kalori='$kalori', protein='$protein', karbohidrat='$karbo', lemak='$lemak', serat='$serat' WHERE id_menu='$id_menu'";
    } else {
        $q = "INSERT INTO nilai_gizi (id_menu, kalori, protein, karbohidrat, lemak, serat) VALUES ('$id_menu', '$kalori', '$protein', '$karbo', '$lemak', '$serat')";
    }
    
    if(mysqli_query($conn, $q)) {
        header("Location: nilai_gizi.php?status=updated"); exit;
    }
}

// Ambil data untuk rekap gizi
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
    <title>Nilai Gizi - SPPG Gizi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6B8E23; --dark-olive: #1A2605; --white: #FFFFFF;
            --bg: #F4F7FE; --accent: #D9E8B5; --text-main: #2D3748; --text-light: #8B949E;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; text-decoration: none; list-style: none; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-main); display: flex; min-height: 100vh; }

        /* SIDEBAR PREMIUM (Identik dengan Dashboard & Menu Harian) */
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

        /* MAIN CONTENT */
        .main { margin-left: 320px; width: calc(100% - 320px); padding: 50px 40px; }
        .welcome-header { margin-bottom: 40px; }
        .welcome-header h1 { font-size: 32px; font-weight: 800; color: var(--dark-olive); margin-bottom: 8px; }

        /* CARDS REKAP GIZI */
        .list-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }
        .rekap-card {
            background: var(--white);
            border-radius: 28px;
            padding: 25px;
            border: 1px solid #F1F5F9;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .rekap-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.05); }

        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .card-header span { font-size: 11px; font-weight: 800; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; }
        
        .menu-title { font-size: 18px; font-weight: 800; color: var(--dark-olive); margin-bottom: 15px; line-height: 1.3; height: 48px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }

        .gizi-container {
            background: #F8FAFC;
            border-radius: 20px;
            padding: 15px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .gizi-item { padding: 10px; border-radius: 12px; background: white; border: 1px solid #F1F5F9; }
        .gizi-item small { display: block; font-size: 10px; font-weight: 700; color: var(--text-light); text-transform: uppercase; margin-bottom: 2px; }
        .gizi-item b { font-size: 14px; color: var(--primary); }
        .full-width { grid-column: span 2; display: flex; justify-content: space-between; align-items: center; }

        /* BUTTONS */
        .btn-group { display: flex; gap: 12px; margin-top: 20px; }
        .btn-detail, .btn-edit-gizi {
            flex: 1; border: none; padding: 12px; border-radius: 16px;
            font-weight: 700; font-size: 13px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-detail { background: #F1F5F9; color: var(--text-light); border: 1px solid #E2E8F0; }
        .btn-detail:hover { background: #E2E8F0; color: var(--dark-olive); transform: translateY(-2px); }
        .btn-edit-gizi { 
            background: linear-gradient(135deg, #1A2605, var(--primary)); 
            color: white; box-shadow: 0 4px 12px rgba(107, 142, 35, 0.2);
        }
        .btn-edit-gizi:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(107, 142, 35, 0.4); filter: brightness(1.1); }

        /* MODAL */
        .modal { display: none; position: fixed; inset: 0; background: rgba(30, 41, 59, 0.8); z-index: 200; align-items: center; justify-content: center; backdrop-filter: blur(8px); }
        .modal-content { background: white; width: 420px; border-radius: 35px; overflow: hidden; animation: zoom 0.3s; }
        @keyframes zoom { from { transform: scale(0.8); } to { transform: scale(1); } }
        
        .modal-body { padding: 35px; }
        label { display: block; font-size: 12px; font-weight: 700; color: var(--text-light); margin-bottom: 6px; margin-top: 12px; }
        input[type="number"] {
            width: 100%; padding: 14px; border-radius: 14px; border: 2px solid #F1F5F9; 
            background: #F8FAFC; outline: none; font-family: inherit; font-size: 14px; transition: 0.3s;
        }
        input[type="number"]:focus { border-color: var(--primary); background: white; }
        .btn-save-final { width: 100%; background: var(--dark-olive); color: white; border: none; padding: 16px; border-radius: 18px; font-weight: 700; margin-top: 25px; cursor: pointer; transition: 0.3s; }
        .btn-save-final:hover { background: var(--primary); transform: translateY(-3px); }
        @media (max-width: 768px) {
            .sidebar { 
                display: flex; transform: translateX(-110%); 
                transition: transform 0.3s ease-in-out;
                box-shadow: 10px 0 30px rgba(0,0,0,0.1);
            }
            .sidebar.active { transform: translateX(0); }
            
            .main { margin-left: 0; width: 100%; padding: 20px; padding-top: 80px; }
            
            /* Mobile Toggle Button */
            .mobile-toggle { 
                display: flex !important; 
                position: fixed; top: 20px; right: 20px; z-index: 1001; 
                background: var(--dark-olive); color: white; width: 45px; height: 45px; 
                border-radius: 12px; align-items: center; justify-content: center; 
                box-shadow: 0 4px 15px rgba(0,0,0,0.2); font-size: 20px; cursor: pointer;
            }
        }
        .mobile-toggle { display: none; }
    </style>
</head>
<body>

    <!-- Mobile Toggle Button -->
    <div class="mobile-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">
        <i class="fas fa-bars"></i>
    </div>

    <div class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-box"><i class="fas fa-apple-whole"></i></div>
            <div class="logo-text">SPPG <span>GIZI</span></div>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard_gizi.php"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></a>
            <a href="menu_harian.php"><i class="fas fa-utensils"></i> <span>Menu Harian</span></a>
            <a href="nilai_gizi.php" class="active"><i class="fas fa-chart-simple"></i> <span>Nilai Gizi</span></a>
        </div>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fas fa-power-off"></i> Keluar Sistem</a>
        </div>
    </div>

    <div class="main">
        <div class="welcome-header" style="display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <h1>Rekapitulasi Nilai Gizi</h1>
                <p style="color: var(--text-light);">Kelola data kandungan nutrisi lengkap untuk kesehatan pasien.</p>
            </div>
            <div style="background: white; padding: 12px 20px; border-radius: 18px; font-weight: 800; border: 1px solid #E2E8F0; font-size: 14px; color: var(--dark-olive);">
                <i class="far fa-calendar-alt" style="color: var(--primary); margin-right: 10px;"></i> 
                <span id="live-date">Memuat...</span>
            </div>
        </div>

        <div class="list-wrapper">
            <?php while($row = mysqli_fetch_assoc($result)): 
                $img = (!empty($row['foto']) && file_exists("uploads/".$row['foto'])) ? "uploads/".$row['foto'] : "https://via.placeholder.com/400x300";
            ?>
            <div class="rekap-card">
                <div class="card-header">
                    <span><i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($row['tanggal'])) ?></span>
                    <i class="fas fa-circle-check" style="color: var(--primary); font-size: 14px;"></i>
                </div>
                
                <h4 class="menu-title"><?= $row['nama_menu'] ?></h4>

                <div class="gizi-container">
                    <div class="gizi-item full-width">
                        <small>Total Energi</small>
                        <b style="font-size: 16px; color: var(--dark-olive);"><?= $row['kalori'] ?? 0 ?> kcal</b>
                    </div>
                    <div class="gizi-item"><small>Protein</small><b><?= $row['protein'] ?? 0 ?>g</b></div>
                    <div class="gizi-item"><small>Karbo</small><b><?= $row['karbohidrat'] ?? 0 ?>g</b></div>
                    <div class="gizi-item"><small>Lemak</small><b><?= $row['lemak'] ?? 0 ?>g</b></div>
                    <div class="gizi-item"><small>Serat</small><b><?= $row['serat'] ?? 0 ?>g</b></div>
                </div>

                <div class="btn-group">
                    <button class="btn-detail" onclick="openFotoModal('<?= $row['nama_menu'] ?>', '<?= $img ?>')">
                        <i class="fas fa-image"></i> Foto
                    </button>
                    <button class="btn-edit-gizi" onclick="openEditGizi(<?= htmlspecialchars(json_encode($row)) ?>)">
                        <i class="fas fa-pen-to-square"></i> Edit Gizi
                    </button>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="foto-modal" class="modal" onclick="this.style.display='none'">
        <div class="modal-content" onclick="event.stopPropagation()">
            <img id="m-img" src="" style="width: 100%; height: 260px; object-fit: cover;">
            <div style="padding: 30px; text-align: center;">
                <h3 id="m-nama" style="font-weight: 800; color: var(--dark-olive); margin-bottom: 20px;"></h3>
                <button onclick="document.getElementById('foto-modal').style.display='none'" class="btn-detail" style="width: 100%; padding: 15px;">Tutup</button>
            </div>
        </div>
    </div>

    <div id="edit-gizi-modal" class="modal">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-body">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 id="edit-title" style="color: var(--dark-olive); font-weight: 800;">Edit Gizi</h3>
                    <i class="fas fa-chart-pie" style="color: var(--primary); font-size: 20px;"></i>
                </div>
                <form method="POST">
                    <input type="hidden" name="id_menu" id="e-id">
                    
                    <label>Energi Kalori (kcal)</label>
                    <input type="number" step="0.01" name="kalori" id="e-kal" required>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label>Protein (g)</label>
                            <input type="number" step="0.01" name="protein" id="e-pro" required>
                        </div>
                        <div>
                            <label>Karbohidrat (g)</label>
                            <input type="number" step="0.01" name="karbohidrat" id="e-kar" required>
                        </div>
                        <div>
                            <label>Lemak (g)</label>
                            <input type="number" step="0.01" name="lemak" id="e-lem" required>
                        </div>
                        <div>
                            <label>Serat (g)</label>
                            <input type="number" step="0.01" name="serat" id="e-ser" required>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 10px;">
                        <button type="button" onclick="document.getElementById('edit-gizi-modal').style.display='none'" class="btn-detail" style="margin-top: 25px; flex: 1;">Batal</button>
                        <button type="submit" name="update_gizi" class="btn-save-final" style="flex: 2;">Simpan Perubahan</button>
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

        function openFotoModal(nama, img) {
            document.getElementById('m-nama').innerText = nama;
            document.getElementById('m-img').src = img;
            document.getElementById('foto-modal').style.display = 'flex';
        }

        function openEditGizi(data) {
            document.getElementById('edit-title').innerText = data.nama_menu;
            document.getElementById('e-id').value = data.id_menu;
            document.getElementById('e-kal').value = data.kalori || 0;
            document.getElementById('e-pro').value = data.protein || 0;
            document.getElementById('e-kar').value = data.karbohidrat || 0;
            document.getElementById('e-lem').value = data.lemak || 0;
            document.getElementById('e-ser').value = data.serat || 0;
            document.getElementById('edit-gizi-modal').style.display = 'flex';
        }

        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>