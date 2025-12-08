<?php
// JANGAN ada session_start() di sini

$host = "localhost";
$user = "root";
$pass = "";
$db   = "pelaporan_masyarakat"; // sesuaikan

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
