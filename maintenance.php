<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedang Dalam Perbaikan - SIJAKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #1e88e5;
            --secondary-blue: #42a5f5;
            --light-blue: #bbdefb;
            --primary-yellow: #ffb300;
            --secondary-yellow: #ffd54f;
            --accent-orange: #ff9800;
            --construction-red: #e53935;
            --construction-green: #43a047;
            --light-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #37474f;
            --text-light: #546e7a;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Background animasi partikel konstruksi */
        .particles-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, var(--secondary-blue), var(--primary-yellow));
            opacity: 0.2;
            animation: floatConstruction 20s infinite linear;
            filter: drop-shadow(0 0 5px rgba(255, 179, 0, 0.3));
        }
        
        .construction-particle {
            background: radial-gradient(circle, var(--construction-red), var(--accent-orange));
        }
        
        @keyframes floatConstruction {
            0% {
                transform: translateY(0) translateX(0) rotate(0deg) scale(1);
            }
            25% {
                transform: translateY(-40px) translateX(30px) rotate(90deg) scale(1.1);
            }
            50% {
                transform: translateY(20px) translateX(-40px) rotate(180deg) scale(0.9);
            }
            75% {
                transform: translateY(-20px) translateX(20px) rotate(270deg) scale(1.05);
            }
            100% {
                transform: translateY(0) translateX(0) rotate(360deg) scale(1);
            }
        }
        
        /* Dekorasi konstruksi */
        .construction-decoration {
            position: absolute;
            z-index: 2;
            font-size: 2rem;
            color: var(--primary-yellow);
            opacity: 0.1;
        }
        
        /* Kartu utama dengan efek glassmorphism terang */
        .main-container {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .maintenance-card {
            text-align: center;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 28px;
            padding: 60px 40px;
            max-width: 1000px;
            width: 100%;
            box-shadow: 
                0 20px 60px rgba(30, 136, 229, 0.15),
                0 0 0 1px rgba(255, 179, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            animation: cardEntranceConstruction 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            transform-origin: center;
            position: relative;
            overflow: hidden;
        }
        
        .maintenance-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-blue), var(--primary-yellow), var(--primary-blue));
            z-index: 2;
        }
        
        .maintenance-card::after {
            content: '';
            position: absolute;
            top: -100%;
            left: -100%;
            width: 300%;
            height: 300%;
            background: radial-gradient(circle, rgba(255, 179, 0, 0.05) 0%, rgba(255, 179, 0, 0) 70%);
            z-index: -1;
        }
        
        @keyframes cardEntranceConstruction {
            0% {
                opacity: 0;
                transform: scale(0.85) translateY(60px) rotateX(10deg);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0) rotateX(0);
            }
        }
        
        /* Header dengan efek konstruksi */
        .card-header {
            margin-bottom: 40px;
            position: relative;
        }
        
        .icon-container {
            position: relative;
            margin-bottom: 30px;
            display: inline-block;
        }
        
        .icon-maintenance {
            font-size: 6.5rem;
            color: var(--primary-yellow);
            filter: drop-shadow(0 0 15px rgba(255, 179, 0, 0.5));
            animation: iconFloatConstruction 4s ease-in-out infinite alternate;
            position: relative;
            z-index: 2;
        }
        
        @keyframes iconFloatConstruction {
            0% {
                transform: translateY(0) rotate(0deg);
                filter: drop-shadow(0 0 15px rgba(255, 179, 0, 0.5));
            }
            100% {
                transform: translateY(-20px) rotate(10deg);
                filter: drop-shadow(0 0 25px rgba(255, 179, 0, 0.7));
            }
        }
        
        .icon-orbits {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
        }
        
        .orbit {
            position: absolute;
            top: 50%;
            left: 50%;
            border-radius: 50%;
            border: 2px solid rgba(30, 136, 229, 0.2);
            transform: translate(-50%, -50%);
        }
        
        .orbit-1 {
            width: 160px;
            height: 160px;
            animation: spin 20s linear infinite;
        }
        
        .orbit-2 {
            width: 120px;
            height: 120px;
            animation: spinReverse 25s linear infinite;
        }
        
        .orbit-3 {
            width: 80px;
            height: 80px;
            animation: spin 30s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        @keyframes spinReverse {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(-360deg); }
        }
        
        h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(90deg, var(--primary-blue), var(--primary-yellow));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            display: inline-block;
        }
        
        h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 25%;
            width: 50%;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--primary-yellow), transparent);
            border-radius: 3px;
        }
        
        p {
            font-size: 1.2rem;
            line-height: 1.7;
            margin-bottom: 25px;
            color: var(--text-light);
            max-width: 90%;
            margin-left: auto;
            margin-right: auto;
        }
        
        .highlight {
            color: var(--primary-blue);
            font-weight: 600;
            position: relative;
        }
        
        /* Loading animasi konstruksi */
        .loading-container {
            margin: 50px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .loading-text {
            font-size: 1.2rem;
            margin-bottom: 25px;
            color: var(--primary-blue);
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .loading-animation {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .loading-dot {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-yellow), var(--accent-orange));
            animation: bounceConstruction 1.6s infinite ease-in-out both;
            box-shadow: 0 0 15px rgba(255, 179, 0, 0.6);
            position: relative;
        }
        
        .loading-dot::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
        }
        
        .loading-dot:nth-child(1) {
            animation-delay: -0.32s;
        }
        
        .loading-dot:nth-child(2) {
            animation-delay: -0.16s;
        }
        
        @keyframes bounceConstruction {
            0%, 80%, 100% {
                transform: scale(0.6);
                opacity: 0.5;
            }
            40% {
                transform: scale(1.1);
                opacity: 1;
            }
        }
        
        .progress-container {
            width: 80%;
            height: 8px;
            background: rgba(30, 136, 229, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
            position: relative;
            border: 1px solid rgba(30, 136, 229, 0.2);
        }
        
        .progress-bar {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--primary-blue), var(--primary-yellow), var(--primary-blue));
            border-radius: 10px;
            animation: progressLoadConstruction 5s ease-in-out infinite;
            position: relative;
            overflow: hidden;
        }
        
        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
            animation: shineConstruction 3s infinite;
        }
        
        @keyframes progressLoadConstruction {
            0% {
                width: 15%;
            }
            50% {
                width: 85%;
            }
            100% {
                width: 15%;
            }
        }
        
        @keyframes shineConstruction {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }
        
        /* Tombol login konstruksi */
        .login-section {
            margin: 50px 0 30px;
            padding-top: 30px;
            border-top: 1px solid rgba(30, 136, 229, 0.2);
            position: relative;
        }
        
        .btn-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            padding: 18px 45px;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 50%, var(--primary-blue) 100%);
            color: white;
            text-decoration: none;
            border-radius: 60px;
            font-weight: 600;
            font-size: 1.2rem;
            transition: all 0.4s ease;
            box-shadow: 
                0 10px 30px rgba(30, 136, 229, 0.3),
                0 0 0 2px rgba(66, 165, 245, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
            z-index: 1;
            border: none;
            letter-spacing: 1px;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.7s ease;
            z-index: -1;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 
                0 15px 40px rgba(30, 136, 229, 0.4),
                0 0 0 2px rgba(255, 179, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
            background: linear-gradient(135deg, var(--secondary-blue) 0%, var(--primary-blue) 50%, var(--secondary-blue) 100%);
        }
        
        .btn-login:active {
            transform: translateY(-2px) scale(1.02);
        }
        
        .btn-login i {
            font-size: 1.4rem;
            transition: transform 0.3s ease;
        }
        
        .btn-login:hover i {
            transform: translateX(5px);
        }
        
        /* Container untuk semua fitur hiburan */
        .entertainment-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }
        
        /* Mini Game Container */
        .game-container, .quiz-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            border: 1px solid rgba(30, 136, 229, 0.2);
            padding: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 10px 30px rgba(30, 136, 229, 0.1);
        }
        
        .game-container:hover, .quiz-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(30, 136, 229, 0.2);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .section-title {
            font-size: 1.5rem;
            color: var(--primary-blue);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: 'Montserrat', sans-serif;
        }
        
        /* Game Area */
        .game-instructions {
            color: var(--text-light);
            margin-bottom: 25px;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .game-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .game-stats {
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex: 1;
        }
        
        .stat {
            display: flex;
            justify-content: space-between;
            padding: 12px 15px;
            background: rgba(30, 136, 229, 0.08);
            border-radius: 12px;
            border: 1px solid rgba(30, 136, 229, 0.2);
        }
        
        .stat-label {
            color: var(--text-light);
        }
        
        .stat-value {
            color: var(--primary-blue);
            font-weight: 600;
        }
        
        .game-controls {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .game-btn {
            padding: 12px 20px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .game-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(30, 136, 229, 0.3);
        }
        
        .game-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        .game-canvas-container {
            width: 100%;
            height: 200px;
            background: rgba(30, 136, 229, 0.05);
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(30, 136, 229, 0.2);
            margin-top: 10px;
        }
        
        #gameCanvas {
            width: 100%;
            height: 100%;
            display: block;
        }
        
        .game-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.95);
            padding: 15px 25px;
            border-radius: 15px;
            color: var(--primary-blue);
            font-weight: 600;
            text-align: center;
            display: none;
            z-index: 10;
            border: 1px solid rgba(30, 136, 229, 0.3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        /* Quiz Container */
        .quiz-area {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .quiz-question {
            background: rgba(30, 136, 229, 0.08);
            padding: 20px;
            border-radius: 15px;
            border: 1px solid rgba(30, 136, 229, 0.2);
            font-size: 1.1rem;
            line-height: 1.5;
            color: var(--text-dark);
        }
        
        .quiz-options {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .quiz-option {
            padding: 15px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(30, 136, 229, 0.3);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: left;
            color: var(--text-dark);
        }
        
        .quiz-option:hover {
            background: rgba(30, 136, 229, 0.1);
            transform: translateX(5px);
            border-color: var(--primary-blue);
        }
        
        .quiz-option.selected {
            background: rgba(255, 179, 0, 0.15);
            border-color: var(--primary-yellow);
        }
        
        .quiz-option.correct {
            background: rgba(67, 160, 71, 0.15);
            border-color: var(--construction-green);
        }
        
        .quiz-option.incorrect {
            background: rgba(229, 57, 53, 0.1);
            border-color: var(--construction-red);
        }
        
        .quiz-explanation {
            background: rgba(30, 136, 229, 0.08);
            padding: 15px;
            border-radius: 10px;
            border: 1px solid rgba(30, 136, 229, 0.2);
            margin-top: 10px;
            display: none;
            font-size: 0.95rem;
            color: var(--text-light);
            line-height: 1.5;
        }
        
        .quiz-explanation.show {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .quiz-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        
        .quiz-score {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: var(--primary-blue);
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            font-size: 0.9rem;
            color: var(--text-light);
            padding-top: 20px;
            border-top: 1px solid rgba(30, 136, 229, 0.1);
        }
        
        /* Efek trail mouse konstruksi */
        .trail {
            position: fixed;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--primary-yellow), transparent);
            pointer-events: none;
            z-index: 9999;
            opacity: 0;
            transform: translate(-50%, -50%);
            mix-blend-mode: multiply;
            filter: blur(1px);
        }
        
        /* Responsif */
        @media (max-width: 992px) {
            .entertainment-container {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .maintenance-card {
                padding: 50px 30px;
            }
            
            h1 {
                font-size: 2.5rem;
            }
            
            p {
                font-size: 1.1rem;
            }
            
            .game-area {
                flex-direction: column;
            }
            
            .game-stats, .game-controls {
                width: 100%;
            }
            
            .btn-login {
                padding: 16px 35px;
                font-size: 1.1rem;
            }
            
            .icon-maintenance {
                font-size: 5.5rem;
            }
            
            .entertainment-container {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            h1 {
                font-size: 2rem;
            }
            
            .icon-maintenance {
                font-size: 4.5rem;
            }
            
            .btn-login {
                padding: 14px 28px;
                font-size: 1rem;
            }
            
            .game-container, .quiz-container {
                padding: 20px;
            }
            
            .section-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <!-- Background particles -->
    <div class="particles-container" id="particles"></div>
    
    <!-- Efek trail mouse -->
    
    <div class="main-container">
        <div class="maintenance-card">
            <div class="card-header">
                <div class="icon-container">
                    <div class="icon-orbits">
                        <div class="orbit orbit-1"></div>
                        <div class="orbit orbit-2"></div>
                        <div class="orbit orbit-3"></div>
                    </div>
                    <i class="fas fa-tools icon-maintenance"></i>
                </div>
                
                <h1>Sedang Dalam Perbaikan</h1>
            </div>
            
            <p>Mohon maaf atas ketidaknyamanannya. Saat ini sistem <span class="highlight">SIJAKA</span> (Sistem Informasi Jalan dan Kerusakan Aset) sedang menjalani pemeliharaan rutin untuk meningkatkan kualitas layanan kami.</p>
            <p>Kami bekerja keras untuk menyelesaikan proses ini secepat mungkin. Silakan coba beberapa fitur hiburan di bawah ini sambil menunggu.</p>
            
            <div class="loading-container">
                <div class="loading-text">Memproses Pembaruan Sistem</div>
                <div class="loading-animation">
                    <div class="loading-dot"></div>
                    <div class="loading-dot"></div>
                    <div class="loading-dot"></div>
                </div>
                <div class="progress-container">
                    <div class="progress-bar"></div>
                </div>
            </div>
            
            <!-- Container untuk semua fitur hiburan -->
            <div class="entertainment-container">
                <!-- Mini Game -->
                <div class="game-container">
                    <div class="section-title">
                        <i class="fas fa-hard-hat"></i>
                        <span>Game Konstruksi: Kumpulkan Material</span>
                    </div>
                    
                    <div class="game-instructions">
                        Bantu tukang mengumpulkan material konstruksi yang jatuh! Setiap material memberi 10 poin. Hindari rintangan yang mengurangi 5 poin.
                    </div>
                    
                    <div class="game-area">
                        <div class="game-stats">
                            <div class="stat">
                                <span class="stat-label">Skor:</span>
                                <span class="stat-value" id="score">0</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Waktu:</span>
                                <span class="stat-value" id="time">60</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Level:</span>
                                <span class="stat-value" id="level">1</span>
                            </div>
                        </div>
                        
                        <div class="game-controls">
                            <button class="game-btn" id="startBtn">
                                <i class="fas fa-play"></i>
                                <span>Mulai Game</span>
                            </button>
                            <button class="game-btn" id="resetBtn" disabled>
                                <i class="fas fa-redo"></i>
                                <span>Reset Game</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="game-canvas-container">
                        <canvas id="gameCanvas"></canvas>
                        <div class="game-message" id="gameMessage"></div>
                    </div>
                </div>
                
                <!-- Quiz Konstruksi -->
                <div class="quiz-container">
                    <div class="section-title">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Kuis Konstruksi & DPU</span>
                    </div>
                    
                    <div class="quiz-area">
                        <div class="quiz-question" id="quizQuestion">
                            Apa kepanjangan dari DPU?
                        </div>
                        
                        <div class="quiz-options" id="quizOptions">
                            <!-- Opsi akan diisi oleh JavaScript -->
                        </div>
                        
                        <div class="quiz-explanation" id="quizExplanation">
                            <!-- Penjelasan akan diisi oleh JavaScript -->
                        </div>
                        
                        <div class="quiz-controls">
                            <button class="game-btn" id="submitQuiz" style="padding: 10px 20px;">
                                <i class="fas fa-check"></i>
                                <span>Submit Jawaban</span>
                            </button>
                            
                            <div class="quiz-score">
                                <i class="fas fa-star" style="color: var(--primary-yellow);"></i>
                                <span id="quizScore">Skor: 0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="login-section">
                <a href="admin/login.php" class="btn-login">
                    <i class="fas fa-lock me-2"></i> 
                    <span>Login Petugas</span>
                    <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            
            <div class="footer">
                <p>&copy; 2023 SIJAKA. Hak Cipta Dilindungi. | Sistem dalam pemeliharaan hingga pukul 23:59 WIB</p>
                <p style="font-size: 0.8rem; margin-top: 5px;">Dinas Pekerjaan Umum dan Tata Ruang</p>
            </div>
        </div>
    </div>

    <script>
        // Inisialisasi partikel background konstruksi
        function initParticles() {
            const container = document.getElementById('particles');
            const particleCount = 20;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Setiap partikel ke-3 menjadi partikel konstruksi
                if (i % 3 === 0) {
                    particle.classList.add('construction-particle');
                }
                
                // Ukuran acak
                const size = Math.random() * 60 + 15;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Posisi acak
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                
                // Warna acak dengan gradien kuning-biru
                const hue1 = Math.random() * 60 + 200; // Biru
                const hue2 = Math.random() * 30 + 40;  // Kuning
                const opacity = Math.random() * 0.2 + 0.1;
                particle.style.background = `radial-gradient(circle, hsl(${hue1}, 80%, 65%), hsl(${hue2}, 90%, 65%))`;
                particle.style.opacity = opacity;
                
                // Animasi delay acak
                particle.style.animationDelay = `${Math.random() * 20}s`;
                
                // Efek blur untuk beberapa partikel
                if (Math.random() > 0.7) {
                    particle.style.filter = 'blur(2px)';
                }
                
                container.appendChild(particle);
            }
        }
        
        // Efek trail mouse konstruksi
        function initMouseTrail() {
            const trailCount = 15;
            const trails = [];
            
            // Buat elemen trail
            for (let i = 0; i < trailCount; i++) {
                const trail = document.createElement('div');
                trail.classList.add('trail');
                document.body.appendChild(trail);
                trails.push({
                    el: trail,
                    x: 0,
                    y: 0,
                    size: 18 - i,
                    opacity: 0
                });
            }
            
            let mouseX = 0;
            let mouseY = 0;
            let trailIndex = 0;
            
            // Update posisi mouse
            document.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
            });
            
            // Animasi trail
            function animateTrail() {
                // Update posisi trail pertama
                const leadTrail = trails[trailIndex];
                leadTrail.x = mouseX;
                leadTrail.y = mouseY;
                leadTrail.opacity = 0.6;
                leadTrail.size = 18;
                
                // Update trail lainnya
                for (let i = 0; i < trails.length; i++) {
                    const trail = trails[i];
                    
                    // Perlahan mengikuti trail sebelumnya
                    if (i !== trailIndex) {
                        const prevIndex = (i === 0) ? trails.length - 1 : i - 1;
                        const prevTrail = trails[prevIndex];
                        
                        trail.x += (prevTrail.x - trail.x) * 0.2;
                        trail.y += (prevTrail.y - trail.y) * 0.2;
                        trail.size = Math.max(5, trail.size * 0.85);
                        trail.opacity *= 0.85;
                    }
                    
                    // Terapkan ke elemen
                    trail.el.style.left = `${trail.x}px`;
                    trail.el.style.top = `${trail.y}px`;
                    trail.el.style.width = `${trail.size}px`;
                    trail.el.style.height = `${trail.size}px`;
                    trail.el.style.opacity = trail.opacity;
                    
                    // Warna trail berdasarkan posisi
                    const hue = 200 + (trail.y / window.innerHeight) * 60;
                    trail.el.style.background = `radial-gradient(circle, hsl(${hue}, 80%, 65%), transparent)`;
                }
                
                // Pindah ke trail berikutnya
                trailIndex = (trailIndex + 1) % trails.length;
                
                requestAnimationFrame(animateTrail);
            }
            
            animateTrail();
        }
        
        // Mini Game: Kumpulkan Material Konstruksi
        class MiniGame {
            constructor() {
                this.canvas = document.getElementById('gameCanvas');
                this.ctx = this.canvas.getContext('2d');
                this.scoreElement = document.getElementById('score');
                this.timeElement = document.getElementById('time');
                this.levelElement = document.getElementById('level');
                this.startBtn = document.getElementById('startBtn');
                this.resetBtn = document.getElementById('resetBtn');
                this.gameMessage = document.getElementById('gameMessage');
                
                // Game state
                this.score = 0;
                this.timeLeft = 60;
                this.level = 1;
                this.isPlaying = false;
                this.gameInterval = null;
                this.timeInterval = null;
                
                // Game objects
                this.worker = {
                    x: 300,
                    y: 180,
                    width: 60,
                    height: 20,
                    color: '#1e88e5'
                };
                
                this.materials = [];  // Material konstruksi
                this.obstacles = [];  // Rintangan di lokasi konstruksi
                
                // Material types
                this.materialTypes = [
                    { name: 'Semen', color: '#bdbdbd', icon: '◼', points: 10 },
                    { name: 'Batu Bata', color: '#795548', icon: '■', points: 15 },
                    { name: 'Besi', color: '#757575', icon: '▲', points: 20 },
                    { name: 'Kayu', color: '#8d6e63', icon: '◼', points: 12 }
                ];
                
                // Setup canvas
                this.resizeCanvas();
                window.addEventListener('resize', () => this.resizeCanvas());
                
                // Event listeners
                this.canvas.addEventListener('mousemove', (e) => this.moveWorker(e));
                this.startBtn.addEventListener('click', () => this.startGame());
                this.resetBtn.addEventListener('click', () => this.resetGame());
                
                // Draw initial screen
                this.draw();
                
                // Add some initial decorative elements
                this.createInitialElements();
            }
            
            resizeCanvas() {
                const container = this.canvas.parentElement;
                this.canvas.width = container.clientWidth;
                this.canvas.height = container.clientHeight;
                
                // Center worker
                this.worker.x = this.canvas.width / 2 - this.worker.width / 2;
                
                this.draw();
            }
            
            createInitialElements() {
                // Create some decorative materials and obstacles for the idle state
                for (let i = 0; i < 6; i++) {
                    const materialType = this.materialTypes[Math.floor(Math.random() * this.materialTypes.length)];
                    this.materials.push({
                        x: Math.random() * this.canvas.width,
                        y: Math.random() * this.canvas.height,
                        type: 'material',
                        size: 12 + Math.random() * 8,
                        color: materialType.color,
                        icon: materialType.icon,
                        points: materialType.points,
                        speed: 0,
                        active: false
                    });
                }
                
                for (let i = 0; i < 3; i++) {
                    this.obstacles.push({
                        x: Math.random() * this.canvas.width,
                        y: Math.random() * this.canvas.height,
                        type: 'obstacle',
                        size: 10 + Math.random() * 6,
                        color: '#e53935',
                        icon: '⚠',
                        speed: 0,
                        active: false
                    });
                }
            }
            
            draw() {
                // Clear canvas
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                
                // Draw background gradient
                const gradient = this.ctx.createLinearGradient(0, 0, 0, this.canvas.height);
                gradient.addColorStop(0, 'rgba(187, 222, 251, 0.3)');
                gradient.addColorStop(1, 'rgba(227, 242, 253, 0.2)');
                this.ctx.fillStyle = gradient;
                this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                
                // Draw decorative grid (like construction grid)
                this.drawGrid();
                
                // Draw materials
                this.materials.forEach(material => {
                    this.drawMaterial(material);
                });
                
                // Draw obstacles
                this.obstacles.forEach(obstacle => {
                    this.drawObstacle(obstacle);
                });
                
                // Draw worker
                this.drawWorker();
                
                // Draw game info if playing
                if (this.isPlaying) {
                    this.drawGameInfo();
                }
            }
            
            drawGrid() {
                this.ctx.strokeStyle = 'rgba(30, 136, 229, 0.1)';
                this.ctx.lineWidth = 1;
                
                // Vertical lines
                for (let x = 0; x <= this.canvas.width; x += 40) {
                    this.ctx.beginPath();
                    this.ctx.moveTo(x, 0);
                    this.ctx.lineTo(x, this.canvas.height);
                    this.ctx.stroke();
                }
                
                // Horizontal lines
                for (let y = 0; y <= this.canvas.height; y += 40) {
                    this.ctx.beginPath();
                    this.ctx.moveTo(0, y);
                    this.ctx.lineTo(this.canvas.width, y);
                    this.ctx.stroke();
                }
            }
            
            drawMaterial(material) {
                this.ctx.save();
                
                // Material shadow
                this.ctx.shadowColor = material.color;
                this.ctx.shadowBlur = 10;
                
                // Material body
                this.ctx.fillStyle = material.color;
                this.ctx.beginPath();
                this.ctx.arc(material.x, material.y, material.size, 0, Math.PI * 2);
                this.ctx.fill();
                
                // Material detail (icon)
                this.ctx.fillStyle = '#ffffff';
                this.ctx.font = `bold ${material.size}px Arial`;
                this.ctx.textAlign = 'center';
                this.ctx.textBaseline = 'middle';
                this.ctx.fillText(material.icon, material.x, material.y);
                
                this.ctx.restore();
            }
            
            drawObstacle(obstacle) {
                this.ctx.save();
                
                // Obstacle shadow
                this.ctx.shadowColor = obstacle.color;
                this.ctx.shadowBlur = 10;
                
                // Obstacle body
                this.ctx.fillStyle = obstacle.color;
                this.ctx.beginPath();
                this.ctx.arc(obstacle.x, obstacle.y, obstacle.size, 0, Math.PI * 2);
                this.ctx.fill();
                
                // Obstacle detail (warning icon)
                this.ctx.fillStyle = '#ffffff';
                this.ctx.font = `bold ${obstacle.size}px Arial`;
                this.ctx.textAlign = 'center';
                this.ctx.textBaseline = 'middle';
                this.ctx.fillText(obstacle.icon, obstacle.x, obstacle.y);
                
                this.ctx.restore();
            }
            
            drawWorker() {
                this.ctx.save();
                
                // Worker shadow
                this.ctx.shadowColor = 'rgba(30, 136, 229, 0.8)';
                this.ctx.shadowBlur = 15;
                
                // Worker body (construction cart)
                this.ctx.fillStyle = this.worker.color;
                this.ctx.beginPath();
                this.ctx.roundRect(this.worker.x, this.worker.y, this.worker.width, this.worker.height, 8);
                this.ctx.fill();
                
                // Worker wheels
                this.ctx.fillStyle = '#37474f';
                this.ctx.beginPath();
                this.ctx.arc(this.worker.x + 10, this.worker.y + this.worker.height, 5, 0, Math.PI * 2);
                this.ctx.arc(this.worker.x + this.worker.width - 10, this.worker.y + this.worker.height, 5, 0, Math.PI * 2);
                this.ctx.fill();
                
                // Worker detail (hard hat)
                this.ctx.fillStyle = '#ffb300';
                this.ctx.beginPath();
                this.ctx.arc(this.worker.x + this.worker.width/2, this.worker.y - 5, 10, 0, Math.PI * 2);
                this.ctx.fill();
                
                this.ctx.restore();
            }
            
            drawGameInfo() {
                this.ctx.save();
                
                // Score display
                this.ctx.fillStyle = '#1e88e5';
                this.ctx.font = 'bold 16px Poppins';
                this.ctx.textAlign = 'left';
                this.ctx.textBaseline = 'top';
                this.ctx.fillText(`SKOR: ${this.score}`, 10, 10);
                
                // Time display
                this.ctx.fillStyle = '#42a5f5';
                this.ctx.fillText(`WAKTU: ${this.timeLeft}s`, 10, 35);
                
                // Level display
                this.ctx.fillStyle = '#43a047';
                this.ctx.fillText(`LEVEL: ${this.level}`, 10, 60);
                
                this.ctx.restore();
            }
            
            moveWorker(e) {
                const rect = this.canvas.getBoundingClientRect();
                const mouseX = e.clientX - rect.left;
                
                // Update worker position
                this.worker.x = mouseX - this.worker.width / 2;
                
                // Keep worker within canvas bounds
                if (this.worker.x < 0) this.worker.x = 0;
                if (this.worker.x + this.worker.width > this.canvas.width) {
                    this.worker.x = this.canvas.width - this.worker.width;
                }
                
                // Check collisions if playing
                if (this.isPlaying) {
                    this.checkCollisions();
                }
                
                this.draw();
            }
            
            checkCollisions() {
                // Check material collisions
                for (let i = this.materials.length - 1; i >= 0; i--) {
                    const material = this.materials[i];
                    
                    if (material.active && 
                        material.x + material.size > this.worker.x && 
                        material.x - material.size < this.worker.x + this.worker.width &&
                        material.y + material.size > this.worker.y &&
                        material.y - material.size < this.worker.y + this.worker.height) {
                        
                        // Collision detected - collect material
                        this.score += material.points * this.level;
                        this.scoreElement.textContent = this.score;
                        
                        // Remove material and create effect
                        this.materials.splice(i, 1);
                        this.createCollectionEffect(material.x, material.y, `+${material.points}`);
                        
                        // Add new material
                        this.spawnMaterial();
                    }
                }
                
                // Check obstacle collisions
                for (let i = this.obstacles.length - 1; i >= 0; i--) {
                    const obstacle = this.obstacles[i];
                    
                    if (obstacle.active && 
                        obstacle.x + obstacle.size > this.worker.x && 
                        obstacle.x - obstacle.size < this.worker.x + this.worker.width &&
                        obstacle.y + obstacle.size > this.worker.y &&
                        obstacle.y - obstacle.size < this.worker.y + this.worker.height) {
                        
                        // Collision detected - hit by obstacle
                        this.score = Math.max(0, this.score - 5);
                        this.scoreElement.textContent = this.score;
                        
                        // Remove obstacle and create effect
                        this.obstacles.splice(i, 1);
                        this.createCollectionEffect(obstacle.x, obstacle.y, '-5', '#e53935');
                        
                        // Add new obstacle
                        this.spawnObstacle();
                    }
                }
            }
            
            createCollectionEffect(x, y, text, color = '#1e88e5') {
                // Create a temporary text effect
                const effect = {
                    x: x,
                    y: y,
                    text: text,
                    color: color,
                    alpha: 1,
                    size: 20
                };
                
                // Animate the effect
                const animateEffect = () => {
                    effect.y -= 1;
                    effect.alpha -= 0.02;
                    
                    if (effect.alpha > 0) {
                        this.ctx.save();
                        this.ctx.globalAlpha = effect.alpha;
                        this.ctx.fillStyle = effect.color;
                        this.ctx.font = `bold ${effect.size}px Poppins`;
                        this.ctx.textAlign = 'center';
                        this.ctx.textBaseline = 'middle';
                        this.ctx.fillText(effect.text, effect.x, effect.y);
                        this.ctx.restore();
                        
                        requestAnimationFrame(animateEffect);
                    }
                };
                
                animateEffect();
            }
            
            spawnMaterial() {
                const materialType = this.materialTypes[Math.floor(Math.random() * this.materialTypes.length)];
                const material = {
                    x: Math.random() * (this.canvas.width - 30) + 15,
                    y: -20,
                    type: 'material',
                    size: 12 + Math.random() * 8,
                    color: materialType.color,
                    icon: materialType.icon,
                    points: materialType.points,
                    speed: 1 + Math.random() * 0.5 + (this.level * 0.2),
                    active: true
                };
                
                this.materials.push(material);
            }
            
            spawnObstacle() {
                const obstacle = {
                    x: Math.random() * (this.canvas.width - 30) + 15,
                    y: -20,
                    type: 'obstacle',
                    size: 10 + Math.random() * 6,
                    color: '#e53935',
                    icon: '⚠',
                    speed: 1.5 + Math.random() * 0.8 + (this.level * 0.3),
                    active: true
                };
                
                this.obstacles.push(obstacle);
            }
            
            updateGameObjects() {
                // Update materials position
                this.materials.forEach(material => {
                    if (material.active) {
                        material.y += material.speed;
                        
                        // Reset if out of bounds
                        if (material.y > this.canvas.height + 30) {
                            material.y = -20;
                            material.x = Math.random() * (this.canvas.width - 30) + 15;
                            material.speed = 1 + Math.random() * 0.5 + (this.level * 0.2);
                        }
                    }
                });
                
                // Update obstacles position
                this.obstacles.forEach(obstacle => {
                    if (obstacle.active) {
                        obstacle.y += obstacle.speed;
                        
                        // Reset if out of bounds
                        if (obstacle.y > this.canvas.height + 30) {
                            obstacle.y = -20;
                            obstacle.x = Math.random() * (this.canvas.width - 30) + 15;
                            obstacle.speed = 1.5 + Math.random() * 0.8 + (this.level * 0.3);
                        }
                    }
                });
                
                this.draw();
            }
            
            updateLevel() {
                // Level up every 100 points
                const newLevel = Math.floor(this.score / 100) + 1;
                
                if (newLevel > this.level) {
                    this.level = newLevel;
                    this.levelElement.textContent = this.level;
                    
                    // Show level up message
                    this.showMessage(`Level ${this.level}!`);
                }
            }
            
            showMessage(text, duration = 2000) {
                this.gameMessage.textContent = text;
                this.gameMessage.style.display = 'block';
                
                setTimeout(() => {
                    this.gameMessage.style.display = 'none';
                }, duration);
            }
            
            startGame() {
                if (this.isPlaying) return;
                
                this.isPlaying = true;
                this.startBtn.disabled = true;
                this.resetBtn.disabled = false;
                
                // Clear existing objects
                this.materials = this.materials.filter(m => !m.active);
                this.obstacles = this.obstacles.filter(o => !o.active);
                
                // Spawn initial objects
                for (let i = 0; i < 5; i++) {
                    setTimeout(() => this.spawnMaterial(), i * 500);
                }
                
                for (let i = 0; i < 3; i++) {
                    setTimeout(() => this.spawnObstacle(), i * 800);
                }
                
                // Start game loop
                this.gameInterval = setInterval(() => this.updateGameObjects(), 16);
                
                // Start timer
                this.timeInterval = setInterval(() => {
                    this.timeLeft--;
                    this.timeElement.textContent = this.timeLeft;
                    
                    if (this.timeLeft <= 0) {
                        this.endGame();
                    }
                    
                    // Spawn new objects periodically
                    if (this.timeLeft % 5 === 0 && this.materials.length < 10) {
                        this.spawnMaterial();
                    }
                    
                    if (this.timeLeft % 7 === 0 && this.obstacles.length < 5) {
                        this.spawnObstacle();
                    }
                    
                    // Update level
                    this.updateLevel();
                }, 1000);
                
                this.showMessage('Game dimulai! Kumpulkan material!', 1500);
            }
            
            endGame() {
                this.isPlaying = false;
                clearInterval(this.gameInterval);
                clearInterval(this.timeInterval);
                
                this.startBtn.disabled = false;
                
                // Show game over message
                this.showMessage(`Game Selesai! Skor akhir: ${this.score}`, 5000);
                
                // Stop active objects
                this.materials.forEach(material => material.active = false);
                this.obstacles.forEach(obstacle => obstacle.active = false);
            }
            
            resetGame() {
                this.endGame();
                
                // Reset game state
                this.score = 0;
                this.timeLeft = 60;
                this.level = 1;
                
                // Update UI
                this.scoreElement.textContent = this.score;
                this.timeElement.textContent = this.timeLeft;
                this.levelElement.textContent = this.level;
                
                // Clear objects
                this.materials = [];
                this.obstacles = [];
                
                // Create decorative elements
                this.createInitialElements();
                
                this.draw();
                
                this.showMessage('Game direset!', 1500);
            }
        }
        
        // Kuis Konstruksi & DPU
        class ConstructionQuiz {
            constructor() {
                this.quizQuestion = document.getElementById('quizQuestion');
                this.quizOptions = document.getElementById('quizOptions');
                this.quizExplanation = document.getElementById('quizExplanation');
                this.submitBtn = document.getElementById('submitQuiz');
                this.quizScoreElement = document.getElementById('quizScore');
                
                // Pertanyaan tentang konstruksi, jalan, dan DPU
                this.questions = [
                    {
                        question: "Apa kepanjangan dari DPU?",
                        options: [
                            "Dinas Pembangunan Umum",
                            "Dinas Pekerjaan Umum",
                            "Departemen Pekerjaan Umum",
                            "Direktorat Pembangunan Umum"
                        ],
                        correct: 1,
                        explanation: "DPU adalah singkatan dari Dinas Pekerjaan Umum, yang bertanggung jawab atas pembangunan dan pemeliharaan infrastruktur publik seperti jalan, jembatan, dan gedung pemerintahan."
                    },
                    {
                        question: "Apa fungsi utama dari aspal dalam konstruksi jalan?",
                        options: [
                            "Sebagai bahan pengisi celah",
                            "Sebagai bahan pengikat agregat",
                            "Sebagai bahan pelapis permukaan",
                            "Sebagai bahan pengering"
                        ],
                        correct: 1,
                        explanation: "Aspal berfungsi sebagai bahan pengikat (binder) yang merekatkan agregat (batu, kerikil, pasir) menjadi campuran yang padat dan kuat untuk lapisan permukaan jalan."
                    },
                    {
                        question: "Berapa standar ketebalan minimum perkerasan jalan untuk jalan kolektor?",
                        options: [
                            "10-15 cm",
                            "15-20 cm",
                            "20-25 cm",
                            "25-30 cm"
                        ],
                        correct: 2,
                        explanation: "Standar ketebalan perkerasan jalan untuk jalan kolektor umumnya 20-25 cm, tergantung pada beban lalu lintas dan kondisi tanah dasar."
                    },
                    {
                        question: "Apa yang dimaksud dengan 'jalan arteri'?",
                        options: [
                            "Jalan lingkungan perumahan",
                            "Jalan penghubung antar kota",
                            "Jalan utama dalam kota",
                            "Jalan untuk pejalan kaki"
                        ],
                        correct: 2,
                        explanation: "Jalan arteri adalah jalan utama dalam kota yang berfungsi melayani pergerakan lalu lintas dengan volume tinggi dan jarak perjalanan yang relatif panjang."
                    },
                    {
                        question: "Material apa yang paling sesuai untuk lapisan dasar (subbase) jalan?",
                        options: [
                            "Aspal beton",
                            "Batu pecah (split)",
                            "Tanah pilihan (selected soil)",
                            "Sirtu (pasir batu)"
                        ],
                        correct: 3,
                        explanation: "Sirtu (pasir batu) sering digunakan sebagai material lapisan dasar (subbase) karena memiliki sifat drainase yang baik dan mudah dipadatkan."
                    },
                    {
                        question: "Apa fungsi dari tata ruang dalam pembangunan infrastruktur?",
                        options: [
                            "Menentukan harga material",
                            "Mengatur penggunaan lahan",
                            "Menetapkan upah pekerja",
                            "Menentukan jadwal pembangunan"
                        ],
                        correct: 1,
                        explanation: "Tata ruang berfungsi mengatur pemanfaatan ruang dan penggunaan lahan untuk mencapai pemanfaatan yang optimal, berkelanjutan, dan terintegrasi."
                    },
                    {
                        question: "Apa yang dimaksud dengan 'kerusakan jalan jenis crocodile cracking'?",
                        options: [
                            "Retakan berbentuk garis lurus",
                            "Retakan berbentuk pola kulit buaya",
                            "Retakan melingkar di permukaan",
                            "Retakan membentuk pola bintang"
                        ],
                        correct: 1,
                        explanation: "Crocodile cracking adalah jenis kerusakan jalan yang berbentuk pola retakan saling berhubungan menyerupai kulit buaya, biasanya disebabkan oleh beban berlebih atau lapisan dasar yang lemah."
                    },
                    {
                        question: "Berapa frekuensi pemeliharaan rutin jalan yang ideal?",
                        options: [
                            "Setiap bulan",
                            "Setiap 6 bulan",
                            "Setiap 1-2 tahun",
                            "Setiap 5 tahun"
                        ],
                        correct: 2,
                        explanation: "Pemeliharaan rutin jalan sebaiknya dilakukan setiap 1-2 tahun untuk mencegah kerusakan kecil berkembang menjadi kerusakan besar yang membutuhkan biaya perbaikan lebih tinggi."
                    }
                ];
                
                this.currentQuestion = 0;
                this.score = 0;
                this.selectedOption = null;
                
                this.init();
            }
            
            init() {
                this.loadQuestion();
                
                // Event listeners for options
                this.quizOptions.addEventListener('click', (e) => {
                    if (e.target.classList.contains('quiz-option')) {
                        this.selectOption(e.target);
                    }
                });
                
                // Event listener for submit button
                this.submitBtn.addEventListener('click', () => this.checkAnswer());
            }
            
            loadQuestion() {
                const question = this.questions[this.currentQuestion];
                this.quizQuestion.textContent = `Pertanyaan ${this.currentQuestion + 1}: ${question.question}`;
                
                // Clear previous options and explanation
                this.quizOptions.innerHTML = '';
                this.quizExplanation.classList.remove('show');
                this.quizExplanation.textContent = '';
                this.selectedOption = null;
                
                // Add new options
                question.options.forEach((option, index) => {
                    const optionElement = document.createElement('div');
                    optionElement.className = 'quiz-option';
                    optionElement.dataset.value = index;
                    optionElement.textContent = `${String.fromCharCode(65 + index)}. ${option}`;
                    this.quizOptions.appendChild(optionElement);
                });
                
                // Set explanation
                this.quizExplanation.textContent = question.explanation;
            }
            
            selectOption(optionElement) {
                // Remove selection from all options
                document.querySelectorAll('.quiz-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                
                // Add selection to clicked option
                optionElement.classList.add('selected');
                this.selectedOption = parseInt(optionElement.dataset.value);
            }
            
            checkAnswer() {
                if (this.selectedOption === null) {
                    this.showNotification("Pilih jawaban terlebih dahulu!", "warning");
                    return;
                }
                
                const question = this.questions[this.currentQuestion];
                const options = document.querySelectorAll('.quiz-option');
                
                // Show correct/incorrect answers
                options.forEach((option, index) => {
                    if (index === question.correct) {
                        option.classList.add('correct');
                    } else if (index === this.selectedOption && index !== question.correct) {
                        option.classList.add('incorrect');
                    }
                    
                    // Disable further selection
                    option.style.pointerEvents = 'none';
                });
                
                // Show explanation
                this.quizExplanation.classList.add('show');
                
                // Update score if correct
                if (this.selectedOption === question.correct) {
                    this.score += 10;
                    this.showNotification("Jawaban benar! +10 poin", "success");
                } else {
                    this.showNotification(`Jawaban salah!`, "error");
                }
                
                // Update score display
                this.quizScoreElement.textContent = `Skor: ${this.score}`;
                
                // Enable next question after delay
                this.submitBtn.disabled = true;
                setTimeout(() => {
                    this.nextQuestion();
                }, 3000);
            }
            
            nextQuestion() {
                this.currentQuestion++;
                
                if (this.currentQuestion >= this.questions.length) {
                    this.showNotification(`Kuis selesai! Skor akhir: ${this.score}/${this.questions.length * 10}`, "success");
                    this.currentQuestion = 0;
                    this.score = 0;
                    this.quizScoreElement.textContent = `Skor: ${this.score}`;
                }
                
                this.loadQuestion();
                this.submitBtn.disabled = false;
            }
            
            showNotification(message, type) {
                // Create notification element
                const notification = document.createElement('div');
                notification.textContent = message;
                notification.style.position = 'fixed';
                notification.style.bottom = '20px';
                notification.style.left = '50%';
                notification.style.transform = 'translateX(-50%)';
                notification.style.background = type === 'success' ? 'rgba(67, 160, 71, 0.9)' : 
                                               type === 'error' ? 'rgba(229, 57, 53, 0.9)' : 
                                               'rgba(255, 179, 0, 0.9)';
                notification.style.color = 'white';
                notification.style.padding = '10px 20px';
                notification.style.borderRadius = '10px';
                notification.style.zIndex = '10000';
                notification.style.border = '1px solid rgba(255, 255, 255, 0.3)';
                notification.style.fontSize = '0.9rem';
                notification.style.maxWidth = '80%';
                notification.style.textAlign = 'center';
                notification.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.2)';
                
                document.body.appendChild(notification);
                
                // Remove notification after 3 seconds
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transition = 'opacity 0.5s';
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 500);
                }, 3000);
            }
        }
        
        // Animasi tambahan untuk login button
        function initLoginButtonEffects() {
            const loginBtn = document.querySelector('.btn-login');
            
            // Tambahkan partikel saat hover
            loginBtn.addEventListener('mouseenter', function() {
                createButtonParticles(this);
            });
            
            // Efek klik
            loginBtn.addEventListener('click', function(e) {
                createClickRipple(e, this);
            });
        }
        
        function createButtonParticles(button) {
            const rect = button.getBoundingClientRect();
            const particleCount = 8;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.style.position = 'fixed';
                particle.style.width = '6px';
                particle.style.height = '6px';
                particle.style.borderRadius = '50%';
                particle.style.background = `hsl(${Math.random() * 60 + 200}, 80%, 60%)`;
                particle.style.pointerEvents = 'none';
                particle.style.zIndex = '1000';
                
                // Start from button center
                const startX = rect.left + rect.width / 2;
                const startY = rect.top + rect.height / 2;
                
                particle.style.left = `${startX}px`;
                particle.style.top = `${startY}px`;
                
                document.body.appendChild(particle);
                
                // Animate particle
                const angle = Math.random() * Math.PI * 2;
                const speed = 2 + Math.random() * 3;
                const vx = Math.cos(angle) * speed;
                const vy = Math.sin(angle) * speed;
                
                let opacity = 1;
                const animateParticle = () => {
                    opacity -= 0.03;
                    particle.style.opacity = opacity;
                    particle.style.transform = `translate(${vx * (1 - opacity) * 50}px, ${vy * (1 - opacity) * 50}px)`;
                    
                    if (opacity > 0) {
                        requestAnimationFrame(animateParticle);
                    } else {
                        particle.remove();
                    }
                };
                
                animateParticle();
            }
        }
        
        function createClickRipple(e, button) {
            const ripple = document.createElement('div');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.7)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.pointerEvents = 'none';
            
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = `${size}px`;
            ripple.style.height = `${size}px`;
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            
            button.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        }
        
        // Tambahkan style untuk ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Inisialisasi semua efek ketika halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            initParticles();
            initMouseTrail();
            initLoginButtonEffects();
            
            // Inisialisasi semua fitur hiburan
            const miniGame = new MiniGame();
            const constructionQuiz = new ConstructionQuiz();
            
            // Tambahkan efek ketik untuk teks utama
            const title = document.querySelector('h1');
            title.style.animation = 'none';
            setTimeout(() => {
                title.style.animation = 'cardEntranceConstruction 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards';
            }, 100);
        });
    </script>
</body>
</html>