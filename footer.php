<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-5 mb-4">
                <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-bullhorn me-2"></i>SISTEM LAPOR KETUA
                </h5>
                <p class="small text-white-50">
                    Wadah digital aspirasi dan pengaduan masyarakat. Sampaikan laporan Anda 
                    terkait pelayanan publik maupun infrastruktur desa secara transparan 
                    dan akuntabel demi kemajuan bersama.
                </p>
            </div>

            <div class="col-md-3 mb-4">
                <h6 class="fw-bold mb-3 text-uppercase small ls-1">Menu Pintas</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <a href="index.php" class="text-decoration-none text-white-50 link-light">
                            <i class="fas fa-home me-2"></i>Beranda
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="panduan.php" class="text-decoration-none text-white-50 link-light">
                            <i class="fas fa-book me-2"></i>Panduan Laporan
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="register.php" class="text-decoration-none text-white-50 link-light">
                            <i class="fas fa-user-plus me-2"></i>Daftar Akun
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="login.php" class="text-decoration-none text-white-50 link-light">
                            <i class="fas fa-sign-in-alt me-2"></i>Login Masyarakat
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-md-4 mb-4">
                <h6 class="fw-bold mb-3 text-uppercase small ls-1">Hubungi Kami</h6>
                <ul class="list-unstyled small text-white-50">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-3"></i>Kantor Desa Sukamaju</li>
                    <li class="mb-2"><i class="fas fa-phone me-3"></i>(021) 123-4567</li>
                    <li class="mb-2"><i class="fas fa-envelope me-3"></i>admin@lapor-desa.id</li>
                </ul>
                <div class="mt-4">
                    <span class="d-block text-white-50 small mb-2">Akses Khusus Petugas:</span>
                    <a href="login_petugas.php" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                        <i class="fas fa-user-shield me-1"></i> Login Admin
                    </a>
                </div>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="row align-items-center small">
            <div class="col-md-6 text-center text-md-start text-white-50">
                &copy; <?= date('Y'); ?> <strong>Sistem Pelaporan Masyarakat</strong>. All rights reserved.
            </div>
            <div class="col-md-6 text-center text-md-end text-white-50">
                Dibuat dengan <i class="fas fa-heart text-danger mx-1"></i> untuk Warga.
            </div>
        </div>
    </div>
</footer>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>