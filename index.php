<?php
require 'koneksi.php';
require 'header.php';

// Mengambil data statistik singkat
$get_masyarakat = mysqli_query($conn, "SELECT * FROM masyarakat");
$jml_masyarakat = mysqli_num_rows($get_masyarakat);

$get_laporan = mysqli_query($conn, "SELECT * FROM laporan");
$jml_laporan = mysqli_num_rows($get_laporan);

$get_selesai = mysqli_query($conn, "SELECT * FROM laporan WHERE status='Selesai'");
$jml_selesai = mysqli_num_rows($get_selesai);
?>

<div class="bg-primary text-white py-5 mb-5" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 text-center text-lg-start">
                <h1 class="display-4 fw-bold mb-3">Layanan Aspirasi & Pengaduan Desa</h1>
                <p class="lead mb-4 text-white-50">Sampaikan laporan Anda terkait infrastruktur, pelayanan, atau kejadian di sekitar Anda. Kami siap melayani demi kemajuan desa bersama.</p>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center justify-content-lg-start">
                    <?php if (isset($_SESSION['role'])): ?>
                        <a href="buat_laporan.php" class="btn btn-light btn-lg px-4 fw-bold text-primary rounded-pill shadow-sm">
                            <i class="fas fa-pen me-2"></i>Tulis Laporan
                        </a>
                        <a href="riwayat_laporan.php" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                            Riwayat Saya
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-light btn-lg px-4 fw-bold text-primary rounded-pill shadow-sm">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk untuk Melapor
                        </a>
                        <a href="register.php" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                            Daftar Akun
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 text-center">
    <img src="foto/ilustrasi_depan.png" 
         class="img-fluid opacity-90" 
         style="max-height: 350px; transform: rotate(0deg); filter: drop-shadow(0 10px 5px rgba(0,0,0,0.2));" 
         alt="Ilustrasi Layanan">
</div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 text-center py-4 hover-card">
                <div class="card-body">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex p-3 mb-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h3 class="fw-bold"><?= $jml_masyarakat; ?></h3>
                    <p class="text-muted mb-0">Masyarakat Terdaftar</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 text-center py-4 hover-card">
                <div class="card-body">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex p-3 mb-3">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                    <h3 class="fw-bold"><?= $jml_laporan; ?></h3>
                    <p class="text-muted mb-0">Total Laporan Masuk</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 text-center py-4 hover-card">
                <div class="card-body">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-3 mb-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h3 class="fw-bold"><?= $jml_selesai; ?></h3>
                    <p class="text-muted mb-0">Laporan Diselesaikan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Alur Pengaduan</h2>
            <p class="text-muted">Proses pelaporan cepat, transparan, dan mudah.</p>
        </div>
        
        <div class="row g-4 text-center justify-content-center position-relative">
            <div class="col-md-3 position-relative" style="z-index: 1;">
                <div class="pt-3"> <div class="bg-light border border-4 border-primary rounded-circle d-inline-flex p-3 mb-3 text-primary">
                        <i class="fas fa-pen-fancy fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mt-2">1. Tulis Laporan</h5>
                    <p class="small text-muted mb-0">Laporkan keluhan atau aspirasi Anda dengan jelas dan lengkap.</p>
                </div>
            </div>

            <div class="col-md-3 position-relative" style="z-index: 1;">
                <div class="pt-3">
                    <div class="bg-light border border-4 border-primary rounded-circle d-inline-flex p-3 mb-3 text-primary">
                        <i class="fas fa-search fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mt-2">2. Verifikasi</h5>
                    <p class="small text-muted mb-0">Laporan diverifikasi kebenarannya oleh petugas terkait.</p>
                </div>
            </div>

            <div class="col-md-3 position-relative" style="z-index: 1;">
                <div class="pt-3">
                    <div class="bg-light border border-4 border-primary rounded-circle d-inline-flex p-3 mb-3 text-primary">
                        <i class="fas fa-tools fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mt-2">3. Tindak Lanjut</h5>
                    <p class="small text-muted mb-0">Petugas melakukan penanganan masalah secara langsung.</p>
                </div>
            </div>

            <div class="col-md-3 position-relative" style="z-index: 1;">
                <div class="pt-3">
                    <div class="bg-success text-white border border-4 border-white rounded-circle d-inline-flex p-3 mb-3 shadow-sm">
                        <i class="fas fa-check fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mt-2 text-success">4. Selesai</h5>
                    <p class="small text-muted mb-0">Laporan selesai dan Anda akan menerima notifikasi.</p>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require 'footer.php'; ?>