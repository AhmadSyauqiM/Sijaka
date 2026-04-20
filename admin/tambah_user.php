<?php
session_start();
require '../koneksi.php';

// 1. CEK KEAMANAN (Hanya Admin)
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// 2. PROSES JIKA TOMBOL DITEKAN
if (isset($_POST['simpan_user'])) {
    
    // Ambil Data
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $username     = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password     = mysqli_real_escape_string($koneksi, $_POST['password']); 
    $role         = mysqli_real_escape_string($koneksi, $_POST['role']);
    $divisi       = mysqli_real_escape_string($koneksi, $_POST['divisi']);

    // 3. VALIDASI USERNAME (BAGIAN YANG ERROR TADI)
    // Kita cek dulu apakah query berhasil dijalankan?
    $query_cek = "SELECT username FROM admin WHERE username = '$username'";
    $cek_user  = mysqli_query($koneksi, $query_cek);

    // --- PERBAIKAN UTAMA DISINI ---
    if (!$cek_user) {
        // Jika query gagal (return false), tampilkan pesan error asli dari MySQL
        die("FATAL ERROR SQL: " . mysqli_error($koneksi)); 
    }
    // -------------------------------
    
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>
                alert('GAGAL: Username \'$username\' sudah dipakai!');
                window.history.back(); 
              </script>";
    } else {
        // 4. SIMPAN DATA
        $query_simpan = "INSERT INTO admin (username, password, nama_lengkap, role, divisi) 
                         VALUES ('$username', '$password', '$nama_lengkap', '$role', '$divisi')";
        
        if (mysqli_query($koneksi, $query_simpan)) {
            echo "<script>
                    alert('SUKSES! User berhasil ditambahkan.');
                    window.location='dashboard_admin.php';
                  </script>";
        } else {
            echo "<script>alert('Gagal Simpan: " . mysqli_error($koneksi) . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - SIJAKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --primary-light: #4cc9f0;
            --secondary: #7209b7;
            --accent: #4895ef;
            --light: #f8f9fa;
            --dark: #1e293b;
            --success: #4ade80;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gradient-1: linear-gradient(135deg, #4361ee, #3a0ca3, #7209b7);
            --gradient-2: linear-gradient(135deg, #4cc9f0, #4361ee);
            --gradient-3: linear-gradient(135deg, #4361ee, #4895ef);
            --glass: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --shadow-soft: 0 10px 30px rgba(67, 97, 238, 0.15);
            --shadow-hard: 0 20px 50px rgba(67, 97, 238, 0.25);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--gradient-1);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
            padding: 20px;
        }
        
        /* Background Particles */
        .bg-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }
        
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: floatParticle 20s infinite linear;
            opacity: 0;
        }
        
        @keyframes floatParticle {
            0% {
                transform: translateY(100vh) rotate(0deg) scale(0.5);
                opacity: 0;
            }
            10% {
                opacity: 0.5;
            }
            90% {
                opacity: 0.5;
            }
            100% {
                transform: translateY(-100px) rotate(360deg) scale(1);
                opacity: 0;
            }
        }
        
        /* Background Shapes */
        .bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: floatShape 25s infinite ease-in-out;
        }
        
        .shape-1 {
            width: 400px;
            height: 400px;
            top: -200px;
            left: -200px;
            animation-delay: 0s;
        }
        
        .shape-2 {
            width: 300px;
            height: 300px;
            bottom: -150px;
            right: -150px;
            animation-delay: 5s;
        }
        
        .shape-3 {
            width: 200px;
            height: 200px;
            top: 50%;
            left: 80%;
            animation-delay: 10s;
        }
        
        @keyframes floatShape {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(100px, 80px) scale(1.1); }
            50% { transform: translate(-80px, 120px) scale(0.9); }
            75% { transform: translate(-120px, -60px) scale(1.05); }
        }
        
        /* Main Container */
        .main-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: var(--shadow-hard);
            border: 1px solid var(--glass-border);
            animation: slideUp 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
            opacity: 0;
            transform: translateY(50px);
        }
        
        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Header */
        .form-header {
            background: var(--gradient-2);
            padding: 30px 40px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .form-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            z-index: 0;
        }
        
        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .header-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            animation: pulseIcon 2s infinite alternate;
        }
        
        @keyframes pulseIcon {
            from { transform: scale(1); }
            to { transform: scale(1.1); }
        }
        
        .header-text h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 5px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        
        .header-text p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        /* Form Body */
        .form-body {
            padding: 40px;
            animation: fadeIn 1s 0.3s forwards;
            opacity: 0;
        }
        
        @keyframes fadeIn {
            to { opacity: 1; }
        }
        
        /* Message Styling */
        .message-container {
            animation: slideDown 0.5s ease forwards;
            opacity: 0;
            transform: translateY(-20px);
            margin-bottom: 30px;
        }
        
        @keyframes slideDown {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-message {
            border-radius: 15px;
            padding: 18px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .alert-success {
            background: linear-gradient(90deg, #d4edda, #c3e6cb);
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-error {
            background: linear-gradient(90deg, #f8d7da, #f5c6cb);
            border: 1px solid #f5c6cb;
            color: #721c24;
            animation: shakeError 0.5s ease forwards;
        }
        
        @keyframes shakeError {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        /* Form Groups */
        .form-group {
            margin-bottom: 30px;
            position: relative;
            animation: slideIn 0.6s forwards;
            opacity: 0;
            transform: translateX(-20px);
        }
        
        .form-group:nth-child(1) { animation-delay: 0.4s; }
        .form-group:nth-child(2) { animation-delay: 0.5s; }
        .form-group:nth-child(3) { animation-delay: 0.6s; }
        
        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
        }
        
        .form-label i {
            color: var(--primary);
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .form-control {
            height: 60px;
            border-radius: 15px;
            border: 2px solid #e2e8f0;
            padding: 0 20px;
            font-size: 1.05rem;
            font-weight: 500;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 
                0 10px 25px rgba(67, 97, 238, 0.15),
                0 0 0 3px rgba(67, 97, 238, 0.1);
            transform: translateY(-3px);
        }
        
        .row .col-md-6 {
            animation: slideIn 0.6s forwards;
            opacity: 0;
            transform: translateX(-20px);
        }
        
        .row .col-md-6:nth-child(1) { animation-delay: 0.7s; }
        .row .col-md-6:nth-child(2) { animation-delay: 0.8s; }
        
        .form-select {
            height: 60px;
            border-radius: 15px;
            border: 2px solid #e2e8f0;
            padding: 0 20px;
            font-size: 1.05rem;
            font-weight: 500;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%234361ee' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px 12px;
        }
        
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 
                0 10px 25px rgba(67, 97, 238, 0.15),
                0 0 0 3px rgba(67, 97, 238, 0.1);
            transform: translateY(-3px);
        }
        
        /* Button Group */
        .button-group {
            display: flex;
            gap: 20px;
            margin-top: 40px;
            animation: fadeIn 1s 1s forwards;
            opacity: 0;
        }
        
        .btn-simpan {
            flex: 1;
            height: 65px;
            border-radius: 15px;
            background: var(--gradient-2);
            border: none;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
            letter-spacing: 0.5px;
        }
        
        .btn-simpan:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 15px 40px rgba(67, 97, 238, 0.4),
                0 0 20px rgba(76, 201, 240, 0.3);
            letter-spacing: 1px;
        }
        
        .btn-simpan:active {
            transform: translateY(-2px);
        }
        
        .btn-simpan::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.7s;
        }
        
        .btn-simpan:hover::before {
            left: 100%;
        }
        
        .btn-kembali {
            flex: 1;
            height: 65px;
            border-radius: 15px;
            background: rgba(100, 116, 139, 0.1);
            border: 2px solid #64748b;
            color: #64748b;
            font-weight: 700;
            font-size: 1.2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 5px 20px rgba(100, 116, 139, 0.1);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-kembali:hover {
            background: rgba(67, 97, 238, 0.1);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.2);
        }
        
        /* Floating Elements */
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 5;
        }
        
        .floating-element {
            position: absolute;
            width: 40px;
            height: 40px;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(67, 97, 238, 0.2);
            animation: floatElement 20s infinite ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: rgba(67, 97, 238, 0.5);
        }
        
        .floating-element:nth-child(1) {
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }
        
        .floating-element:nth-child(2) {
            top: 70%;
            right: 5%;
            animation-delay: 5s;
        }
        
        .floating-element:nth-child(3) {
            top: 40%;
            left: 3%;
            animation-delay: 10s;
        }
        
        @keyframes floatElement {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(30px, 40px) rotate(90deg); }
            50% { transform: translate(-20px, 30px) rotate(180deg); }
            75% { transform: translate(40px, -20px) rotate(270deg); }
        }
        
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(30, 41, 59, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.5s ease;
        }
        
        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .spinner-container {
            text-align: center;
        }
        
        .cube-spinner {
            width: 80px;
            height: 80px;
            position: relative;
            margin: 0 auto 30px;
            transform-style: preserve-3d;
            animation: rotateCube 3s infinite linear;
        }
        
        .cube-face {
            position: absolute;
            width: 80px;
            height: 80px;
            background: var(--gradient-2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            opacity: 0.8;
        }
        
        .front  { transform: translateZ(40px); }
        .back   { transform: rotateY(180deg) translateZ(40px); }
        .right  { transform: rotateY(90deg) translateZ(40px); }
        .left   { transform: rotateY(-90deg) translateZ(40px); }
        .top    { transform: rotateX(90deg) translateZ(40px); }
        .bottom { transform: rotateX(-90deg) translateZ(40px); }
        
        @keyframes rotateCube {
            0% { transform: rotateX(0) rotateY(0) rotateZ(0); }
            100% { transform: rotateX(360deg) rotateY(360deg) rotateZ(360deg); }
        }
        
        .loading-text {
            color: white;
            font-size: 1.3rem;
            font-weight: 500;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
            }
            
            .form-header {
                padding: 25px 20px;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .form-body {
                padding: 25px 20px;
            }
            
            .button-group {
                flex-direction: column;
                gap: 15px;
            }
            
            .btn-simpan, .btn-kembali {
                height: 55px;
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Background Particles -->
    <div class="bg-particles" id="bgParticles"></div>
    
    <!-- Background Shapes -->
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    
    <!-- Floating Elements -->
    <div class="floating-elements">
        <div class="floating-element"><i class="fas fa-user-plus"></i></div>
        <div class="floating-element"><i class="fas fa-users"></i></div>
        <div class="floating-element"><i class="fas fa-id-card"></i></div>
    </div>
    
    <!-- Main Container -->
    <div class="main-container">
        <!-- Header -->
        <div class="form-header">
            <div class="header-content">
                <div class="header-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="header-text">
                    <h1>Tambah Pengguna Baru</h1>
                    <p>SIJAKA - Sistem Informasi Pengaduan Masyarakat</p>
                </div>
            </div>
        </div>
        
        <!-- Form Body -->
        <div class="form-body">
            <?php if(isset($message) && $message != ""): ?>
                <div class="message-container">
                    <div class="alert-message <?php echo $message_type == 'success' ? 'alert-success' : 'alert-error'; ?>">
                        <i class="fas <?php echo $message_type == 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?> fa-lg"></i>
                        <span><?php echo $message; ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="userForm">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user"></i>
                        Nama Lengkap
                    </label>
                    <input type="text" name="nama_lengkap" class="form-control" required 
                           placeholder="Masukkan nama lengkap"
                           value="<?php echo isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-at"></i>
                        Username
                    </label>
                    <input type="text" name="username" class="form-control" required 
                           placeholder="Masukkan username"
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <input type="text" name="password" class="form-control" required 
                           placeholder="Masukkan password"
                           value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">
                            <i class="fas fa-user-tag"></i>
                            Jabatan
                        </label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="kadis" <?php echo (isset($_POST['role']) && $_POST['role'] == 'kadis') ? 'selected' : ''; ?>>Kadis</option>
                            <option value="kabid" <?php echo (isset($_POST['role']) && $_POST['role'] == 'kabid') ? 'selected' : ''; ?>>Kabid</option>
                            <option value="pekerja" <?php echo (isset($_POST['role']) && $_POST['role'] == 'pekerja') ? 'selected' : ''; ?>>Pekerja</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label class="form-label">
                            <i class="fas fa-building"></i>
                            Divisi
                        </label>
                        <select name="divisi" class="form-select" required>
                            <option value="Umum" <?php echo (isset($_POST['divisi']) && $_POST['divisi'] == 'Umum') ? 'selected' : ''; ?>>Umum</option>
                            <option value="Bina Marga" <?php echo (isset($_POST['divisi']) && $_POST['divisi'] == 'Bina Marga') ? 'selected' : ''; ?>>Bina Marga</option>
                            <option value="Cipta Karya" <?php echo (isset($_POST['divisi']) && $_POST['divisi'] == 'Cipta Karya') ? 'selected' : ''; ?>>Cipta Karya</option>
                        </select>
                    </div>
                </div>
                
                <div class="button-group">
                    <button type="submit" name="simpan_user" class="btn btn-simpan">
                        <i class="fas fa-save me-2"></i>Simpan Pengguna
                    </button>
                    <a href="dashboard_admin.php" class="btn btn-kembali">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-container">
            <div class="cube-spinner">
                <div class="cube-face front"></div>
                <div class="cube-face back"></div>
                <div class="cube-face right"></div>
                <div class="cube-face left"></div>
                <div class="cube-face top"></div>
                <div class="cube-face bottom"></div>
            </div>
            <div class="loading-text">Menyimpan data pengguna...</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inisialisasi variabel
        document.addEventListener('DOMContentLoaded', function() {
            const userForm = document.getElementById('userForm');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const bgParticles = document.getElementById('bgParticles');
            
            // Buat partikel background
            createBackgroundParticles();
            
            // Form submission handler
            userForm.addEventListener('submit', function(e) {
                // Validasi form
                if (!validateForm()) {
                    e.preventDefault();
                    return;
                }
                
                // Tampilkan loading overlay
                showLoading();
                
                // Biarkan form submit normal
            });
            
            // Buat partikel background
            function createBackgroundParticles() {
                const particleCount = 25;
                
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.classList.add('particle');
                    
                    // Ukuran random
                    const size = Math.random() * 15 + 5;
                    particle.style.width = `${size}px`;
                    particle.style.height = `${size}px`;
                    
                    // Posisi random
                    particle.style.left = `${Math.random() * 100}%`;
                    
                    // Warna sesuai tema
                    const colors = [
                        'rgba(67, 97, 238, 0.2)',
                        'rgba(76, 201, 240, 0.2)',
                        'rgba(58, 12, 163, 0.2)',
                        'rgba(114, 9, 183, 0.2)',
                        'rgba(255, 255, 255, 0.1)'
                    ];
                    particle.style.background = colors[Math.floor(Math.random() * colors.length)];
                    
                    // Animasi dengan delay random
                    const duration = Math.random() * 20 + 15;
                    const delay = Math.random() * 10;
                    particle.style.animationDuration = `${duration}s`;
                    particle.style.animationDelay = `${delay}s`;
                    
                    bgParticles.appendChild(particle);
                }
            }
            
            // Validasi form
            function validateForm() {
                let isValid = true;
                const inputs = userForm.querySelectorAll('input[required], select[required]');
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        markInvalid(input);
                        isValid = false;
                    } else {
                        markValid(input);
                    }
                });
                
                if (!isValid) {
                    // Animasi shake untuk form
                    userForm.style.animation = 'shakeError 0.5s ease';
                    setTimeout(() => {
                        userForm.style.animation = '';
                    }, 500);
                    
                    // Tampilkan partikel error
                    createErrorParticles();
                }
                
                return isValid;
            }
            
            // Mark input sebagai invalid
            function markInvalid(input) {
                input.style.borderColor = 'var(--danger)';
                input.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
            }
            
            // Mark input sebagai valid
            function markValid(input) {
                input.style.borderColor = 'var(--success)';
                input.style.boxShadow = '0 0 0 3px rgba(40, 167, 69, 0.1)';
            }
            
            // Tampilkan loading
            function showLoading() {
                loadingOverlay.classList.add('active');
                
                // Tambahkan partikel loading
                createLoadingParticles();
            }
            
            // Buat partikel error
            function createErrorParticles() {
                const formRect = userForm.getBoundingClientRect();
                const particleCount = 8;
                
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.style.position = 'fixed';
                    particle.style.width = '6px';
                    particle.style.height = '6px';
                    particle.style.background = 'var(--danger)';
                    particle.style.borderRadius = '50%';
                    particle.style.left = `${formRect.left + Math.random() * formRect.width}px`;
                    particle.style.top = `${formRect.top}px`;
                    particle.style.zIndex = '1000';
                    particle.style.pointerEvents = 'none';
                    
                    document.body.appendChild(particle);
                    
                    // Animate particle
                    const angle = Math.random() * Math.PI * 2;
                    const distance = Math.random() * 60 + 30;
                    const targetX = Math.cos(angle) * distance;
                    const targetY = Math.sin(angle) * distance;
                    
                    const animation = particle.animate([
                        { transform: 'translate(0, 0) scale(1)', opacity: 1 },
                        { transform: `translate(${targetX}px, ${targetY}px) scale(0)`, opacity: 0 }
                    ], {
                        duration: 1000,
                        easing: 'ease-out'
                    });
                    
                    animation.onfinish = () => particle.remove();
                }
            }
            
            // Buat partikel loading
            function createLoadingParticles() {
                const particleCount = 10;
                const overlayRect = loadingOverlay.getBoundingClientRect();
                
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.style.position = 'fixed';
                    particle.style.width = '8px';
                    particle.style.height = '8px';
                    particle.style.background = 'var(--primary)';
                    particle.style.borderRadius = '50%';
                    particle.style.left = `${Math.random() * 100}%`;
                    particle.style.top = `${Math.random() * 100}%`;
                    particle.style.zIndex = '10000';
                    particle.style.pointerEvents = 'none';
                    
                    document.body.appendChild(particle);
                    
                    // Animate particle menuju center loading
                    const startX = parseFloat(particle.style.left) / 100 * window.innerWidth;
                    const startY = parseFloat(particle.style.top) / 100 * window.innerHeight;
                    
                    const targetX = overlayRect.left + overlayRect.width / 2 - startX;
                    const targetY = overlayRect.top + overlayRect.height / 2 - startY;
                    
                    const animation = particle.animate([
                        { transform: 'translate(0, 0) scale(1)', opacity: 1 },
                        { transform: `translate(${targetX}px, ${targetY}px) scale(0)`, opacity: 0 }
                    ], {
                        duration: 1200,
                        easing: 'ease-out',
                        delay: Math.random() * 500
                    });
                    
                    animation.onfinish = () => particle.remove();
                }
            }
            
            // Animasi input focus
            const inputs = userForm.querySelectorAll('.form-control, .form-select');
            inputs.forEach(input => {
                // Efek saat input focus
                input.addEventListener('focus', function() {
                    this.style.transform = 'translateY(-3px)';
                    this.style.boxShadow = '0 10px 25px rgba(67, 97, 238, 0.15), 0 0 0 3px rgba(67, 97, 238, 0.1)';
                    
                    // Tambahkan partikel focus
                    createFocusParticles(this);
                });
                
                // Efek saat input blur
                input.addEventListener('blur', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.05)';
                });
                
                // Efek real-time validation saat typing
                input.addEventListener('input', function() {
                    if (this.value.trim().length > 0) {
                        this.style.borderColor = 'var(--success)';
                    } else {
                        this.style.borderColor = '#e2e8f0';
                    }
                });
            });
            
            // Buat partikel saat focus input
            function createFocusParticles(inputElement) {
                const rect = inputElement.getBoundingClientRect();
                const particleCount = 4;
                
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.style.position = 'fixed';
                    particle.style.width = '4px';
                    particle.style.height = '4px';
                    particle.style.background = 'var(--primary)';
                    particle.style.borderRadius = '50%';
                    particle.style.left = `${rect.left}px`;
                    particle.style.top = `${rect.top + rect.height}px`;
                    particle.style.zIndex = '1000';
                    particle.style.pointerEvents = 'none';
                    
                    document.body.appendChild(particle);
                    
                    // Animate particle
                    const targetX = Math.random() * rect.width;
                    const targetY = -Math.random() * 20;
                    
                    const animation = particle.animate([
                        { transform: 'translate(0, 0) scale(1)', opacity: 1 },
                        { transform: `translate(${targetX}px, ${targetY}px) scale(0)`, opacity: 0 }
                    ], {
                        duration: 600,
                        easing: 'ease-out'
                    });
                    
                    animation.onfinish = () => particle.remove();
                }
            }
            
            // Animasi untuk tombol simpan
            const simpanBtn = userForm.querySelector('button[name="simpan_user"]');
            simpanBtn.addEventListener('click', function(e) {
                // Tambahkan efek ripple
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple-effect');
                
                // Hapus ripple sebelumnya
                const existingRipple = this.querySelector('.ripple-effect');
                if (existingRipple) {
                    existingRipple.remove();
                }
                
                this.appendChild(ripple);
                
                // Hapus ripple setelah animasi selesai
                setTimeout(() => {
                    if (ripple.parentElement) {
                        ripple.parentElement.removeChild(ripple);
                    }
                }, 1000);
            });
            
            // Tambahkan CSS untuk animasi tambahan
            const style = document.createElement('style');
            style.textContent = `
                .ripple-effect {
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.6);
                    transform: scale(0);
                    animation: ripple 0.8s linear;
                    pointer-events: none;
                }
                
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
            
            // Auto-hide success message setelah 5 detik
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.opacity = '0';
                    successAlert.style.transform = 'translateY(-20px)';
                    successAlert.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        if (successAlert.parentElement) {
                            successAlert.parentElement.removeChild(successAlert);
                        }
                    }, 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>