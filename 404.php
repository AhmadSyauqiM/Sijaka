<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - SIJAKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a2980 0%, #26d0ce 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            padding: 20px;
        }
        
        .error-container {
            text-align: center;
            padding: 50px 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            width: 100%;
            position: relative;
            z-index: 10;
            backdrop-filter: blur(10px);
            animation: fadeIn 1s ease-out;
        }
        
        .error-header {
            margin-bottom: 30px;
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: 900;
            background: linear-gradient(to right, #1a2980, #26d0ce);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            line-height: 1;
            text-shadow: 0 5px 15px rgba(38, 208, 206, 0.2);
            animation: float 3s ease-in-out infinite;
        }
        
        .error-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1a2980;
            margin-bottom: 15px;
        }
        
        .error-text {
            color: #5a6c7d;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 40px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
        }
        
        .btn {
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-back {
            background-color: #fff;
            color: #1a2980;
            border: 2px solid #1a2980;
        }
        
        .btn-back:hover {
            background-color: #f8f9fa;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .btn-home {
            background: linear-gradient(to right, #1a2980, #26d0ce);
            color: white;
        }
        
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(26, 41, 128, 0.3);
            background: linear-gradient(to right, #16236d, #21b9b7);
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .search-suggestion {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            border-left: 4px solid #26d0ce;
        }
        
        .search-title {
            color: #1a2980;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .search-box {
            display: flex;
            margin-top: 15px;
        }
        
        .search-box input {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #e1e5eb;
            border-radius: 50px 0 0 50px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s;
        }
        
        .search-box input:focus {
            border-color: #26d0ce;
        }
        
        .search-box button {
            background: linear-gradient(to right, #1a2980, #26d0ce);
            color: white;
            border: none;
            padding: 0 25px;
            border-radius: 0 50px 50px 0;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .search-box button:hover {
            background: linear-gradient(to right, #16236d, #21b9b7);
        }
        
        .decoration {
            position: absolute;
            z-index: 1;
        }
        
        .circle {
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            position: absolute;
        }
        
        .circle-1 {
            width: 300px;
            height: 300px;
            top: 10%;
            left: 5%;
            animation: float 6s ease-in-out infinite;
        }
        
        .circle-2 {
            width: 200px;
            height: 200px;
            bottom: 15%;
            right: 8%;
            animation: float 7s ease-in-out infinite reverse;
        }
        
        .triangle {
            width: 0;
            height: 0;
            border-left: 50px solid transparent;
            border-right: 50px solid transparent;
            border-bottom: 86.6px solid rgba(255, 255, 255, 0.08);
            position: absolute;
            animation: spin 20s linear infinite;
        }
        
        .triangle-1 {
            top: 20%;
            right: 15%;
        }
        
        .triangle-2 {
            bottom: 25%;
            left: 10%;
            animation-direction: reverse;
        }
        
        .floating-icon {
            position: absolute;
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.7);
            animation: float 5s ease-in-out infinite;
        }
        
        .icon-1 {
            top: 15%;
            right: 20%;
            animation-delay: 0.5s;
        }
        
        .icon-2 {
            bottom: 20%;
            left: 15%;
            animation-delay: 1s;
        }
        
        .icon-3 {
            top: 40%;
            left: 5%;
            animation-delay: 1.5s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @media (max-width: 768px) {
            .error-code {
                font-size: 6rem;
            }
            
            .error-title {
                font-size: 1.8rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
            }
            
            .circle-1, .circle-2 {
                display: none;
            }
        }
        
        @media (max-width: 480px) {
            .error-code {
                font-size: 5rem;
            }
            
            .error-container {
                padding: 40px 25px;
            }
        }
    </style>
</head>
<body>
    <!-- Elemen dekorasi latar belakang -->
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>
    <div class="triangle triangle-1"></div>
    <div class="triangle triangle-2"></div>
    <div class="floating-icon icon-1"><i class="fas fa-search"></i></div>
    <div class="floating-icon icon-2"><i class="fas fa-exclamation-triangle"></i></div>
    <div class="floating-icon icon-3"><i class="fas fa-map-signs"></i></div>
    
    <!-- Konten utama -->
    <div class="error-container">
        <div class="error-header">
            <div class="error-code">404</div>
            <h1 class="error-title">Halaman Tidak Ditemukan</h1>
            <p class="error-text">
                Sepertinya halaman yang Anda cari tidak tersedia, telah dipindahkan, atau mungkin Anda salah mengetik URL. Mari kami bantu Anda menemukan jalan yang benar.
            </p>
        </div>
        
        <div class="action-buttons">
            <button onclick="history.back()" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Halaman Sebelumnya
            </button>
            
            <a href="/sijaka/index.php" class="btn btn-home">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
        </div>
        
        <div class="search-suggestion">
            <h3 class="search-title">Mencari sesuatu yang spesifik?</h3>
            <p>Coba gunakan pencarian di bawah ini untuk menemukan apa yang Anda butuhkan.</p>
            
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Ketik kata kunci pencarian...">
                <button onclick="handleSearch()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        
        <div class="mt-4">
            <p style="color: #5a6c7d; font-size: 0.9rem;">
                <i class="fas fa-info-circle"></i> Jika masalah ini terus berlanjut, hubungi tim dukungan kami.
            </p>
        </div>
    </div>
    
    <script>
        // Animasi tambahan untuk elemen dekoratif
        document.addEventListener('DOMContentLoaded', function() {
            const errorCode = document.querySelector('.error-code');
            
            // Efek hover pada kode error
            errorCode.addEventListener('mouseover', function() {
                this.style.transform = 'scale(1.05)';
                this.style.transition = 'transform 0.3s ease';
            });
            
            errorCode.addEventListener('mouseout', function() {
                this.style.transform = 'scale(1)';
            });
            
            // Efek ketik pada kotak pencarian
            const searchInput = document.getElementById('searchInput');
            const placeholderText = "Ketik kata kunci pencarian...";
            let i = 0;
            
            function typeWriter() {
                if (i < placeholderText.length) {
                    searchInput.setAttribute('placeholder', placeholderText.substring(0, i+1));
                    i++;
                    setTimeout(typeWriter, 50);
                }
            }
            
            // Mulai efek ketik setelah delay
            setTimeout(typeWriter, 1000);
            
            // Tambahkan partikel bergerak di latar belakang
            createParticles();
        });
        
        function handleSearch() {
            const searchInput = document.getElementById('searchInput');
            const searchTerm = searchInput.value.trim();
            
            if (searchTerm) {
                // Simulasi pencarian
                alert(`Mencari: "${searchTerm}"\n\n(Fitur pencarian akan mengarahkan ke hasil pencarian yang sesuai)`);
                // Dalam implementasi nyata, Anda akan mengarahkan ke halaman pencarian
                // window.location.href = `/search?q=${encodeURIComponent(searchTerm)}`;
            } else {
                searchInput.focus();
                searchInput.style.borderColor = '#e74a3b';
                setTimeout(() => {
                    searchInput.style.borderColor = '#e1e5eb';
                }, 1000);
            }
        }
        
        // Fungsi untuk membuat partikel bergerak di latar belakang
        function createParticles() {
            const colors = ['rgba(255, 255, 255, 0.3)', 'rgba(38, 208, 206, 0.3)', 'rgba(26, 41, 128, 0.3)'];
            
            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Atur posisi dan ukuran acak
                const size = Math.random() * 10 + 5;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.background = colors[Math.floor(Math.random() * colors.length)];
                particle.style.borderRadius = '50%';
                particle.style.position = 'absolute';
                particle.style.left = `${Math.random() * 100}vw`;
                particle.style.top = `${Math.random() * 100}vh`;
                particle.style.zIndex = '1';
                
                // Tambahkan animasi
                const duration = Math.random() * 20 + 10;
                const delay = Math.random() * 5;
                particle.style.animation = `float ${duration}s ease-in-out ${delay}s infinite`;
                
                document.body.appendChild(particle);
            }
            
            // Tambahkan style untuk partikel
            const style = document.createElement('style');
            style.textContent = `
                .particle {
                    pointer-events: none;
                }
                
                @keyframes float {
                    0%, 100% { transform: translateY(0) translateX(0); }
                    25% { transform: translateY(-20px) translateX(10px); }
                    50% { transform: translateY(0) translateX(20px); }
                    75% { transform: translateY(20px) translateX(10px); }
                }
            `;
            document.head.appendChild(style);
        }
    </script>
</body>
</html>