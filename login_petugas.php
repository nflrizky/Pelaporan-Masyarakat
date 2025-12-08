<?php
ob_start(); // PENTING: Mengatasi error "Headers already sent"
session_start();
require 'koneksi.php';

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'petugas' || $_SESSION['role'] == 'admin') {
        header("Location: dashboard_admin.php");
        exit;
    }
}

$error = "";

// LOGIKA LOGIN
if (isset($_POST['login'])) {
    
    // Bersihkan input agar aman dari SQL Injection
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); 
    // Catatan: Jika di database password terenkripsi md5, gunakan: $password = md5($_POST['password']);

    // Cek Database
    $sql = "SELECT * FROM petugas WHERE email='$email' AND password='$password'";
    $res = mysqli_query($conn, $sql);

    // Cek apakah query berhasil dijalankan?
    if (!$res) {
        // Jika query gagal (misal tabel tidak ditemukan), tampilkan error
        die("Query Error: " . mysqli_error($conn)); 
    }

    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        
        // Set Session
        $_SESSION['role'] = 'petugas'; 
        $_SESSION['id_petugas'] = $row['id_petugas'];
        $_SESSION['nama_petugas'] = $row['nama_petugas'];
        $_SESSION['username'] = $row['username'];

        // Redirect Sukses
        header("Location: dashboard_admin.php");
        exit;
    } else {
        $error = "Email atau Password salah!";
    }
}

require 'header.php'; 
?>

<section class="py-5" style="background-color: #e9ecef; min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h4 class="fw-bold mb-0"><i class="fas fa-user-shield me-2"></i>Login Petugas</h4>
                        <p class="mb-0 small opacity-75">Area Khusus Admin & Perangkat Desa</p>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i><?= $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Petugas</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope text-primary"></i></span>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="contoh@desa.id" required autofocus>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-lock text-primary"></i></span>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukan password" required>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" name="login" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                                    Masuk Dashboard <i class="fas fa-sign-in-alt ms-2"></i>
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <a href="index.php" class="text-decoration-none text-muted small">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda
                            </a>
                        </div>

                    </div>
                </div>

                <div class="text-center mt-3 text-muted small">
                    &copy; <?= date('Y'); ?> Sistem Informasi Desa. All rights reserved.
                </div>

            </div>
        </div>
    </div>
</section>

<?php 
require 'footer.php'; 
ob_end_flush(); // Selesaikan buffering
?>