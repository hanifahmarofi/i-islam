<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* --- 1. THE DEEP NIGHT SKY BACKGROUND --- */
        .starry-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Vibrant Deep Emerald to Black Gradient */
            background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #022c22 100%);
            z-index: -1;
            overflow: hidden;
        }

        /* --- 2. STAR ANIMATIONS (From your snippet) --- */
        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle var(--duration) infinite;
            opacity: 0;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.5); box-shadow: 0 0 10px gold; } /* Added Gold Glow */
        }

        .shooting-star {
            position: absolute;
            height: 2px;
            background: linear-gradient(90deg, transparent, #ffd700, transparent); /* Gold Trail */
            animation: shoot 6s linear infinite;
            opacity: 0;
        }

        @keyframes shoot {
            0% { opacity: 0; transform: translateX(0) translateY(0); }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; transform: translateX(500px) translateY(500px); }
        }

        /* --- 3. FLOATING LOGO (Your Feature) --- */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .floating-logo {
            animation: float 4s ease-in-out infinite;
            filter: drop-shadow(0 0 20px rgba(255, 215, 0, 0.5)); /* Gold Glow behind logo */
        }

        /* --- 4. NEON PULSE (For Buttons & Cards) --- */
        @keyframes neonPulse {
            0% { box-shadow: 0 0 5px rgba(34, 197, 94, 0.4); border-color: rgba(34, 197, 94, 0.4); }
            50% { box-shadow: 0 0 20px rgba(34, 197, 94, 0.8), 0 0 10px rgba(34, 197, 94, 0.6); border-color: rgba(34, 197, 94, 1); }
            100% { box-shadow: 0 0 5px rgba(34, 197, 94, 0.4); border-color: rgba(34, 197, 94, 0.4); }
        }
        .neon-effect {
            animation: neonPulse 3s infinite alternate;
        }

        /* Glassmorphism for Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="font-sans antialiased text-white min-h-screen relative">

    <!-- THE DYNAMIC BACKGROUND -->
    <div class="starry-background" id="starryBg"></div>

    <!-- Navbar -->
    <nav class="fixed w-full z-50 top-0 bg-black/20 backdrop-blur-md border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo Text -->
                <div class="flex-shrink-0 flex items-center gap-2">
                    <span class="text-3xl animate-pulse">🌙</span>
                    <span class="font-extrabold text-2xl text-green-400 tracking-tight drop-shadow-sm">i-Islam</span>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-gray-200 hover:text-green-400 transition">
                                Dashboard →
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold text-gray-200 hover:text-green-400 transition">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-green-600 text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-green-500 transition shadow-lg neon-effect">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative min-h-screen flex items-center justify-center pt-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            
            <!-- 🖼️ FLOATING LOGO -->
            <div class="mb-8 flex justify-center">
                <img src="{{ asset('img/logo.png') }}" alt="i-Islam Mascot" 
                     class="w-48 h-48 md:w-64 md:h-64 object-contain floating-logo">
            </div>

            <!-- Badge -->
            <span class="inline-block py-1 px-4 rounded-full bg-yellow-500/20 border border-yellow-400 text-yellow-300 text-xs font-extrabold tracking-widest uppercase mb-6 shadow-md neon-effect">
                ✨ FYP Project 2025 ✨
            </span>

            <!-- Title -->
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-6 leading-tight drop-shadow-lg">
                Learning Islam made <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-400 via-emerald-300 to-teal-200 animate-pulse">
                    Magical & Fun
                </span>
            </h1>

            <p class="mt-4 text-xl text-gray-300 max-w-2xl mx-auto mb-10 font-medium">
                Join our stickman adventures! Master your lessons, compete in live quizzes, and unlock trophies in the arcade.
            </p>

            <!-- Big Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-green-600 text-white rounded-full font-bold text-lg transition transform hover:scale-105 neon-effect">
                        Go to Dashboard 🚀
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-green-600 text-white rounded-full font-bold text-lg transition transform hover:scale-105 neon-effect">
                        Start Learning Now
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-transparent text-green-300 border-2 border-green-400 rounded-full font-bold text-lg hover:bg-green-900/30 transition transform hover:scale-105 neon-effect">
                        Login to Account
                    </a>
                @endauth
            </div>

            <!-- Feature Cards (Glassmorphism + Neon) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-20 text-left pb-10">
                <!-- Card 1 -->
                <div class="glass-card p-6 rounded-3xl neon-effect hover:bg-white/10 transition transform hover:-translate-y-2">
                    <div class="text-4xl mb-4 animate-bounce">📚</div>
                    <h3 class="font-bold text-xl text-green-300">Interactive Lessons</h3>
                    <p class="text-gray-300 text-sm mt-2 font-medium">Fun modules on Rukun Islam & Adab.</p>
                </div>
                
                <!-- Card 2 -->
                <div class="glass-card p-6 rounded-3xl neon-effect hover:bg-white/10 transition transform hover:-translate-y-2" style="animation-delay: 1s;">
                    <div class="text-4xl mb-4 animate-pulse">🏆</div>
                    <h3 class="font-bold text-xl text-yellow-300">Live Leaderboards</h3>
                    <p class="text-gray-300 text-sm mt-2 font-medium">Earn points and beat your high score!</p>
                </div>

                <!-- Card 3 -->
                <div class="glass-card p-6 rounded-3xl neon-effect hover:bg-white/10 transition transform hover:-translate-y-2" style="animation-delay: 2s;">
                    <div class="text-4xl mb-4 animate-spin-slow">🎮</div>
                    <h3 class="font-bold text-xl text-blue-300">Arcade Games</h3>
                    <p class="text-gray-300 text-sm mt-2 font-medium">Hangman, Word Scramble & Match Pairs.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- JAVASCRIPT TO CREATE THE STARS DYNAMICALLY -->
    <script>
        function createStars() {
            const bg = document.getElementById('starryBg');
            
            // Create 150 twinkling stars
            for (let i = 0; i < 150; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                
                // Random Position
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                
                // Random Size
                const size = Math.random() * 3 + 1; // 1px to 4px
                star.style.width = size + 'px';
                star.style.height = size + 'px';
                
                // Random Animation Speed
                star.style.setProperty('--duration', (Math.random() * 3 + 2) + 's');
                
                bg.appendChild(star);
            }

            // Create 5 Shooting Stars
            for (let i = 0; i < 5; i++) {
                const shootingStar = document.createElement('div');
                shootingStar.className = 'shooting-star';
                
                // Random Start Position
                shootingStar.style.left = Math.random() * 100 + '%';
                shootingStar.style.top = Math.random() * 50 + '%'; // Only top half
                
                // Random Width (Length of tail)
                shootingStar.style.width = (Math.random() * 100 + 100) + 'px';
                
                // Random Timing
                shootingStar.style.animationDelay = Math.random() * 5 + 's';
                shootingStar.style.animationDuration = (Math.random() * 2 + 4) + 's';
                
                bg.appendChild(shootingStar);
            }
        }

        // Run the function
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