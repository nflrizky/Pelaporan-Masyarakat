<?php
function allowedTransitions() {
    return [
        'Diajukan'     => ['Diverifikasi'],
        'Diverifikasi' => ['Diproses'],
        'Diproses'     => ['Selesai', 'Ditolak'],
        'Selesai'      => ['Ditutup'],
        'Ditolak'      => ['Ditutup'],
        'Ditutup'      => []
    ];
}

// untuk class badge Bootstrap 4: badge badge-*
function badgeClass($status) {
    switch ($status) {
        case 'Diajukan':     return 'secondary';
        case 'Diverifikasi': return 'info';
        case 'Diproses':     return 'warning';
        case 'Selesai':      return 'success';
        case 'Ditolak':      return 'danger';
        case 'Ditutup':      return 'dark';
        default:             return 'secondary';
    }
}
?>
