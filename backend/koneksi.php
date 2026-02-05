<?php
$conn = mysqli_connect("localhost", "root", "", "sppg_mbg");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>