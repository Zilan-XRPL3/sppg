<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] != 'admin') exit;

if (isset($_POST['simpan'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    mysqli_query($koneksi,
        "INSERT INTO users (username,password,role)
         VALUES ('$username','$password','$role')"
    );

    header("Location: user.php");
}
?>
<form method="POST">
    <h3>Tambah User</h3>
    Username <br>
    <input name="username"><br><br>

    Password <br>
    <input type="password" name="password"><br><br>

    Role <br>
    <select name="role">
        <option value="admin">Admin</option>
        <option value="petugas_gizi">Petugas Gizi</option>
        <option value="petugas_pengaduan">Petugas Pengaduan</option>
    </select><br><br>

    <button name="simpan">Simpan</button>
</form>
