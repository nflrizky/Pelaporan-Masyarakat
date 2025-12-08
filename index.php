<?php
require 'koneksi.php';
require 'header.php'; // Panggil header

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// LOGIKA TAMBAH LAPORAN
if (isset($_POST['submit_laporan'])) {
    // Cek Login Dulu
    if (!isset($_SESSION['role'])) {
        echo "<script>alert('Anda harus login untuk melapor!'); window.location='login.php';</script>";
        exit;
    }

    $judul       = htmlspecialchars($_POST['judul']);
    $id_kategori = $_POST['id_kategori'];
    $deskripsi   = htmlspecialchars($_POST['deskripsi']);
    $id_masyarakat = $_SESSION['id_masyarakat'] ?? 0; // Ambil ID dari session

    // Jika user adalah petugas, kita block atau set id dummy (opsional)
    if ($_SESSION['role'] != 'masyarakat') {
        echo "<script>alert('Petugas tidak dapat membuat laporan pengaduan.'); window.location='index.php';</script>";
        exit;
    }

    $status = "Diajukan";
    $sql = "INSERT INTO laporan (id_masyarakat, id_kategori, judul, deskripsi, status) 
            VALUES ('$id_masyarakat', '$id_kategori', '$judul', '$deskripsi', '$status')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Laporan Berhasil Dikirim!'); window.location='lihat_laporan.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<section class="py-5" style="background: linear-gradient(135deg, #d71149 0%, #a80a35 100%); color: white; margin-top: -1px;">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold display-5">LAYANAN ASPIRASI DAN PENGADUAN ONLINE RAKYAT</h1>
            <p class="lead opacity-75">Sampaikan laporan Anda langsung kepada instansi pemerintah berwenang</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0" style="border-radius: 15px;">
                    <div class="card-body p-4 text-dark">
                        <div class="border-bottom mb-3 pb-2">
                            <h4 class="text-merah fw-bold"><i class="fas fa-pen-square"></i> Tulis Laporan</h4>
                        </div>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="fw-bold mb-1">Judul Laporan</label>
                                <input type="text" name="judul" class="form-control" placeholder="Ketik Judul Laporan Anda *" required>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold mb-1">Isi Laporan</label>
                                <textarea name="deskripsi" class="form-control" rows="5" placeholder="Ketik Isi Laporan Anda *" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="fw-bold mb-1">Kategori</label>
                                    <select class="form-select" name="id_kategori" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php
                                        $qKat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY jenis_laporan");
                                        while ($k = mysqli_fetch_assoc($qKat)) {
                                            echo "<option value='{$k['id_kategori']}'>{$k['jenis_laporan']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4 d-grid align-items-end mb-3">
                                    <button type="submit" name="submit_laporan" class="btn btn-lapor py-2 shadow">
                                        LAPOR!
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container py-5 text-center">
    <div class="row">
        <div class="col-md-4 mb-4">
            <i class="fas fa-check-circle fa-3x text-merah mb-3"></i>
            <h5>Mudah & Cepat</h5>
            <p class="text-muted">Laporkan keluhan Anda dengan langkah yang sangat mudah.</p>
        </div>
        <div class="col-md-4 mb-4">
            <i class="fas fa-user-shield fa-3x text-merah mb-3"></i>
            <h5>Rahasia Terjamin</h5>
            <p class="text-muted">Identitas pelapor aman dan dilindungi.</p>
        </div>
        <div class="col-md-4 mb-4">
            <i class="fas fa-reply-all fa-3x text-merah mb-3"></i>
            <h5>Pasti Ditanggapi</h5>
            <p class="text-muted">Laporan Anda akan diverifikasi dan ditindaklanjuti.</p>
        </div>
    </div>
</section>

</div> <?php
// Panggil file footer yang baru dibuat
require 'footer.php'; 
?>