<?php
session_start();
// Pastikan file koneksi ada di folder yang sama
include 'config/database.php';
// 2. PASANG SATPAM DISINI (Ini yang hilang di file kamu sebelumnya)
include 'cek_maintenance.php';

// Menentukan halaman aktif berdasarkan parameter URL
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIJAKA - Lapor Kerusakan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Placeholder for any page-specific non-extracted styles */
    </style>
</head>
<body class="bg-light">
    
    <!-- Scroll to Top Button -->
    <div class="scroll-top" onclick="scrollToTop()">
        <i class="fas fa-chevron-up"></i>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner mb-3"></div>
            <h4 class="text-primary mb-2">Mengirim Laporan...</h4>
            <p class="text-muted">Harap tunggu sebentar, laporan Anda sedang diproses.</p>
        </div>
    </div>

    <!-- Modern Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="?page=home">
                <img src="assets/img/Logos.png" alt="Logo">
            </a>
            <button class="navbar-toggler burger-btn border-0 shadow-none" type="button" 
        data-bs-toggle="collapse" data-bs-target="#navbarNav" 
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"
        onclick="this.classList.toggle('burger-active')">
    
    <svg class="burger-icon" xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 200 200">
        <g stroke-width="6.5" stroke-linecap="round">
            <path class="burger-path" d="M72 82.286h28.75" fill="#009100" fill-rule="evenodd" stroke="#333" />
            <path class="burger-path" d="M100.75 103.714l72.482-.143c.043 39.398-32.284 71.434-72.16 71.434-39.878 0-72.204-32.036-72.204-71.554" fill="none" stroke="#333" />
            <path class="burger-path" d="M72 125.143h28.75" fill="#009100" fill-rule="evenodd" stroke="#333" />
            <path class="burger-path" d="M100.75 103.714l-71.908-.143c.026-39.638 32.352-71.674 72.23-71.674 39.876 0 72.203 32.036 72.203 71.554" fill="none" stroke="#333" />
            <path class="burger-path" d="M100.75 82.286h28.75" fill="#009100" fill-rule="evenodd" stroke="#333" />
            <path class="burger-path" d="M100.75 125.143h28.75" fill="#009100" fill-rule="evenodd" stroke="#333" />
        </g>
    </svg>
