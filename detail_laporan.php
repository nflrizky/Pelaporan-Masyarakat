<?php
require 'koneksi.php';
require 'header.php';

$id = $_GET['id'];
// Ambil Data Laporan
$sql = "SELECT l.*, k.jenis_laporan, m.nama AS nama_pelapor 
        FROM laporan l 
        LEFT JOIN kategori k ON l.id_kategori = k.id_kategori
        LEFT JOIN masyarakat m ON l.id_masyarakat = m.id_masyarakat
        WHERE l.id_laporan = '$id'";
$query = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($query);

// Warna Status
$statusColor = 'bg-secondary';
if($data['status'] == 'Proses') $statusColor = 'bg-primary';
if($data['status'] == 'Selesai') $statusColor = 'bg-success';
if($data['status'] == 'Ditolak') $statusColor = 'bg-danger';
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <a href="lihat_laporan.php" class="text-decoration-none text-muted mb-3 d-inline-block">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>

            <div class="card shadow border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="fw-bold text-dark mb-1"><?= htmlspecialchars($data['judul']); ?></h3>
                            <div class="text-muted small">
                                <i class="fas fa-user me-1"></i> <?= $data['nama_pelapor']; ?> &bull; 
                                <i class="fas fa-calendar me-1"></i> <?= $data['tgl_pengaduan']; ?>
                            </div>
                        </div>
                        <span class="badge <?= $statusColor; ?> fs-6 px-3 py-2 rounded-pill"><?= $data['status']; ?></span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <h6 class="fw-bold text-merah">ISI LAPORAN</h6>
                    <p class="text-secondary" style="line-height: 1.6; white-space: pre-line;">
                        <?= htmlspecialchars($data['deskripsi']); ?>
                    </p>
                    
                    <div class="mt-4 pt-3 border-top">
                        <span class="badge bg-light text-dark border">
                            <i class="fas fa-tag me-1"></i> Kategori: <?= $data['jenis_laporan']; ?>
                        </span>
                        
                        <?php if(!empty($data['foto'])): ?>
                            <div class="mt-3">
                                <img src="foto/<?= $data['foto']; ?>" class="img-fluid rounded shadow-sm" style="max-height:300px;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-footer bg-light p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-comments"></i> Tindak Lanjut / Tanggapan</h5>
                    
                    <?php 
                    // Ambil Tanggapan
                    $sqlt = "SELECT t.*, p.nama_petugas 
                             FROM tanggapan t 
                             JOIN petugas p ON t.id_petugas = p.id_petugas 
                             WHERE t.id_laporan='$id' ORDER BY t.tgl_tanggapan DESC";
                    $queryt = mysqli_query($conn, $sqlt);
                    
                    if(mysqli_num_rows($queryt) > 0) {
                        while($t = mysqli_fetch_assoc($queryt)){
                    ?>
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong class="text-primary"><i class="fas fa-user-tie"></i> <?= $t['nama_petugas']; ?></strong>
                                    <small class="text-muted"><?= $t['tgl_tanggapan']; ?></small>
                                </div>
                                <p class="mb-0 text-dark"><?= $t['tanggapan']; ?></p>
                            </div>
                        </div>
                    <?php 
                        }
                    } else {
                        echo "<div class='text-muted fst-italic'>Belum ada tanggapan dari petugas.</div>";
                    }
                    ?>
                    
                    <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'petugas')): ?>
                        <hr>
                        <form action="proses_tanggapan.php" method="POST">
                            <input type="hidden" name="id_laporan" value="<?= $id; ?>">
                            <div class="mb-2">
                                <label class="fw-bold small">Beri Tanggapan:</label>
                                <textarea name="tanggapan" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" name="kirim_tanggapan" class="btn btn-primary btn-sm">
                                <i class="fas fa-paper-plane"></i> Kirim Tanggapan
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</div> <footer>
    <div class="container"><small>&copy; 2024 LAPOR!</small></div>