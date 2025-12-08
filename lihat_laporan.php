<?php
require 'koneksi.php';
require 'header.php';
?>

<section class="laporan-section py-5">
    <div class="container">
        
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                
                <div class="section-heading">
                    <div class="section-heading-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="section-heading-text">
                        <h2>Riwayat Laporan</h2>
                        <span>Pantau perkembangan status aduan Anda di sini.</span>
                    </div>
                </div>

                <?php
                // QUERY DATABASE
                $sql = "SELECT l.id_laporan, l.judul, l.deskripsi, l.status, k.jenis_laporan, l.tgl_pengaduan
                        FROM laporan l
                        JOIN kategori k ON l.id_kategori = k.id_kategori
                        ORDER BY l.id_laporan DESC";
                $hasil = mysqli_query($conn, $sql);
                
                if(mysqli_num_rows($hasil) > 0):
                    while ($row = mysqli_fetch_assoc($hasil)) :
                        
                        // LOGIKA WARNA BADGE
                        $badgeClass = 'bg-secondary';
                        if($row['status'] == 'Selesai') $badgeClass = 'bg-success';
                        if($row['status'] == 'Proses') $badgeClass = 'bg-primary';
                        if($row['status'] == 'Ditolak') $badgeClass = 'bg-danger';
                ?>
                
                    <div class="card laporan-card p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="laporan-title"><?= htmlspecialchars($row['judul']); ?></h5>
                                
                                <div class="laporan-meta mt-2">
                                    <span>
                                        <i class="fas fa-calendar-alt"></i> 
                                        <?= date('d M Y', strtotime($row['tgl_pengaduan'])); ?>
                                    </span>
                                    <span>
                                        <i class="fas fa-tag"></i> 
                                        <?= $row['jenis_laporan']; ?>
                                    </span>
                                </div>
                            </div>
                            
                            <span class="badge badge-status <?= $badgeClass; ?>">
                                <?= $row['status']; ?>
                            </span>
                        </div>

                        <div class="divider-soft"></div>

                        <p class="laporan-desc">
                            <?= htmlspecialchars(substr($row['deskripsi'], 0, 150)); ?>...
                        </p>

                        <div class="d-flex justify-content-end mt-2">
                            <a href="detail_laporan.php?id=<?= $row['id_laporan']; ?>" class="btn btn-detail">
                                Lihat Detail <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>

                <?php 
                    endwhile; 
                else:
                ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h5>Belum Ada Laporan</h5>
                        <p>Anda belum pernah mengajukan laporan atau izin apapun.</p>
                        <a href="index.php" class="btn btn-danger rounded-pill px-4 mt-3">Buat Laporan Baru</a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<?php require 'footer.php'; ?>