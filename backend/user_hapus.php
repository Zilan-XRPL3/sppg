<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] != 'admin') exit;

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM users WHERE id=$id");

header("Location: user.php");
