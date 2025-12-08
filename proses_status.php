<?php
require 'koneksi.php';
require 'fungsi_status.php';

// kalau sudah ada login petugas, aktifkan ini:
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petugas') {
//     header("Location: login_petugas.php");
//     exit;
// }

if (isset($_POST['simpan_status'])) {
    $id_laporan  = $_POST['id_laporan'];
    $status_lama = $_POST['status_lama'];
    $status_baru = $_POST['status_baru'];
    $catatan     = $_POST['catatan'];

    // sementara id_petugas = 1, nanti bisa ambil dari $_SESSION
    $id_petugas = isset($_SESSION['id_petugas']) ? $_SESSION['id_petugas'] : 1;

    $allowed = allowedTransitions();

    if (!isset($allowed[$status_lama]) || !in_array($status_baru, $allowed[$status_lama])) {
        echo "<script>alert('Perubahan status tidak valid'); history.back();</script>";
        exit;
    }

    // upload bukti (opsional)
    $nama_bukti = '';
    if (!empty($_FILES['bukti']['name'])) {
        if (!is_dir('upload')) {
            mkdir('upload', 0777, true);
        }
        $nama_file = time() . "_" . basename($_FILES['bukti']['name']);
        $tujuan    = "upload/" . $nama_file;
        if (move_uploaded_file($_FILES['bukti']['tmp_name'], $tujuan)) {
            $nama_bukti = $nama_file;
        }
    }

    // insert ke tabel tanggapan (riwayat status)
    $sqlT = "INSERT INTO tanggapan 
                (id_laporan, id_petugas, waktu_tanggapan, status_set, catatan, bukti)
             VALUES 
                ('$id_laporan', '$id_petugas', NOW(), '$status_baru', '$catatan', '$nama_bukti')";
    mysqli_query($conn, $sqlT);

    // update status di tabel laporan
    $sqlL = "UPDATE laporan SET status = '$status_baru' WHERE id_laporan = '$id_laporan'";
    mysqli_query($conn, $sqlL);

    echo "<script>alert('Status berhasil diubah'); window.location='detail_laporan.php?id=$id_laporan';</script>";
}
?>
