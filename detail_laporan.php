<?php
session_start();
require 'koneksi.php';
require 'fungsi_status.php';

// 1. Belum login -> ke login
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

// 2. Kalau login sebagai masyarakat -> tolak & balik ke index
if ($_SESSION['role'] === 'masyarakat') {
    echo "<script>
            alert('Anda tidak memiliki akses ke halaman ini. Halaman ini hanya untuk Admin dan Petugas.');
            window.location.href = 'index.php';
          </script>";
    exit;
}

// 3. Kalau bukan admin dan bukan petugas (misal role aneh) -> tolak juga
if (!in_array($_SESSION['role'], ['admin', 'petugas'])) {
    echo "<script>
            alert('Akses ditolak!');
            window.location.href = 'index.php';
          </script>";
    exit;
}

// ====== lanjut kode detail_laporan di bawah ini ======
$id_laporan = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_laporan <= 0) {
    die('ID laporan tidak valid');
}

$sql  = "SELECT l.*, k.jenis_laporan, m.nama AS nama_pelapor
         FROM laporan l
         JOIN kategori k ON l.id_kategori = k.id_kategori
         JOIN masyarakat m ON l.id_masyarakat = m.id_masyarakat
         WHERE l.id_laporan = '$id_laporan'";
$hasil   = mysqli_query($conn, $sql);
$laporan = mysqli_fetch_assoc($hasil);

if (!$laporan) {
    die('Laporan tidak ditemukan');
}

$allowed         = allowedTransitions();
$status_sekarang = $laporan['status'];
$status_boleh    = $allowed[$status_sekarang];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Laporan</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h3>Detail Laporan</h3>
    <p><b>Judul:</b> <?= $laporan['judul']; ?></p>
    <p><b>Pelapor:</b> <?= $laporan['nama_pelapor']; ?></p>
    <p><b>Kategori:</b> <?= $laporan['jenis_laporan']; ?></p>
    <p><b>Status:</b> <span class="text-dark"><?= $laporan['status']; ?></span></p>
    <p><b>Deskripsi:</b><br><?= nl2br($laporan['deskripsi']); ?></p>

    <hr>
    <h5>Ubah Status</h5>

    <?php if (empty($status_boleh)) : ?>
        <p>Status sudah <b>final</b>, tidak dapat diubah lagi.</p>
    <?php else : ?>
        <form action="proses_status.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_laporan" value="<?= $id_laporan; ?>">
            <input type="hidden" name="status_lama" value="<?= $status_sekarang; ?>">

            <div class="form-group">
                <label>Status Baru</label>
                <select name="status_baru" class="form-control">
                    <?php foreach ($status_boleh as $s) : ?>
                        <option value="<?= $s; ?>"><?= $s; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Bukti (opsional)</label>
                <input type="file" name="bukti" class="form-control-file">
            </div>

            <button type="submit" name="simpan_status" class="btn btn-primary">
                Simpan Perubahan
            </button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    <?php endif; ?>

    <hr>
    <h5>Riwayat Status</h5>
    <?php
    $qT = "SELECT t.*, p.nama_petugas 
           FROM tanggapan t
           JOIN petugas p ON t.id_petugas = p.id_petugas
           WHERE t.id_laporan = '$id_laporan'
           ORDER BY t.waktu_tanggapan ASC";
    $rT = mysqli_query($conn, $qT);
    ?>

    <ul class="list-group">
        <?php while ($t = mysqli_fetch_assoc($rT)) : ?>
            <li class="list-group-item">
                <b><?= $t['waktu_tanggapan']; ?> - <?= $t['status_set']; ?></b><br>
                Petugas: <?= $t['nama_petugas']; ?><br>
                Catatan: <?= nl2br($t['catatan']); ?><br>
                <?php if (!empty($t['bukti'])): ?>
                    Bukti: <a href="upload/<?= $t['bukti']; ?>" target="_blank">Lihat</a>
                <?php endif; ?>
            </li>
        <?php endwhile; ?>
    </ul>

</div>
</body>
</html>
