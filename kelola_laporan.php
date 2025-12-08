<?php
require 'koneksi.php';
require 'header.php';

// Cek akses admin
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    header("Location: login.php");
    exit;
}

// LOGIKA FILTER
$where = " WHERE 1=1 ";
if (isset($_GET['status']) && $_GET['status'] != '') {
    $s = $_GET['status'];
    $where .= " AND l.status = '$s'";
}
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="fas fa-clipboard-list me-2"></i>Kelola Laporan</h3>
        
        <form class="d-flex" method="GET">
            <select name="status" class="form-select me-2" onchange="this.form.submit()">
                <option value="">-- Semua Status --</option>
                <option value="Diajukan" <?= (isset($_GET['status']) && $_GET['status']=='Diajukan') ? 'selected' : ''; ?>>Baru (Diajukan)</option>
                <option value="Proses" <?= (isset($_GET['status']) && $_GET['status']=='Proses') ? 'selected' : ''; ?>>Sedang Diproses</option>
                <option value="Selesai" <?= (isset($_GET['status']) && $_GET['status']=='Selesai') ? 'selected' : ''; ?>>Selesai</option>
            </select>
        </form>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Tanggal</th>
                            <th>Pelapor</th>
                            <th>Judul Laporan</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sql = "SELECT l.*, m.nama FROM laporan l 
                                JOIN masyarakat m ON l.id_masyarakat = m.id_masyarakat 
                                $where
                                ORDER BY l.id_laporan DESC";
                        $query = mysqli_query($conn, $sql);

                        if(mysqli_num_rows($query) > 0):
                            while ($r = mysqli_fetch_assoc($query)) :
                                $bg_status = 'bg-secondary';
                                if($r['status'] == 'Diajukan') $bg_status = 'bg-warning text-dark';
                                if($r['status'] == 'Proses') $bg_status = 'bg-info text-dark';
                                if($r['status'] == 'Selesai') $bg_status = 'bg-success';
                                if($r['status'] == 'Ditolak') $bg_status = 'bg-danger';
                        ?>
                        <tr>
                            <td class="ps-4"><?= $no++; ?></td>
                            <td><?= date('d M Y', strtotime($r['tgl_pengaduan'])); ?></td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($r['nama']); ?></div>
                                <?php if($r['tipe_laporan'] == 'Rahasia'): ?>
                                    <span class="badge bg-dark rounded-pill" style="font-size: 0.6rem;"><i class="fas fa-user-secret"></i> RAHASIA</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars(substr($r['judul'], 0, 30)); ?>...</td>
                            <td><?= htmlspecialchars($r['lokasi']); ?></td>
                            <td><span class="badge <?= $bg_status; ?>"><?= $r['status']; ?></span></td>
                            <td>
                                <a href="detail_laporan.php?id=<?= $r['id_laporan']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Tindak Lanjut
                                </a>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else:
                        ?>
                            <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada laporan ditemukan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>