</button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= $page == 'home' ? 'active' : '' ?>" href="?page=home">
                            <i class="fas fa-home me-2"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page == 'lapor' ? 'active' : '' ?>" href="?page=lapor">
                            <i class="fas fa-plus-circle me-2"></i>Lapor Kerusakan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page == 'status' ? 'active' : '' ?>" href="?page=status">
                            <i class="fas fa-search me-2"></i>Cek Status
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page == 'about' ? 'active' : '' ?>" href="?page=about">
                            <i class="fas fa-info-circle me-2"></i>Tentang Kami
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page == 'contact' ? 'active' : '' ?>" href="?page=contact">
                            <i class="fas fa-phone-alt me-2"></i>Kontak
                        </a>
                    </li>
                </ul>
                <a href="admin/dashboard_admin.php" class="btn btn-light btn-sm fw-bold text-primary px-4 py-2 rounded-pill">
                    <i class="fas fa-user-shield me-2"></i>Login Petugas
                </a>
            </div>
        </div>
    </nav>

    <?php if ($page == 'home'): ?>
    <!-- Modern Hero Section -->
    <section class="hero-section">
        <div class="hero-slider">
            <div class="hero-slide active" style="background-image: url('assets/img/bckg.jpeg');"></div>
            <div class="hero-slide" style="background-image: url('assets/img/bckg1.jpeg');"></div>
            <div class="hero-slide" style="background-image: url('assets/img/bckg2.png');"></div>
        </div>
        
        <div class="slider-indicators">
            <span class="slider-indicator active" data-slide="0"></span>
            <span class="slider-indicator" data-slide="1"></span>
            <span class="slider-indicator" data-slide="2"></span>
        </div>
        
        <div class="container hero-content">
            <h1 class="hero-title">SIJAKA Kuningan</h1>
            <h2 class="hero-subtitle">Layanan Pengaduan Infrastruktur</h2>
            <p class="lead hero-description">DPUTR Kabupaten Kuningan</p>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <p class="hero-description">Layanan pengaduan kerusakan infrastruktur publik di Kabupaten Kuningan. Laporkan kerusakan jalan, jembatan, drainase, dan infrastruktur lainnya dengan mudah dan cepat.</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="?page=lapor" class="btn btn-hero">
                            <i class="fas fa-plus-circle me-2"></i>Lapor Kerusakan
                        </a>
                        <a href="?page=status" class="btn btn-outline-light btn-lg border-2 rounded-pill px-4">
                            <i class="fas fa-search me-2"></i>Cek Status
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Statistics Section -->
    <section class="container my-5 py-5">
        <div class="row text-center mb-5">
            <div class="col-md-12">
                <h2 class="section-title fw-bold mb-3" style="font-size: 2.5rem;">Statistik Laporan</h2>
                <p class="text-muted">Real-time monitoring laporan kerusakan infrastruktur</p>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card interactive-card h-100">
                    <div class="card-body py-5">
                        <div class="card-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="stats-counter" data-target="1245">0</h3>
                        <p class="text-muted mb-0">Total Laporan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card interactive-card h-100">
                    <div class="card-body py-5">
                        <div class="card-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="stats-counter" data-target="892">0</h3>
                        <p class="text-muted mb-0">Laporan Selesai</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card interactive-card h-100">
                    <div class="card-body py-5">
                        <div class="card-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h3 class="stats-counter" data-target="215">0</h3>
                        <p class="text-muted mb-0">Dalam Proses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card interactive-card h-100">
                    <div class="card-body py-5">
                        <div class="card-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="stats-counter" data-target="138">0</h3>
                        <p class="text-muted mb-0">Menunggu Tindakan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Services Section -->
    <section class="container my-5 py-5">
        <div class="row">
            <div class="col-md-12 text-center mb-5">
                <h2 class="section-title fw-bold mb-3" style="font-size: 2.5rem;">Layanan Kami</h2>
                <p class="text-muted">Jenis-jenis kerusakan yang dapat Anda laporkan</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card service-card interactive-card h-100">
                    <div class="card-body text-center p-5">
                        <div class="card-icon">
                            <i class="fas fa-road"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Laporan Kerusakan Jalan</h4>
                        <p class="text-muted">Laporkan kerusakan jalan seperti lubang, retakan, atau kerusakan permukaan jalan lainnya.</p>
                        <div class="progress-bar-custom mt-4">
                            <div class="progress-fill" style="width: 85%"></div>
                        </div>
                        <small class="text-muted">85% Laporan Terproses</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card service-card interactive-card h-100">
                    <div class="card-body text-center p-5">
                        <div class="card-icon">
                            <i class="fas fa-bridge"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Kerusakan Jembatan</h4>
                        <p class="text-muted">Laporkan masalah pada jembatan seperti kerusakan struktur atau komponen jembatan.</p>
                        <div class="progress-bar-custom mt-4">
                            <div class="progress-fill" style="width: 70%"></div>
                        </div>
                        <small class="text-muted">70% Laporan Terproses</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card service-card interactive-card h-100">
                    <div class="card-body text-center p-5">
                        <div class="card-icon">
                            <i class="fas fa-water"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Masalah Drainase</h4>
                        <p class="text-muted">Laporkan masalah drainase seperti penyumbatan, kerusakan saluran air, atau genangan.</p>
                        <div class="progress-bar-custom mt-4">
                            <div class="progress-fill" style="width: 90%"></div>
                        </div>
                        <small class="text-muted">90% Laporan Terproses</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Stats Banner -->
    <div class="container-fluid py-4 mb-5" style="background: var(--gradient-primary); border-radius: 20px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 text-white">
                    <h4 class="fw-bold mb-2">Butuh Bantuan Cepat?</h4>
                    <p class="mb-0">Tim kami siap membantu 24/7 untuk keadaan darurat</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="tel:0232876146" class="btn btn-light btn-lg rounded-pill px-4">
                        <i class="fas fa-phone-alt me-2"></i>Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($page == 'lapor'): ?>
    <div class="page-header">
        <div class="container text-center">
            <h2 class="fw-bold mb-2">Lapor Kerusakan</h2>
            <p class="lead mb-0">DPUTR Kabupaten Kuningan</p>
        </div>
    </div>

    <section class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card floating-element">
                    <div class="card-body p-5">
                        
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center">
                                <i class="fas fa-check-circle me-3 fa-2x"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Berhasil!</h5>
                                    <?= $_SESSION['success']; ?>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-3 fa-2x"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Perhatian!</h5>
                                    <?= $_SESSION['error']; ?>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form action="Proses_lapor.php" method="POST" enctype="multipart/form-data" id="formPengaduan" onsubmit="showLoading()">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label required fw-bold">
                                        <i class="fas fa-user me-2"></i>Nama Pelapor
                                    </label>
                                    <input type="text" class="form-control" name="nama_pelapor" 
                                           value="<?= $_SESSION['old_input']['nama_pelapor'] ?? '' ?>" 
                                           placeholder="Nama Lengkap Anda" required>
                                    <div class="form-text">Contoh: Budi Santoso</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required fw-bold">
                                        <i class="fas fa-tools me-2"></i>Jenis Kerusakan
                                    </label>
                                    <select class="form-select" name="jenis_aset" required>
                                        <option value="" selected disabled>-- Pilih Kategori --</option>
                                        <option value="Jalan" <?= (isset($_SESSION['old_input']['jenis_aset']) && $_SESSION['old_input']['jenis_aset'] == 'Jalan') ? 'selected' : '' ?>> Jalan Raya</option>
                                        <option value="Jembatan" <?= (isset($_SESSION['old_input']['jenis_aset']) && $_SESSION['old_input']['jenis_aset'] == 'Jembatan') ? 'selected' : '' ?>> Jembatan</option>
                                        <option value="Drainase" <?= (isset($_SESSION['old_input']['jenis_aset']) && $_SESSION['old_input']['jenis_aset'] == 'Drainase') ? 'selected' : '' ?>> Drainase / Selokan</option>
                                        <option value="Gedung" <?= (isset($_SESSION['old_input']['jenis_aset']) && $_SESSION['old_input']['jenis_aset'] == 'Gedung') ? 'selected' : '' ?>> Gedung</option>
                                        <option value="Lainnya" <?= (isset($_SESSION['old_input']['jenis_aset']) && $_SESSION['old_input']['jenis_aset'] == 'Lainnya') ? 'selected' : '' ?>> Lainnya</option>
                                    </select>
                                    <div class="form-text">Pilih jenis infrastruktur yang rusak</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label required fw-bold">
                                    <i class="fas fa-map-marker-alt me-2"></i>Lokasi Aset
                                </label>
                                <textarea class="form-control" name="lokasi_aset" rows="2" 
                                          placeholder="Contoh: Desa Ciawigebang, depan Alfamart..." required
                                          style="resize: none;"><?= $_SESSION['old_input']['lokasi_aset'] ?? '' ?></textarea>
                                <div class="form-text">Sebutkan lokasi detail dengan jelas</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label required fw-bold">
                                    <i class="fas fa-clipboard-list me-2"></i>Keterangan Detail
                                </label>
                                <textarea class="form-control" name="keterangan" rows="3" 
                                          placeholder="Deskripsikan kerusakan (misal: lubang dalam, banjir)..." required
                                          style="resize: none;"><?= $_SESSION['old_input']['keterangan'] ?? '' ?></textarea>
                                <div class="form-text">Jelaskan kerusakan secara rinci</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label required fw-bold">
                                    <i class="fas fa-camera me-2"></i>Bukti Foto (Maks 3 Foto)
                                </label>
                                <div class="file-upload-area border-2 border-dashed rounded-3 p-4 text-center mb-3">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                    <h5 class="mb-2">Seret & Lepas atau Klik untuk Upload</h5>
                                    <p class="text-muted mb-3">Format yang didukung: JPG, PNG, JPEG</p>
                                    <input type="file" class="form-control d-none" id="bukti_foto" name="bukti_foto[]" 
                                           accept="image/jpeg,image/png,image/jpg" multiple required>
                                    <label for="bukti_foto" class="btn btn-outline-primary px-4">
                                        <i class="fas fa-folder-open me-2"></i>Pilih File
                                    </label>
                                    <div class="form-text text-danger small mt-2">*Maksimal 3 foto, 5MB per foto</div>
                                </div>
                                
                                <div id="imagePreview" class="preview-container"></div>
                                <div id="fileList" class="mt-2"></div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="submit" class="btn btn-primary btn-lg fw-bold py-3" id="submitBtn">
                                    <i class="fas fa-paper-plane me-2"></i>KIRIM LAPORAN
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($page == 'status'): ?>
    
    <?php
    // --- LOGIKA PHP (SERVER SIDE) ---
    // Di sini kita cek database apakah tiket ada.
    $data_tiket = null;
    $pesan_error = "";
    
    if (isset($_GET['tiket_cari'])) {
        $tiket = mysqli_real_escape_string($koneksi, $_GET['tiket_cari']);
        $query = mysqli_query($koneksi, "SELECT * FROM pengaduan WHERE nomor_tiket = '$tiket'");
        
        if (mysqli_num_rows($query) > 0) {
            $data_tiket = mysqli_fetch_assoc($query);
        } else {
            $pesan_error = "Nomor Tiket <strong>" . htmlspecialchars($tiket) . "</strong> tidak ditemukan.";
        }
    }
    ?>

    <div class="page-header">
        <div class="container text-center">
            <h2 class="fw-bold mb-2">Cek Status Laporan</h2>
            <p class="lead mb-0">Pantau perkembangan laporan kerusakan yang telah Anda laporkan</p>
        </div>
    </div>

    <section class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-5 floating-element">
                    <div class="card-body p-5">
                        <h3 class="card-title text-center mb-4">
                            <i class="fas fa-search me-2"></i>Lacak Tiket Anda
                        </h3>
                        <form action="index.php" method="GET" class="search-form">
                            <input type="hidden" name="page" value="status">

                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-ticket-alt me-2"></i>Nomor Tiket Laporan
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-barcode"></i>
                                    </span>
                                    <input type="text" class="form-control text-uppercase fw-bold" 
                                           name="tiket_cari"
                                           placeholder="Contoh: TKT-2026..." 
                                           value="<?= isset($_GET['tiket_cari']) ? htmlspecialchars($_GET['tiket_cari']) : '' ?>"
                                           required
                                           style="letter-spacing: 1px;">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Lacak
                                    </button>
                                </div>
                                <div class="form-text">Masukkan nomor tiket yang Anda terima setelah melapor</div>
                            </div>
                        </form>

                        <?php if ($pesan_error): ?>
                            <div class="alert alert-danger mt-4 text-center p-4">
                                <i class="fas fa-exclamation-circle fa-2x mb-3"></i>
                                <h5 class="alert-heading">Tiket Tidak Ditemukan</h5>
                                <?= $pesan_error; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($data_tiket): ?>
                <div class="card shadow-sm mt-4 floating-element" id="statusResult">
                    <div class="card-body p-5">
                        <div class="row align-items-center mb-4">
                            <div class="col-md-8">
                                <h4 class="card-title mb-2">
                                    <i class="fas fa-clipboard-check text-primary me-2"></i>
                                    Status Laporan
                                </h4>
                                <p class="text-muted mb-0">Detail lengkap laporan Anda</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <?php 
                                    $st = $data_tiket['status'];
                                    $bg = 'secondary';
                                    if($st == 'Selesai') $bg = 'success';
                                    if($st == 'Proses') $bg = 'warning';
                                    if($st == 'Ditolak') $bg = 'danger';
                                ?>
                                <span class="badge bg-<?= $bg; ?> px-3 py-2 fs-5 rounded-pill">
                                    <?= strtoupper($st); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-info-circle me-2"></i>Informasi Tiket
                                        </h6>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-barcode text-muted me-3"></i>
                                            <div>
                                                <small class="text-muted d-block">Nomor Tiket</small>
                                                <strong class="text-primary"><?= htmlspecialchars($data_tiket['nomor_tiket']); ?></strong>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-calendar text-muted me-3"></i>
                                            <div>
                                                <small class="text-muted d-block">Tanggal Lapor</small>
                                                <strong><?= date('d F Y', strtotime($data_tiket['tanggal_lapor'])); ?></strong>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-tools text-muted me-3"></i>
                                            <div>
                                                <small class="text-muted d-block">Jenis Kerusakan</small>
                                                <strong><?= htmlspecialchars($data_tiket['jenis_aset']); ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-map-marker-alt me-2"></i>Lokasi & Progress
                                        </h6>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-location-dot text-muted me-3"></i>
                                            <div>
                                                <small class="text-muted d-block">Lokasi</small>
                                                <strong><?= htmlspecialchars($data_tiket['lokasi_aset']); ?></strong>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-calendar-check text-muted me-3"></i>
                                            <div>
                                                <small class="text-muted d-block">Tgl Selesai</small>
                                                <strong><?= $data_tiket['tgl_selesai'] ? date('d F Y', strtotime($data_tiket['tgl_selesai'])) : '-'; ?></strong>
                                            </div>
                                        </div>
                                        <div class="progress-bar-custom">
                                            <?php 
                                                $progress = 0;
                                                if($st == 'Pending') $progress = 25;
                                                if($st == 'Proses') $progress = 60;
                                                if($st == 'Selesai') $progress = 100;
                                                if($st == 'Ditolak') $progress = 100;
                                            ?>
                                            <div class="progress-fill" style="width: <?= $progress ?>%"></div>
                                        </div>
                                        <small class="text-muted">Progress: <?= $progress ?>%</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if(!empty($data_tiket['bukti_foto'])): ?>
                        <div class="mt-5">
                            <h5 class="fw-bold mb-3">
                                <i class="fas fa-images me-2 text-primary"></i>Foto Bukti
                            </h5>
                            <div class="row g-3" id="photoGallery">
                                <?php 
                                    $fotos = explode(',', $data_tiket['bukti_foto']);
                                    foreach($fotos as $index => $f): if(trim($f) != ''):
                                ?>
                                <div class="col-md-4">
                                    <a href="uploads/<?= $f ?>" class="gallery-item" data-fancybox="gallery">
                                        <div class="card border-0 overflow-hidden">
                                            <img src="uploads/<?= $f ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Bukti Foto <?= $index+1 ?>">
                                            <div class="card-footer bg-white text-center">
                                                <small class="text-muted">Bukti Foto <?= $index+1 ?></small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <?php endif; endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <h5 class="mt-5 mb-4 fw-bold">
                            <i class="fas fa-stream me-2 text-primary"></i>Timeline Proses
                        </h5>
                        <div class="timeline mt-3">
                            <div class="timeline-item <?= $st != 'Pending' ? 'completed' : 'active' ?>">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-check text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold">Laporan Diterima</h6>
                                        <p class="text-muted mb-0 small"><?= date('d M Y - H:i', strtotime($data_tiket['tanggal_lapor'])); ?></p>
                                        <p class="mb-0">Laporan telah masuk ke sistem kami.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($st != 'Pending' && $st != 'Ditolak'): ?>
                            <div class="timeline-item <?= $st == 'Selesai' ? 'completed' : 'active' ?>">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-tools text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold">Sedang Ditangani</h6>
                                        <p class="mb-0">Petugas sedang menindaklanjuti laporan.</p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($st == 'Selesai'): ?>
                            <div class="timeline-item completed">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-flag text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold text-success">Perbaikan Selesai</h6>
                                        <p class="text-muted mb-0 small"><?= date('d M Y', strtotime($data_tiket['tgl_selesai'])); ?></p>
                                        <p class="mb-0">Perbaikan telah selesai dilakukan.</p>
                                        <?php if ($data_tiket['foto_perbaikan']): ?>
                                            <div class="mt-3">
                                                <a href="uploads/<?= $data_tiket['foto_perbaikan']; ?>" data-fancybox="perbaikan">
                                                    <img src="uploads/<?= $data_tiket['foto_perbaikan']; ?>" class="img-fluid rounded" style="max-height: 150px;">
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($st == 'Ditolak'): ?>
                            <div class="timeline-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-times text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold text-danger">Laporan Ditolak</h6>
                                        <p class="mb-0">Mohon maaf, laporan tidak dapat diproses.</p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($page == 'about'): ?>
    <div class="page-header">
        <div class="container text-center">
            <h2 class="fw-bold mb-2">Tentang SIJAKA</h2>
            <p class="lead mb-0">Sistem Informasi Pengaduan Jalan Kerusakan Aset Kabupaten Kuningan</p>
        </div>
    </div>

    <section class="container my-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm border-0 mb-5 floating-element">
                    <div class="card-body p-5">
                        <h3 class="section-title fw-bold mb-4">Visi & Misi</h3>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card bg-primary text-white h-100">
                                    <div class="card-body p-4">
                                        <h4 class="fw-bold">
                                            <i class="fas fa-eye me-2"></i>Visi
                                        </h4>
                                        <p class="mb-0 mt-3">Menjadi sistem pengaduan kerusakan infrastruktur terdepan yang memberikan pelayanan terbaik bagi masyarakat Kabupaten Kuningan.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light h-100">
                                    <div class="card-body p-4">
                                        <h4 class="fw-bold text-primary">
                                            <i class="fas fa-bullseye me-2"></i>Misi
                                        </h4>
                                        <ul class="mt-3 ps-3">
                                            <li class="mb-2">Menyediakan platform pengaduan yang mudah diakses</li>
                                            <li class="mb-2">Mempercepat respon terhadap laporan kerusakan</li>
                                            <li class="mb-2">Meningkatkan transparansi penanganan laporan</li>
                                            <li>Mengoptimalkan pemeliharaan aset publik</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-5 floating-element">
                    <div class="card-body p-5">
                        <h3 class="section-title fw-bold mb-4">Sejarah</h3>
                        <p>SIJAKA (Sistem Informasi Pengaduan Kerusakan Aset) diluncurkan pada tahun 2025 oleh Dinas Pekerjaan Umum dan Tata Ruang (DPUTR) Kabupaten Kuningan sebagai respons terhadap meningkatnya kebutuhan masyarakat akan pelaporan kerusakan infrastruktur secara digital.</p>
                        <p>Sejak diluncurkan, sistem ini telah membantu menangani ribuan laporan kerusakan jalan, jembatan, drainase, dan infrastruktur publik lainnya di seluruh wilayah Kabupaten Kuningan.</p>
                    </div>
                </div>

                <div class="card shadow-sm floating-element">
                    <div class="card-body p-5">
                        <h3 class="section-title fw-bold mb-4">Struktur Organisasi</h3>
                        <div class="row text-center">
                            <div class="col-md-4 mb-4">
                                <div class="bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px;">
                                    <i class="fas fa-user-tie fa-3x text-primary"></i>
                                </div>
                                <h5>Kepala Dinas</h5>
                                <p class="text-muted">Ir. I Putu Bagiasna, M.T.</p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px;">
                                    <i class="fas fa-users fa-3x text-primary"></i>
                                </div>
                                <h5>Bidang Jalan & Jembatan</h5>
                                <p class="text-muted">Asep Abdus Syakur, S.T.</p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px;">
                                    <i class="fas fa-tools fa-3x text-primary"></i>
                                </div>
                                <h5>Tim Developer</h5>
                                <p class="text-muted">4 Orang Tenaga Ahli</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($page == 'contact'): ?>
    <div class="page-header">
        <div class="container text-center">
            <h2 class="fw-bold mb-2">Hubungi Kami</h2>
            <p class="lead mb-0">Kami siap membantu Anda dengan segala pertanyaan dan keluhan</p>
        </div>
    </div>

    <section class="container my-5">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="card-icon" style="width: 70px; height: 70px;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <h4>Alamat Kantor</h4>
                                <p class="text-muted">Jl. RE. Martadinata, Ancaran, Kuningan<br>Jawa Barat 45511</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="card-icon" style="width: 70px; height: 70px;">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <h4>Telepon</h4>
                                <p class="text-muted">(0232) 876146<br>Senin - Jumat, 08:00 - 16:00</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="card-icon" style="width: 70px; height: 70px;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <h4>Email</h4>
                                <p class="text-muted">dputrkuningan@gmail.com<br>darrkunproject@gmail.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="card-icon" style="width: 70px; height: 70px;">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h4>Jam Operasional</h4>
                                <p class="text-muted">Senin - Kamis: 08:00 - 16:00<br>Jumat: 08:00 - 11:00, 13:00 - 16:00</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body p-5">
                                <h3 class="section-title fw-bold mb-4">Kirim Pesan</h3>
                                <form id="formKontak">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Nama Lengkap</label>
                                            <input type="text" class="form-control" placeholder="Nama Anda" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Email</label>
                                            <input type="email" class="form-control" placeholder="email@contoh.com" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Subjek</label>
                                        <input type="text" class="form-control" placeholder="Subjek pesan" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Pesan</label>
                                        <textarea class="form-control" rows="5" placeholder="Tulis pesan Anda di sini..." required></textarea>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body p-4">
                                <h4 class="card-title mb-3">Lokasi Kantor</h4>
                                <div class="maps-placeholder">
                                    <div class="maps-overlay"></div>
                                    <div class="maps-content">
                                        <i class="fas fa-map-marked-alt fa-4x mb-3"></i>
                                        <h5>Peta Lokasi</h5>
                                        <p>DPUTR Kabupaten Kuningan</p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <p class="mb-2"><strong>Petunjuk Lokasi:</strong></p>
                                    <ul class="text-muted">
                                        <li>Dari alun-alun Kuningan, ambil jalan ke arah timur</li>
                                        <li>Lurus sekitar 2 km sampai menemukan Kantor DPUTR</li>
                                        <li>Lokasi berada di sebelah kanan jalan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-3">Media Sosial</h4>
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <a href="https://www.facebook.com/profile.php?id=61584151419956" class="text-decoration-none">
                                    <div class="card border-0 bg-primary text-white h-100">
                                        <div class="card-body py-4">
                                            <i class="fab fa-facebook-f fa-2x mb-3"></i>
                                            <h6>Facebook</h6>
                                            <small class="opacity-75">@DPUTR_Kuningan</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="https://www.instagram.com/darrkun_?igsh=MWZqZmhlNnh5endpbg==" class="text-decoration-none">
                                    <div class="card border-0 bg-instagram text-white h-100">
                                        <div class="card-body py-4">
                                            <i class="fab fa-instagram fa-2x mb-3"></i>
                                            <h6>Instagram</h6>
                                            <small class="opacity-75">@darrkun_</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="https://x.com/darrkteam?t=r7hy0qmOkStYmKcuim0oNQ&s=09" class="text-decoration-none">
                                    <div class="card border-0 bg-info text-white h-100">
                                        <div class="card-body py-4">
                                            <i class="fab fa-twitter fa-2x mb-3"></i>
                                            <h6>Twitter</h6>
                                            <small class="opacity-75">@darrkteam</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="https://www.youtube.com/@DAARKUNProject" class="text-decoration-none">
                                    <div class="card border-0 bg-danger text-white h-100">
                                        <div class="card-body py-4">
                                            <i class="fab fa-youtube fa-2x mb-3"></i>
                                            <h6>YouTube</h6>
                                            <small class="opacity-75">@DAARKUNProject</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Modern Footer -->
    <footer class="bg-dark text-white pt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 mb-4">
                    <div class="card bg-transparent border-0">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">
                                <img src="Logos.png" alt="Logo" height="40" class="me-2 rounded">
                                SIJAKA Kuningan
                            </h5>
                            <p class="text-white-50 mb-4">Sistem Informasi Pengaduan Jalan Kerusakan Aset Kabupaten Kuningan. Layanan pengaduan kerusakan infrastruktur publik.</p>
                            <div class="social-links">
                                <a href="https://www.facebook.com/profile.php?id=61584151419956" class="text-white" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://x.com/darrkteam?t=r7hy0qmOkStYmKcuim0oNQ&s=09" class="text-white" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://www.instagram.com/darrkun_?igsh=MWZqZmhlNnh5endpbg==" class="text-white" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="https://www.youtube.com/@DAARKUNProject" class="text-white" title="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="mailto:dputrkuningan@gmail.com" class="text-white" title="Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="row g-4">
                        <div class="col-md-4 mb-4">
                            <h6 class="fw-bold mb-3 text-primary">Tautan Cepat</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="?page=home" class="text-white-50 text-decoration-none d-flex align-items-center">
                                        <i class="fas fa-chevron-right me-2 fa-xs"></i>Beranda
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="?page=lapor" class="text-white-50 text-decoration-none d-flex align-items-center">
                                        <i class="fas fa-chevron-right me-2 fa-xs"></i>Lapor Kerusakan
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="?page=status" class="text-white-50 text-decoration-none d-flex align-items-center">
                                        <i class="fas fa-chevron-right me-2 fa-xs"></i>Cek Status
                                    </a>
                                </li>
                                <li>
                                    <a href="?page=about" class="text-white-50 text-decoration-none d-flex align-items-center">
                                        <i class="fas fa-chevron-right me-2 fa-xs"></i>Tentang Kami
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <h6 class="fw-bold mb-3 text-primary">Layanan</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="text-white-50 d-flex align-items-center">
                                        <i class="fas fa-road me-2 fa-xs"></i>Pengaduan Jalan
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <span class="text-white-50 d-flex align-items-center">
                                        <i class="fas fa-bridge me-2 fa-xs"></i>Pengaduan Jembatan
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <span class="text-white-50 d-flex align-items-center">
                                        <i class="fas fa-water me-2 fa-xs"></i>Pengaduan Drainase
                                    </span>
                                </li>
                                <li>
                                    <span class="text-white-50 d-flex align-items-center">
                                        <i class="fas fa-building me-2 fa-xs"></i>Pengaduan Gedung
                                    </span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <h6 class="fw-bold mb-3 text-primary">Kontak</h6>
                            <ul class="list-unstyled text-white-50">
                                <li class="mb-3 d-flex align-items-start">
                                    <i class="fas fa-map-marker-alt me-3 mt-1"></i>
                                    <span>Jl. RE. Martadinata, Ancaran, Kuningan<br>Jawa Barat 45511</span>
                                </li>
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="fas fa-phone-alt me-3"></i>
                                    <span>(0232) 876146</span>
                                </li>
                                <li class="d-flex align-items-center">
                                    <i class="fas fa-envelope me-3"></i>
                                    <span>dputrkuningan@gmail.com</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="my-4 border-white-10">
            
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="mb-0 text-white-50">
                        <i class="far fa-copyright me-1"></i> 2025 SIJAKA - DPUTR Kuningan. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-white-50 text-decoration-none me-3">
                        Kebijakan Privasi
                    </a>
                    <a href="#" class="text-white-50 text-decoration-none">
                        Syarat & Ketentuan
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    
    <script>
        // Initialize Fancybox for image galleries
        Fancybox.bind("[data-fancybox]", {
            // Your custom options
        });

        // Scroll to Top Functionality
        const scrollTop = document.querySelector('.scroll-top');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollTop.classList.add('active');
            } else {
                scrollTop.classList.remove('active');
            }
        });

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Hero Slider Script - Enhanced with autoplay
        <?php if ($page == 'home'): ?>
        $(document).ready(function() {
            let currentSlide = 0;
            const slides = $('.hero-slide');
            const indicators = $('.slider-indicator');
            const totalSlides = slides.length;
            let slideInterval;
            
            function showSlide(index) {
                slides.removeClass('active');
                indicators.removeClass('active');
                
                currentSlide = index;
                if (currentSlide >= totalSlides) currentSlide = 0;
                if (currentSlide < 0) currentSlide = totalSlides - 1;
                
                slides.eq(currentSlide).addClass('active');
                indicators.eq(currentSlide).addClass('active');
            }
            
            function startAutoSlide() {
                slideInterval = setInterval(() => {
                    showSlide(currentSlide + 1);
                }, 5000);
            }
            
            function stopAutoSlide() {
                clearInterval(slideInterval);
            }
            
            // Start autoplay
            startAutoSlide();
            
            // Click event for indicators
            indicators.on('click', function() {
                stopAutoSlide();
                const slideIndex = $(this).data('slide');
                showSlide(slideIndex);
                startAutoSlide();
            });
            
            // Pause on hover
            $('.hero-section').hover(
                () => stopAutoSlide(),
                () => startAutoSlide()
            );
            
            // Manual navigation with keyboard arrows
            $(document).on('keydown', function(e) {
                if (e.key === 'ArrowLeft') {
                    stopAutoSlide();
                    showSlide(currentSlide - 1);
                    startAutoSlide();
                } else if (e.key === 'ArrowRight') {
                    stopAutoSlide();
                    showSlide(currentSlide + 1);
                    startAutoSlide();
                }
            });
            
            // Animated counter for statistics
            $('.stats-counter').each(function() {
                const $this = $(this);
                const target = parseInt($this.data('target'));
                const duration = 2000;
                const steps = 60;
                const increment = target / steps;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        $this.text(target.toLocaleString());
                        clearInterval(timer);
                    } else {
                        $this.text(Math.floor(current).toLocaleString());
                    }
                }, duration / steps);
            });
        });
        <?php endif; ?>
        
        // Modern File Upload with Drag & Drop
        <?php if ($page == 'lapor'): ?>
        $(document).ready(function() {
            const fileInput = $('#bukti_foto');
            const previewContainer = $('#imagePreview');
            const fileList = $('#fileList');
            const fileUploadArea = $('.file-upload-area');
            let files = [];
            
            // Drag and drop functionality
            fileUploadArea.on('dragover', function(e) {
                e.preventDefault();
                fileUploadArea.css({
                    'border-color': 'var(--primary)',
                    'background-color': 'rgba(13, 110, 253, 0.05)'
                });
            });
            
            fileUploadArea.on('dragleave', function(e) {
                e.preventDefault();
                fileUploadArea.css({
                    'border-color': '#dee2e6',
                    'background-color': ''
                });
            });
            
            fileUploadArea.on('drop', function(e) {
                e.preventDefault();
                fileUploadArea.css({
                    'border-color': '#dee2e6',
                    'background-color': ''
                });
                
                const droppedFiles = e.originalEvent.dataTransfer.files;
                handleFiles(droppedFiles);
            });
            
            // File input change
            fileInput.on('change', function() {
                handleFiles(this.files);
            });
            
            function handleFiles(fileList) {
                files = Array.from(fileList);
                
                // Validate number of files
                if (files.length > 3) {
                    alert('Maksimal hanya boleh upload 3 foto!');
                    fileInput.val('');
                    return;
                }
                
                // Validate file size and type
                const validFiles = [];
                files.forEach(file => {
                    if (file.size > 5 * 1024 * 1024) {
                        alert(`File ${file.name} terlalu besar (Max 5MB)`);
                        return;
                    }
                    
                    if (!file.type.match('image.*')) {
                        alert(`File ${file.name} bukan gambar`);
                        return;
                    }
                    
                    validFiles.push(file);
                });
                
                files = validFiles;
                
                // Update file input
                const dataTransfer = new DataTransfer();
                files.forEach(file => dataTransfer.items.add(file));
                fileInput[0].files = dataTransfer.files;
                
                // Update preview
                updatePreview();
                updateFileList();
            }
            
            function updatePreview() {
                previewContainer.html('');
                
                if (files.length > 0) {
                    files.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const preview = $(`
                                <div class="preview-item position-relative">
                                    <img src="${e.target.result}" class="preview-image" alt="Preview">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" data-index="${index}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            `);
                            previewContainer.append(preview);
                        }
                        reader.readAsDataURL(file);
                    });
                    
                    // Add remove functionality
                    previewContainer.on('click', '.btn-danger', function() {
                        const index = $(this).data('index');
                        files.splice(index, 1);
                        
                        // Update file input
                        const dataTransfer = new DataTransfer();
                        files.forEach(file => dataTransfer.items.add(file));
                        fileInput[0].files = dataTransfer.files;
                        
                        updatePreview();
                        updateFileList();
                    });
                }
            }
            
            function updateFileList() {
                fileList.html('');
                
                if (files.length > 0) {
                    const list = $('<div class="mt-3"></div>');
                    files.forEach((file, index) => {
                        const size = (file.size / (1024 * 1024)).toFixed(2);
                        list.append(`
                            <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-2">
                                <div>
                                    <i class="fas fa-file-image text-primary me-2"></i>
                                    <span>${file.name}</span>
                                </div>
                                <div>
                                    <span class="badge bg-primary">${size} MB</span>
                                </div>
                            </div>
                        `);
                    });
                    fileList.append(list);
                }
            }
            
            // Loading animation for form submission
            function showLoading() {
                $('#loadingOverlay').fadeIn();
                $('#submitBtn').prop('disabled', true);
                $('#submitBtn').html('<i class="fas fa-spinner fa-spin me-2"></i>MENGIRIM...');
            }
        });
        <?php endif; ?>
        
        // Form validation and enhancement
        $(document).ready(function() {
            // Add focus effects to form elements
            $('input, textarea, select').on('focus', function() {
                $(this).parent().addClass('focused');
            }).on('blur', function() {
                $(this).parent().removeClass('focused');
            });
            
            // Form submission animation for contact form
            $('#formKontak').on('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                
                submitBtn.prop('disabled', true);
                submitBtn.html(`
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Mengirim...
                `);
                
                // Simulate API call
                setTimeout(() => {
                    alert('Pesan Anda telah berhasil dikirim! Kami akan merespons dalam 1-2 hari kerja.');
                    this.reset();
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                }, 1500);
            });
        });
        
        // Add floating animation to elements
        $(document).ready(function() {
            $('.floating-element').each(function(index) {
                $(this).css({
                    'animation-delay': (index * 0.2) + 's'
                });
            });
        });
        
        // Smooth scrolling for anchor links
        $(document).on('click', 'a[href^="#"]', function(e) {
            if ($(this).attr('href') !== '#') {
                e.preventDefault();
                const target = $(this).attr('href');
                if ($(target).length) {
                    $('html, body').animate({
                        scrollTop: $(target).offset().top - 80
                    }, 800);
                }
            }
        });
        
        // Add animation on scroll
        function animateOnScroll() {
            const elements = document.querySelectorAll('.card, .section-title, .feature-icon');
            
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementTop < windowHeight - 100) {
                    element.classList.add('animate');
                }
            });
        }
        
        window.addEventListener('scroll', animateOnScroll);
        animateOnScroll(); // Initial check
        
        // Instagram gradient color
        const style = document.createElement('style');
        style.textContent = `
            .bg-instagram {
                background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d) !important;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>