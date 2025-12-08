<?php
require 'koneksi.php';
require 'header.php';

// Hitung Statistik Sederhana
$total_laporan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laporan"));
$laporan_selesai = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laporan WHERE status='Selesai'"));
?>

<section class="py-5 bg-white border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-5 fw-bold text-dark mb-3">Layanan Aspirasi & Pengaduan Masyarakat Desa</h1>
                <p class="lead text-muted mb-4">Sampaikan masalah di lingkungan Anda (jalan rusak, keamanan, administrasi) agar segera ditindaklanjuti oleh perangkat desa.</p>
                <div class="d-flex gap-3">
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'masyarakat'): ?>
                        <a href="buat_laporan.php" class="btn btn-utama btn-lg shadow">
                            <i class="fas fa-pen-nib me-2"></i>Buat Laporan Sekarang
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-utama btn-lg shadow">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk untuk Melapor
                        </a>
                        <a href="register.php" class="btn btn-outline-primary btn-lg">Daftar Akun</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 text-center mt-4 mt-lg-0">
                <img src="https://img.freepik.com/free-vector/village-landscape-illustration_1284-58580.jpg" alt="Ilustrasi Desa" class="img-fluid rounded-4 shadow-sm" style="max-height: 400px;">
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row text-center justify-content-center">
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm p-3">
                    <h2 class="fw-bold text-primary"><?= $total_laporan; ?></h2>
                    <p class="text-muted mb-0">Laporan Masuk</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm p-3">
                    <h2 class="fw-bold text-success"><?= $laporan_selesai; ?></h2>
                    <p class="text-muted mb-0">Laporan Selesai</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <h3 class="text-center fw-bold mb-5">Bagaimana Cara Melapor?</h3>
        <div class="row text-center">
            <div class="col-md-3">
                <div class="mb-3"><i class="fas fa-edit fa-3x text-primary"></i></div>
                <h5>1. Tulis Laporan</h5>
                <p class="small text-muted">Laporkan keluhan Anda dengan jelas dan lengkap.</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3"><i class="fas fa-share-square fa-3x text-primary"></i></div>
                <h5>2. Proses Verifikasi</h5>
                <p class="small text-muted">Admin desa akan memverifikasi dan meneruskan laporan.</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3"><i class="fas fa-tools fa-3x text-primary"></i></div>
                <h5>3. Tindak Lanjut</h5>
                <p class="small text-muted">Petugas terkait akan menindaklanjuti laporan Anda.</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3"><i class="fas fa-check-circle fa-3x text-success"></i></div>
                <h5>4. Selesai</h5>
                <p class="small text-muted">Laporan selesai dan Anda mendapat notifikasi.</p>
            </div>
        </div>
    </div>
</section>

<section id="kontak" class="py-5 text-white" style="background: #2c3e50;">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h4>Kantor Kepala Desa</h4>
                <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i> Jl. Raya Desa No. 1, Kecamatan X</p>
                <p class="mb-1"><i class="fas fa-phone me-2"></i> 0812-3456-7890 (WhatsApp)</p>
                <p><i class="fas fa-envelope me-2"></i> admin@desamaju.id</p>
            </div>
            <div class="col-md-6 text-md-end align-self-center">
                <small>&copy; <?= date('Y'); ?> Sistem Informasi Desa. All rights reserved.</small>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>