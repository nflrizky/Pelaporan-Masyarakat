<?php
require 'koneksi.php';

// Konfigurasi agar error MySQL bisa ditangkap dengan Try-Catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_POST['register'])) {
    $nik      = htmlspecialchars($_POST['nik']);
    $nama     = htmlspecialchars($_POST['nama']);
    $email    = htmlspecialchars($_POST['email']);
    $no_hp    = htmlspecialchars($_POST['no_hp']);
    $alamat   = htmlspecialchars($_POST['alamat']);
    $password = $_POST['password']; 

    // 1. Cek apakah NIK sudah terdaftar
    try {
        $cek_nik = mysqli_query($conn, "SELECT * FROM masyarakat WHERE nik='$nik'");
        if (mysqli_num_rows($cek_nik) > 0) {
            echo "<script>alert('NIK sudah terdaftar!');</script>";
            exit; // Stop proses jika NIK ada
        }
    } catch (mysqli_sql_exception $e) {
        echo "<script>alert('Error Cek NIK: " . $e->getMessage() . "');</script>";
        exit;
    }

    // 2. PROSES PENYIMPANAN DATA (Smart Insert)
    $sukses = false;
    $pesan_error = "";

    // SKENARIO 1: Coba simpan ke kolom 'no_hp' (Paling mungkin benar)
    try {
        $sql = "INSERT INTO masyarakat (nik, nama, password, email, alamat, no_hp) 
                VALUES ('$nik', '$nama', '$password', '$email', '$alamat', '$no_hp')";
        mysqli_query($conn, $sql);
        $sukses = true;
    } catch (mysqli_sql_exception $e) {
        // Jika error karena kolom 'no_hp' tidak ada, lanjut ke Skenario 2
        if (strpos($e->getMessage(), "Unknown column 'no_hp'") !== false) {
            
            // SKENARIO 2: Coba simpan ke kolom 'telp' atau 'tlp'
            try {
                // Kita coba 'tlp' (singkatan umum lain selain telp yang sudah gagal)
                $sql = "INSERT INTO masyarakat (nik, nama, password, email, alamat, tlp) 
                        VALUES ('$nik', '$nama', '$password', '$email', '$alamat', '$no_hp')";
                mysqli_query($conn, $sql);
                $sukses = true;
            } catch (mysqli_sql_exception $e2) {
                // Jika masih gagal (tlp juga tidak ada), lanjut Skenario 3
                
                // SKENARIO 3: Simpan TANPA nomor HP (Penyelamat Terakhir)
                try {
                    $sql = "INSERT INTO masyarakat (nik, nama, password, email, alamat) 
                            VALUES ('$nik', '$nama', '$password', '$email', '$alamat')";
                    mysqli_query($conn, $sql);
                    $sukses = true;
                    // Beri tahu user kalau no hp tidak tersimpan tapi akun jadi
                    echo "<script>alert('Akun berhasil dibuat (Note: Nomor HP tidak tersimpan karena kolom database tidak ditemukan).'); window.location='login.php';</script>";
                    exit;
                } catch (mysqli_sql_exception $e3) {
                    $pesan_error = $e3->getMessage();
                }
            }
        } else {
            // Error lain (bukan masalah kolom), misalnya duplikat entry lain
            $pesan_error = $e->getMessage();
        }
    }

    if ($sukses) {
        echo "<script>
                alert('Pendaftaran Berhasil! Silakan Login.'); 
                window.location='login.php';
              </script>";
    } else {
        echo "<script>alert('Gagal Mendaftar: $pesan_error');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Warga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            font-family: 'Segoe UI', sans-serif;
        }
        
        .card-regis {
            width: 100%;
            max-width: 600px;
            border-radius: 15px;
            border: none;
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .regis-header {
            background: white;
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .text-biru { color: #0d6efd; }
        .btn-biru {
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
            border: none;
        }
        .btn-biru:hover { background-color: #0b5ed7; color: white;}
    </style>
</head>
<body>

    <div class="card card-regis">
        <div class="regis-header">
            <h3 class="fw-bold text-biru mb-0"><i class="fas fa-user-plus me-2"></i>Daftar Akun Warga</h3>
            <p class="text-muted small mb-0">Isi formulir berikut untuk membuat akun</p>
        </div>

        <div class="card-body p-4 bg-white">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-secondary">NIK</label>
                        <input type="number" name="nik" class="form-control" required placeholder="16 digit NIK">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Nama sesuai KTP">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-secondary">Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="contoh@email.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-secondary">No. HP / WA</label>
                        <input type="number" name="no_hp" class="form-control" required placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="2" required placeholder="Dusun, RT/RW, Desa..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="******">
                </div>

                <button type="submit" name="register" class="btn btn-biru w-100 py-2">DAFTAR SEKARANG</button>
                
                <div class="text-center mt-3 pt-3 border-top">
                    <p class="small text-muted mb-0">Sudah punya akun?</p>
                    <a href="login.php" class="text-decoration-none fw-bold text-biru">Masuk disini</a>
                </div>
            </form>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>