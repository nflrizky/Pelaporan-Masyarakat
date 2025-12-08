<?php
require 'koneksi.php';
require 'header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'masyarakat') {
    header("Location: login.php");
    exit;
}

$id_warga = $_SESSION['id_masyarakat'];
$sql = "SELECT l.*, k.jenis_laporan FROM laporan l 
        JOIN kategori k ON l.id_kategori = k.id_kategori 
        WHERE l.id_masyarakat = '$id_warga' 
        ORDER BY l.id_laporan DESC";
$hasil = mysqli_query($conn, $sql);
?>

<div class="container py-5">
    <h3 class="fw-bold mb-4 border-start border-4 border-primary ps-3">Laporan Saya</h3>
    
    <div class="row">
        <?php if (mysqli_num_rows($hasil) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($hasil)): 
                // Warna Status
                $bg = 'bg-secondary';
                if($row['status'] == 'Proses') $bg = 'bg-primary';
                if($row['status'] == 'Selesai') $bg = 'bg-success';
                if($row['status'] == 'Ditolak') $bg = 'bg-danger';
            ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-light text-dark border">
                                    <i class="fas fa-tag me-1"></i> <?= $row['jenis_laporan']; ?>
                                </span>
                                <span class="badge <?= $bg; ?> rounded-pill px-3"><?= $row['status']; ?></span>
                            </div>

                            <h5 class="card-title fw-bold text-primary mb-1"><?= htmlspecialchars($row['judul']); ?></h5>
                            <small class="text-muted mb-3 d-block">
                                <i class="fas fa-calendar me-1"></i> <?= date('d M Y', strtotime($row['tgl_pengaduan'])); ?> | 
                                <i class="fas fa-map-marker-alt me-1"></i> <?= $row['lokasi']; ?>
                            </small>

                            <p class="card-text text-secondary small">
                                <?= htmlspecialchars(substr($row['deskripsi'], 0, 100)); ?>...
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center">
                            <small class="text-muted fw-bold"><?= $row['tipe_laporan']; ?></small>
                            <a href="detail_laporan.php?id=<?= $row['id_laporan']; ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                                Lihat Progres <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="mb-3 text-muted"><i class="fas fa-folder-open fa-3x"></i></div>
                <h5>Anda belum memiliki laporan.</h5>
                <a href="buat_laporan.php" class="btn btn-primary mt-2">Buat Laporan Baru</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require 'footer.php'; ?>