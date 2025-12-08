<?php
require 'koneksi.php';

$id_laporan = $_GET['id'];

$q = mysqli_query($conn, "SELECT * FROM laporan WHERE id_laporan = '$id_laporan'");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    die("Laporan tidak ditemukan");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Laporan</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
<div class="container mt-4">
    <h2 class="text-center">Edit Laporan Masyarakat</h2>

    <div class="row justify-content-md-center">
        <div class="col-8">

            <form method="POST" action="">
                <div class="form-group">
                    <label>Judul Laporan</label>
                    <input type="text" class="form-control" name="judul" 
                           value="<?= $data['judul']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select class="form-control" name="id_kategori" required>
                        <?php
                        $qKat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY jenis_laporan");
                        while ($k = mysqli_fetch_assoc($qKat)) {
                            $selected = ($k['id_kategori'] == $data['id_kategori']) ? "selected" : "";
                            echo "<option value='{$k['id_kategori']}' $selected>{$k['jenis_laporan']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="4" required><?= $data['deskripsi']; ?></textarea>
                </div>

                <button type="submit" name="update" class="btn btn-warning">Perbarui</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>

            <?php
            if (isset($_POST['update'])) {
                $judul       = $_POST['judul'];
                $id_kategori = $_POST['id_kategori'];
                $deskripsi   = $_POST['deskripsi'];

                $sqlUpdate = "UPDATE laporan SET 
                                judul = '$judul',
                                id_kategori = '$id_kategori',
                                deskripsi = '$deskripsi'
                              WHERE id_laporan = '$id_laporan'";

                $hasil = mysqli_query($conn, $sqlUpdate);

                if ($hasil) {
                    echo "<script>alert('Laporan berhasil diperbarui'); window.location='index.php';</script>";
                } else {
                    echo "<div class='alert alert-danger mt-3'>Gagal memperbarui laporan: " . mysqli_error($conn) . "</div>";
                }
            }
            ?>

        </div>
    </div>
</div>
</body>
</html>
