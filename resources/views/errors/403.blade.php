<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Truy cập bị từ chối | 403 Forbidden</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/android-chrome-192x192.png') }}">
    <link rel="manifest" href="{{ asset('images/site.webmanifest') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        :root {
            --primary-color: #FF5252;
            --primary-dark: #D32F2F;
            --primary-light: #FF8A80;
            --secondary-color: #121212;
            --text-color: #FFFFFF;
            --light-color: rgba(255, 255, 255, 0.95);
            --galaxy-purple: #9C27B0;
            --galaxy-blue: #2196F3;
            --galaxy-pink: #E91E63;
            --button-bg: #FF5252;
            --button-hover: #D32F2F;
            --button-text: #FFFFFF;
            --back-button-bg: #424242;
            --back-button-hover: #616161;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            touch-action: none;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #121212 0%, #000000 100%);
            cursor: default;
        }

        /* Galaxy background effect */
        .galaxy {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .star {
            position: absolute;
            background-color: white;
            border-radius: 50%;
            animation: twinkle var(--duration) infinite ease-in-out;
            opacity: 0;
            pointer-events: none;
        }

        .shooting-star {
            position: absolute;
            width: 4px;
            height: 4px;
            background: linear-gradient(to right,
                    rgba(255, 255, 255, 0) 0%,
                    var(--galaxy-purple) 30%,
                    white 50%,
                    var(--galaxy-blue) 70%,
                    rgba(255, 255, 255, 0) 100%);
            border-radius: 50%;
            filter: blur(1px);
            animation: shootingStarFly var(--duration) linear infinite;
            transform: rotate(var(--angle)) translateX(0);
            opacity: 0;
            pointer-events: none;
        }

        .galaxy-particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: var(--color);
            border-radius: 50%;
            filter: blur(1px);
            animation: particleFall var(--duration) linear forwards;
            opacity: 0;
            pointer-events: none;
        }

        .error-container {
            background-color: rgba(30, 30, 30, 0.9);
            border-radius: 20px;
            padding: 3rem 2rem;
            max-width: 700px;
            width: 90%;
            position: relative;
            z-index: 10;
            box-shadow: 0 0 50px rgba(255, 82, 82, 0.3);
            border: 1px solid rgba(255, 82, 82, 0.2);
            backdrop-filter: blur(15px);
        }

        .error-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle,
                    rgba(255, 82, 82, 0.15) 0%,
                    transparent 70%);
            animation: shine 8s infinite linear;
            z-index: -1;
        }

        .error-content {
            position: relative;
            z-index: 2;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 900;
            color: var(--primary-color);
            margin: 0;
            line-height: 1;
            text-shadow: 0 0 15px rgba(255, 82, 82, 0.7);
            animation: pulse 1.5s infinite;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .error-title {
            font-size: 2.5rem;
            margin: 1rem 0;
            color: var(--text-color);
            font-weight: 700;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }

        .error-message {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.6;
            padding: 0 1rem;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.8rem 1.8rem;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            outline: none;
            min-width: 160px;
            pointer-events: auto;
        }

        .home-button {
            background-color: var(--button-bg);
            color: var(--button-text);
            box-shadow: 0 4px 15px rgba(255, 82, 82, 0.4);
        }

        .home-button:hover {
            background-color: var(--button-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 82, 82, 0.6);
        }

        .back-button {
            background-color: var(--back-button-bg);
            color: var(--button-text);
            box-shadow: 0 4px 15px rgba(66, 66, 66, 0.4);
        }

        .back-button:hover {
            background-color: var(--back-button-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(97, 97, 97, 0.6);
        }

        .floating-lock {
            position: absolute;
            opacity: 0.2;
            z-index: 1;
            animation: float 6s ease-in-out infinite;
            filter: drop-shadow(0 0 10px var(--primary-color));
            pointer-events: none;
        }

        .floating-lock:nth-child(1) {
            top: 10%;
            left: 5%;
            width: 60px;
            animation-delay: 0s;
            animation-duration: 7s;
        }

        .floating-lock:nth-child(2) {
            top: 70%;
            right: 5%;
            width: 80px;
            animation-delay: 1.5s;
            animation-duration: 9s;
        }

        .floating-lock:nth-child(3) {
            bottom: 10%;
            left: 15%;
            width: 50px;
            animation-delay: 2.5s;
            animation-duration: 6s;
        }

        /* Animations */
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.9;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-30px) rotate(5deg);
            }
        }

        @keyframes shine {
            0% {
                transform: rotate(0deg);
                opacity: 0.3;
            }

            50% {
                opacity: 0.6;
            }

            100% {
                transform: rotate(360deg);
                opacity: 0.3;
            }
        }

        @keyframes twinkle {
            0% {
                opacity: 0;
                transform: scale(0.5);
            }

            50% {
                opacity: 1;
                transform: scale(1);
            }

            100% {
                opacity: 0;
                transform: scale(0.5);
            }
        }

        @keyframes shootingStarFly {
            0% {
                transform: rotate(var(--angle)) translateX(-100vw) translateY(-100vh);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: rotate(var(--angle)) translateX(100vw) translateY(100vh);
                opacity: 0;
            }
        }

        @keyframes particleFall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 6rem;
            }

            .error-title {
                font-size: 2rem;
            }

            .error-message {
                font-size: 1rem;
            }

            .button-container {
                flex-direction: column;
                align-items: center;
            }

            .action-button {
                width: 100%;
                max-width: 220px;
            }
        }
    </style>
