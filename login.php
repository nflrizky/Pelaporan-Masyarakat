<?php
session_start();
require 'koneksi.php';

$error = "";

if (isset($_POST['login'])) {
    $role     = $_POST['role'];       // 'masyarakat' atau 'petugas'
    $email    = $_POST['email'];
    $password = $_POST['password'];   // untuk tugas boleh plain text

    if ($role == 'petugas') {
        // LOGIN PETUGAS / ADMIN (sama-sama di tabel petugas)
        $sql  = "SELECT * FROM petugas 
                 WHERE email='$email' AND password='$password'";
        $res  = mysqli_query($conn, $sql);

        if (mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);

            // simpan role dari kolom role (admin/petugas)
            $_SESSION['role']         = $row['role']; // pastikan ada kolom role di tabel petugas
            $_SESSION['id_petugas']   = $row['id_petugas'];
            $_SESSION['nama_petugas'] = $row['nama_petugas'];

            // arahkan ke index.php (nanti akses dibatasi lagi di masing2 halaman)
            header("Location: index.php");
            exit;
        } else {
            $error = "Email atau password petugas/admin salah.";
        }

    } else {
        // LOGIN MASYARAKAT
        $sql  = "SELECT * FROM masyarakat 
                 WHERE email='$email' AND password='$password'";
        $res  = mysqli_query($conn, $sql);

        if (mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);

            $_SESSION['role']            = 'masyarakat';
            $_SESSION['id_masyarakat']   = $row['id_masyarakat'];
            $_SESSION['nama_masyarakat'] = $row['nama'];

            header("Location: index.php");
            exit;
        } else {
            $error = "Email atau password masyarakat salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Sistem Pelaporan Masyarakat</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="card shadow" style="width: 400px;">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">Login Pelaporan Masyarakat</h4>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email"
                           class="form-control" placeholder="contoh: user@mail.com" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password"
                           class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>Login sebagai</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio"
                               name="role" id="roleMasyarakat" value="masyarakat" checked>
                        <label class="form-check-label" for="roleMasyarakat">Masyarakat</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio"
                               name="role" id="rolePetugas" value="petugas">
                        <label class="form-check-label" for="rolePetugas">Petugas/Admin</label>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100">
                    Login
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
