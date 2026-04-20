<?php
// delete_pengaduan.php
require '../koneksi.php'; // Pakai titik dua untuk mundur ke folder utama

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: admin.php');
    exit;
}

// ambil nama file foto dulu
$stmt = $koneksi->prepare("SELECT bukti_foto FROM pengaduan WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($bukti_foto);
$stmt->fetch();
$stmt->close();

// hapus record
$stmt2 = $koneksi->prepare("DELETE FROM pengaduan WHERE id = ?");
$stmt2->bind_param("i", $id);
if ($stmt2->execute()) {
    // hapus file fisik jika ada
    if (!empty($bukti_foto)) {
        $path = __DIR__ . '/uploads/' . $bukti_foto;
        if (file_exists($path)) {
            @unlink($path);
        }
    }
}
$stmt2->close();
$koneksi->close();

header('Location: admin.php');
exit;
