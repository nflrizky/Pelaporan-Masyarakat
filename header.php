<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Ambil data pengaturan desa (opsional, default string jika gagal)
// require 'koneksi.php'; // Pastikan koneksi dipanggil di file induk
$nama_desa = "Sistem Lapor Desa"; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izin Lapor</title>
    <link rel="icon" type="image/png" href="foto/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --warna-utama: #2c7be5; --warna-hover: #1a5cba; } /* Warna Biru Desa */
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6f9; }
        .navbar-desa { background-color: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .navbar-brand { font-weight: 700; color: var(--warna-utama) !important; }
        .nav-link { color: #555 !important; font-weight: 500; }
        .nav-link:hover, .nav-link.active { color: var(--warna-utama) !important; }
        .btn-utama { background-color: var(--warna-utama); color: white; border-radius: 50px; padding: 8px 25px; }
        .btn-utama:hover { background-color: var(--warna-hover); color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-desa sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="index.php">
     <i class="fas fa-clipboard-list me-2"></i> Izin Lapor
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="panduan.php">Panduan</a></li>
        
        <?php if (!isset($_SESSION['role']) || $_SESSION['role'] == 'masyarakat'): ?>
            <li class="nav-item"><a class="nav-link" href="lihat_laporan.php">Laporan Publik</a></li>
        <?php endif; ?>
        
        <?php if (!isset($_SESSION['role'])): ?>
            <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
            <li class="nav-item ms-2">
                <a class="btn btn-outline-primary rounded-pill px-4" href="login.php">Masuk</a>
            </li>
            <li class="nav-item ms-2">
                <a class="btn btn-utama" href="register.php">Daftar</a> </li>

        <?php elseif ($_SESSION['role'] == 'masyarakat'): ?>
            <li class="nav-item"><a class="nav-link" href="buat_laporan.php">Buat Laporan</a></li>
            <li class="nav-item dropdown ms-2">
                <a class="nav-link dropdown-toggle btn btn-light px-3 rounded-pill" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-1"></i> <?= $_SESSION['nama_masyarakat'] ?? 'Warga'; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><a class="dropdown-item" href="profil.php"><i class="fas fa-id-card me-2"></i>Profil Saya</a></li>
                    <li><a class="dropdown-item" href="riwayat_laporan.php"><i class="fas fa-history me-2"></i>Laporan Saya</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Keluar</a></li>
                </ul>
            </li>

        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="dashboard_admin.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="kelola_laporan.php">Kelola Laporan</a></li>
            <li class="nav-item dropdown ms-2">
                <a class="nav-link dropdown-toggle btn btn-primary text-white px-3 rounded-pill" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-shield me-1"></i> Admin
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><a class="dropdown-item" href="manajemen_user.php">Data Warga</a></li>
                    <li><a class="dropdown-item" href="pengaturan.php">Pengaturan</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php">Keluar</a></li>
                </ul>
            </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>