<?php
require 'koneksi.php';
require 'header.php';

$id = $_GET['id'];

// PROSES KIRIM TANGGAPAN / UBAH STATUS (HANYA ADMIN)
if (isset($_POST['proses_admin'])) {
    if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
        die("Akses ditolak.");
    }
    
    $status_baru = $_POST['status'];
    $tanggapan   = htmlspecialchars($_POST['tanggapan']);
    $id_petugas  = $_SESSION['id_petugas'];
    $tgl_respon  = date('Y-m-d');

    // 1. Update status laporan
    mysqli_query($conn, "UPDATE laporan SET status='$status_baru' WHERE id_laporan='$id'");

    // 2. Insert ke tabel tanggapan
    if (!empty($tanggapan)) {
        $sql_tanggapan = "INSERT INTO tanggapan (id_laporan, tgl_tanggapan, tanggapan, id_petugas) 
                          VALUES ('$id', '$tgl_respon', '$tanggapan', '$id_petugas')";
        mysqli_query($conn, $sql_tanggapan);
    }

    echo "<script>alert('Laporan berhasil diproses!'); window.location='detail_laporan.php?id=$id';</script>";
}

// AMBIL DATA LAPORAN
$sql = "SELECT l.*, k.jenis_laporan, m.nama AS nama_pelapor, m.nik, m.no_hp
        FROM laporan l 
        LEFT JOIN kategori k ON l.id_kategori = k.id_kategori
        LEFT JOIN masyarakat m ON l.id_masyarakat = m.id_masyarakat
        WHERE l.id_laporan = '$id'";
$query = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($query);

// Cek privasi: Jika rahasia, sembunyikan nama pelapor untuk user biasa (selain admin & pemilik)
$is_admin = (isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'petugas'));
$is_owner = (isset($_SESSION['id_masyarakat']) && $_SESSION['id_masyarakat'] == $data['id_masyarakat']);

if ($data['tipe_laporan'] == 'Rahasia' && !$is_admin && !$is_owner) {
    // Redirect atau sembunyikan
    // Opsional: header("Location: index.php"); exit; 
    // Disini kita samarkan saja:
    $data['nama_pelapor'] = "Pelapor Rahasia";
    $data['nik'] = "********";
    $data['no_hp'] = "-";
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary rounded-pill"><?= $data['jenis_laporan']; ?></span>
                        <span class="text-muted small"><i class="fas fa-clock me-1"></i> <?= date('d M Y', strtotime($data['tgl_pengaduan'])); ?></span>
                    </div>
                    
                    <h3 class="fw-bold mb-3"><?= htmlspecialchars($data['judul']); ?></h3>
                    
                    <?php if(!empty($data['foto'])): ?>
                        <img src="foto/<?= $data['foto']; ?>" class="img-fluid rounded mb-4 w-100" style="max-height: 400px; object-fit: cover;">
                    <?php endif; ?>

                    <h5 class="fw-bold text-secondary">Deskripsi:</h5>
                    <p style="white-space: pre-line;"><?= htmlspecialchars($data['deskripsi']); ?></p>

                    <hr>
                    <div class="row text-muted small">
                        <div class="col-md-6">
                            <strong>Lokasi:</strong> <br> <?= htmlspecialchars($data['lokasi']); ?>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <strong>Status Saat Ini:</strong> <br> 
                            <span class="badge bg-dark"><?= $data['status']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow border-0 mt-4">
                <div class="card-header bg-white fw-bold"><i class="fas fa-comments me-2"></i> Tindak Lanjut & Tanggapan</div>
                <div class="card-body">
                    <?php
                    $sqlt = "SELECT t.*, p.nama_petugas FROM tanggapan t 
                             JOIN petugas p ON t.id_petugas = p.id_petugas 
                             WHERE t.id_laporan='$id' ORDER BY t.tgl_tanggapan DESC, t.id_tanggapan DESC";
                    $qt = mysqli_query($conn, $sqlt);
                    if(mysqli_num_rows($qt) > 0) {
                        while($t = mysqli_fetch_assoc($qt)) {
                            echo "<div class='alert alert-light border mb-2'>";
                            echo "<strong class='text-primary'>{$t['nama_petugas']}</strong> <small class='text-muted float-end'>".date('d/m/Y', strtotime($t['tgl_tanggapan']))."</small>";
                            echo "<p class='mb-0 mt-1'>{$t['tanggapan']}</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<em class='text-muted'>Belum ada tanggapan dari petugas.</em>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold border-bottom pb-2">Informasi Pelapor</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-user me-2 text-secondary"></i> <?= $data['nama_pelapor']; ?></li>
                        <?php if($is_admin): ?>
                            <li class="mb-2"><i class="fas fa-id-card me-2 text-secondary"></i> <?= $data['nik']; ?></li>
                            <li class="mb-2"><i class="fas fa-phone me-2 text-secondary"></i> <?= $data['no_hp']; ?></li>
                        <?php endif; ?>
                        <li>
                            <?php if($data['tipe_laporan'] == 'Rahasia'): ?>
                                <span class="badge bg-danger w-100 mt-2"><i class="fas fa-lock"></i> Laporan Rahasia</span>
                            <?php else: ?>
                                <span class="badge bg-success w-100 mt-2"><i class="fas fa-globe"></i> Laporan Publik</span>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>

            <?php if($is_admin): ?>
            <div class="card shadow border-0 bg-light">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Verifikasi & Proses</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="fw-bold small">Update Status</label>
                            <select name="status" class="form-select">
                                <option value="Diajukan" <?= ($data['status']=='Diajukan')?'selected':''; ?>>Diajukan (Baru)</option>
                                <option value="Proses" <?= ($data['status']=='Proses')?'selected':''; ?>>Proses (Sedang Dikerjakan)</option>
                                <option value="Selesai" <?= ($data['status']=='Selesai')?'selected':''; ?>>Selesai (Tuntas)</option>
                                <option value="Ditolak" <?= ($data['status']=='Ditolak')?'selected':''; ?>>Ditolak (Tidak Valid)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small">Isi Tanggapan / Catatan</label>
                            <textarea name="tanggapan" class="form-control" rows="4" placeholder="Tulis catatan tindak lanjut disini..."></textarea>
                        </div>
                        <button type="submit" name="proses_admin" class="btn btn-primary w-100 fw-bold">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require 'footer.php'; ?>