<?php
require 'koneksi.php';
require 'header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'masyarakat') {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id_masyarakat'];

// PROSES UPDATE
if (isset($_POST['update'])) {
    $nama   = htmlspecialchars($_POST['nama']);
    // Kita tetap pakai variabel $telp untuk menampung inputan, tapi nanti disimpan ke kolom no_hp
    $telp   = htmlspecialchars($_POST['telp']); 
    $alamat = htmlspecialchars($_POST['alamat']);
    $pass   = $_POST['password'];

    // Cek apakah ganti password
    if (!empty($pass)) {
        // PERBAIKAN: Hapus "telp='$telp'," karena kolom itu tidak ada di database.
        // Gunakan no_hp='$telp'
        $sql = "UPDATE masyarakat SET nama='$nama', no_hp='$telp', alamat='$alamat', password='$pass' WHERE id_masyarakat='$id'";
    } else {
        // PERBAIKAN: Sama seperti diatas, hapus "telp='$telp',"
        $sql = "UPDATE masyarakat SET nama='$nama', no_hp='$telp', alamat='$alamat' WHERE id_masyarakat='$id'";
    }

    if (mysqli_query($conn, $sql)) {
        $_SESSION['nama_masyarakat'] = $nama; // Update session nama
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profil.php';</script>";
    } else {
        echo "<script>alert('Gagal update. Error: " . mysqli_error($conn) . "');</script>";
    }
}

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM masyarakat WHERE id_masyarakat='$id'"));
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-white fw-bold">
                    <i class="fas fa-user-edit me-2"></i> Edit Profil Saya
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted small">NIK</label>
                            <input type="text" class="form-control bg-light" value="<?= $data['nik']; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= $data['nama']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor HP / WA</label>
                            <input type="text" name="telp" class="form-control" value="<?= $data['no_hp']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3"><?= $data['alamat']; ?></textarea>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ganti Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti password">
                        </div>
                        <button type="submit" name="update" class="btn btn-primary w-100">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>