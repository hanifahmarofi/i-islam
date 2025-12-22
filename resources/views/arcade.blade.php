<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arcade Zone - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Poppins:wght@400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #022c22; /* Base Dark Green */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            
            /* --- CHANGED HERE: Allow scrolling --- */
            overflow-y: auto;  /* Enable vertical scroll */
            overflow-x: hidden; /* Hide horizontal scroll */
            
            color: #fff;
            position: relative;
        }

        /* --- 1. VIBRANT GREEN BACKGROUND WITH GLOW --- */
        .starry-background {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            /* Vibrant Dark Green Gradient */
            background: radial-gradient(circle at 50% 50%, #065f46 0%, #064e3b 40%, #022c22 100%);
            z-index: -1;
        }

        /* --- 2. FALLING & BLINKING STARS --- */
        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            opacity: 0;
            box-shadow: 0 0 5px #fff, 0 0 10px #4ade80; /* Greenish glow on stars */
        }

        /* Twinkle Animation (Blinking) */
        @keyframes twinkle {
            0%, 100% { opacity: 0.2; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1.2); }
        }

        /* Falling Animation */
        @keyframes fall {
            0% { transform: translateY(-10vh) translateX(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(110vh) translateX(20px); opacity: 0; }
        }

        /* --- 3. CONTAINER --- */
        .arcade-container {
            width: 100%;
            max-width: 1000px;
            position: relative;
            z-index: 10;
            text-align: center;
            /* Glass backdrop for contrast */
            background: rgba(2, 44, 34, 0.6); 
            backdrop-filter: blur(8px);
            border-radius: 30px;
            padding: 40px;
            border: 1px solid rgba(74, 222, 128, 0.1);
            box-shadow: 0 0 50px rgba(6, 78, 59, 0.5);
            
            /* Ensure margin on top/bottom for scrolling space */
            margin-top: 40px;
            margin-bottom: 40px;
        }

        /* --- 4. GLITCH TEXT EFFECT (Green Tint) --- */
        .glitch-title {
            font-family: 'Press Start 2P', cursive;
            font-size: 3rem;
            color: #fff;
            position: relative;
            display: inline-block;
            margin-bottom: 10px;
            text-shadow: 2px 2px 0px #22c55e, -2px -2px 0px #a7f3d0;
        }

        /* Responsive Text Size */
        @media (max-width: 768px) {
            .glitch-title {
                font-size: 2rem;
            }
        }

        .glitch-title::before, .glitch-title::after {
            content: attr(data-text);
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: #022c22; /* Matches bg */
        }
        
        .glitch-title::before {
            left: 2px; text-shadow: -1px 0 #4ade80;
            clip: rect(44px, 450px, 56px, 0);
            animation: glitch-anim 5s infinite linear alternate-reverse;
        }
        
        .glitch-title::after {
            left: -2px; text-shadow: -1px 0 #10b981;
            clip: rect(44px, 450px, 56px, 0);
            animation: glitch-anim2 5s infinite linear alternate-reverse;
        }

        @keyframes glitch-anim {
            0% { clip: rect(30px, 9999px, 10px, 0); }
            5% { clip: rect(80px, 9999px, 90px, 0); }
            100% { clip: rect(0, 0, 0, 0); }
        }
        @keyframes glitch-anim2 {
            0% { clip: rect(10px, 9999px, 60px, 0); }
            100% { clip: rect(0, 0, 0, 0); }
        }

        /* --- 5. NEON GAME CARDS --- */
        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            margin-top: 50px;
        }

        .neon-card {
            background: rgba(0, 0, 0, 0.4);
            border: 2px solid #fff;
            border-radius: 20px;
            padding: 40px 20px;
            position: relative;
            transition: 0.3s;
            cursor: pointer;
            overflow: hidden;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            box-shadow: 0 0 10px rgba(74, 222, 128, 0.1);
        }

        /* Neon Pulsing Animations */
        .neon-yellow { animation: border-pulse-yellow 2s infinite; }
        .neon-red { animation: border-pulse-red 2s infinite; }
        .neon-blue { animation: border-pulse-blue 2s infinite; }

        @keyframes border-pulse-yellow {
            0%, 100% { border-color: #facc15; box-shadow: 0 0 10px #facc15; }
            50% { border-color: #fef08a; box-shadow: 0 0 25px #facc15; }
        }
        @keyframes border-pulse-red {
            0%, 100% { border-color: #ef4444; box-shadow: 0 0 10px #ef4444; }
            50% { border-color: #fca5a5; box-shadow: 0 0 25px #ef4444; }
        }
        @keyframes border-pulse-blue {
            0%, 100% { border-color: #3b82f6; box-shadow: 0 0 10px #3b82f6; }
            50% { border-color: #93c5fd; box-shadow: 0 0 25px #3b82f6; }
        }

        .neon-card:hover { transform: scale(1.05) translateY(-10px); background: rgba(0,0,0,0.6); }

        .icon-box {
            font-size: 3.5rem;
            margin-bottom: 10px;
            filter: drop-shadow(0 0 10px rgba(255,255,255,0.5));
        }

        /* --- BUTTONS --- */
        .back-btn {
            position: absolute; top: 30px; left: 30px;
            color: #a7f3d0; font-weight: bold; text-decoration: none;
            display: flex; align-items: center; gap: 10px;
            background: rgba(6, 78, 59, 0.6); padding: 10px 20px;
            border-radius: 30px; border: 1px solid #34d399;
            transition: 0.3s; z-index: 20;
        }
        .back-btn:hover { background: #34d399; color: #000; box-shadow: 0 0 20px #34d399; }

    </style>
</head>
<body>

    <div class="starry-background" id="starryBg"></div>

    <a href="{{ route('dashboard') }}" class="back-btn">
        <i class="fa-solid fa-rocket"></i> Dashboard
    </a>

    <div class="arcade-container">
        
        <div class="mb-2">
            <h1 class="glitch-title" data-text="ARCADE ZONE">ARCADE ZONE</h1>
        </div>
        <p class="text-green-300 text-lg font-mono tracking-widest">SELECT_YOUR_MISSION</p>

        <div class="games-grid">
            
            <a href="{{ route('games.scramble') }}" class="neon-card neon-yellow group">
                <div class="icon-box text-yellow-400">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <h3 class="text-2xl font-bold text-yellow-100 font-mono">WORD SCRAMBLE</h3>
                <p class="text-sm text-gray-300 font-mono">Decrypt the hidden Islamic terms.</p>
            </a>

            <a href="{{ route('games.hangman') }}" class="neon-card neon-red group">
                <div class="icon-box text-red-400">
                    <i class="fa-solid fa-skull-crossbones"></i>
                </div>
                <h3 class="text-2xl font-bold text-red-100 font-mono">HANGMAN</h3>
                <p class="text-sm text-gray-300 font-mono">Guess correctly or face the void.</p>
            </a>

            <a href="{{ route('games.match') }}" class="neon-card neon-blue group">
                <div class="icon-box text-blue-400">
                    <i class="fa-solid fa-clone"></i>
                </div>
                <h3 class="text-2xl font-bold text-blue-100 font-mono">MATCH PAIRS</h3>
                <p class="text-sm text-gray-300 font-mono">Connect the knowledge fragments.</p>
            </a>
            
             <a href="{{ route('games.quiz') }}" class="neon-card neon-blue group" style="border-color: #a855f7; animation: border-pulse-purple 2s infinite;">
                <div class="icon-box text-purple-400">
                    <i class="fa-solid fa-question-circle"></i>
                </div>
                <h3 class="text-2xl font-bold text-purple-100 font-mono">QUIZ</h3>
                <p class="text-sm text-gray-300 font-mono">Test your knowledge.</p>
            </a>

        </div>

        <div class="mt-16 opacity-80 hover:opacity-100 transition duration-500">
             <img src="{{ asset('img/logo.png') }}" alt="i-Islam" class="w-24 mx-auto drop-shadow-[0_0_20px_rgba(74,222,128,0.5)]">
        </div>
    </div>

    <script>
        // --- GLOBAL SOUND LISTENER ---
        const clickSound = new Audio("{{ asset('audio/click.mp3') }}");

        document.addEventListener('click', function(e) {
            if (e.target.closest('a, button, .neon-card')) {
                const sound = clickSound.cloneNode();
                sound.volume = 0.4;
                sound.play().catch(err => {});
            }
        });

        // --- FALLING STARS GENERATOR ---
        const bg = document.getElementById('starryBg');
        
        // 1. Static Twinkling Stars (Background depth)
        for (let i = 0; i < 50; i++) {
            const star = document.createElement('div');
            star.className = 'star';
            star.style.left = Math.random() * 100 + '%';
            star.style.top = Math.random() * 100 + '%';
            const size = Math.random() * 2 + 1;
            star.style.width = size + 'px'; star.style.height = size + 'px';
            star.style.animation = `twinkle ${Math.random() * 3 + 2}s infinite`;
            bg.appendChild(star);
        }

        // 2. Falling Stars (Rain effect)
        for (let i = 0; i < 30; i++) {
            const star = document.createElement('div');
            star.className = 'star';
            star.style.left = Math.random() * 100 + '%';
            star.style.top = -10 + 'px'; // Start above screen
            const size = Math.random() * 3 + 2;
            star.style.width = size + 'px'; star.style.height = size + 'px';
            
            // Randomize fall duration and delay
            const duration = Math.random() * 5 + 3; 
            const delay = Math.random() * 5;
            
            star.style.animation = `fall ${duration}s linear ${delay}s infinite`;
            bg.appendChild(star);
        }
    </script>

</body>
</html>