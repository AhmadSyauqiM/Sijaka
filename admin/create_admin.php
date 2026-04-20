<?php
// create_admin.php
require '../koneksi.php'; // Pakai titik dua untuk mundur ke folder utama

$username = "admin";
$password_plain = "admin123"; // ganti jika mau
$nama_petugas = "Admin DPUTR";

$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

// Cek apakah username sudah ada
$stmt = $koneksi->prepare("SELECT id FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo "Username sudah ada. Hentikan.\n";
    exit;
}
$stmt->close();

$stmt = $koneksi->prepare("INSERT INTO admin (username, password, nama_petugas) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password_hashed, $nama_petugas);
if ($stmt->execute()) {
    echo "Admin berhasil dibuat. Username: $username  Password: $password_plain\n";
} else {
    echo "Gagal membuat admin: " . $koneksi->error;
}
$stmt->close();
$koneksi->close();