</head>

<body>
    <!-- Galaxy background elements -->
    <div class="galaxy" id="galaxy"></div>

    <div class="error-container">
        <!-- Floating lock icons -->
        <svg class="floating-lock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="var(--primary-color)">
            <path d="M12 2C9.243 2 7 4.243 7 7v3H6c-1.103 0-2 .897-2 2v8c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-8c0-1.103-.897-2-2-2h-1V7c0-2.757-2.243-5-5-5zm6 10v8H6v-8h12zm-9-2V7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9z" />
        </svg>
        <svg class="floating-lock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="var(--primary-color)">
            <path d="M12 2C9.243 2 7 4.243 7 7v3H6c-1.103 0-2 .897-2 2v8c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-8c0-1.103-.897-2-2-2h-1V7c0-2.757-2.243-5-5-5zm6 10v8H6v-8h12zm-9-2V7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9z" />
        </svg>
        <svg class="floating-lock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="var(--primary-color)">
            <path d="M12 2C9.243 2 7 4.243 7 7v3H6c-1.103 0-2 .897-2 2v8c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-8c0-1.103-.897-2-2-2h-1V7c0-2.757-2.243-5-5-5zm6 10v8H6v-8h12zm-9-2V7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9z" />
        </svg>

        <div class="error-content">
            <h1 class="error-code">403</h1>
            <h2 class="error-title">TRUY CẬP BỊ TỪ CHỐI</h2>
            <p class="error-message">
                Bạn không có quyền truy cập vào tài nguyên này. Nếu bạn cho rằng đây là lỗi, vui lòng liên hệ với quản trị viên hệ thống.
            </p>
            
            <div class="button-container">
                <button class="action-button back-button" onclick="window.history.back()">
                    ← Quay lại trang trước
                </button>
            </div>
        </div>
    </div>

    <script>
        // Tạo ngôi sao ngay khi script được load
        (function createInitialStars() {
            const galaxy = document.getElementById('galaxy');
            const initialStarsCount = 300; // Số ngôi sao ban đầu
            
            // Tạo các ngôi sao ban đầu
            for (let i = 0; i < initialStarsCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'galaxy-particle';
                
                const posX = Math.random() * 100;
                const duration = 3 + Math.random() * 7;
                const size = 6 + Math.random() * 3;
                const colors = ['var(--galaxy-purple)', 'var(--galaxy-blue)', 'var(--galaxy-pink)', 'white'];
                const color = colors[Math.floor(Math.random() * colors.length)];
                
                particle.style.left = `${posX}%`;
                particle.style.top = `${Math.random() * 100}%`;
                particle.style.setProperty('--duration', `${duration}s`);
                particle.style.animationDelay = `${Math.random() * 6}s`;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.setProperty('--color', color);
                particle.style.opacity = '1';
                
                galaxy.appendChild(particle);
                
                // Bắt đầu animation ngay lập tức
                particle.animate([
                    { transform: `translateY(${-Math.random() * 100 - 50}vh) rotate(0deg)`, opacity: 1 },
                    { transform: `translateY(100vh) rotate(360deg)`, opacity: 0 }
                ], {
                    duration: duration * 1000,
                    iterations: 1,
                    fill: 'forwards'
                }).onfinish = () => {
                    particle.remove();
                };
            }
        })();

        // Tạo phần còn lại của galaxy khi DOM load xong
        function createGalaxy() {
            const galaxy = document.getElementById('galaxy');
            const starsCount = 200;
            const shootingStarsCount = 5;
            const particlesCount = 300;
            const colors = ['var(--galaxy-purple)', 'var(--galaxy-blue)', 'var(--galaxy-pink)', 'white'];

            // Create stars
            for (let i = 0; i < starsCount; i++) {
                const star = document.createElement('div');
                star.className = 'star';

                const size = 1 + Math.random() * 2;
                const posX = Math.random() * 100;
                const posY = Math.random() * 100;
                const duration = 3 + Math.random() * 7;
                const delay = Math.random() * 15;

                star.style.width = `${size}px`;
                star.style.height = `${size}px`;
                star.style.left = `${posX}%`;
                star.style.top = `${posY}%`;
                star.style.setProperty('--duration', `${duration}s`);
                star.style.animationDelay = `${delay}s`;

                galaxy.appendChild(star);
            }

            // Create shooting stars
            for (let i = 0; i < shootingStarsCount; i++) {
                const shootingStar = document.createElement('div');
                shootingStar.className = 'shooting-star';

                const posX = Math.random() * 100;
                const posY = Math.random() * 100;
                const angle = 20 + Math.random() * 40;
                const duration = 5 + Math.random() * 10;
                const delay = Math.random() * 20;
                const size = 2 + Math.random() * 3;

                shootingStar.style.left = `${posX}%`;
                shootingStar.style.top = `${posY}%`;
                shootingStar.style.setProperty('--angle', `${angle}deg`);
                shootingStar.style.setProperty('--duration', `${duration}s`);
                shootingStar.style.animationDelay = `${delay}s`;
                shootingStar.style.width = `${size}px`;
                shootingStar.style.height = `${size}px`;

                galaxy.appendChild(shootingStar);
            }

            // Create falling particles
            for (let i = 0; i < particlesCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'galaxy-particle';

                const posX = Math.random() * 100;
                const duration = 8 + Math.random() * 15;
                const delay = Math.random() * 25;
                const size = 2 + Math.random() * 3;
                const color = colors[Math.floor(Math.random() * colors.length)];

                particle.style.left = `${posX}%`;
                particle.style.top = `-20px`;
                particle.style.setProperty('--duration', `${duration}s`);
                particle.style.animationDelay = `${delay}s`;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.setProperty('--color', color);

                galaxy.appendChild(particle);
            }
        }

        // Disable all interactions
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        document.addEventListener('keydown', function(e) {
            // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U
            if (e.key === 'F12' ||
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.shiftKey && e.key === 'J') ||
                (e.ctrlKey && e.key === 'u')) {
                e.preventDefault();
            }
        });

        // Prevent scrolling
        window.addEventListener('scroll', function(e) {
            window.scrollTo(0, 0);
        });

        document.addEventListener('touchmove', function(e) {
            e.preventDefault();
        }, {
            passive: false
        });

        // Initialize galaxy when DOM is loaded
        document.addEventListener('DOMContentLoaded', createGalaxy);

        // Reset galaxy on resize to avoid performance issues
        window.addEventListener('resize', function() {
            const galaxy = document.getElementById('galaxy');
            galaxy.innerHTML = '';
            createInitialStars();
            createGalaxy();
        });
    </script>
</body>

</html>