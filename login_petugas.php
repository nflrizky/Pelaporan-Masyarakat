<?php
require 'koneksi.php';

if (isset($_POST['login'])) {
    $email    = $_POST['email'];
    $password = $_POST['password']; // untuk tugas boleh plain text

    $sql = "SELECT * FROM petugas WHERE email='$email' AND password='$password'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $_SESSION['role']      = 'petugas';
        $_SESSION['id_petugas'] = $row['id_petugas'];
        $_SESSION['nama_petugas'] = $row['nama_petugas'];

        header("Location: index.php"); // dashboard petugas
        exit;
    } else {
        $error = "Email atau password salah";
    }
}
?>

<!-- FORM LOGIN SINGKAT -->
<!DOCTYPE html>
<html>
<head>
    <title>Login Petugas</title>
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
</head>
<body>
<div class="container mt-5" style="max-width:400px">
    <h3 class="text-center">Login Petugas</h3>

    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
    </form>
</div>
</body>
</html>
