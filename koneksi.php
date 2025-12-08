<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pelaporan_masyarakat"; // Sesuaikan nama database

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>