<?php
include 'koneksi.php';

$username = "racun";
$password = "12345racun";

// cek apakah user sudah ada
$cek = mysqli_query($koneksi,
  "SELECT * FROM users WHERE username='$username'"
);

if (mysqli_num_rows($cek) == 0) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    mysqli_query($koneksi,
      "INSERT INTO users (username, password)
       VALUES ('$username','$hash')"
    );
    echo "USER racun BERHASIL DIBUAT";
} else {
    echo "USER racun SUDAH ADA";
}
?>
