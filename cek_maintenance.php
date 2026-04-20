<?php
// Pastikan session dimulai hanya jika belum aktif
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek koneksi, jika belum ada, include. 
// Menggunakan __DIR__ . '/koneksi.php' (tambahkan slash di depan koneksi.php)
if (!isset($koneksi)) {
    include __DIR__ . '/config/database.php'; 
}

// 1. Ambil Status Maintenance dari Database
// Kita cek apakah tabel ada dan datanya ada
$query_sys = mysqli_query($koneksi, "SELECT maintenance_mode FROM pengaturan_sistem WHERE id = 1");

if ($query_sys && mysqli_num_rows($query_sys) > 0) {
    $sys_data = mysqli_fetch_assoc($query_sys);
    $is_maintenance = ($sys_data['maintenance_mode'] == 1);
} else {
    // Default jika tabel error
    $is_maintenance = false;
}

// 2. Cek Siapa yang Mengakses
// Admin ditandai dengan session status = login
$is_admin_login = (isset($_SESSION['status']) && $_SESSION['status'] == 'login');

// 3. Logika Tendang (Gatekeeper)
// Jika Mode Maintenance AKTIF DAN yang akses BUKAN Admin
if ($is_maintenance && !$is_admin_login) {
    
    // Dapatkan nama file yang sedang diakses
    $current_page = basename($_SERVER['PHP_SELF']);
    
    // Jangan redirect jika user SUDAH di halaman maintenance atau halaman login admin
    if ($current_page != 'maintenance.php' && $current_page != 'login.php' && $current_page != 'proses_login.php') {
        
        // Redirect ke maintenance.php
        header("Location: maintenance.php");
        exit;
    }
}
?>