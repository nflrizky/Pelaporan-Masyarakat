<?php
require 'koneksi.php';
require 'header.php';
?>

<style>
    /* ====== STYLE KHUSUS HALAMAN LAPORAN ====== */
    .laporan-section {
        min-height: 80vh;
        background: #f5f7fb;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: .5rem;
    }

    .section-heading-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #ff416c, #ff4b2b);
        color: #fff;
        font-size: 1.25rem;
    }

    .section-heading-text h2 {
        margin: 0;
        font-weight: 700;
        letter-spacing: .02em;
        font-size: 1.75rem;
        color: #212529;
    }

    .section-heading-text span {
        font-size: .95rem;
        color: #6c757d;
    }

    .laporan-card {
        border-radius: 18px;
        border: none;
        background: #ffffff;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        transition: all .2s ease;
        position: relative;
        overflow: hidden;
    }

    .laporan-card::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        border: 1px solid transparent;
        background: linear-gradient(135deg, rgba(255, 65, 108, .18), rgba(255, 75, 43, .18)) border-box;
        -webkit-mask: 
            linear-gradient(#fff 0 0) padding-box,
            linear-gradient(#fff 0 0);
        mask: 
            linear-gradient(#fff 0 0) padding-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity .2s ease;
        pointer-events: none;
    }

    .laporan-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.12);
    }

    .laporan-card:hover::before {
        opacity: 1;
    }

    .laporan-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #333;
    }

    .laporan-meta {
        font-size: .85rem;
        color: #6c757d;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 5px;
    }

    .laporan-meta i {
        margin-right: .35rem;
        color: #ff4b2b;
    }

    .laporan-desc {
        font-size: .95rem;
        color: #4b5563;
        line-height: 1.6;
        margin-bottom: 0;
    }

    .badge-status {
        font-size: .75rem;
        padding: .4em .9em;
        border-radius: 50px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .badge-status.bg-secondary { background-color: #94a3b8 !important; }
    .badge-status.bg-primary { background-color: #3b82f6 !important; }
    .badge-status.bg-success { background-color: #10b981 !important; }
    .badge-status.bg-danger { background-color: #ef4444 !important; }

    .btn-detail {
        border-radius: 50px;
        font-size: .85rem;
        font-weight: 600;
        padding: 8px 24px;
        border: 1px solid #ff4b2b;
        color: #ff4b2b;
        background: transparent;
        transition: all 0.3s;
    }

    .btn-detail:hover {
        background: #ff4b2b;
        color: white;
    }

    .btn-detail i {
        transition: transform .18s ease;
    }

    .btn-detail:hover i {
        transform: translateX(3px);
    }

    .divider-soft {
        height: 1px;
        width: 100%;
        background: linear-gradient(to right, transparent, rgba(148, 163, 184, 0.3), transparent);
        margin: 1.5rem 0;
    }

    .empty-state {
        max-width: 480px;
        margin: 5rem auto;
        text-align: center;
        padding: 2rem;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #fff0f3;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ff4b2b;
        font-size: 2.5rem;
        margin: 0 auto 1.5rem;
    }

    .empty-state h5 {
        font-weight: 700;
        margin-bottom: .5rem;
        color: #333;
    }

    .empty-state p {
        color: #6b7280;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 576px) {
        .laporan-card { border-radius: 14px; }
        .laporan-title { font-size: 1rem; }
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
                        <h2>Laporan Terbaru</h2>
                        <span>Pantau perkembangan aspirasi masyarakat desa terkini.</span>
                    </div>
                </div>
                <div class="divider-soft"></div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">

                <?php
                // Query mengambil data laporan
                $sql = "SELECT l.id_laporan, l.judul, l.deskripsi, l.status, k.jenis_laporan, l.tgl_pengaduan
                        FROM laporan l
                        JOIN kategori k ON l.id_kategori = k.id_kategori
                        ORDER BY l.id_laporan DESC";
                $hasil = mysqli_query($conn, $sql);

                if (mysqli_num_rows($hasil) > 0) :
                    while ($row = mysqli_fetch_assoc($hasil)) :
                        
                        // Logika Warna Badge Status
                        $badgeClass = 'bg-secondary'; // Default (Menunggu)
                        if ($row['status'] == 'Selesai') $badgeClass = 'bg-success';
                        if ($row['status'] == 'Proses')  $badgeClass = 'bg-primary';
                        if ($row['status'] == 'Ditolak') $badgeClass = 'bg-danger';

                        // Format Tanggal
                        $tanggal = date('d M Y', strtotime($row['tgl_pengaduan']));
                        
                        // Potong deskripsi agar tidak terlalu panjang
                        $deskripsi_pendek = mb_strimwidth($row['deskripsi'], 0, 160, "...");
                ?>
                
                        <div class="card laporan-card mb-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="laporan-title mb-1">
                                            <?= htmlspecialchars($row['judul']); ?>
                                        </h5>
                                        <div class="laporan-meta">
                                            <span><i class="fas fa-calendar-alt"></i> <?= $tanggal; ?></span>
                                            <span><i class="fas fa-tag"></i> <?= htmlspecialchars($row['jenis_laporan']); ?></span>
                                        </div>
                                    </div>
                                    
                                    <span class="badge badge-status <?= $badgeClass; ?>">
                                        <?= htmlspecialchars($row['status']); ?>
                                    </span>
                                </div>

                                <hr class="border-light my-3">

                                <p class="laporan-desc mb-4">
                                    <?= htmlspecialchars($deskripsi_pendek); ?>
                                </p>

                                <div class="d-flex justify-content-end">
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
                        <h5>Belum Ada Laporan</h5>
                        <p>
                            Saat ini belum terdapat pengaduan yang masuk ke sistem. <br>
                            Jadilah yang pertama untuk menyampaikan aspirasi.
                        </p>
                        <a href="buat_laporan.php" class="btn btn-danger rounded-pill px-4 shadow-sm">
                            <i class="fas fa-plus me-2"></i>Buat Laporan Baru
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<?php require 'footer.php'; ?>