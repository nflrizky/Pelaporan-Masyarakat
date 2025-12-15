<?php
session_start();
require 'koneksi.php';

// 1. Cek apakah user sudah login
if (!isset($_SESSION['role'])) {
    echo "<script>alert('Akses Ditolak! Harap login terlebih dahulu.'); window.location='login.php';</script>";
    exit;
}

// 2. Cek apakah ada ID laporan yang dikirim di URL
if (isset($_GET['id'])) {
    $id_laporan = $_GET['id'];
    
    // Ambil data laporan dulu untuk validasi kepemilikan & mengambil nama file foto
    $query = mysqli_query($conn, "SELECT * FROM laporan WHERE id_laporan = '$id_laporan'");
    $data  = mysqli_fetch_assoc($query);

    // Cek apakah laporan ditemukan
    if (!$data) {
        echo "<script>alert('Data laporan tidak ditemukan!'); window.location='lihat_laporan.php';</script>";
        exit;
    }

    // 3. VALIDASI KEPEMILIKAN (Khusus Masyarakat)
    // Jika role-nya masyarakat, pastikan NIK di sesi sama dengan NIK di laporan
    if ($_SESSION['role'] == 'masyarakat') {
        
        // Pastikan Session ID Masyarakat tidak kosong
        if (!isset($_SESSION['id_masyarakat'])) {
             echo "<script>alert('Sesi habis, silakan login ulang.'); window.location='login.php';</script>";
             exit;
        }

        // Cek kesamaan NIK/ID
        if ($data['id_masyarakat'] != $_SESSION['id_masyarakat']) {
            echo "<script>alert('Anda tidak berhak menghapus laporan orang lain!'); window.location='lihat_laporan.php';</script>";
            exit;
        }
        
        // Masyarakat hanya boleh hapus jika status masih 'Diajukan'
        if ($data['status'] != 'Diajukan') {
            echo "<script>alert('Laporan yang sudah diproses atau selesai tidak bisa dihapus!'); window.location='lihat_laporan.php';</script>";
            exit;
        }
    }

    // 4. PROSES HAPUS FOTO FISIK (Jika ada)
    // Cek apakah kolom foto tidak kosong
    if (!empty($data['foto'])) {
        $foto_path = 'foto/' . $data['foto'];
        
        // Cek apakah file fisiknya benar-benar ada di folder
        if (file_exists($foto_path)) {
            unlink($foto_path); // Hapus file
        }
    }

    // 5. PROSES HAPUS DATA DI DATABASE
    $sql_hapus = "DELETE FROM laporan WHERE id_laporan = '$id_laporan'";
    
    if (mysqli_query($conn, $sql_hapus)) {
        // Berhasil
        echo "<script>alert('Laporan berhasil dihapus.'); window.location='lihat_laporan.php';</script>";
    } else {
        // Gagal (misal masalah koneksi)
        echo "<script>alert('Gagal menghapus laporan: " . mysqli_error($conn) . "'); window.location='lihat_laporan.php';</script>";
    }

} else {
    // Jika tidak ada ID di URL, kembalikan ke halaman lihat laporan
    echo "<script>window.location='lihat_laporan.php';</script>";
}
?>