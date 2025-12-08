<?php
// require 'koneksi.php'; // Opsional, aktifkan jika perlu akses database
require 'header.php';
?>

<section class="bg-primary text-white py-5 mb-5" style="background: linear-gradient(45deg, #2c7be5, #1a5cba);">
    <div class="container text-center">
        <h1 class="fw-bold display-5">Panduan & Bantuan</h1>
        <p class="lead mb-0 opacity-75">Informasi cara menggunakan layanan pengaduan masyarakat desa.</p>
    </div>
</section>

<section class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold text-dark"><i class="fas fa-map-signs text-primary me-2"></i>Alur Pengaduan</h2>
                        <p class="text-muted">Ikuti langkah-langkah berikut untuk menyampaikan laporan Anda.</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 50px; height: 50px; font-size: 1.25rem; font-weight: bold;">1</span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold">Login atau Daftar</h5>
                                    <p class="text-muted small">Anda harus memiliki akun untuk melapor. Silakan <a href="register.php">Daftar</a> jika belum punya akun, atau <a href="login.php">Masuk</a> jika sudah terdaftar.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 50px; height: 50px; font-size: 1.25rem; font-weight: bold;">2</span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold">Buat Laporan Baru</h5>
                                    <p class="text-muted small">Klik menu "Buat Laporan". Isi judul, isi laporan secara detail, pilih kategori yang sesuai, dan lampirkan foto bukti jika ada.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 50px; height: 50px; font-size: 1.25rem; font-weight: bold;">3</span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold">Verifikasi & Proses</h5>
                                    <p class="text-muted small">Admin akan memverifikasi laporan Anda. Jika valid, status berubah menjadi <strong>Proses</strong> dan petugas akan menindaklanjuti.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 50px; height: 50px; font-size: 1.25rem; font-weight: bold;">4</span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold">Selesai & Tanggapan</h5>
                                    <p class="text-muted small">Setelah masalah tertangani, laporan ditandai <strong>Selesai</strong>. Anda dapat melihat tanggapan resmi dari petugas di detail laporan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h3 class="fw-bold mb-4"><i class="fas fa-question-circle text-primary me-2"></i>Pertanyaan Umum (FAQ)</h3>
                
                <div class="accordion shadow-sm" id="accordionFaq">
                    
                    <div class="accordion-item border-0 mb-2 rounded overflow-hidden">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Apakah identitas pelapor aman?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFaq">
                            <div class="accordion-body text-muted bg-light">
                                Ya, data pribadi pelapor dijaga kerahasiaannya. Nama Anda hanya muncul di dashboard petugas untuk keperluan verifikasi, namun tidak akan dipublikasikan secara terbuka jika Anda memilih opsi anonim (fitur mendatang) atau sesuai kebijakan desa.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-2 rounded overflow-hidden">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Berapa lama laporan diproses?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFaq">
                            <div class="accordion-body text-muted bg-light">
                                Waktu penanganan bervariasi tergantung jenis laporan dan tingkat urgensinya. Biasanya admin akan merespon/memverifikasi dalam 1x24 jam hari kerja.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-2 rounded overflow-hidden">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Laporan apa saja yang boleh dikirimkan?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFaq">
                            <div class="accordion-body text-muted bg-light">
                                Anda dapat melaporkan hal-hal terkait pelayanan publik desa, infrastruktur (jalan rusak, lampu mati), keamanan, kebersihan, atau administrasi kependudukan. <span class="text-danger">Hindari laporan palsu, SARA, atau ujaran kebencian.</span>
                            </div>
                        </div>
                    </div>

                     <div class="accordion-item border-0 rounded overflow-hidden">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Bagaimana jika laporan saya ditolak?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionFaq">
                            <div class="accordion-body text-muted bg-light">
                                Laporan mungkin ditolak jika: 
                                <ul>
                                    <li>Data tidak lengkap atau tidak jelas.</li>
                                    <li>Bukan wewenang pemerintah desa.</li>
                                    <li>Mengandung unsur provokasi atau hoax.</li>
                                </ul>
                                Silakan perbaiki laporan Anda dan kirim ulang.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mt-5" role="alert">
                <i class="fas fa-headset fa-2x me-3"></i>
                <div>
                    <h5 class="alert-heading fw-bold mb-1">Masih Butuh Bantuan?</h5>
                    <p class="mb-0 small">Jika Anda mengalami kendala teknis, silakan hubungi admin via WhatsApp di <strong>0812-3456-7890</strong> atau email ke <strong>admin@desamaju.id</strong>.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require 'footer.php'; ?>