<?php
session_start();
require '../config/database.php';

// Jika sudah login, langsung arahkan sesuai role (Auto-Redirect)
if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    if ($_SESSION['role'] == 'admin') { header("Location: dashboard_admin.php"); }
    else if ($_SESSION['role'] == 'kadis') { header("Location: ../kadis/index.php"); }
    else if ($_SESSION['role'] == 'bidang') { header("Location: ../bidang/index.php"); }
    else if ($_SESSION['role'] == 'pekerja') { header("Location: ../pekerja/index.php"); }
    exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // PERBAIKAN DI SINI: Menggunakan tabel 'admin' (bukan 'users')
    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    
    // Cek error jika query gagal (Debugging)
    if (!$query) {
        die("Query Error: " . mysqli_error($koneksi));
    }

    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);

        // 1. SET SESSION
        $_SESSION['status'] = "login";
        $_SESSION['id_user'] = $data['id'];
        
        // Pastikan kolom 'nama_lengkap', 'role', 'divisi' ADA di tabel admin kamu
        // Jika di database namanya 'nama_petugas', ubah baris di bawah ini jadi $data['nama_petugas']
        $_SESSION['nama']   = isset($data['nama_lengkap']) ? $data['nama_lengkap'] : $data['nama_petugas']; 
        $_SESSION['role']   = $data['role'];
        $_SESSION['divisi'] = $data['divisi'];

        // 2. CEK ROLE & REDIRECT (ARAHKAN KE RUANGAN)
        if ($data['role'] == 'admin') {
            echo "<script>alert('Halo Admin!'); window.location='dashboard_admin.php';</script>";
        
        } else if ($data['role'] == 'kadis') {
            echo "<script>alert('Selamat Datang Pak Kadis!'); window.location='../kadis/index.php';</script>";
        
        } else if ($data['role'] == 'bidang') {
            echo "<script>alert('Halo Kabid " . $data['divisi'] . "!'); window.location='../bidang/index.php';</script>";
        
        } else if ($data['role'] == 'pekerja') {
            echo "<script>alert('Selamat Bekerja, " . $_SESSION['nama'] . "!'); window.location='../pekerja/index.php';</script>";
        }

    } else {
        $error = "Username atau Password Salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem - SIJAKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --accent: #4cc9f0;
            --success: #4ade80;
            --dark: #1e293b;
            --light: #f8fafc;
            --gradient-1: linear-gradient(135deg, #4361ee, #3a0ca3, #7209b7);
            --gradient-2: linear-gradient(135deg, #4cc9f0, #4361ee);
            --shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 25px 50px rgba(0, 0, 0, 0.15);
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
        
        /* Animated Background Elements */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite ease-in-out;
        }
        
        .shape:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 200px;
            height: 200px;
            top: 50%;
            right: 10%;
            animation-delay: 5s;
        }
        
        .shape:nth-child(3) {
            width: 150px;
            height: 150px;
            bottom: 10%;
            left: 10%;
            animation-delay: 10s;
        }
        
        .shape:nth-child(4) {
            width: 250px;
            height: 250px;
            bottom: -100px;
            right: -100px;
            animation-delay: 15s;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(50px, 50px) rotate(90deg); }
            50% { transform: translate(0, 100px) rotate(180deg); }
            75% { transform: translate(-50px, 50px) rotate(270deg); }
        }
        
        /* Login Container */
        .login-container {
            width: 100%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            display: flex;
            min-height: 700px;
            animation: slideUp 1s ease-out forwards;
            opacity: 0;
            transform: translateY(50px);
        }
        
        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Left Panel */
        .left-panel {
            flex: 1;
            background: var(--gradient-2);
            padding: 60px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .left-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: cover;
        }
        
        .app-logo {
            margin-bottom: 40px;
            animation: fadeInLeft 1s 0.3s forwards;
            opacity: 0;
        }
        
        .app-logo img {
            width: 120px;
            height: auto;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.2));
        }
        
        .app-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            animation: fadeInLeft 1s 0.4s forwards;
            opacity: 0;
        }
        
        .app-subtitle {
            font-size: 1rem;
            font-weight: 300;
            margin-bottom: 50px;
            opacity: 0.9;
            animation: fadeInLeft 1s 0.5s forwards;
            opacity: 0;
        }
        
        .features-list {
            list-style: none;
            margin-top: 30px;
            animation: fadeInLeft 1s 0.6s forwards;
            opacity: 0;
        }
        
        .features-list li {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            font-size: 1.1rem;
            opacity: 0;
            animation: slideInListItem 0.5s forwards;
        }
        
        .features-list li:nth-child(1) { animation-delay: 0.7s; }
        .features-list li:nth-child(2) { animation-delay: 0.8s; }
        .features-list li:nth-child(3) { animation-delay: 0.9s; }
        .features-list li:nth-child(4) { animation-delay: 1s; }
        
        @keyframes slideInListItem {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .features-list i {
            background: rgba(255, 255, 255, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Right Panel */
        .right-panel {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-header {
            margin-bottom: 40px;
            text-align: center;
            animation: fadeInRight 1s 0.4s forwards;
            opacity: 0;
        }
        
        .login-header h2 {
            font-size: 2.2rem;
            color: var(--dark);
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #64748b;
            font-size: 1.1rem;
        }
        
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Form Styling */
        .form-container {
            animation: fadeInRight 1s 0.6s forwards;
            opacity: 0;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 8px;
            display: block;
            transition: all 0.3s ease;
        }
        
        .input-group {
            position: relative;
        }
        
        .form-control {
            height: 56px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding-left: 50px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: var(--light);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            transform: translateY(-2px);
        }
        
        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.2rem;
            z-index: 10;
            transition: all 0.3s ease;
        }
        
        .form-control:focus + .input-icon {
            color: var(--primary);
        }
        
        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
        }
        
        .password-toggle:hover {
            color: var(--primary);
        }
        
        /* Error Message */
        .alert-danger {
            border-radius: 12px;
            border: none;
            background: #fee2e2;
            color: #dc2626;
            padding: 12px 20px;
            margin-bottom: 25px;
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        /* Login Button */
        .btn-login {
            height: 56px;
            border-radius: 12px;
            background: var(--gradient-2);
            border: none;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        .btn-login:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(40, 40);
                opacity: 0;
            }
        }
        
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(30, 41, 59, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .spinner {
            width: 80px;
            height: 80px;
            position: relative;
        }
        
        .spinner div {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 8px solid transparent;
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spinnerOne 1.2s linear infinite;
        }
        
        .spinner div:nth-child(2) {
            border: 8px solid transparent;
            border-bottom-color: var(--primary);
            animation: spinnerTwo 1.2s linear infinite;
        }
        
        @keyframes spinnerOne {
            0% { transform: rotate(0deg); border-width: 8px; }
            50% { transform: rotate(180deg); border-width: 1px; }
            100% { transform: rotate(360deg); border-width: 8px; }
        }
        
        @keyframes spinnerTwo {
            0% { transform: rotate(0deg); border-width: 1px; }
            50% { transform: rotate(180deg); border-width: 8px; }
            100% { transform: rotate(360deg); border-width: 1px; }
        }
        
        .loading-text {
            color: white;
            font-size: 1.2rem;
            margin-top: 20px;
            font-weight: 500;
            text-align: center;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 30px;
            animation: fadeInRight 1s 0.8s forwards;
            opacity: 0;
        }
        
        .back-link a {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .back-link a:hover {
            color: var(--primary);
            transform: translateX(-5px);
        }
        
        .back-link i {
            margin-right: 8px;
            transition: all 0.3s ease;
        }
        
        /* Floating Particles */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: floatParticle 15s infinite linear;
        }
        
        @keyframes floatParticle {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
            }
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
                max-width: 600px;
            }
            
            .left-panel, .right-panel {
                padding: 40px 30px;
            }
            
            .left-panel {
                order: 2;
            }
            
            .right-panel {
                order: 1;
            }
            
            .app-title {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .login-container {
                border-radius: 16px;
            }
            
            .left-panel, .right-panel {
                padding: 30px 20px;
            }
            
            .app-title {
                font-size: 1.8rem;
            }
            
            .login-header h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <!-- Floating Particles -->
    <div class="particles" id="particles"></div>
    
    <!-- Login Container -->
    <div class="login-container">
        <!-- Left Panel - App Info -->
        <div class="left-panel">
            <div class="app-logo">
                <img src="../assets/img/Logos.png" alt="SIJAKA Logo" >
            </div>
            
            <h1 class="app-title">SIJAKA</h1>
            <p class="app-subtitle">Sistem Informasi Pengaduan Masyarakat Kabupaten Kuningan</p>
            
            <ul class="features-list">
                <li><i class="fas fa-check-circle"></i> Platform pengaduan mudah diakses oleh masyarakat</li>
                <li><i class="fas fa-bolt"></i> Mempercepat respon terhadap laporan kerusakan infrastruktur</li>
                <li><i class="fas fa-eye"></i> Meningkatkan transparansi dalam penanganan laporan</li>
                <li><i class="fas fa-tools"></i> Mengoptimalkan pemeliharaan aset publik di Kabupaten Kuningan</li>
            </ul>
        </div>
        
        <!-- Right Panel - Login Form -->
        <div class="right-panel">
            <div class="login-header">
                <h2>Masuk ke Akun</h2>
                <p>Silakan masuk dengan kredensial Anda</p>
            </div>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger p-3"><?= $error; ?></div>
            <?php endif; ?>
            
            <div class="form-container">
                <form method="POST" id="loginForm">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <div class="input-group">
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" name="login" class="btn btn-login fw-bold">
                            <span class="login-text">MASUK</span>
                            <span class="loading-spinner" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i> Memproses...
                            </span>
                        </button>
                    </div>
                </form>
                
                <div class="back-link">
                    <a href="../index.php"><i class="fas fa-arrow-left"></i> Kembali ke Website Warga</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner">
                <div></div>
                <div></div>
            </div>
            <div class="loading-text">Memproses login, harap tunggu...</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inisialisasi variabel
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const loginForm = document.getElementById('loginForm');
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            // Buat partikel floating
            createParticles();
            
            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
            
            // Form submission dengan animasi loading
            loginForm.addEventListener('submit', function(e) {
                // Validasi form
                const username = this.username.value.trim();
                const password = this.password.value.trim();
                
                if (!username || !password) {
                    e.preventDefault();
                    showErrorAnimation();
                    return;
                }
                
                // Tampilkan loading overlay
                showLoading();
                
                // Simulasi delay untuk animasi (akan digantikan dengan proses login sebenarnya)
                // Form akan tetap submit setelah menampilkan animasi loading
            });
            
            // Animasi error jika form kosong
            function showErrorAnimation() {
                const inputs = document.querySelectorAll('.form-control');
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.style.borderColor = '#dc2626';
                        input.style.animation = 'shake 0.5s ease';
                        setTimeout(() => {
                            input.style.animation = '';
                        }, 500);
                    }
                });
            }
            
            // Tampilkan loading overlay
            function showLoading() {
                loadingOverlay.classList.add('active');
                
                // Simulasi timeout jika proses terlalu lama (fallback)
                setTimeout(() => {
                    // Hanya sembunyikan jika masih aktif (sebagai fallback)
                    if (loadingOverlay.classList.contains('active')) {
                        loadingOverlay.classList.remove('active');
                    }
                }, 10000); // 10 detik timeout
            }
            
            // Buat partikel floating
            function createParticles() {
                const particlesContainer = document.getElementById('particles');
                const particleCount = 30;
                
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.classList.add('particle');
                    
                    // Ukuran random
                    const size = Math.random() * 10 + 5;
                    particle.style.width = `${size}px`;
                    particle.style.height = `${size}px`;
                    
                    // Posisi random
                    particle.style.left = `${Math.random() * 100}%`;
                    
                    // Warna random dengan transparansi
                    const colors = [
                        'rgba(76, 201, 240, 0.5)',
                        'rgba(67, 97, 238, 0.5)',
                        'rgba(255, 255, 255, 0.5)',
                        'rgba(58, 12, 163, 0.3)'
                    ];
                    particle.style.background = colors[Math.floor(Math.random() * colors.length)];
                    
                    // Animasi dengan delay random
                    const duration = Math.random() * 20 + 15;
                    const delay = Math.random() * 5;
                    particle.style.animationDuration = `${duration}s`;
                    particle.style.animationDelay = `${delay}s`;
                    
                    particlesContainer.appendChild(particle);
                }
            }
            
            // Animasi untuk input focus
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                // Efek saat input focus
                input.addEventListener('focus', function() {
                    this.parentElement.querySelector('.input-icon').style.transform = 'translateY(-50%) scale(1.2)';
                    this.parentElement.querySelector('.input-icon').style.color = 'var(--primary)';
                });
                
                // Efek saat input blur
                input.addEventListener('blur', function() {
                    this.parentElement.querySelector('.input-icon').style.transform = 'translateY(-50%) scale(1)';
                    if (!this.value) {
                        this.parentElement.querySelector('.input-icon').style.color = '#94a3b8';
                    }
                });
            });
            
            // Animasi ripple effect untuk tombol
            const buttons = document.querySelectorAll('.btn-login');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Ripple effect
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
                        ripple.remove();
                    }, 1000);
                });
            });
            
            // Tambahkan CSS untuk ripple effect
            const style = document.createElement('style');
            style.textContent = `
                .ripple-effect {
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.7);
                    transform: scale(0);
                    animation: ripple-animation 0.6s linear;
                }
                
                @keyframes ripple-animation {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>