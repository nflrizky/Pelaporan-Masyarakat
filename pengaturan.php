<?php
require 'koneksi.php';
require 'header.php';

// CEK AKSES: Hanya Petugas/Admin yang boleh akses
if (!isset($_SESSION['role']) || $_SESSION['role'] == 'masyarakat') {
    echo "<script>alert('Akses ditolak!'); window.location='index.php';</script>";
    exit;
}

// AMBIL ID DARI SESSION
$id_petugas = $_SESSION['id_petugas'];

// --- LOGIKA 1: UPDATE PROFIL ---
if (isset($_POST['simpan_profil'])) {
    $nama  = htmlspecialchars($_POST['nama_petugas']);
    $email = htmlspecialchars($_POST['email']); // Ganti username jadi email
    $telp  = htmlspecialchars($_POST['telp']);

    // Query Update (Pastikan kolom 'telp' sudah dibuat di database)
    $sql = "UPDATE petugas SET nama_petugas='$nama', email='$email', telp='$telp' WHERE id_petugas='$id_petugas'";
    
    if (mysqli_query($conn, $sql)) {
        // Update session agar nama di navbar langsung berubah
        $_SESSION['nama_petugas'] = $nama;
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='pengaturan.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui profil: " . mysqli_error($conn) . "');</script>";
    }
}

// --- LOGIKA 2: GANTI PASSWORD ---
if (isset($_POST['ganti_password'])) {
    $pass_lama  = $_POST['pass_lama']; 
    $pass_baru  = $_POST['pass_baru'];
    $konfirmasi = $_POST['konfirmasi_pass'];

    // Cek password lama di database
    $cek = mysqli_query($conn, "SELECT password FROM petugas WHERE id_petugas='$id_petugas'");
    $data_pass = mysqli_fetch_assoc($cek);

    // Validasi Password Lama (Langsung string compare sesuai login_petugas.php)
    if ($pass_lama == $data_pass['password']) {
        
        if ($pass_baru == $konfirmasi) {
            // Update password baru
            $update = mysqli_query($conn, "UPDATE petugas SET password='$pass_baru' WHERE id_petugas='$id_petugas'");
            if ($update) {
                echo "<script>alert('Password berhasil diganti! Silakan login ulang.'); window.location='logout.php';</script>";
            } else {
                echo "<script>alert('Gagal mengganti password.');</script>";
            }
        } else {
            echo "<script>alert('Konfirmasi password baru tidak cocok!');</script>";
        }

    } else {
        echo "<script>alert('Password lama salah!');</script>";
    }
}

// AMBIL DATA TERBARU
$q_data = mysqli_query($conn, "SELECT * FROM petugas WHERE id_petugas='$id_petugas'");
$d = mysqli_fetch_assoc($q_data);

// Mencegah error jika kolom telp belum ada/kosong
$telp_value = isset($d['telp']) ? $d['telp'] : ''; 
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-cogs fa-2x text-primary me-3"></i>
                    <h2 class="fw-bold mb-0">Pengaturan Akun</h2>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom p-0">
                        <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active py-3 fw-bold border-0" id="profil-tab" data-bs-toggle="tab" data-bs-target="#profil" type="button">
                                    <i class="fas fa-user-edit me-2"></i>Edit Profil
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-3 fw-bold border-0" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button">
                                    <i class="fas fa-key me-2"></i>Ganti Password
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <div class="tab-content" id="myTabContent">
                            
                            <div class="tab-pane fade show active" id="profil" role="tabpanel">
                                <h5 class="mb-4 text-muted">Perbarui Informasi Pribadi</h5>
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nama Lengkap</label>
                                        <input type="text" name="nama_petugas" class="form-control" value="<?= htmlspecialchars($d['nama_petugas']); ?>" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Email (Untuk Login)</label>
                                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($d['email']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">No. Telepon</label>
                                            <input type="text" name="telp" class="form-control" value="<?= htmlspecialchars($telp_value); ?>" placeholder="08xx">
                                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                                *Jika error, pastikan kolom 'telp' sudah ada di database.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-end">
                                        <button type="submit" name="simpan_profil" class="btn btn-primary px-4 rounded-pill">
                                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="password" role="tabpanel">
                                <h5 class="mb-4 text-muted">Keamanan Akun</h5>
                                <div class="alert alert-warning small">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Pastikan password baru Anda kuat.
                                </div>
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Password Lama</label>
                                        <input type="password" name="pass_lama" class="form-control" required placeholder="Masukkan password saat ini">
                                    </div>
                                    <hr class="my-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Password Baru</label>
                                        <input type="password" name="pass_baru" class="form-control" required placeholder="Minimal 6 karakter">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Ulangi Password Baru</label>
                                        <input type="password" name="konfirmasi_pass" class="form-control" required placeholder="Ketik ulang password baru">
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" name="ganti_password" class="btn btn-danger px-4 rounded-pill">
                                            <i class="fas fa-check-circle me-2"></i>Ganti Password
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php require 'footer.php'; ?>