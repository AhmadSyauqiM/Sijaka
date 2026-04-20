<?php
// update_status.php
require '../koneksi.php';  // Pakai titik dua untuk mundur ke folder utama

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.php');
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';

$valid = ['Pending','Proses','Selesai','Ditolak'];
if ($id <= 0 || !in_array($status, $valid)) {
    $_SESSION['msg'] = "Data tidak valid.";
    header('Location: admin.php');
    exit;
}

$stmt = $koneksi->prepare("UPDATE pengaduan SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);
if ($stmt->execute()) {
    // sukses
}
$stmt->close();
$koneksi->close();

header('Location: admin.php');
exit;
