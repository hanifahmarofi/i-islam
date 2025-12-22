<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Zone - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* --- 1. NIGHT SKY BACKGROUND --- */
        .starry-background {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #022c22 100%);
            z-index: -1; overflow: hidden;
        }

        /* --- 2. ANIMATIONS --- */
        .star { position: absolute; background: white; border-radius: 50%; animation: twinkle var(--duration) infinite; opacity: 0; }
        @keyframes twinkle { 0%, 100% { opacity: 0.2; transform: scale(1); } 50% { opacity: 1; transform: scale(1.5); box-shadow: 0 0 10px gold; } }
        .shooting-star { position: absolute; height: 2px; background: linear-gradient(90deg, transparent, #ffd700, transparent); animation: shoot 6s linear infinite; opacity: 0; }
        @keyframes shoot { 0% { opacity: 0; transform: translateX(0) translateY(0); } 10% { opacity: 1; } 90% { opacity: 1; } 100% { opacity: 0; transform: translateX(500px) translateY(500px); } }

        /* --- 3. NEON GLOW --- */
        @keyframes neonPulse { 0% { box-shadow: 0 0 5px rgba(34, 197, 94, 0.4); } 50% { box-shadow: 0 0 15px rgba(34, 197, 94, 0.6); } 100% { box-shadow: 0 0 5px rgba(34, 197, 94, 0.4); } }
        .neon-effect { animation: neonPulse 3s infinite alternate; }

        /* --- 4. GLASS CARDS --- */
        .glass-card { background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.15); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37); }
        
        /* Specific Card Hovers */
        .hover-red:hover { border-color: rgba(239, 68, 68, 0.5); box-shadow: 0 0 20px rgba(239, 68, 68, 0.4); }
        .hover-yellow:hover { border-color: rgba(234, 179, 8, 0.5); box-shadow: 0 0 20px rgba(234, 179, 8, 0.4); }
        .hover-blue:hover { border-color: rgba(59, 130, 246, 0.5); box-shadow: 0 0 20px rgba(59, 130, 246, 0.4); }
        
        /* Floating Logo Animation */
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
        .floating-logo { animation: float 3s ease-in-out infinite; filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.4)); }

        /* Speech Bubble Arrow */
        .speech-bubble::after {
            content: ''; position: absolute; bottom: -8px; right: 30px;
            border-width: 8px 8px 0; border-style: solid; border-color: white transparent;
            display: block; width: 0;
        }
    </style>
