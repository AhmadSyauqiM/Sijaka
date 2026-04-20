<?php
require_once __DIR__ . '/../includes/Env.php';

// Load .env variables
Env::load(__DIR__ . '/../.env');

$host = Env::get('DB_HOST', 'localhost');
$user = Env::get('DB_USER', 'root');
$pass = Env::get('DB_PASS', '');
$db   = Env::get('DB_NAME', 'db_sijaka');

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
