<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: ../login.php");
    exit;
}
require '../koneksi.php';

// --- PERBAIKAN: Definisikan $admin_id DI SINI (Paling Atas) ---
$admin_id = $_SESSION['id_user'] ?? 0;
// -------------------------------------------------------------

// Fungsi bantuan
function cekTabel($koneksi, $nama_tabel) {
    $result = mysqli_query($koneksi, "SHOW TABLES LIKE '$nama_tabel'");
    return ($result && mysqli_num_rows($result) > 0);
}

// Ambil preferensi tema (Sekarang aman karena $admin_id sudah ada isinya)
$theme_preference = 'light'; 
if (cekTabel($koneksi, 'pengaturan_sistem')) {
    // Pastikan tabel pengaturan_sistem punya kolom 'user_id' jika ingin filter per user, 
    // TAPI biasanya pengaturan sistem itu global (id=1), jadi querynya saya sesuaikan agar lebih aman:
    $q_theme = mysqli_query($koneksi, "SELECT theme FROM pengaturan_sistem WHERE id = 1 LIMIT 1");
    
    if ($q_theme && mysqli_num_rows($q_theme) > 0) {
        $theme_data = mysqli_fetch_assoc($q_theme);
        $theme_preference = $theme_data['theme'] ?? 'light';
    }
}

// Ambil data template
$templates = false;
if (cekTabel($koneksi, 'template_balasan')) {
    $templates = mysqli_query($koneksi, "SELECT * FROM template_balasan ORDER BY id DESC");
}

// Ambil data instansi
$instansi_data = [];
if (cekTabel($koneksi, 'pengaturan_instansi')) {
    $q_instansi = mysqli_query($koneksi, "SELECT * FROM pengaturan_instansi LIMIT 1");
    if ($q_instansi && mysqli_num_rows($q_instansi) > 0) {
        $instansi_data = mysqli_fetch_assoc($q_instansi);
    }
}

// Ambil data kategori
$kategories = false;
if (cekTabel($koneksi, 'kategori_aset')) {
    $kategories = mysqli_query($koneksi, "SELECT * FROM kategori_aset WHERE status = 'aktif'");
}

// Ambil data admin (Variable $admin_id sudah didefinisikan di atas, jadi aman)
$admin_data = [];
if (cekTabel($koneksi, 'users')) {
    $q_admin = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$admin_id'");
    if ($q_admin && mysqli_num_rows($q_admin) > 0) {
        $admin_data = mysqli_fetch_assoc($q_admin);
    }
}

// Default data jika kosong
if (empty($admin_data)) {
    $admin_data = [
        'username' => $_SESSION['nama'] ?? 'Admin',
        'email' => '',
        'nama_lengkap' => $_SESSION['nama'] ?? 'Administrator',
        'foto' => '',
        'telepon' => ''
    ];
}
?>

