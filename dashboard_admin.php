<?php
require 'koneksi.php';
require 'header.php'; // Navbar otomatis menyesuaikan karena login sebagai petugas

// Cek Login Petugas/Admin
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    header("Location: login.php");
    exit;
}

// Hitung Statistik
$total    = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laporan"));
$baru     = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laporan WHERE status='Diajukan'"));
$proses   = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laporan WHERE status='Proses'"));
$selesai  = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laporan WHERE status='Selesai'"));
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Desa</h3>
        <span class="text-muted">Halo, <?= $_SESSION['nama_petugas']; ?> (<?= ucfirst($_SESSION['role']); ?>)</span>
    </div>

    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-0"><?= $total; ?></h2>
                            <small>Total Laporan</small>
                        </div>
                        <i class="fas fa-bullhorn fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-0"><?= $baru; ?></h2>
                            <small>Belum Diverifikasi</small>
                        </div>
                        <i class="fas fa-inbox fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-0"><?= $proses; ?></h2>
                            <small>Sedang Diproses</small>
                        </div>
                        <i class="fas fa-tools fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-0"><?= $selesai; ?></h2>
                            <small>Selesai</small>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Laporan Masuk Terbaru</h5>
            <a href="kelola_laporan.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Pelapor</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Tampilkan 5 laporan terbaru
                        $sql = "SELECT l.*, m.nama FROM laporan l 
                                JOIN masyarakat m ON l.id_masyarakat = m.id_masyarakat 
                                ORDER BY l.id_laporan DESC LIMIT 5";
                        $q = mysqli_query($conn, $sql);
                        
                        while ($r = mysqli_fetch_assoc($q)):
                            $bg_status = 'bg-secondary';
                            if($r['status'] == 'Diajukan') $bg_status = 'bg-warning text-dark';
                            if($r['status'] == 'Proses') $bg_status = 'bg-info text-dark';
                            if($r['status'] == 'Selesai') $bg_status = 'bg-success';
                            if($r['status'] == 'Ditolak') $bg_status = 'bg-danger';
                        ?>
                        <tr>
                            <td class="ps-4 small text-muted"><?= date('d/m/Y', strtotime($r['tgl_pengaduan'])); ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($r['nama']); ?></td>
                            <td><?= htmlspecialchars($r['judul']); ?></td>
                            <td><span class="badge <?= $bg_status; ?> rounded-pill"><?= $r['status']; ?></span></td>
                            <td>
                                <a href="detail_laporan.php?id=<?= $r['id_laporan']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Detail
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