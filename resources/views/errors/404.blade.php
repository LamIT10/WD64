<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Not Found</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
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
            z-index: 10;
            padding: 2rem;
            max-width: 800px;
        }

        .logo {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #00ff88;
            text-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
            animation: glow 2s infinite alternate;
        }

        h1 {
            font-size: 8rem;
            margin: 0;
            background: linear-gradient(45deg, #00ff88, #0099ff);
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
            background: linear-gradient(45deg, #00ff88, #0099ff);
            color: #16213e;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.3);
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 255, 136, 0.4);
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

        .gamepad {
            width: 150px;
            height: 100px;
            margin: 2rem auto;
            position: relative;
        }

        .gamepad-base {
            width: 150px;
            height: 80px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            position: relative;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .gamepad-stick {
            width: 30px;
            height: 30px;
            background: linear-gradient(45deg, #00ff88, #0099ff);
            border-radius: 50%;
            position: absolute;
            top: 25px;
            left: 30px;
            animation: moveStick 4s infinite ease-in-out;
        }

        .gamepad-buttons {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }

        .gamepad-button {
            width: 20px;
            height: 20px;
            background: linear-gradient(45deg, #ff3366, #ff0066);
            border-radius: 50%;
            animation: blink 2s infinite;
        }

        .gamepad-button:nth-child(2) {
            animation-delay: 0.5s;
            background: linear-gradient(45deg, #ffcc00, #ff9900);
        }

        .gamepad-button:nth-child(3) {
            animation-delay: 1s;
            background: linear-gradient(45deg, #0099ff, #0066ff);
        }

        .gamepad-button:nth-child(4) {
            animation-delay: 1.5s;
            background: linear-gradient(45deg, #9900ff, #6600cc);
        }

        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            pointer-events: none;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
            }

            to {
                text-shadow: 0 0 20px rgba(0, 255, 136, 0.8), 0 0 30px rgba(0, 255, 136, 0.6);
            }
        }

        @keyframes moveStick {
            0% {
                transform: translate(0, 0);
            }

            25% {
                transform: translate(20px, -10px);
            }

            50% {
                transform: translate(0, 10px);
            }

            75% {
                transform: translate(-20px, -10px);
            }

            100% {
                transform: translate(0, 0);
            }
        }

        @keyframes blink {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
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
        }
    </style>
</head>

<body>
    <div class="particles" id="particles"></div>
    <div class="container">
        <div class="logo">KHÔNG TÌM THẤY</div>
        <h1>404</h1>
        <h2>Không tìm thấy trang</h2>
        <p>Rất tiếc! Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển. Hãy quay lại trang chủ để tiếp tục trải nghiệm.</p>
        <div class="gamepad">
            <div class="gamepad-base">
                <div class="gamepad-stick"></div>
                <div class="gamepad-buttons">
                    <div class="gamepad-button"></div>
                    <div class="gamepad-button"></div>
                    <div class="gamepad-button"></div>
                    <div class="gamepad-button"></div>
                </div>
            </div>
        </div>

        <a href="/" class="btn">Continue to Home</a>
    </div>

    <script>
        // Particle animation
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;

            for (let i = 0; i < particleCount; i++) {
                createParticle();
            }

            function createParticle() {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                // Random properties
                const size = Math.random() * 5 + 2;
                const posX = Math.random() * window.innerWidth;
                const posY = Math.random() * window.innerHeight;
                const duration = Math.random() * 20 + 10;
                const delay = Math.random() * 5;
                const opacity = Math.random() * 0.5 + 0.1;
                const color = getRandomColor();

                // Apply styles
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${posX}px`;
                particle.style.top = `${posY}px`;
                particle.style.backgroundColor = color;
                particle.style.opacity = opacity;
                particle.style.animation = `float ${duration}s linear ${delay}s infinite`;

                particlesContainer.appendChild(particle);

                // Make particles move randomly
                setInterval(() => {
                    particle.style.left = `${Math.random() * window.innerWidth}px`;
                    particle.style.top = `${Math.random() * window.innerHeight}px`;
                }, duration * 1000);
            }

            function getRandomColor() {
                const colors = [
                    'rgba(0, 255, 136, 0.7)',
                    'rgba(0, 153, 255, 0.7)',
                    'rgba(255, 51, 102, 0.7)',
                    'rgba(255, 204, 0, 0.7)',
                    'rgba(153, 0, 255, 0.7)'
                ];
                return colors[Math.floor(Math.random() * colors.length)];
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

            // Add keyboard interaction
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowUp' || e.key === 'ArrowDown' ||
                    e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                    const stick = document.querySelector('.gamepad-stick');
                    stick.style.animation = 'none';
                    void stick.offsetWidth; // Trigger reflow

                    let transform = '';
                    if (e.key === 'ArrowUp') transform = 'translate(0, -15px)';
                    if (e.key === 'ArrowDown') transform = 'translate(0, 15px)';
                    if (e.key === 'ArrowLeft') transform = 'translate(-15px, 0)';
                    if (e.key === 'ArrowRight') transform = 'translate(15px, 0)';

                    stick.style.transform = transform;

                    setTimeout(() => {
                        stick.style.animation = 'moveStick 4s infinite ease-in-out';
                        stick.style.transform = '';
                    }, 500);
                }
            });
        });
    </script>
</body>

</html>