<!DOCTYPE html>
<html lang="id" data-theme="<?= $theme_preference ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan Sistem - SIJAKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            /* Light Theme Variables */
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --success: #4cc9f0;
            --warning: #f8961e;
            --danger: #e63946;
            --info: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --text-primary: #1a202c;
            --text-secondary: #4a5568;
            --text-muted: #718096;
            --bg-primary: #ffffff;
            --bg-secondary: #f7fafc;
            --bg-tertiary: #edf2f7;
            --border-color: #e2e8f0;
            --card-bg: #ffffff;
            --sidebar-bg: #ffffff;
            --navbar-bg: #ffffff;
            --input-bg: #ffffff;
            --table-bg: #ffffff;
            --table-hover: #f8fafc;
            --modal-bg: #ffffff;
            --shadow-color: rgba(0, 0, 0, 0.08);
            --shadow-hover: rgba(67, 97, 238, 0.15);
            --border-radius: 12px;
            --shadow: 0 8px 30px var(--shadow-color);
            --shadow-hover: 0 15px 40px var(--shadow-hover);
            --transition-speed: 0.3s;
        }

        [data-theme="dark"] {
            /* Dark Theme Variables */
            --primary: #5a7dff;
            --secondary: #6d28d9;
            --success: #60d9fa;
            --warning: #fbbf24;
            --danger: #f87171;
            --info: #60d9fa;
            --light: #1f2937;
            --dark: #f9fafb;
            --text-primary: #f7fafc;
            --text-secondary: #cbd5e0;
            --text-muted: #a0aec0;
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --border-color: #475569;
            --card-bg: #1e293b;
            --sidebar-bg: #1e293b;
            --navbar-bg: #1e293b;
            --input-bg: #334155;
            --table-bg: #1e293b;
            --table-hover: #2d3748;
            --modal-bg: #1e293b;
            --shadow-color: rgba(0, 0, 0, 0.3);
            --shadow-hover: rgba(67, 97, 238, 0.25);
        }

        body {
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            color: var(--text-primary);
            transition: background-color var(--transition-speed) ease,
                        color var(--transition-speed) ease;
        }

        /* Settings Container */
        .settings-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Sidebar */
        .settings-sidebar {
            background: var(--sidebar-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            position: sticky;
            top: 20px;
            overflow: hidden;
            transition: all var(--transition-speed) ease;
        }

        .settings-nav-item {
            padding: 16px 20px;
            border-left: 4px solid transparent;
            color: var(--text-secondary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all var(--transition-speed) ease;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            background: transparent;
        }

        .settings-nav-item:hover {
            background: var(--bg-tertiary);
            color: var(--primary);
            border-left-color: var(--primary);
        }

        .settings-nav-item.active {
            background: linear-gradient(90deg, color-mix(in srgb, var(--primary) 10%, transparent) 0%, color-mix(in srgb, var(--primary) 5%, transparent) 100%);
            color: var(--primary);
            border-left-color: var(--primary);
            font-weight: 600;
        }

        .settings-nav-item i {
            width: 20px;
            text-align: center;
        }

        /* Cards */
        .settings-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            transition: all var(--transition-speed) ease;
            overflow: hidden;
        }

        .settings-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .settings-card-header {
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 20px 30px;
            border-bottom: 1px solid var(--border-color);
        }

        .settings-card-body {
            padding: 30px;
            background: var(--card-bg);
        }

        /* Form Elements */
        .form-control, .form-select {
            background: var(--input-bg);
            border: 2px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 8px;
            padding: 10px 15px;
            transition: all var(--transition-speed) ease;
        }

        .form-control:focus, .form-select:focus {
            background: var(--input-bg);
            border-color: var(--primary);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 30%, transparent);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-label {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .form-check-label {
            color: var(--text-secondary);
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            font-weight: 600;
            color: white;
            transition: all var(--transition-speed) ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px color-mix(in srgb, var(--primary) 40%, transparent);
            color: white;
        }

        .btn-outline-primary {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--text-secondary);
        }

        .btn-outline-secondary:hover {
            background: var(--bg-tertiary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        /* Logo Preview */
        .logo-preview {
            width: 120px;
            height: 120px;
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: var(--bg-tertiary);
        }

        .logo-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Template Cards */
        .template-card {
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all var(--transition-speed) ease;
            position: relative;
        }

        .template-card:hover {
            border-color: var(--primary);
            background: var(--bg-tertiary);
        }

        /* Stats Cards */
        .stats-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--shadow);
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .stats-card .number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            display: block;
        }

        .stats-card .label {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Tables */
        .table {
            color: var(--text-primary);
            background: var(--table-bg);
            border-color: var(--border-color);
        }

        .table thead th {
            background: var(--bg-tertiary);
            border-color: var(--border-color);
            color: var(--text-secondary);
        }

        .table-hover tbody tr:hover {
            background-color: var(--table-hover);
        }

        /* Modal */
        .modal-content {
            background: var(--modal-bg);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .modal-header {
            border-bottom-color: var(--border-color);
            background: var(--card-bg);
        }

        .modal-footer {
            border-top-color: var(--border-color);
            background: var(--card-bg);
        }

        /* Avatar */
        .avatar-upload {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
        }

        .avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            font-weight: 600;
            cursor: pointer;
            overflow: hidden;
            border: 3px solid var(--border-color);
        }

        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Theme Toggle Switch */
        .theme-toggle {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
            margin: 0 10px;
        }

        .theme-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .theme-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            transition: .4s;
            border-radius: 34px;
        }

        .theme-toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .theme-toggle-slider {
            background: linear-gradient(90deg, var(--secondary) 0%, var(--primary) 100%);
        }

        input:checked + .theme-toggle-slider:before {
            transform: translateX(30px);
        }

        .theme-toggle-icons {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 8px;
            pointer-events: none;
        }

        .theme-toggle-icons i {
            color: white;
            font-size: 12px;
        }

        /* Alert */
        .alert-info {
            background: color-mix(in srgb, var(--info) 20%, transparent);
            border-color: color-mix(in srgb, var(--info) 30%, transparent);
            color: var(--text-primary);
        }

        .alert-info-subtle {
            background: color-mix(in srgb, var(--info) 10%, var(--bg-secondary));
            border: none;
            color: var(--text-secondary);
        }

        /* Navbar */
        .navbar {
            background: var(--navbar-bg);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 2px 10px var(--shadow-color);
        }

        .navbar-brand {
            color: var(--primary) !important;
        }

        /* Dropdown */
        .dropdown-menu {
            background: var(--modal-bg);
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 25px var(--shadow-color);
        }

        .dropdown-item {
            color: var(--text-primary);
        }

        .dropdown-item:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }

        .dropdown-divider {
            border-color: var(--border-color);
        }

        /* Kop Surat Preview */
        .kop-surat-preview {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 40px;
            background: var(--card-bg);
            min-height: 400px;
        }

        /* Progress Bar */
        .progress {
            background: var(--bg-tertiary);
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
        }

        /* Badges */
        .badge {
            color: white;
        }

        .badge.bg-primary {
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%) !important;
        }

        .badge.bg-success {
            background: linear-gradient(90deg, var(--success) 0%, #2a9d8f 100%) !important;
        }

        .badge.bg-danger {
            background: linear-gradient(90deg, var(--danger) 0%, #d62828 100%) !important;
        }

        .badge.bg-info {
            background: linear-gradient(90deg, var(--info) 0%, #118ab2 100%) !important;
        }

        /* Text Colors */
        .text-primary { color: var(--primary) !important; }
        .text-secondary { color: var(--text-secondary) !important; }
        .text-muted { color: var(--text-muted) !important; }
        .text-dark { color: var(--text-primary) !important; }

        /* Responsive */
        @media (max-width: 768px) {
            .settings-card-body {
                padding: 20px;
            }
            
            .settings-sidebar {
                position: static;
                margin-bottom: 20px;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* Selection Color */
        ::selection {
            background: color-mix(in srgb, var(--primary) 30%, transparent);
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light shadow-sm">
        <div class="container-fluid settings-container">
            <a class="navbar-brand fw-bold" href="dashboard_admin.php">
                <i class="fas fa-cogs me-2"></i>SIJAKA - Pengaturan
            </a>
            <div class="d-flex align-items-center gap-3">
                <!-- Theme Toggle -->
                <div class="d-flex align-items-center">
                    <i class="fas fa-sun text-warning me-1"></i>
                    <label class="theme-toggle">
                        <input type="checkbox" id="themeToggle" <?= $theme_preference == 'dark' ? 'checked' : '' ?>>
                        <span class="theme-toggle-slider"></span>
                        <div class="theme-toggle-icons">
                            <i class="fas fa-sun"></i>
                            <i class="fas fa-moon"></i>
                        </div>
                    </label>
                    <i class="fas fa-moon text-info ms-1"></i>
                </div>
                
                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light rounded-pill d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center me-2" 
                             style="width:30px; height:30px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white;">
                            <?= strtoupper(substr($admin_data['username'], 0, 1)) ?>
                        </div>
                        <span class="me-2"><?= htmlspecialchars($admin_data['username']) ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="dashboard_admin.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="settings-container py-4">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="settings-sidebar">
                    <div class="p-4 border-bottom">
                        <h5 class="fw-bold mb-0"><i class="fas fa-sliders-h me-2"></i>Pengaturan</h5>
                        <p class="text-muted small mt-2">Kelola konfigurasi sistem</p>
                    </div>
                    
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="settings-nav-item active w-100 text-start border-0" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#profile" type="button">
                            <i class="fas fa-user-cog"></i> Profil & Akun
                        </button>
                        <button class="settings-nav-item w-100 text-start border-0" id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button">
                            <i class="fas fa-shield-alt"></i> Keamanan
                        </button>
                        <button class="settings-nav-item w-100 text-start border-0" id="v-pills-letterhead-tab" data-bs-toggle="pill" data-bs-target="#letterhead" type="button">
                            <i class="fas fa-file-signature"></i> Kop Surat
                        </button>
                        <button class="settings-nav-item w-100 text-start border-0" id="v-pills-templates-tab" data-bs-toggle="pill" data-bs-target="#templates" type="button">
                            <i class="fas fa-mail-bulk"></i> Template
                            <?php if($templates): ?>
                            <span class="badge bg-primary ms-auto"><?= mysqli_num_rows($templates) ?></span>
                            <?php endif; ?>
                        </button>
                        <button class="settings-nav-item w-100 text-start border-0" id="v-pills-categories-tab" data-bs-toggle="pill" data-bs-target="#categories" type="button">
                            <i class="fas fa-layer-group"></i> Kategori Aset
                            <?php if($kategories): ?>
                            <span class="badge bg-primary ms-auto"><?= mysqli_num_rows($kategories) ?></span>
                            <?php endif; ?>
                        </button>
                        <button class="settings-nav-item w-100 text-start border-0" id="v-pills-system-tab" data-bs-toggle="pill" data-bs-target="#system" type="button">
                            <i class="fas fa-server"></i> Sistem & Umum
                        </button>
                    </div>
                    
                    <div class="p-3 mt-3 border-top">
                        <small class="text-muted d-block mb-2">Statistik Cepat</small>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="stats-card py-2">
                                    <span class="number"><?= ($templates) ? mysqli_num_rows($templates) : 0 ?></span>
                                    <span class="label">Template</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stats-card py-2">
                                    <span class="number"><?= ($kategories) ? mysqli_num_rows($kategories) : 0 ?></span>
                                    <span class="label">Kategori</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="profile">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <h5 class="mb-0"><i class="fas fa-user-cog me-2"></i>Profil Administrator</h5>
                            </div>
                            <div class="settings-card-body">
                                <form id="profileForm" action="proses_pengaturan.php?act=update_profile" method="POST">
                                    <div class="avatar-upload">
                                        <div class="avatar-preview" id="avatarPreview">
                                            <?php if(isset($admin_data['foto']) && $admin_data['foto']): ?>
                                                <img src="../uploads/avatars/<?= $admin_data['foto'] ?>" alt="Avatar">
                                            <?php else: ?>
                                                <?= strtoupper(substr($admin_data['username'], 0, 1)) ?>
                                            <?php endif; ?>
                                        </div>
                                        <input type="file" id="avatarInput" name="avatar" accept="image/*" onchange="previewAvatar(event)">
                                        <button type="button" class="btn btn-sm btn-primary mt-2 position-absolute top-100 start-50 translate-middle-x" onclick="document.getElementById('avatarInput').click()">
                                            <i class="fas fa-camera me-1"></i>Ganti
                                        </button>
                                    </div>
                                    <br>
                                    
                                    <div class="row mt-4">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($admin_data['username']) ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($admin_data['email'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" name="nama_lengkap" value="<?= htmlspecialchars($admin_data['nama_lengkap'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nomor Telepon</label>
                                            <input type="tel" class="form-control" name="telepon" value="<?= htmlspecialchars($admin_data['telepon'] ?? '') ?>">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="security">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Keamanan Akun</h5>
                            </div>
                            <div class="settings-card-body">
                                <form id="securityForm" action="proses_pengaturan.php?act=change_password" method="POST">
                                    <div class="alert alert-info border-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Pastikan password minimal 8 karakter dan mengandung kombinasi huruf dan angka.
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Password Saat Ini</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('currentPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Password Baru</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="newPassword" name="new_password" minlength="8" required>
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('newPassword')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="password-strength mt-2">
                                                <small>Kekuatan: <span id="passwordStrength">-</span></small>
                                                <div class="progress" style="height: 5px;">
                                                    <div class="progress-bar" id="passwordStrengthBar" style="width: 0%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Konfirmasi Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" minlength="8" required>
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmPassword')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <small id="passwordMatch" class="text-danger"></small>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key me-2"></i>Update Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="letterhead">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <h5 class="mb-0"><i class="fas fa-file-signature me-2"></i>Pengaturan Kop Surat</h5>
                            </div>
                            <div class="settings-card-body">
                                <form action="proses_pengaturan.php?act=update_letterhead" method="POST" enctype="multipart/form-data">
                                    <div class="row align-items-center mb-4">
                                        <div class="col-md-3">
                                            <div class="logo-preview" id="logoPreview">
                                                <img src="../<?= isset($instansi_data['logo']) ? $instansi_data['logo'] : 'assets/logo_default.png' ?>" 
                                                     alt="Logo Instansi" 
                                                     id="previewImage" 
                                                     onerror="this.src='https://ui-avatars.com/api/?name=Instansi&background=<?= urlencode($theme_preference == 'dark' ? '1e293b' : 'f8fafc') ?>&color=<?= urlencode($theme_preference == 'dark' ? '94a3b8' : '475569') ?>'">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="mb-3">
                                                <label class="form-label">Upload Logo Instansi</label>
                                                <input type="file" class="form-control" name="logo" accept="image/*" onchange="previewLogo(event)">
                                                <small class="text-muted">Format: PNG dengan background transparan, maksimal 2MB</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Instansi</label>
                                            <input type="text" class="form-control" name="nama_instansi" value="<?= htmlspecialchars($instansi_data['nama_instansi'] ?? 'PEMERINTAH KABUPATEN') ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Dinas/Unit</label>
                                            <input type="text" class="form-control" name="nama_dinas" value="<?= htmlspecialchars($instansi_data['nama_dinas'] ?? 'DINAS PEKERJAAN UMUM') ?>" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Alamat Lengkap</label>
                                            <textarea class="form-control" name="alamat" rows="2" required><?= htmlspecialchars($instansi_data['alamat'] ?? '') ?></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Telepon</label>
                                            <input type="text" class="form-control" name="telepon" value="<?= htmlspecialchars($instansi_data['telepon'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email_instansi" value="<?= htmlspecialchars($instansi_data['email'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Website</label>
                                            <input type="url" class="form-control" name="website" value="<?= htmlspecialchars($instansi_data['website'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Kode Pos</label>
                                            <input type="text" class="form-control" name="kode_pos" value="<?= htmlspecialchars($instansi_data['kode_pos'] ?? '') ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#previewModal">
                                            <i class="fas fa-eye me-2"></i>Preview
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="templates">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <h5 class="mb-0"><i class="fas fa-mail-bulk me-2"></i>Template Balasan</h5>
                            </div>
                            <div class="settings-card-body">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#templateModal">
                                        <i class="fas fa-plus me-2"></i>Tambah Template
                                    </button>
                                    <div class="input-group" style="max-width: 300px;">
                                        <input type="text" class="form-control" placeholder="Cari template..." id="searchTemplate">
                                        <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                                
                                <div class="row" id="templateList">
                                    <?php if ($templates && mysqli_num_rows($templates) > 0): ?>
                                        <?php while($tpl = mysqli_fetch_assoc($templates)): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="template-card">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="fw-bold mb-0"><?= htmlspecialchars($tpl['judul']) ?></h6>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-link" type="button" data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#" onclick="copyTemplate('<?= addslashes($tpl['isi_pesan']) ?>')"><i class="fas fa-copy me-2"></i>Salin</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger" href="proses_pengaturan.php?act=delete_template&id=<?= $tpl['id'] ?>" onclick="return confirmDelete()"><i class="fas fa-trash me-2"></i>Hapus</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <p class="text-muted mb-0 small template-content"><?= substr(htmlspecialchars($tpl['isi_pesan']), 0, 100) ?>...</p>
                                                <div class="mt-3">
                                                    <span class="badge bg-secondary"><?= htmlspecialchars($tpl['kategori']) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <div class="col-12 text-center text-muted py-5">
                                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                                            <p>Belum ada template balasan.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="categories">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>Manajemen Kategori</h5>
                            </div>
                            <div class="settings-card-body">
                                <div class="mb-4">
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#categoryModal">
                                        <i class="fas fa-plus me-2"></i>Tambah Kategori
                                    </button>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kategori</th>
                                                <th>Deskripsi</th>
                                                <th>Icon</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($kategories && mysqli_num_rows($kategories) > 0): ?>
                                                <?php while($kat = mysqli_fetch_assoc($kategories)): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="rounded p-2 me-2" style="background-color: <?= $kat['warna'] ?>20;">
                                                                <i class="fas fa-<?= $kat['icon'] ?>" style="color: <?= $kat['warna'] ?>;"></i>
                                                            </div>
                                                            <strong><?= htmlspecialchars($kat['nama_kategori']) ?></strong>
                                                        </div>
                                                    </td>
                                                    <td><?= htmlspecialchars($kat['deskripsi']) ?></td>
                                                    <td><code><?= $kat['icon'] ?></code></td>
                                                    <td>
                                                        <span class="badge bg-<?= $kat['status'] == 'aktif' ? 'success' : 'danger' ?>">
                                                            <?= ucfirst($kat['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="proses_pengaturan.php?act=delete_category&id=<?= $kat['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirmDelete()">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr><td colspan="5" class="text-center py-4">Belum ada kategori aset.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="system">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <h5 class="mb-0"><i class="fas fa-server me-2"></i>Pengaturan Sistem</h5>
                            </div>
                            <div class="settings-card-body">
                                <form action="proses_pengaturan.php?act=system_settings" method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Aplikasi</label>
                                            <input type="text" class="form-control" name="app_name" value="SIJAKA" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Versi</label>
                                            <input type="text" class="form-control" name="app_version" value="2.0.1" readonly>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="maintenance_mode" id="maintenanceMode" role="switch">
                                                <label class="form-check-label" for="maintenanceMode">Mode Maintenance (Hanya Admin yang bisa login)</label>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Simpan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="templateModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Template Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses_pengaturan.php?act=add_template" method="POST" id="templateForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Template</label>
                            <input type="text" class="form-control" name="judul" placeholder="Contoh: Laporan Ditolak" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="kategori">
                                <option value="penolakan">Penolakan</option>
                                <option value="penerimaan">Penerimaan</option>
                                <option value="informasi">Informasi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Isi Pesan</label>
                            <textarea class="form-control" name="isi" rows="6" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="categoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses_pengaturan.php?act=add_category" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" name="nama_kategori" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icon (FontAwesome)</label>
                            <select class="form-select" name="icon">
                                <option value="road">Jalan (road)</option>
                                <option value="bridge">Jembatan (bridge)</option>
                                <option value="water">Drainase (water)</option>
                                <option value="building">Gedung (building)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Warna</label>
                            <input type="color" class="form-control form-control-color" name="warna" value="#4361ee">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade preview-modal" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Kop Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="kop-surat-preview text-center" id="kopPreview"></div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-5 py-3 border-top text-center text-muted">
        <div class="settings-container">
            <small>SIJAKA v2.0 &copy; <?= date('Y') ?></small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Theme Management
        const themeToggle = document.getElementById('themeToggle');
        const htmlElement = document.documentElement;
        
        // Load theme preference
        function loadTheme() {
            const savedTheme = localStorage.getItem('sijaka_theme') || '<?= $theme_preference ?>';
            htmlElement.setAttribute('data-theme', savedTheme);
            themeToggle.checked = savedTheme === 'dark';
        }
        
        // Save theme preference
        function saveTheme(theme) {
            localStorage.setItem('sijaka_theme', theme);
            
            // Send to server to save in database
            fetch('proses_pengaturan.php?act=save_theme', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `theme=${theme}`
            });
        }
        
        // Toggle theme
        themeToggle.addEventListener('change', function() {
            const newTheme = this.checked ? 'dark' : 'light';
            htmlElement.setAttribute('data-theme', newTheme);
            saveTheme(newTheme);
            
            // Update avatar if it uses placeholder
            updateAvatarPlaceholder(newTheme);
        });
        
        // Update avatar placeholder based on theme
        function updateAvatarPlaceholder(theme) {
            const avatarImg = document.getElementById('avatarPreview').querySelector('img');
            if (avatarImg && avatarImg.src.includes('ui-avatars.com')) {
                const name = encodeURIComponent('<?= $admin_data["username"] ?? "A" ?>');
                const bg = theme === 'dark' ? '1e293b' : 'f8fafc';
                const color = theme === 'dark' ? '94a3b8' : '475569';
                avatarImg.src = `https://ui-avatars.com/api/?name=${name}&background=${bg}&color=${color}`;
            }
        }
        
        // Initialize theme
        document.addEventListener('DOMContentLoaded', function() {
            loadTheme();
            
            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
        
        // Password strength checker
        document.getElementById('newPassword')?.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            const strengthText = document.getElementById('passwordStrength');
            
            let strength = 0;
            if (password.length >= 8) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            if (/[^A-Za-z0-9]/.test(password)) strength += 25;
            
            strengthBar.style.width = strength + '%';
            
            if (strength < 50) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Lemah';
            } else if (strength < 75) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Sedang';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Kuat';
            }
        });
        
        // Password confirmation check
        document.getElementById('confirmPassword')?.addEventListener('input', function() {
            const newPass = document.getElementById('newPassword').value;
            const confirmPass = this.value;
            const matchText = document.getElementById('passwordMatch');
            
            if (confirmPass && newPass !== confirmPass) {
                matchText.textContent = 'Password tidak cocok!';
            } else {
                matchText.textContent = '';
            }
        });
        
        // Toggle password visibility
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === "password" ? "text" : "password";
        }
        
        // Logo preview
        function previewLogo(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('previewImage').src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
        
        // Avatar preview
        function previewAvatar(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('avatarPreview').innerHTML = `<img src="${reader.result}" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">`;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
        
        function confirmDelete() {
            return Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4361ee',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                return result.isConfirmed;
            });
        }
        
        // Copy template to clipboard
        function copyTemplate(text) {
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Template telah disalin ke clipboard',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }
        
        // Preview Kop Surat
        document.getElementById('previewModal')?.addEventListener('show.bs.modal', function () {
            const logoSrc = document.getElementById('previewImage').src;
            const instansi = document.querySelector('input[name="nama_instansi"]').value;
            const dinas = document.querySelector('input[name="nama_dinas"]').value;
            const alamat = document.querySelector('textarea[name="alamat"]').value;
            
            document.getElementById('kopPreview').innerHTML = `
                <img src="${logoSrc}" height="80" class="mb-3">
                <h4 class="fw-bold text-uppercase mb-1">${instansi}</h4>
                <h5 class="fw-bold mb-2">${dinas}</h5>
                <p class="mb-0 small">${alamat}</p>
                <hr style="border-top: 3px double var(--border-color); margin: 30px 0;">
                <div class="text-start mt-4">
                    <p><strong>Nomor</strong>: 001/SIJAKA/I/2024</p>
                    <p><strong>Lampiran</strong>: -</p>
                    <p><strong>Perihal</strong>: Konfirmasi Laporan</p>
                </div>
            `;
        });
        
        // Search templates
        document.getElementById('searchTemplate')?.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const templates = document.querySelectorAll('.template-card');
            
            templates.forEach(template => {
                const title = template.querySelector('h6').textContent.toLowerCase();
                const content = template.querySelector('.template-content').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || content.includes(searchTerm)) {
                    template.parentElement.style.display = 'block';
                } else {
                    template.parentElement.style.display = 'none';
                }
            });
        });
        
        // Auto-save form data (optional feature)
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            const formId = form.id || 'form-' + Math.random().toString(36).substr(2, 9);
            
            form.addEventListener('input', function() {
                const formData = new FormData(this);
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });
                localStorage.setItem('form_autosave_' + formId, JSON.stringify(data));
            });
            
            // Load auto-saved data
            const savedData = localStorage.getItem('form_autosave_' + formId);
            if (savedData) {
                const data = JSON.parse(savedData);
                Object.keys(data).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input && input.type !== 'file') {
                        input.value = data[key];
                    }
                });
            }
        });
    </script>
</body>
</html>