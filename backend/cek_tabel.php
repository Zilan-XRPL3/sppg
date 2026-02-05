<?php
include 'koneksi.php';

echo "<h2>Pemeriksaan Database</h2>";
echo "Database yang terkoneksi: <b>" . $conn->database . "</b><br><br>";

// Cek struktur tabel sekolah
$query = "SHOW COLUMNS FROM sekolah";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error: Tabel 'sekolah' tidak ditemukan! <br>";
    echo mysqli_error($conn);
} else {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr style='background:#ccc'><th>Nama Kolom (Field)</th><th>Tipe Data</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td><b>" . $row['Field'] . "</b></td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>