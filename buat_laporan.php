<?php
require 'koneksi.php';
require 'header.php';

// Cek Login Warga
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'masyarakat') {
    echo "<script>alert('Harap login sebagai warga untuk melapor!'); window.location='login.php';</script>";
    exit;
}

if (isset($_POST['kirim'])) {
    $judul       = htmlspecialchars($_POST['judul']);
    $kategori    = $_POST['id_kategori'];
    $lokasi      = htmlspecialchars($_POST['lokasi']);
    $deskripsi   = htmlspecialchars($_POST['deskripsi']);
    $tipe        = $_POST['tipe_laporan'];
    $id_warga    = $_SESSION['id_masyarakat'];
    $tgl         = date('Y-m-d');
    $status      = 'Diajukan';

    // Upload Foto
    $foto = '';
    if (!empty($_FILES['foto']['name'])) {
        $foto = time() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], 'foto/' . $foto);
    }

    $sql = "INSERT INTO laporan (id_masyarakat, id_kategori, tgl_pengaduan, judul, deskripsi, foto, status, lokasi, tipe_laporan)
            VALUES ('$id_warga', '$kategori', '$tgl', '$judul', '$deskripsi', '$foto', '$status', '$lokasi', '$tipe')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Laporan Berhasil Dikirim!'); window.location='riwayat_laporan.php';</script>";
    } else {
        echo "<script>alert('Gagal mengirim laporan.');</script>";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4"><i class="fas fa-pen-square text-primary me-2"></i>Buat Laporan Baru</h3>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="fw-bold">Judul Laporan</label>
                            <input type="text" name="judul" class="form-control" placeholder="Contoh: Jalan Berlubang di RT 05" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Kategori</label>
                                <select name="id_kategori" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php
                                    $k = mysqli_query($conn, "SELECT * FROM kategori");
                                    while ($r = mysqli_fetch_assoc($k)) {
                                        echo "<option value='{$r['id_kategori']}'>{$r['jenis_laporan']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Lokasi Kejadian</label>
                                <input type="text" name="lokasi" class="form-control" placeholder="Nama Dusun / RT / RW" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Deskripsi Masalah</label>
                            <textarea name="deskripsi" class="form-control" rows="5" placeholder="Jelaskan detail masalahnya..." required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Bukti Foto (Opsional)</label>
                                <input type="file" name="foto" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Privasi Laporan</label>
                                <select name="tipe_laporan" class="form-select">
                                    <option value="Publik">Publik (Bisa dilihat semua orang)</option>
                                    <option value="Rahasia">Rahasia (Hanya petugas yang tahu)</option>
                                </select>
                                <small class="text-muted">Pilih "Rahasia" jika menyangkut sensitivitas.</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" name="kirim" class="btn btn-primary btn-lg">Kirim Laporan</button>
                            <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>