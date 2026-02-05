<?php include '../backend/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tim Ahli SPPG - MBG</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #4A5D23; --bg: #F8FAF7; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); padding-bottom: 50px; }
        
        .container { max-width: 1000px; margin: 60px auto; padding: 0 20px; }
        
        .header-section { text-align: center; margin-bottom: 50px; }
        .header-section h2 { font-size: 32px; font-weight: 800; color: #1a202c; margin-bottom: 10px; }
        .header-section p { color: #718096; font-size: 16px; }

        .grid-tim { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 30px; 
            justify-content: center;
        }

        .card-tim { 
            background: white; 
            border-radius: 40px; 
            padding: 45px 30px; 
            text-align: center; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.03); 
            border: 1px solid #f0f4f2;
            transition: all 0.4s ease;
        }
        .card-tim:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); }

        /* Inisial Nama Bulat Mewah */
        .avatar-circle {
            width: 110px;
            height: 110px;
            background: linear-gradient(135deg, #4A5D23 0%, #82955B 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: 800;
            margin: 0 auto 25px;
            box-shadow: 0 10px 20px rgba(74, 93, 35, 0.2);
            border: 5px solid #F8FAF7;
        }

        .badge-jabatan { 
            background: #E8F0E5; 
            color: var(--primary); 
            padding: 8px 18px; 
            border-radius: 15px; 
            font-size: 11px; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            display: inline-block;
            margin-bottom: 15px;
        }

        .nama-petugas { font-size: 22px; font-weight: 800; color: #2d3748; margin-bottom: 5px; text-transform: capitalize; }
        .sub-text { font-size: 14px; color: #a0aec0; font-weight: 500; }
        
        .contact-btn {
            margin-top: 25px;
            display: inline-block;
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            transition: 0.3s;
        }
        .contact-btn:hover { opacity: 0.7; }
    </style>
</head>
<body>

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