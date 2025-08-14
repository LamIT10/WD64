<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Lost in Space</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/android-chrome-192x192.png') }}">
    <link rel="manifest" href="{{ asset('images/site.webmanifest') }}">
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: radial-gradient(ellipse at bottom, #1B2735 0%, #090A0F 100%);
            color: #fff;
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .container {
            text-align: center;
            z-index: 20;
            padding: 2rem;
            max-width: 800px;
        }

        .logo {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #4facfe;
            text-shadow: 0 0 10px rgba(79, 172, 254, 0.5);
            animation: glow 2s infinite alternate;
        }

        h1 {
            font-size: 8rem;
            margin: 0;
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: float 3s ease-in-out infinite;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: #e6f1ff;
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: #a8b2d1;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: #16213e;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            z-index: 10;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4);
        }

        .btn:active {
            transform: translateY(1px);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        /* Spaceship Animation */
        .spaceship-container {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 5;
            overflow: hidden;
        }

        .spaceship {
            position: absolute;
            width: 120px;
            height: 60px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 60"><path d="M10,30 L50,10 L110,30 L50,50 Z" fill="%234facfe"/><circle cx="80" cy="30" r="10" fill="%2300f2fe"/><path d="M20,25 L40,25 M20,35 L40,35" stroke="white" stroke-width="2"/></svg>') no-repeat;
            animation: fly 20s linear infinite;
            filter: drop-shadow(0 0 10px rgba(79, 172, 254, 0.7));
        }

        /* Planets */
        .planet {
            position: absolute;
            border-radius: 50%;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3));
        }

        .planet-1 {
            width: 100px;
            height: 100px;
            background: radial-gradient(circle at 30% 30%, #ff9a9e, #fad0c4);
            top: 20%;
            left: 15%;
            animation: float 15s ease-in-out infinite;
        }

        .planet-2 {
            width: 150px;
            height: 150px;
            background: radial-gradient(circle at 70% 30%, #a1c4fd, #c2e9fb);
            bottom: 10%;
            right: 10%;
            animation: float 18s ease-in-out infinite reverse;
        }

        /* Stars */
        .stars {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .star {
            position: absolute;
            background-color: white;
            border-radius: 50%;
            animation: twinkle var(--duration) infinite ease-in-out;
        }

        /* Shooting Stars */
        .shooting-star {
            position: absolute;
            width: 4px;
            height: 4px;
            background: linear-gradient(to right, rgba(255, 255, 255, 0), white);
            border-radius: 50%;
            filter: drop-shadow(0 0 6px white);
            animation: shoot var(--time) linear infinite;
            opacity: 0;
        }

        @keyframes fly {
            0% {
                transform: translateX(-150px) translateY(100px) rotate(10deg);
            }
            25% {
                transform: translateX(25vw) translateY(30vh) rotate(-5deg);
            }
            50% {
                transform: translateX(50vw) translateY(50vh) rotate(0deg);
            }
            75% {
                transform: translateX(75vw) translateY(20vh) rotate(5deg);
            }
            100% {
                transform: translateX(100vw) translateY(100px) rotate(10deg);
            }
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 10px rgba(79, 172, 254, 0.5);
            }
            to {
                text-shadow: 0 0 20px rgba(79, 172, 254, 0.8), 0 0 30px rgba(79, 172, 254, 0.6);
            }
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 1; }
        }

        @keyframes shoot {
            0% {
                transform: translateX(0) translateY(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            70% {
                opacity: 1;
            }
            100% {
                transform: translateX(500px) translateY(300px);
                opacity: 0;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            h1 {
                font-size: 5rem;
            }
            h2 {
                font-size: 1.5rem;
            }
            p {
                font-size: 1rem;
            }
            .spaceship {
                width: 80px;
                height: 40px;
            }
        }
    </style>
</head>

<body>
    <!-- Space Background Elements -->
    <div class="stars" id="stars"></div>
    <div class="spaceship-container">
        <div class="spaceship"></div>
        <div class="planet planet-1"></div>
        <div class="planet planet-2"></div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h1>404</h1>
        <h2>Không tìm thấy trang</h2>
        <p>Rất tiếc! Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển. Hãy quay lại trang chủ để tiếp tục trải nghiệm.</p>
        <a href="/admin/dashboard" class="btn">Trở về Trang chủ</a>
    </div>

    <script>
        // Create stars
        document.addEventListener('DOMContentLoaded', function() {
            const starsContainer = document.getElementById('stars');
            const starCount = 200;
            
            for (let i = 0; i < starCount; i++) {
                const star = document.createElement('div');
                star.classList.add('star');
                
                const size = Math.random() * 3;
                const posX = Math.random() * 100;
                const posY = Math.random() * 100;
                const duration = Math.random() * 5 + 5;
                
                star.style.width = `${size}px`;
                star.style.height = `${size}px`;
                star.style.left = `${posX}%`;
                star.style.top = `${posY}%`;
                star.style.setProperty('--duration', `${duration}s`);
                
                starsContainer.appendChild(star);
            }

            // Create shooting stars
            function createShootingStar() {
                const shootingStar = document.createElement('div');
                shootingStar.classList.add('shooting-star');
                
                const startX = Math.random() * 20;
                const startY = Math.random() * 20;
                const time = Math.random() * 3 + 2;
                
                shootingStar.style.left = `${startX}%`;
                shootingStar.style.top = `${startY}%`;
                shootingStar.style.setProperty('--time', `${time}s`);
                
                document.body.appendChild(shootingStar);
                
                setTimeout(() => {
                    shootingStar.remove();
                }, time * 1000);
            }
            
            // Regular shooting stars
            setInterval(createShootingStar, 2000);
            
            // Initial shooting stars
            for (let i = 0; i < 3; i++) {
                setTimeout(createShootingStar, i * 800);
            }

            // Button ripple effect
            const btn = document.querySelector('.btn');
            btn.addEventListener('click', function(e) {
                const x = e.clientX - e.target.getBoundingClientRect().left;
                const y = e.clientY - e.target.getBoundingClientRect().top;

                const ripple = document.createElement('span');
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;
                ripple.classList.add('ripple');

                this.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 1000);
            });
        });
    </script>
</body>

</html>