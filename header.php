<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izin Lapor Ketua</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* --- TEMA UTAMA --- */
        :root {
            --warna-utama: #d71149;
            --warna-hover: #b30d3b;
            --warna-bg: #f5f7fb;
        }

        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: var(--warna-bg);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar Style */
        .navbar-custom {
            background: linear-gradient(90deg, var(--warna-utama) 0%, var(--warna-hover) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand { font-weight: 800; letter-spacing: 1px; }

        /* Footer */
        footer {
            margin-top: auto;
            background: #2c3e50;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        /* ===============================
           CSS KHUSUS HALAMAN LIHAT LAPORAN
           (Sesuai Request Anda)
           =============================== */

        .laporan-section {
            min-height: 80vh;
            background: #f5f7fb;
        }

        /* ===== Judul Section ===== */
        .section-heading {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 2rem; /* Sedikit diperlebar agar rapi */
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
        }

        .section-heading-text span {
            font-size: .9rem;
            color: #6c757d;
        }

        /* ===== Card Laporan ===== */
        .laporan-card {
            border-radius: 18px;
            border: none;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
            transition: all .2s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        /* PERBAIKAN: Menambahkan -webkit-mask untuk browser Chrome/Edge */
        .laporan-card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            border: 2px solid transparent; /* Fallback jika mask tidak jalan */
            background: linear-gradient(135deg, rgba(255, 65, 108, .18), rgba(255, 75, 43, .18)) border-box;
            
            /* Masking Logic */
            -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            
            opacity: 0;
            transition: opacity .2s ease;
            pointer-events: none; /* Agar klik tembus ke kartu */
        }

        .laporan-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 35px rgba(15, 23, 42, 0.12);
        }

        .laporan-card:hover::before {
            opacity: 1;
        }

        /* ===== Isi Laporan ===== */
        .laporan-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #e63946;
        }

        .laporan-meta {
            font-size: .85rem;
            color: #6c757d;
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
        }

        .laporan-meta i {
            margin-right: .25rem;
        }

        .laporan-desc {
            font-size: .92rem;
            color: #4b5563;
        }

        /* ===== Badge Status ===== */
        .badge-status {
            font-size: .8rem;
            padding: .35rem .9rem;
            border-radius: 999px;
            color: white; /* Pastikan teks putih */
        }
        .badge-status.bg-secondary { background-color: #6c757d !important; }
        .badge-status.bg-primary { background-color: #0d6efd !important; }
        .badge-status.bg-success { background-color: #16a34a !important; }
        .badge-status.bg-danger { background-color: #dc2626 !important; }

        /* ===== Button ===== */
        .btn-detail {
            border-radius: 999px;
            font-size: .9rem;
            font-weight: 600;
            padding-inline: 1.6rem;
            border: 1px solid #e63946;
            color: #e63946;
            text-decoration: none;
        }
        .btn-detail:hover {
            background-color: #e63946;
            color: white;
        }
        .btn-detail i { transition: transform .18s ease; }
        .btn-detail:hover i { transform: translateX(3px); }

        /* ===== Divider ===== */
        .divider-soft {
            height: 1px;
            width: 100%;
            background: linear-gradient(to right, transparent, rgba(148, 163, 184, .5), transparent);
            margin: 1.25rem 0;
        }

        /* ===== Empty State ===== */
        .empty-state {
            max-width: 480px;
            margin: 4rem auto 2rem;
            text-align: center;
        }
        .empty-state-icon {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: #ffe4e9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e11d48;
            font-size: 2rem;
            margin: 0 auto 1rem;
        }
        .empty-state h5 { font-weight: 700; margin-bottom: .35rem; }
        .empty-state p { color: #6b7280; font-size: .95rem; }

        /* ===== Responsive ===== */
        @media (max-width: 576px) {
            .laporan-card { border-radius: 14px; }
            .laporan-title { font-size: 1rem; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">
        <i class="fas fa-bullhorn me-2"></i>Izin Lapor Ketua
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="lihat_laporan.php">Riwayat Izin</a></li>
        
        <?php if (isset($_SESSION['role'])): ?>
            <li class="nav-item dropdown ms-lg-3">
                <a class="nav-link dropdown-toggle btn btn-outline-light px-3 rounded-pill" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-1"></i>
                    <?= $_SESSION['nama_masyarakat'] ?? $_SESSION['nama_petugas'] ?? 'User'; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li class="nav-item ms-lg-3">
                <a class="nav-link btn btn-light text-danger px-4 rounded-pill fw-bold" href="login.php">Masuk</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>