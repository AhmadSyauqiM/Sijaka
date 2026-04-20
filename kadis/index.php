<?php
session_start();
require '../koneksi.php';

// --- 1. CEK KEAMANAN ---
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'kadis') {
    header("Location: ../admin/login.php");
    exit;
}

// --- 2. AMBIL DATA STATISTIK DARI TABEL PENGADUAN ---

// A. Hitung Total per Status
function hitungStatus($koneksi, $status) {
    if($status == 'ALL') {
        $q = mysqli_query($koneksi, "SELECT COUNT(*) as jlh FROM pengaduan");
    } else {
        $q = mysqli_query($koneksi, "SELECT COUNT(*) as jlh FROM pengaduan WHERE status='$status'");
    }
    return mysqli_fetch_assoc($q)['jlh'];
}

$total_semua = hitungStatus($koneksi, 'ALL');
$total_pending = hitungStatus($koneksi, 'Pending');
$total_proses = hitungStatus($koneksi, 'Proses');
$total_selesai = hitungStatus($koneksi, 'Selesai');
$total_ditolak = hitungStatus($koneksi, 'Ditolak');

// B. Data untuk Grafik Batang (Laporan per Bulan)
$barData = array_fill(1, 12, 0); // Siapkan array kosong bulan 1-12
$tahun_ini = date('Y');
$q_bulan = mysqli_query($koneksi, "SELECT MONTH(tanggal_lapor) as bulan, COUNT(*) as jumlah 
                                   FROM pengaduan 
                                   WHERE YEAR(tanggal_lapor) = '$tahun_ini' 
                                   GROUP BY MONTH(tanggal_lapor)");

while($row = mysqli_fetch_assoc($q_bulan)) {
    $barData[$row['bulan']] = $row['jumlah'];
}

// C. Data Tabel Monitoring (5 Terakhir)
$q_tabel = mysqli_query($koneksi, "SELECT * FROM pengaduan ORDER BY tanggal_lapor DESC LIMIT 10");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kadis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f4f6f9; }
        .card-stat { border:none; border-radius: 10px; color: white; transition: .3s; }
        .card-stat:hover { transform: translateY(-5px); }
        .bg-gradient-1 { background: linear-gradient(45deg, #4e73df, #224abe); } /* Biru */
        .bg-gradient-2 { background: linear-gradient(45deg, #1cc88a, #13855c); } /* Hijau */
        .bg-gradient-3 { background: linear-gradient(45deg, #f6c23e, #dda20a); } /* Kuning */
        .bg-gradient-4 { background: linear-gradient(45deg, #e74a3b, #be2617); } /* Merah */
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="fas fa-chart-line me-2"></i>DASHBOARD KADIS</a>
            <div class="text-white">
                Halo, <?= $_SESSION['nama']; ?> 
                <a href="../admin/logout.php" class="btn btn-outline-light btn-sm ms-3">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card card-stat bg-gradient-1 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Total Laporan</h6>
                            <h2 class="display-5 fw-bold mb-0"><?= $total_semua; ?></h2>
                        </div>
                        <i class="fas fa-file-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat bg-gradient-2 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Selesai</h6>
                            <h2 class="display-5 fw-bold mb-0"><?= $total_selesai; ?></h2>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat bg-gradient-3 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Proses/Pending</h6>
                            <h2 class="display-5 fw-bold mb-0"><?= $total_proses + $total_pending; ?></h2>
                        </div>
                        <i class="fas fa-tools fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat bg-gradient-4 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Ditolak</h6>
                            <h2 class="display-5 fw-bold mb-0"><?= $total_ditolak; ?></h2>
                        </div>
                        <i class="fas fa-times-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-white fw-bold">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>Persentase Status
                    </div>
                    <div class="card-body">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-white fw-bold">
                        <i class="fas fa-chart-bar me-2 text-success"></i>Statistik Laporan Tahun <?= $tahun_ini; ?>
                    </div>
                    <div class="card-body">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow border-0 mb-5">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-table me-2"></i>10 Laporan Terbaru Masuk
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tiket</th>
                                <th>Pelapor</th>
                                <th>Lokasi</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($q_tabel)): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= $row['nomor_tiket']; ?></span></td>
                                <td><?= htmlspecialchars($row['nama_pelapor']); ?></td>
                                <td><?= htmlspecialchars($row['lokasi_aset']); ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal_lapor'])); ?></td>
                                <td>
                                    <?php 
                                        $st = $row['status'];
                                        $badge = ($st=='Selesai')?'success':(($st=='Proses')?'primary':(($st=='Pending')?'warning':'danger'));
                                    ?>
                                    <span class="badge bg-<?= $badge; ?>"><?= $st; ?></span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        // 1. PIE CHART CONFIG
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Selesai', 'Proses', 'Pending', 'Ditolak'],
                datasets: [{
                    data: [<?= $total_selesai ?>, <?= $total_proses ?>, <?= $total_pending ?>, <?= $total_ditolak ?>],
                    backgroundColor: ['#1cc88a', '#4e73df', '#f6c23e', '#e74a3b']
                }]
            }
        });

        // 2. BAR CHART CONFIG
        const ctxBar = document.getElementById('barChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: <?= json_encode(array_values($barData)); ?>,
                    backgroundColor: '#4e73df'
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>

</body>
</html>