</head>
<body class="font-sans antialiased text-white min-h-screen relative selection:bg-green-500 selection:text-white">

    <!-- BACKGROUND -->
    <div class="starry-background" id="starryBg"></div>

    <!-- NAVBAR -->
    <nav class="fixed w-full z-50 top-0 bg-black/30 backdrop-blur-md border-b border-white/10 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <span class="text-2xl animate-pulse">🌙</span>
                    <span class="font-bold text-xl text-green-400 tracking-tight">i-Islam Arcade</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-300 hover:text-white hover:underline transition flex items-center gap-1">
                        <span>←</span> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="max-w-6xl mx-auto py-24 px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-300 via-yellow-200 to-green-300 mb-4 drop-shadow-lg animate-pulse">
                🎮 i-Islam Arcade
            </h1>
            <p class="text-gray-300 text-lg max-w-2xl mx-auto">
                Welcome to the game zone! Choose a challenge, earn points, and master your Islamic knowledge.
            </p>
        </div>

        <!-- Games Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- GAME 1: Hangman (Red Theme) -->
            <div class="glass-card rounded-3xl overflow-hidden hover-red transition transform hover:-translate-y-2 duration-300 group h-full flex flex-col">
                <div class="bg-red-500/20 p-8 text-center border-b border-red-500/30 group-hover:bg-red-500/30 transition">
                    <div class="text-7xl mb-4 transform group-hover:scale-110 transition duration-300 drop-shadow-lg">😵</div>
                    <h2 class="text-3xl font-bold text-red-200 tracking-wide">Hangman</h2>
                </div>
                <div class="p-8 flex-grow flex flex-col justify-between">
                    <p class="text-gray-300 mb-6 text-sm leading-relaxed">Guess the Islamic term before the stickman disappears! A classic vocabulary builder.</p>
                    <a href="{{ route('games.hangman') }}" class="block w-full bg-red-600 hover:bg-red-500 text-white text-center font-bold py-3 rounded-xl shadow-lg hover:shadow-red-500/50 transition neon-effect">
                        Play Now
                    </a>
                </div>
            </div>

            <!-- GAME 2: Scramble Word (Yellow Theme) -->
            <div class="glass-card rounded-3xl overflow-hidden hover-yellow transition transform hover:-translate-y-2 duration-300 group h-full flex flex-col">
                <div class="bg-yellow-500/20 p-8 text-center border-b border-yellow-500/30 group-hover:bg-yellow-500/30 transition">
                    <div class="text-7xl mb-4 transform group-hover:scale-110 transition duration-300 drop-shadow-lg">🔠</div>
                    <h2 class="text-3xl font-bold text-yellow-200 tracking-wide">Word Scramble</h2>
                </div>
                <div class="p-8 flex-grow flex flex-col justify-between">
                    <p class="text-gray-300 mb-6 text-sm leading-relaxed">Unjumble the letters to find the hidden word. Test your spelling skills!</p>
                    <a href="{{ route('games.scramble') }}" class="block w-full bg-yellow-600 hover:bg-yellow-500 text-white text-center font-bold py-3 rounded-xl shadow-lg hover:shadow-yellow-500/50 transition neon-effect">
                        Play Now
                    </a>
                </div>
            </div>

            <!-- GAME 3: Match Pairs (Blue Theme) -->
            <div class="glass-card rounded-3xl overflow-hidden hover-blue transition transform hover:-translate-y-2 duration-300 group h-full flex flex-col">
                <div class="bg-blue-500/20 p-8 text-center border-b border-blue-500/30 group-hover:bg-blue-500/30 transition">
                    <div class="text-7xl mb-4 transform group-hover:scale-110 transition duration-300 drop-shadow-lg">🧩</div>
                    <h2 class="text-3xl font-bold text-blue-200 tracking-wide">Match Pairs</h2>
                </div>
                <div class="p-8 flex-grow flex flex-col justify-between">
                    <p class="text-gray-300 mb-6 text-sm leading-relaxed">Connect the term with its correct meaning. A test of memory and knowledge.</p>
                    <a href="{{ route('games.match') }}" class="block w-full bg-blue-600 hover:bg-blue-500 text-white text-center font-bold py-3 rounded-xl shadow-lg hover:shadow-blue-500/50 transition neon-effect">
                        Play Now
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- 🌙 MASCOT & POPUP (Bottom Right) -->
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
        <!-- Speech Bubble -->
        <div class="speech-bubble bg-white text-green-900 px-5 py-3 rounded-2xl rounded-br-none shadow-2xl mb-3 mr-4 max-w-[200px] text-center relative animate-bounce" style="animation-duration: 2s;">
            <p class="font-extrabold text-sm text-green-800">Ready to play?</p>
            <p class="text-xs text-gray-500 mt-1 font-medium">Good luck!</p>
        </div>
        
        <!-- Mascot Image -->
        <img src="{{ asset('img/logo.png') }}" alt="Mascot" class="w-32 h-32 object-contain floating-logo drop-shadow-2xl hover:scale-110 transition cursor-pointer">
    </div>

    <!-- JS for Stars -->
    <script>
        function createStars() {
            const bg = document.getElementById('starryBg');
            for (let i = 0; i < 120; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                const size = Math.random() * 3 + 1;
                star.style.width = size + 'px';
                star.style.height = size + 'px';
                star.style.setProperty('--duration', (Math.random() * 3 + 2) + 's');
                bg.appendChild(star);
            }
            for (let i = 0; i < 4; i++) {
                const shootingStar = document.createElement('div');
                shootingStar.className = 'shooting-star';
                shootingStar.style.left = Math.random() * 100 + '%';
                shootingStar.style.top = Math.random() * 60 + '%';
                shootingStar.style.width = (Math.random() * 100 + 100) + 'px';
                shootingStar.style.animationDelay = Math.random() * 5 + 's';
                shootingStar.style.animationDuration = (Math.random() * 2 + 4) + 's';
                bg.appendChild(shootingStar);
            }
        }
        createStars();
    </script>

<!-- 🔊 CLICK SOUND EFFECT -->
    <audio id="clickSound" preload="auto">
        <source src="https://assets.mixkit.co/active_storage/sfx/2571/2571-preview.mp3" type="audio/mpeg">
    </audio>

    <script>
        document.addEventListener('click', function(e) {
            const target = e.target.closest('button, a, input[type="submit"], .cursor-pointer');
            if (target) {
                const audio = document.getElementById('clickSound');
                audio.currentTime = 0; // Rewind to start
                audio.volume = 1.0; // Set volume to 30% (not too loud)
                audio.play().catch(error => console.log("Audio play blocked: ", error));
            }
        });
    </script>

      <script src="{{ asset('js/sound.js') }}"></script>

</body>
</html>