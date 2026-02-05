<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] != 'admin') exit;

$id = $_GET['id'];
$user = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT * FROM users WHERE id=$id")
);

if (isset($_POST['update'])) {
    $role = $_POST['role'];
    mysqli_query($koneksi,
        "UPDATE users SET role='$role' WHERE id=$id"
    );
    header("Location: user.php");
}
?>
<form method="POST">
    <h3>Edit User</h3>
    Username: <b><?= $user['username'] ?></b><br><br>

    Role <br>
    <select name="role">
        <option value="admin">Admin</option>
        <option value="petugas_gizi">Petugas Gizi</option>
        <option value="petugas_pengaduan">Petugas Pengaduan</option>
    </select><br><br>

    <button name="update">Update</button>
</form>
