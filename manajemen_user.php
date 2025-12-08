<?php
require 'koneksi.php';
require 'header.php';

// Cek Admin
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    header("Location: login.php");
    exit;
}

// HAPUS USER
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM masyarakat WHERE id_masyarakat='$id_hapus'");
    echo "<script>alert('Data warga berhasil dihapus!'); window.location='manajemen_user.php';</script>";
}
?>

<div class="container py-5">
    <h3 class="fw-bold mb-4"><i class="fas fa-users-cog me-2"></i>Data Warga Terdaftar</h3>

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>No. HP</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sql = "SELECT * FROM masyarakat ORDER BY nama ASC";
                        $q = mysqli_query($conn, $sql);
                        while ($r = mysqli_fetch_assoc($q)):
                        ?>
                        <tr>
                            <td class="ps-4"><?= $no++; ?></td>
                            <td><?= $r['nik']; ?></td>
                            <td class="fw-bold"><?= $r['nama']; ?></td>
                            <td><?= $r['telp'] ?? $r['no_hp']; ?></td>
                            <td><?= $r['alamat']; ?></td>
                            <td>
                                <a href="manajemen_user.php?hapus=<?= $r['id_masyarakat']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Yakin ingin menghapus warga ini? Semua laporan mereka juga akan terhapus.')">
                                    <i class="fas fa-trash"></i> Hapus
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