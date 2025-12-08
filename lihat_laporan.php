<?php
require 'koneksi.php';
require 'header.php';

// PERBAIKAN 1: Izinkan semua role yang sudah login (Masyarakat, Admin, Petugas)
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

// Setup Variabel
$role = $_SESSION['role'];
$id_masyarakat = isset($_SESSION['id_masyarakat']) ? $_SESSION['id_masyarakat'] : null;
?>

<style>
    /* ====== STYLE KHUSUS HALAMAN LAPORAN (TEMA BIRU) ====== */
    .laporan-section {
        min-height: 80vh;
        background: #f8f9fa;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1.5rem;
    }

    .section-heading-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
        color: #fff;
        font-size: 1.25rem;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }

    .section-heading-text h2 {
        margin: 0;
        font-weight: 700;
        font-size: 1.75rem;
        color: #212529;
    }

    .section-heading-text span {
        font-size: .95rem;
        color: #6c757d;
    }

    .laporan-card {
        border-radius: 16px;
        border: none;
        background: #ffffff;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        transition: all .3s ease;
        position: relative;
        overflow: hidden;
        border-left: 4px solid transparent;
    }

    .laporan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(13, 110, 253, 0.15);
        border-left: 4px solid #0d6efd;
    }

    .laporan-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        text-decoration: none;
    }

    .laporan-title:hover {
        color: #0d6efd;
    }

    .laporan-meta {
        font-size: .85rem;
        color: #6c757d;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 8px;
        align-items: center;
    }

    .laporan-meta i {
        margin-right: .35rem;
        color: #0d6efd;
    }

    .laporan-desc {
        font-size: .95rem;
        color: #555;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .badge-status {
        font-size: .7rem;
        padding: .4em .9em;
        border-radius: 30px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .badge-status.bg-secondary { background-color: #e2e3e5 !important; color: #383d41; }
    .badge-status.bg-primary { background-color: #cfe2ff !important; color: #084298; }
    .badge-status.bg-success { background-color: #d1e7dd !important; color: #0f5132; }
    .badge-status.bg-danger { background-color: #f8d7da !important; color: #842029; }

    .btn-detail {
        border-radius: 50px;
        font-size: .85rem;
        font-weight: 600;
        padding: 8px 24px;
        border: 1px solid #0d6efd;
        color: #0d6efd;
        background: transparent;
        transition: all 0.3s;
    }

    .btn-detail:hover {
        background: #0d6efd;
        color: white;
    }

    .btn-detail i {
        transition: transform .18s ease;
    }
    .btn-detail:hover i {
        transform: translateX(3px);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #e7f1ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0d6efd;
        font-size: 2.5rem;
        margin: 0 auto 1.5rem;
    }
</style>

<section class="laporan-section py-5">
    <div class="container">
        
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="section-heading">
                    <div class="section-heading-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="section-heading-text">
                        <h2>Laporan & Aspirasi</h2>
                        <span>Pantau laporan dan aspirasi publik terkini.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">

                <?php
                // PERBAIKAN 2: Logika Query
                // - Jika Masyarakat: Lihat laporan sendiri (termasuk rahasia) ATAU laporan publik orang lain.
                // - Jika Admin/Petugas: Hanya lihat laporan PUBLIK di halaman ini (Laporan rahasia dikelola di Dashboard Admin).
                
                $sql = "SELECT l.*, k.jenis_laporan, m.nama AS nama_pelapor 
                        FROM laporan l
                        JOIN kategori k ON l.id_kategori = k.id_kategori
                        JOIN masyarakat m ON l.id_masyarakat = m.id_masyarakat
                        WHERE 1=1 ";

                if ($role == 'masyarakat') {
                    // Logic untuk Masyarakat
                    $sql .= " AND (l.id_masyarakat = '$id_masyarakat' OR l.tipe_laporan != 'Rahasia')";
                } else {
                    // Logic untuk Admin/Petugas (View Mode Publik)
                    $sql .= " AND l.tipe_laporan != 'Rahasia'";
                }
                
                $sql .= " ORDER BY l.tgl_pengaduan DESC";
                
                $hasil = mysqli_query($conn, $sql);

                if (mysqli_num_rows($hasil) > 0) :
                    while ($row = mysqli_fetch_assoc($hasil)) :
                        
                        // Cek Kepemilikan (Hanya true jika login sebagai pemilik asli)
                        $is_mine = ($role == 'masyarakat' && $row['id_masyarakat'] == $id_masyarakat);

                        $badgeClass = 'bg-secondary'; 
                        if ($row['status'] == 'Selesai') $badgeClass = 'bg-success';
                        if ($row['status'] == 'Proses')  $badgeClass = 'bg-primary';
                        if ($row['status'] == 'Ditolak') $badgeClass = 'bg-danger';

                        $tanggal = date('d M Y', strtotime($row['tgl_pengaduan']));
                        $deskripsi_pendek = mb_strimwidth($row['deskripsi'], 0, 180, "...");
                ?>
                
                        <div class="card laporan-card mb-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <?php if ($is_mine): ?>
                                            <span class="badge bg-primary mb-2 rounded-pill"><i class="fas fa-user me-1"></i> Laporan Saya</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-secondary border mb-2 rounded-pill"><i class="fas fa-globe me-1"></i> Publik: <?= htmlspecialchars($row['nama_pelapor']); ?></span>
                                        <?php endif; ?>

                                        <h5 class="laporan-title mb-1 mt-1">
                                            <?= htmlspecialchars($row['judul']); ?>
                                        </h5>
                                        
                                        <div class="laporan-meta">
                                            <span><i class="fas fa-calendar-alt"></i> <?= $tanggal; ?></span>
                                            <span><i class="fas fa-tag"></i> <?= htmlspecialchars($row['jenis_laporan']); ?></span>
                                            <?php if($row['foto']): ?>
                                                <span class="text-primary"><i class="fas fa-paperclip"></i> Ada Lampiran</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <span class="badge badge-status <?= $badgeClass; ?>">
                                        <?= htmlspecialchars($row['status']); ?>
                                    </span>
                                </div>

                                <hr class="opacity-25 my-3">

                                <p class="laporan-desc">
                                    <?= htmlspecialchars($deskripsi_pendek); ?>
                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <?php if ($is_mine && $row['status'] == 'Diajukan'): ?>
                                            <a href="hapus_laporan.php?id=<?= $row['id_laporan']; ?>" class="text-danger small text-decoration-none fw-bold" onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <a href="detail_laporan.php?id=<?= $row['id_laporan']; ?>" class="btn btn-detail">
                                        Lihat Detail <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                <?php
                    endwhile;
                else :
                ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="far fa-folder-open"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Belum Ada Laporan</h5>
                        <p class="text-muted">
                            Belum ada laporan publik yang tersedia saat ini.
                        </p>
                        <?php if($role == 'masyarakat'): ?>
                        <a href="buat_laporan.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="fas fa-plus me-2"></i>Buat Laporan Baru
                        </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<?php require 'footer.php'; ?>