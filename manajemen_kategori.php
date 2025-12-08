<?php
require 'koneksi.php';
require 'header.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    header("Location: login.php"); exit;
}

if (isset($_POST['tambah'])) {
    $kat = htmlspecialchars($_POST['kategori']);
    mysqli_query($conn, "INSERT INTO kategori (jenis_laporan) VALUES ('$kat')");
    echo "<script>window.location='manajemen_kategori.php';</script>";
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori='$id'");
    echo "<script>window.location='manajemen_kategori.php';</script>";
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="fw-bold mb-4">Manajemen Kategori Laporan</h3>
            
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-body d-flex gap-2">
                    <form method="POST" class="d-flex w-100 gap-2">
                        <input type="text" name="kategori" class="form-control" placeholder="Nama Kategori Baru (Misal: Bencana Alam)" required>
                        <button type="submit" name="tambah" class="btn btn-success text-nowrap"><i class="fas fa-plus"></i> Tambah</button>
                    </form>
                </div>
            </div>

            <div class="card shadow border-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Kategori</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $q = mysqli_query($conn, "SELECT * FROM kategori");
                        while ($r = mysqli_fetch_assoc($q)):
                        ?>
                        <tr>
                            <td class="ps-4"><?= $no++; ?></td>
                            <td><?= $r['jenis_laporan']; ?></td>
                            <td class="text-end pe-4">
                                <a href="manajemen_kategori.php?hapus=<?= $r['id_kategori']; ?>" 
                                   class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori ini?')">
                                   <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>