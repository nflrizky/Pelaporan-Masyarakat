<?php
session_start();
require 'koneksi.php';

$error = "";

if (isset($_POST['login'])) {
    $role     = $_POST['role'];       
    $email    = $_POST['email'];
    $password = $_POST['password'];   

    if ($role == 'petugas') {
        // Login Petugas / Ketua
        $sql  = "SELECT * FROM petugas WHERE email='$email' AND password='$password'";
        $res  = mysqli_query($conn, $sql);

        if (mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);
            $_SESSION['role']         = $row['role']; 
            $_SESSION['id_petugas']   = $row['id_petugas'];
            $_SESSION['nama_petugas'] = $row['nama_petugas'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Email atau Password Ketua salah.";
        }

    } else {
        // Login Masyarakat / Anggota
        $sql  = "SELECT * FROM masyarakat WHERE email='$email' AND password='$password'";
        $res  = mysqli_query($conn, $sql);

        if (mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);
            $_SESSION['role']            = 'masyarakat';
            $_SESSION['id_masyarakat']   = $row['id_masyarakat'];
            $_SESSION['nama_masyarakat'] = $row['nama'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Email atau Password Anggota salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Izin Lapor Ketua</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #d71149 0%, #a80a35 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .card-login {
            width: 100%;
            max-width: 400px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
            border: none;
        }
        .login-header {
            background: white;
            padding: 30px 20px 10px;
            text-align: center;
        }
        .text-merah { color: #d71149; }
        .btn-merah {
            background-color: #d71149;
            color: white;
            font-weight: bold;
            border: none;
        }
        .btn-merah:hover { background-color: #b30d3b; color: white;}
    </style>
</head>
<body>

    <div class="card card-login">
        <div class="login-header">
            <h2 class="fw-bold text-merah display-6"><i class="fas fa-clipboard-list"></i> Izin Lapor</h2>
            <p class="text-muted small">Silakan login untuk melanjutkan</p>
        </div>
        
        <div class="card-body p-4 bg-white">
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2 small text-center mb-3">
                    <i class="fas fa-exclamation-circle me-1"></i> <?= $error; ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                        <input type="text" name="email" class="form-control" placeholder="user@mail.com" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="******" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary d-block">Masuk Sebagai:</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="role" id="role1" value="masyarakat" checked>
                            <label class="btn btn-outline-danger w-100 btn-sm py-2" for="role1">Anggota / Staf</label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="role" id="role2" value="petugas">
                            <label class="btn btn-outline-danger w-100 btn-sm py-2" for="role2">Ketua / Pimpinan</label>
                        </div>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-merah w-100 py-2">MASUK</button>
            </form>
            
            <div class="text-center mt-4">
                <a href="index.php" class="text-decoration-none text-secondary small">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>