<?php
require 'koneksi.php';
require 'header.php';

// Cek apakah user login sebagai masyarakat
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'masyarakat') {
    header("Location: login.php");
    exit;
}

$id_masyarakat = $_SESSION['id_masyarakat'];
?>

<style>
    /* ====== STYLE KHUSUS (SAMA DENGAN LIHAT_LAPORAN.PHP) ====== */
    .riwayat-section {
        min-height: 80vh;
        background: #f8f9fa;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 2rem; /* Jarak heading ke konten lebih lega */
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

    /* CARD STYLE & HOVER EFFECT */
    .laporan-card {
        border-radius: 16px;
        border: none;
        background: #ffffff;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        transition: all .3s ease;
        position: relative;
        overflow: hidden;
        border-left: 4px solid transparent;
        height: 100%; /* Agar tinggi kartu sama rata */
    }

    /* EFEK HOVER */
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
        display: block;
        margin-bottom: 0.5rem;
        /* Batasi judul max 2 baris */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .laporan-title:hover {
        color: #0d6efd;
    }

    .laporan-meta {
        font-size: .85rem;
        color: #6c757d;
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-bottom: 1rem;
        align-items: center;
    }

    .laporan-meta i {
        margin-right: .3rem;
        color: #0d6efd;
    }

    .laporan-desc {
        font-size: .95rem;
        color: #555;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        /* Batasi deskripsi max 3 baris */
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Badge Status */
    .badge-status {
        font-size: .7rem;
        padding: .4em .9em;
        border-radius: 30px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        position: absolute; /* Status melayang di pojok kanan atas */
        top: 15px;
        right: 15px;
    }
    .badge-status.bg-secondary { background-color: #e2e3e5 !important; color: #383d41; }
    .badge-status.bg-primary { background-color: #cfe2ff !important; color: #084298; }
    .badge-status.bg-success { background-color: #d1e7dd !important; color: #0f5132; }
    .badge-status.bg-danger { background-color: #f8d7da !important; color: #842029; }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
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

<section class="riwayat-section py-5">
    <div class="container">
        
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="section-heading">
                    <div class="section-heading-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="section-heading-text">
                        <h2>Riwayat Laporan Saya</h2>
                        <span>Daftar seluruh laporan yang pernah Anda kirimkan.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">

                <?php
                // QUERY: Hanya mengambil laporan milik user yang sedang login
                $sql = "SELECT l.*, k.jenis_laporan 
                        FROM laporan l
                        JOIN kategori k ON l.id_kategori = k.id_kategori
                        WHERE l.id_masyarakat = '$id_masyarakat'
                        ORDER BY l.tgl_pengaduan DESC";
                
                $hasil = mysqli_query($conn, $sql);

                if (mysqli_num_rows($hasil) > 0) :
                ?>
                    <div class="row g-4"> 
                        
                    <?php
                    while ($row = mysqli_fetch_assoc($hasil)) :
                        
                        // Warna Badge Status
                        $badgeClass = 'bg-secondary'; 
                        if ($row['status'] == 'Selesai') $badgeClass = 'bg-success';
                        if ($row['status'] == 'Proses')  $badgeClass = 'bg-primary';
                        if ($row['status'] == 'Ditolak') $badgeClass = 'bg-danger';

                        // Format Tanggal
                        $tanggal = date('d M Y', strtotime($row['tgl_pengaduan']));
                        
                        // Potong Deskripsi
                        $deskripsi_pendek = mb_strimwidth($row['deskripsi'], 0, 120, "...");
                    ?>
                        <div class="col-md-6">
                            <div class="card laporan-card">
                                <div class="card-body p-4 pt-5"> <span class="badge badge-status <?= $badgeClass; ?>">
                                        <?= htmlspecialchars($row['status']); ?>
                                    </span>

                                    <h5 class="laporan-title">
                                        <?= htmlspecialchars($row['judul']); ?>
                                    </h5>
                                    
                                    <div class="laporan-meta">
                                        <span><i class="fas fa-calendar-alt"></i> <?= $tanggal; ?></span>
                                        <span><i class="fas fa-tag"></i> <?= htmlspecialchars($row['jenis_laporan']); ?></span>
                                        
                                        <?php if($row['foto']): ?>
                                            <span class="text-primary ms-2"><i class="fas fa-paperclip"></i> Foto</span>
                                        <?php endif; ?>
                                        
                                        <?php if($row['tipe_laporan'] == 'Rahasia'): ?>
                                            <span class="text-danger ms-2"><i class="fas fa-lock"></i> Rahasia</span>
                                        <?php endif; ?>
                                    </div>

                                    <p class="laporan-desc">
                                        <?= htmlspecialchars($deskripsi_pendek); ?>
                                    </p>

                                    <hr class="opacity-25 my-3">

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <?php if ($row['status'] == 'Diajukan'): ?>
                                                <a href="hapus_laporan.php?id=<?= $row['id_laporan']; ?>" class="text-danger small text-decoration-none fw-bold" onclick="return confirm('Yakin ingin membatalkan laporan ini?')">
                                                    <i class="fas fa-trash me-1"></i> Batalkan
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <a href="detail_laporan.php?id=<?= $row['id_laporan']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                                            Detail <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> <?php endwhile; ?>
                    </div> <?php else : ?>
                    
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Riwayat Kosong</h5>
                        <p class="text-muted">
                            Anda belum pernah mengirimkan laporan pengaduan. <br>
                            Silakan buat laporan baru jika ada aspirasi.
                        </p>
                        <a href="buat_laporan.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="fas fa-plus me-2"></i>Buat Laporan Baru
                        </a>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<?php require 'footer.php'; ?>