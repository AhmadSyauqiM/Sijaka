<?php
session_start();

// Cek status login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

require '../koneksi.php';

// Hitung statistik
$stats = [
    'total' => 0,
    'pending' => 0,
    'proses' => 0,
    'selesai' => 0,
    'ditolak' => 0
];

$query_stats = mysqli_query($koneksi, "SELECT 
    COUNT(*) as total,
    SUM(status = 'Pending') as pending,
    SUM(status = 'Proses') as proses,
    SUM(status = 'Selesai') as selesai,
    SUM(status = 'Ditolak') as ditolak
    FROM pengaduan");

if ($row_stats = mysqli_fetch_assoc($query_stats)) {
    $stats = $row_stats;
}

// Ambil data pengaduan untuk tabel
$query = "SELECT * FROM pengaduan ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - SIJAKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
            --success: #198754;
            --info: #0dcaf0;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #212529;
            --sidebar-width: 260px;
            --header-height: 70px;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f7fb;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #0d6efd 0%, #0b5ed7 100%);
            color: white;
            z-index: 1000;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .sidebar-brand small {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .nav-item {
            margin: 5px 15px;
        }

        .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 12px 20px;
            border-radius: 10px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(5px);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
        }

        /* Header dengan Logout yang lebih terlihat */
        .navbar-top {
            background: white;
            padding: 15px 20px;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }

        /* Logout button yang lebih menonjol */
        .logout-btn {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 10px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
            color: white;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }

        .stat-card.total::before { background: linear-gradient(90deg, #0d6efd, #0dcaf0); }
        .stat-card.pending::before { background: #ffc107; }
        .stat-card.proses::before { background: #0d6efd; }
        .stat-card.selesai::before { background: #198754; }
        .stat-card.ditolak::before { background: #dc3545; }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .stat-card.total .stat-icon { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
        .stat-card.pending .stat-icon { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
        .stat-card.proses .stat-icon { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
        .stat-card.selesai .stat-icon { background: rgba(25, 135, 84, 0.1); color: #198754; }
        .stat-card.ditolak .stat-icon { background: rgba(220, 53, 69, 0.1); color: #dc3545; }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 10px 0;
            line-height: 1;
        }

        .stat-title {
            color: #6c757d;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }

        /* Main Table */
        .main-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            border: none;
            overflow: hidden;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 20px 25px;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-container {
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            margin: 0;
        }

        .custom-table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #eee;
            padding: 15px;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .custom-table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
        }

        .custom-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-proses { background: #cfe2ff; color: #084298; }
        .badge-selesai { background: #d1e7dd; color: #0a3622; }
        .badge-ditolak { background: #f8d7da; color: #58151c; }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-sm-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        /* Photo Gallery */
        .photo-thumbnails {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .photo-thumb {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #eee;
            cursor: pointer;
            transition: transform 0.2s, border-color 0.2s;
        }

        .photo-thumb:hover {
            transform: scale(1.1);
            border-color: #0d6efd;
        }

        /* Modal Custom */
        .modal-custom {
            border-radius: 15px;
            overflow: hidden;
            border: none;
        }

        .modal-header-custom {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: white;
            border-bottom: none;
            padding: 20px 25px;
        }

        .modal-body-custom {
            padding: 25px;
        }

        .detail-item {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .detail-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #6c757d;
        }

        .photo-gallery-modal {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .photo-modal {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .photo-modal:hover {
            transform: scale(1.05);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .navbar-top {
                flex-direction: column;
                gap: 15px;
            }
        }

        /* Search and Filter */
        .search-filter {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }

        .filter-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Mobile menu toggle */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background: #0d6efd;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: flex;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-toggle" id="mobileToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h3><i class="fas fa-road me-2"></i>SIJAKA</h3>
            <small>Sistem Informasi Jalan & Kerusakan Aset</small>
        </div>
        
        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Analitik</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>Pengguna</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- User info di sidebar (tetap ada tapi lebih sederhana) -->
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar" style="width: 40px; height: 40px; font-size: 1rem;">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 0.9rem;">Admin SIJAKA</div>
                    <small style="opacity: 0.8; font-size: 0.8rem;">Administrator</small>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navigation dengan Logout -->
        <nav class="navbar-top">
            <div>
                <h4 class="mb-0">Dashboard Admin</h4>
                <small class="text-muted">Selamat datang di panel administrasi SIJAKA</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-primary">
                    <i class="fas fa-download me-2"></i>Export
                </button>
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Print
                </button>
                <!-- Tombol Logout yang lebih menonjol -->
                <a href="logout.php" class="logout-btn" onclick="return confirmLogout()">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </nav>

        <!-- Search and Filter -->
        <div class="search-filter">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari laporan..." id="searchInput">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Proses">Proses</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterJenis">
                        <option value="">Semua Jenis</option>
                        <option value="Jalan">Jalan</option>
                        <option value="Jembatan">Jembatan</option>
                        <option value="Drainase">Drainase</option>
                        <option value="Gedung">Gedung</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card total fade-in">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-number"><?php echo $stats['total']; ?></div>
                <div class="stat-title">Total Laporan</div>
                <small class="text-muted">+12% dari bulan lalu</small>
            </div>
            
            <div class="stat-card pending fade-in" style="animation-delay: 0.1s;">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"><?php echo $stats['pending']; ?></div>
                <div class="stat-title">Pending</div>
                <small class="text-muted">Menunggu tindakan</small>
            </div>
            
            <div class="stat-card proses fade-in" style="animation-delay: 0.2s;">
                <div class="stat-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="stat-number"><?php echo $stats['proses']; ?></div>
                <div class="stat-title">Dalam Proses</div>
                <small class="text-muted">Sedang ditangani</small>
            </div>
            
            <div class="stat-card selesai fade-in" style="animation-delay: 0.3s;">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?php echo $stats['selesai']; ?></div>
                <div class="stat-title">Selesai</div>
                <small class="text-muted">Sudah diperbaiki</small>
            </div>
            
            <div class="stat-card ditolak fade-in" style="animation-delay: 0.4s;">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-number"><?php echo $stats['ditolak']; ?></div>
                <div class="stat-title">Ditolak</div>
                <small class="text-muted">Tidak valid/ditolak</small>
            </div>
        </div>

        <!-- Main Table -->
        <div class="main-card fade-in" style="animation-delay: 0.5s;">
            <div class="card-header">
                <h5><i class="fas fa-list"></i> Daftar Pengaduan Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table custom-table" id="mainTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelapor</th>
                                <th>Jenis</th>
                                <th>Lokasi</th>
                                <th>Tanggal</th>
                                <th>Foto</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <?php
                                    $id = (int)$row['id'];
                                    $nama_pelapor = htmlspecialchars($row['nama_pelapor']);
                                    $jenis_aset = htmlspecialchars($row['jenis_aset']);
                                    $lokasi_aset = htmlspecialchars($row['lokasi_aset']);
                                    $tanggal_lapor = htmlspecialchars($row['tanggal_lapor']);
                                    $status = htmlspecialchars($row['status']);
                                    $keterangan = htmlspecialchars($row['keterangan']);
                                    $bukti_foto = htmlspecialchars($row['bukti_foto']);
                                    ?>
                                    <tr data-status="<?php echo strtolower($status); ?>" data-jenis="<?php echo strtolower($jenis_aset); ?>">
                                        <td><strong>#<?php echo str_pad($id, 4, '0', STR_PAD_LEFT); ?></strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar" style="width: 35px; height: 35px; font-size: 0.9rem; margin-right: 10px;">
                                                    <?php echo strtoupper(substr($nama_pelapor, 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <div style="font-weight: 500;"><?php echo $nama_pelapor; ?></div>
                                                    <small class="text-muted"><?php echo date('d M Y', strtotime($tanggal_lapor)); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-<?php 
                                                    echo ($jenis_aset == 'Jalan') ? 'road' : 
                                                         (($jenis_aset == 'Jembatan') ? 'bridge' : 
                                                         (($jenis_aset == 'Drainase') ? 'water' : 'building')); 
                                                ?> me-1"></i>
                                                <?php echo $jenis_aset; ?>
                                            </span>
                                        </td>
                                        <td style="max-width: 250px;">
                                            <div class="text-truncate" title="<?php echo $lokasi_aset; ?>">
                                                <?php echo $lokasi_aset; ?>
                                            </div>
                                        </td>
                                        <td><?php echo date('d M Y', strtotime($tanggal_lapor)); ?></td>
                                        <td>
                                            <div class="photo-thumbnails">
                                                <?php
                                                $fotos = explode(',', $bukti_foto);
                                                $foto_count = 0;
                                                foreach($fotos as $f):
                                                    if(!empty($f) && $foto_count < 3):
                                                        $foto_count++;
                                                ?>
                                                <img src="../uploads/<?php echo $f; ?>" class="photo-thumb" 
                                                     data-bs-toggle="tooltip" title="Klik untuk melihat">
                                                <?php endif; endforeach; ?>
                                                <?php if(count($fotos) > 3): ?>
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px; font-size: 0.8rem;">
                                                    +<?php echo count($fotos) - 3; ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = 'badge-pending';
                                            if ($status === 'Proses') $badgeClass = 'badge-proses';
                                            if ($status === 'Selesai') $badgeClass = 'badge-selesai';
                                            if ($status === 'Ditolak') $badgeClass = 'badge-ditolak';
                                            ?>
                                            <span class="status-badge <?php echo $badgeClass; ?>">
                                                <i class="fas fa-<?php 
                                                    echo ($status === 'Pending') ? 'clock' : 
                                                         (($status === 'Proses') ? 'spinner' : 
                                                         (($status === 'Selesai') ? 'check' : 'times')); 
                                                ?>"></i>
                                                <?php echo $status; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-info btn-sm-icon view-btn"
                                                        data-bs-toggle="modal" data-bs-target="#detailModal"
                                                        data-id="<?php echo $id; ?>"
                                                        data-nama="<?php echo $nama_pelapor; ?>"
                                                        data-jenis="<?php echo $jenis_aset; ?>"
                                                        data-lokasi="<?php echo htmlspecialchars($lokasi_aset, ENT_QUOTES); ?>"
                                                        data-ket="<?php echo htmlspecialchars($keterangan, ENT_QUOTES); ?>"
                                                        data-foto="<?php echo $bukti_foto; ?>"
                                                        data-tgl="<?php echo $tanggal_lapor; ?>"
                                                        data-status="<?php echo $status; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                <form action="update_status.php" method="post" class="d-inline" id="statusForm<?php echo $id; ?>">
                                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                    <select name="status" class="form-select form-select-sm status-select" 
                                                            style="width: 120px; display: inline-block;"
                                                            onchange="updateStatus(<?php echo $id; ?>)">
                                                        <option value="Pending" <?php echo ($status == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="Proses" <?php echo ($status == 'Proses') ? 'selected' : ''; ?>>Proses</option>
                                                        <option value="Selesai" <?php echo ($status == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                                                        <option value="Ditolak" <?php echo ($status == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                                                    </select>
                                                </form>
                                                
                                                <button class="btn btn-sm btn-danger btn-sm-icon delete-btn"
                                                        data-id="<?php echo $id; ?>"
                                                        data-nama="<?php echo $nama_pelapor; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Belum ada pengaduan</h5>
                                            <p class="text-muted">Tidak ada laporan pengaduan yang ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Menampilkan <strong><span id="visibleRows"><?php echo mysqli_num_rows($result); ?></span></strong> dari <strong><?php echo $stats['total']; ?></strong> laporan
            </div>
            <nav>
                <ul class="pagination">
                    <li class="page-item disabled"><a class="page-link" href="#">Sebelumnya</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Selanjutnya</a></li>
                </ul>
            </nav>
        </div>
    </main>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title mb-0"><i class="fas fa-file-alt me-2"></i>Detail Pengaduan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-custom">
                    <div class="detail-item">
                        <div class="detail-label">Nomor Laporan</div>
                        <div class="detail-value" id="detail-id">#0000</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Pelapor</div>
                        <div class="detail-value" id="detail-nama">-</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Jenis Aset</div>
                        <div class="detail-value" id="detail-jenis">-</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Lokasi</div>
                        <div class="detail-value" id="detail-lokasi">-</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Laporan</div>
                        <div class="detail-value" id="detail-tgl">-</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="status-badge" id="detail-status-badge">-</span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Keterangan</div>
                        <div class="detail-value" id="detail-ket">-</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Bukti Foto</div>
                        <div class="detail-value">
                            <div class="photo-gallery-modal" id="detail-photos">
                                <!-- Photos will be loaded here by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="printDetail()">
                        <i class="fas fa-print me-2"></i>Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Mobile sidebar toggle
        document.getElementById('mobileToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('mobileToggle');
            if (window.innerWidth <= 768 && !sidebar.contains(event.target) && !toggle.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });

        // Detail Modal
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const jenis = this.getAttribute('data-jenis');
                const lokasi = this.getAttribute('data-lokasi');
                const ket = this.getAttribute('data-ket');
                const fotoString = this.getAttribute('data-foto');
                const tgl = this.getAttribute('data-tgl');
                const status = this.getAttribute('data-status');

                // Set modal content
                document.getElementById('detail-id').textContent = '#' + id.toString().padStart(4, '0');
                document.getElementById('detail-nama').textContent = nama;
                document.getElementById('detail-jenis').textContent = jenis;
                document.getElementById('detail-lokasi').textContent = lokasi;
                document.getElementById('detail-tgl').textContent = tgl;
                document.getElementById('detail-ket').textContent = ket;

                // Set status badge
                const statusBadge = document.getElementById('detail-status-badge');
                statusBadge.textContent = status;
                statusBadge.className = 'status-badge ';
                statusBadge.classList.add('badge-' + status.toLowerCase());

                // Set photos
                const photoContainer = document.getElementById('detail-photos');
                photoContainer.innerHTML = '';

                if (fotoString && fotoString.trim() !== '') {
                    const fotos = fotoString.split(',');
                    fotos.forEach(filename => {
                        if (filename.trim() !== '') {
                            const a = document.createElement('a');
                            a.href = '../uploads/' + filename;
                            a.target = '_blank';
                            a.className = 'photo-modal-container';

                            const img = document.createElement('img');
                            img.src = '../uploads/' + filename;
                            img.alt = 'Bukti Foto';
                            img.className = 'photo-modal';

                            a.appendChild(img);
                            photoContainer.appendChild(a);
                        }
                    });
                } else {
                    photoContainer.innerHTML = '<div class="text-center text-muted py-4">Tidak ada foto</div>';
                }
            });
        });

        // Status update function
        function updateStatus(id) {
            const form = document.getElementById('statusForm' + id);
            const formData = new FormData(form);
            
            fetch('update_status.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Reload halaman setelah update status
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal mengupdate status');
            });
        }

        // Delete confirmation
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                
                if (confirm(`Apakah Anda yakin ingin menghapus pengaduan dari ${nama}? Tindakan ini tidak dapat dibatalkan.`)) {
                    window.location.href = 'delete_pengaduan.php?id=' + id;
                }
            });
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#mainTable tbody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            document.getElementById('visibleRows').textContent = visibleCount;
        });

        // Filter by status
        document.getElementById('filterStatus').addEventListener('change', function() {
            applyFilters();
        });

        // Filter by jenis
        document.getElementById('filterJenis').addEventListener('change', function() {
            applyFilters();
        });

        function applyFilters() {
            const statusFilter = document.getElementById('filterStatus').value.toLowerCase();
            const jenisFilter = document.getElementById('filterJenis').value.toLowerCase();
            const searchFilter = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#mainTable tbody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const jenis = row.getAttribute('data-jenis');
                const text = row.textContent.toLowerCase();
                
                const statusMatch = statusFilter === '' || status === statusFilter;
                const jenisMatch = jenisFilter === '' || jenis === jenisFilter;
                const searchMatch = searchFilter === '' || text.includes(searchFilter);
                
                if (statusMatch && jenisMatch && searchMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            document.getElementById('visibleRows').textContent = visibleCount;
        }

        // Print detail function
        function printDetail() {
            const printContent = `
                <html>
                <head>
                    <title>Detail Pengaduan</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .detail { margin-bottom: 15px; }
                        .label { font-weight: bold; }
                        .photos { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; }
                        .photo { width: 150px; height: 150px; object-fit: cover; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>Detail Pengaduan</h2>
                        <h4>SIJAKA - Sistem Informasi Jalan & Kerusakan Aset</h4>
                    </div>
                    <div class="detail">
                        <div class="label">Nomor Laporan:</div>
                        <div>${document.getElementById('detail-id').textContent}</div>
                    </div>
                    <div class="detail">
                        <div class="label">Pelapor:</div>
                        <div>${document.getElementById('detail-nama').textContent}</div>
                    </div>
                    <div class="detail">
                        <div class="label">Jenis Aset:</div>
                        <div>${document.getElementById('detail-jenis').textContent}</div>
                    </div>
                    <div class="detail">
                        <div class="label">Lokasi:</div>
                        <div>${document.getElementById('detail-lokasi').textContent}</div>
                    </div>
                    <div class="detail">
                        <div class="label">Tanggal Laporan:</div>
                        <div>${document.getElementById('detail-tgl').textContent}</div>
                    </div>
                    <div class="detail">
                        <div class="label">Status:</div>
                        <div>${document.getElementById('detail-status-badge').textContent}</div>
                    </div>
                    <div class="detail">
                        <div class="label">Keterangan:</div>
                        <div>${document.getElementById('detail-ket').textContent}</div>
                    </div>
                </body>
                </html>
            `;
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
        }

        // Confirm logout
        function confirmLogout() {
            return confirm('Apakah Anda yakin ingin logout?');
        }

        // Auto-refresh data every 30 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>