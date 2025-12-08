<?php
require 'koneksi.php';
require 'header.php';

// Cek Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses Ditolak!'); window.location='index.php';</script>";
    exit;
}

// TAMBAH PETUGAS
if (isset($_POST['tambah'])) {
    $nama  = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']); // Tambahan Email
    $user  = htmlspecialchars($_POST['username']);
    $pass  = $_POST['password'];
    $telp  = htmlspecialchars($_POST['telp']);
    $role  = $_POST['role'];

    // Cek email/username kembar
    $cek = mysqli_query($conn, "SELECT * FROM petugas WHERE email='$email' OR username='$user'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Email atau Username sudah terpakai!');</script>";
    } else {
        $sql = "INSERT INTO petugas (nama_petugas, email, username, password, telp, role) 
                VALUES ('$nama', '$email', '$user', '$pass', '$telp', '$role')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Petugas berhasil ditambahkan!'); window.location='manajemen_petugas.php';</script>";
        } else {
            echo "<script>alert('Gagal: ".mysqli_error($conn)."');</script>";
        }
    }
}

// HAPUS PETUGAS
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM petugas WHERE id_petugas='$id'");
    echo "<script>window.location='manajemen_petugas.php';</script>";
}
?>

<div class="container py-5">
    <h3 class="fw-bold mb-4">Manajemen Petugas</h3>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Tambah Petugas Baru</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-2">
                            <label class="small fw-bold">Nama Petugas</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        
                        <div class="mb-2">
                            <label class="small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="contoh@desa.id" required>
                        </div>

                        <div class="mb-2">
                            <label class="small fw-bold">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="small fw-bold">Password</label>
                            <input type="text" name="password" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="small fw-bold">No. Telp</label>
                            <input type="text" name="telp" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">Role / Jabatan</label>
                            <select name="role" class="form-select">
                                <option value="petugas">Petugas Biasa</option>
                                <option value="admin">Admin (Full Akses)</option>
                            </select>
                        </div>
                        <button type="submit" name="tambah" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3">Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $q = mysqli_query($conn, "SELECT * FROM petugas ORDER BY role ASC");
                                while ($r = mysqli_fetch_assoc($q)):
                                ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold"><?= $r['nama_petugas']; ?></div>
                                        <small class="text-muted"><?= $r['username']; ?></small>
                                    </td>
                                    <td><?= $r['email']; ?></td>
                                    <td>
                                        <?php if($r['role']=='admin'): ?>
                                            <span class="badge bg-danger">Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">Petugas</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="manajemen_petugas.php?hapus=<?= $r['id_petugas']; ?>" 
                                           class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus petugas ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>