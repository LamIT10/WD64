<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied | Pro Player</title>
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
            color: #ff3366;
            text-shadow: 0 0 10px rgba(255, 51, 102, 0.5);
            animation: glow 2s infinite alternate;
        }

        h1 {
            font-size: 8rem;
            margin: 0;
            background: linear-gradient(45deg, #ff3366, #ff0066);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: shake 0.5s ease-in-out infinite alternate;
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
            background: linear-gradient(45deg, #ff3366, #ff0066);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(255, 51, 102, 0.3);
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            margin: 0 10px;
        }

        .btn-secondary {
            background: linear-gradient(45deg, #16213e, #1a1a2e);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 51, 102, 0.4);
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

        .shield {
            width: 150px;
            height: 180px;
            margin: 2rem auto;
            position: relative;
            animation: float 3s ease-in-out infinite;
        }

        .shield-body {
            width: 150px;
            height: 180px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 60% 60% 10% 10%;
            position: relative;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .shield-inner {
            width: 120px;
            height: 150px;
            background: rgba(255, 51, 102, 0.2);
            border-radius: 50% 50% 10% 10%;
            position: absolute;
            top: 15px;
            left: 15px;
            border: 2px dashed rgba(255, 255, 255, 0.3);
        }

        .shield-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .warning-sign {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #ff3366, #ff0066);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            animation: pulse 1.5s infinite;
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
                text-shadow: 0 0 10px rgba(255, 51, 102, 0.5);
            }
            to {
                text-shadow: 0 0 20px rgba(255, 51, 102, 0.8), 0 0 30px rgba(255, 51, 102, 0.6);
            }
        }

        @keyframes shake {
            0% {
                transform: translateX(0);
            }
            25% {
                transform: translateX(-5px);
            }
            50% {
                transform: translateX(5px);
            }
            75% {
                transform: translateX(-5px);
            }
            100% {
                transform: translateX(5px);
            }
        }

        @keyframes pulse {
            0% {
                transform: translateX(-50%) scale(1);
                box-shadow: 0 0 0 0 rgba(255, 51, 102, 0.7);
            }
            70% {
                transform: translateX(-50%) scale(1.1);
                box-shadow: 0 0 0 10px rgba(255, 51, 102, 0);
            }
            100% {
                transform: translateX(-50%) scale(1);
                box-shadow: 0 0 0 0 rgba(255, 51, 102, 0);
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
            .btn-container {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="particles" id="particles"></div>
    <div class="container">
        <div class="logo">PRO PLAYER</div>
        <h1>403</h1>
        <h2>Access Denied</h2>
        <p>You don't have permission to access this level. This area is restricted to authorized players only. Please verify your credentials or return to safe zone.</p>
        
        <div class="shield">
            <div class="shield-body">
                <div class="shield-inner"></div>
                <div class="warning-sign">!</div>
                <div class="shield-icon">⛔</div>
            </div>
        </div>
        
        <div class="btn-container">
            <a href="/" class="btn">Return to Home</a>
            <a href="/login" class="btn btn-secondary">Login as Admin</a>
        </div>
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
                    'rgba(255, 51, 102, 0.7)',
                    'rgba(255, 0, 102, 0.7)',
                    'rgba(255, 153, 0, 0.7)',
                    'rgba(255, 204, 0, 0.7)',
                    'rgba(255, 255, 255, 0.7)'
                ];
                return colors[Math.floor(Math.random() * colors.length)];
            }
            
            // Button ripple effect
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(btn => {
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
            
            // Add forbidden sound effect on key press
            document.addEventListener('keydown', function() {
                const shield = document.querySelector('.shield');
                shield.style.animation = 'none';
                void shield.offsetWidth; // Trigger reflow
                shield.style.animation = 'shake 0.3s linear';
                
                setTimeout(() => {
                    shield.style.animation = 'float 3s ease-in-out infinite';
                }, 300);
            });
        });
    </script>
</body>
</html>