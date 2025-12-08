<?php
require 'koneksi.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Laporan</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
<div class="container mt-4">
    <h2 class="text-center">Tambah Laporan Masyarakat</h2>

    <div class="row justify-content-md-center">
        <div class="col-8">

            <form method="POST" action="">
                <div class="form-group">
                    <label>Judul Laporan</label>
                    <input type="text" class="form-control" name="judul" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select class="form-control" name="id_kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php
                        $qKat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY jenis_laporan");
                        while ($k = mysqli_fetch_assoc($qKat)) {
                            echo "<option value='{$k['id_kategori']}'>{$k['jenis_laporan']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="4" required></textarea>
                </div>

                <!-- sementara id_masyarakat dihardcode 1, nanti bisa diambil dari session -->
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>

            <?php
            if (isset($_POST['submit'])) {
                $judul       = $_POST['judul'];
                $id_kategori = $_POST['id_kategori'];
                $deskripsi   = $_POST['deskripsi'];

                $id_masyarakat = 1; // TODO: ganti dengan $_SESSION['id_masyarakat'] kalau sudah ada login

                $sql = "INSERT INTO laporan 
                            (id_masyarakat, id_kategori, judul, deskripsi, status)
                        VALUES
                            ('$id_masyarakat', '$id_kategori', '$judul', '$deskripsi', 'Diajukan')";

                $hasil = mysqli_query($conn, $sql);

                if ($hasil) {
                    echo "<script>alert('Laporan berhasil ditambahkan'); window.location='index.php';</script>";
                } else {
                    echo "<div class='alert alert-danger mt-3'>Gagal menambahkan laporan: " . mysqli_error($conn) . "</div>";
                }
            }
            ?>

        </div>
    </div>
</div>
</body>
</html>
