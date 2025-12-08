<?php
session_start();
require 'koneksi.php';
require 'fungsi_status.php';

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}
?>
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Laporan Masyarakat</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
<div class="container mt-4">
    <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>

    <h2 class="text-center">Data Laporan Masyarakat</h2>

    <button class="btn btn-success mb-3 float-right"
            onclick="location.href='create.php'">Tambah Laporan</button>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php
        $sql = "SELECT l.id_laporan, l.judul, l.status, k.jenis_laporan
                FROM laporan l
                JOIN kategori k ON l.id_kategori = k.id_kategori
                ORDER BY l.id_laporan DESC";
        $hasil = mysqli_query($conn, $sql);
        $no = 1;

        while ($row = mysqli_fetch_assoc($hasil)) :
        ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['judul']; ?></td>
                <td><?= $row['jenis_laporan']; ?></td>
                <td>
                    <span class="text-dark"><?= $row['status']; ?></span>
                </td>
                <td>
                    <a href="detail_laporan.php?id=<?= $row['id_laporan']; ?>" 
                       class="btn btn-info btn-sm">Detail</a>
                    <a href="update.php?id=<?= $row['id_laporan']; ?>" 
                       class="btn btn-warning btn-sm">Edit</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
