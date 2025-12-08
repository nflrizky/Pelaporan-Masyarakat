<?php
require 'koneksi.php';

if (isset($_POST['register'])) {
    $nik = htmlspecialchars($_POST['nik']);
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $no_hp = htmlspecialchars($_POST['no_hp']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $password = $_POST['password']; // Password plain text sesuai request awal (bisa di-hash jika perlu)
    $username = $nik; // Username pakai NIK agar unik

    // Cek NIK sudah ada belum
    $cek = mysqli_query($conn, "SELECT * FROM masyarakat WHERE nik='$nik'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('NIK sudah terdaftar!');</script>";
    } else {
        $sql = "INSERT INTO masyarakat (nik, nama, username, password, telp, email, no_hp, alamat) 
                VALUES ('$nik', '$nama', '$username', '$password', '$no_hp', '$email', '$no_hp', '$alamat')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Gagal Mendaftar: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Akun Warga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .card-regis { max-width: 600px; margin: 50px auto; border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="container">
        <div class="card card-regis p-4">
            <h3 class="text-center fw-bold text-primary mb-4">Pendaftaran Warga</h3>
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIK (Nomor Induk Kependudukan)</label>
                        <input type="number" name="nik" class="form-control" required placeholder="16 digit NIK">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="number" name="no_hp" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat Lengkap (Dusun/RT/RW)</label>
                    <textarea name="alamat" class="form-control" rows="2" required></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" name="register" class="btn btn-primary w-100 py-2">Daftar Sekarang</button>
                <div class="text-center mt-3">
                    Sudah punya akun? <a href="login.php">Masuk disini</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>