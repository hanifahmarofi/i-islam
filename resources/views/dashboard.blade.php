<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f392b; /* Dark Green Background */
            color: white;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* --- BRIGHT FALLING STARS --- */
        .particle {
            position: fixed;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            animation: floatUp linear infinite;
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
        }
        @keyframes floatUp {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }

        /* --- GLASS EFFECT CARDS --- */
        .glass-panel {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
        }

        /* --- NAVBAR --- */
        .navbar {
            background: rgba(6, 78, 59, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* --- ACTION CARDS --- */
        .action-card {
            background: rgba(6, 78, 59, 0.6);
            border: 2px solid transparent;
            transition: 0.3s;
            cursor: pointer;
            display: block; 
            text-decoration: none;
        }
        .action-card:hover {
            transform: translateY(-5px);
            background: rgba(6, 78, 59, 0.8);
        }
        
        .card-blue:hover { border-color: #38bdf8; box-shadow: 0 0 20px rgba(56, 189, 248, 0.3); }
        .card-yellow:hover { border-color: #facc15; box-shadow: 0 0 20px rgba(250, 204, 21, 0.3); }
        .card-purple:hover { border-color: #a855f7; box-shadow: 0 0 20px rgba(168, 85, 247, 0.3); }

        /* --- LESSON ROW --- */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            transition: 0.3s;
        }
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(10px);
        }

        /* --- MASCOT --- */
        .mascot-container {
            position: fixed; bottom: 20px; right: 20px; z-index: 50;
            animation: float 3s ease-in-out infinite;
        }
        .speech-bubble {
            background: white; color: #064e3b; padding: 10px 20px;
            border-radius: 20px; font-weight: bold; font-size: 0.9rem;
            margin-bottom: 10px; position: relative;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            opacity: 0; animation: popIn 0.5s ease-out 1s forwards;
        }
        .speech-bubble::after {
            content: ''; position: absolute; bottom: -10px; right: 30px;
            border-width: 10px 10px 0; border-style: solid;
            border-color: white transparent; display: block; width: 0;
        }

        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
        @keyframes popIn { from { opacity: 0; transform: translateY(20px) scale(0.8); } to { opacity: 1; transform: translateY(0) scale(1); } }

        /* --- FEEDBACK BLOB ANIMATION --- */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
    </style>
</head>
<body class="relative min-h-screen pb-20">

    <div id="particles"></div>

    <nav class="navbar fixed w-full top-0 z-40 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <span class="text-yellow-400 text-2xl"><i class="fa-solid fa-moon"></i></span>
            <span class="text-xl font-bold tracking-wide text-green-100">i-Islam</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="hidden md:block text-sm text-green-200">
                Hello, <span class="font-bold text-white">{{ Auth::user()->full_name }}</span>!
            </span>
            
            <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                class="bg-red-500/80 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg text-sm font-bold transition cursor-pointer">
                Logout
            </button>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto mt-24 px-6 relative z-10">

        @if(session('success'))
        <div class="mb-8 animate-bounce">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg flex items-center justify-between" role="alert">
                <div>
                    <p class="font-bold text-lg"><i class="fa-solid fa-check-circle mr-2"></i> Mabrouk!</p>
                    <p>{{ session('success') }}</p>
                </div>
                <div class="bg-green-600 text-white px-3 py-1 rounded-full font-bold shadow-lg">
                    +50 XP
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-8">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-lg flex items-center" role="alert">
                <i class="fa-solid fa-circle-exclamation text-2xl mr-4"></i>
                <div>
                    <p class="font-bold text-lg">Keep Trying!</p>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif
        
        <div class="glass-panel p-8 mb-8 bg-gradient-to-r from-emerald-900/80 to-teal-900/80 border-l-4 border-l-emerald-400">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Welcome back!</h1>
            <p class="text-emerald-200">You are logged in. Ready to learn something new today?</p>
        </div>

        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl p-6 mb-8 text-white shadow-lg relative z-20 border border-purple-400/30">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-rocket animate-pulse"></i> Join a Live Quiz!
                    </h2>
                    <p class="text-purple-100">Enter the 6-letter code from your teacher.</p>
                </div>
                
                <form action="{{ route('student.quiz.join') }}" method="POST" class="mt-4 md:mt-0 flex gap-2">
                    @csrf
                    <input type="text" name="code" placeholder="CODE" required
                        class="uppercase px-4 py-3 rounded-lg text-gray-900 font-bold text-center tracking-widest w-40 focus:outline-none focus:ring-4 focus:ring-purple-300 border-2 border-white/20">
                    
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold px-6 py-3 rounded-lg shadow-md transition transform hover:scale-105">
                        Join
                    </button>
                </form>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            <a href="#available-lessons" class="action-card card-blue p-6 rounded-2xl flex flex-col justify-between group h-48">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-1">Start Learning</h3>
                    <p class="text-xs text-gray-300">Access your Islamic studies modules.</p>
                </div>
                <div class="h-1 w-full bg-gray-700 mt-4 rounded-full overflow-hidden">
                    <div class="h-full bg-sky-400 w-2/3"></div>
                </div>
            </a>

            <a href="{{ route('games.index') }}" class="action-card card-yellow p-6 rounded-2xl flex flex-col justify-between group h-48 border-yellow-500/30">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-yellow-500/20 text-yellow-400 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition">
                        <i class="fa-solid fa-gamepad"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-1 text-yellow-100">Play Games / Quiz!</h3>
                    <p class="text-xs text-gray-300">Compete with friends & top the leaderboard.</p>
                </div>
            </a>

            <a href="{{ route('profile.index') }}" class="action-card card-purple p-6 rounded-2xl flex flex-col justify-between group h-48 border-purple-500/30">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition">
                        <i class="fa-solid fa-user-astronaut"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-1 text-purple-100">My Profile</h3>
                    <p class="text-xs text-gray-300">Check your points & badges.</p>
                </div>
            </a>
        </div>

        <div class="mb-12 bg-gray-800/80 rounded-2xl p-6 relative overflow-hidden shadow-lg border border-gray-700 backdrop-blur-sm">
            <div class="flex flex-col md:flex-row items-center justify-between z-10 relative">
                
                <div class="mb-4 md:mb-0 text-center md:text-left">
                    <h3 class="text-xl font-bold text-white flex items-center justify-center md:justify-start gap-2">
                        <i class="fa-solid fa-star text-yellow-400"></i> Rate your experience!
                    </h3>
                    <p class="text-gray-300 text-sm mt-1">How many stars would you give i-Islam today?</p>
                </div>

                <div class="flex flex-col items-center">
                    <div class="flex space-x-2" id="star-container">
                        @for ($i = 1; $i <= 5; $i++)
                            <button onclick="submitRating({{ $i }})" 
                                    onmouseenter="hoverRating({{ $i }})" 
                                    onmouseleave="resetRating()"
                                    class="star-btn transition transform hover:scale-110 focus:outline-none p-1">
                                <svg class="w-8 h-8 md:w-10 md:h-10 text-gray-600 fill-current transition-colors duration-200" 
                                     id="star-{{ $i }}" 
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <p id="rating-message" class="text-yellow-400 text-sm mt-2 font-semibold h-5 transition-all duration-300"></p>
                </div>
            </div>
            
            <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-green-500 rounded-full mix-blend-multiply filter blur-2xl opacity-20 animate-blob"></div>
        </div>
        <div id="available-lessons" class="pt-4 pb-20"> 
            
            <h3 class="text-3xl font-bold text-white mb-8 flex items-center gap-3 drop-shadow-md">
                <span class="animate-bounce">📖</span> Available Lessons
            </h3>
            
            <div class="grid grid-cols-1 gap-5">
                @foreach($lessons as $lesson)
                <div class="glass-card p-6 rounded-2xl hover:bg-white/5 transition group border-l-4 border-green-500/50 hover:border-green-400 flex justify-between items-center">
                    <div>
                        <h4 class="text-xl font-bold text-green-300 group-hover:text-green-100 transition">{{ $lesson->title }}</h4>
                        <p class="text-gray-400 mt-1 text-sm">{{ $lesson->description }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block bg-yellow-500/20 text-yellow-300 text-xs font-bold px-3 py-1 rounded-full mb-2 border border-yellow-500/30 shadow-[0_0_10px_rgba(234,179,8,0.2)]">
                            +{{ $lesson->points }} XP
                        </span>
                        <br>
                        <a href="{{ route('lesson.show', $lesson->id) }}" class="inline-block mt-2 text-sm text-green-400 font-bold hover:text-green-200 hover:underline transition">
                            Read Now →
                        </a>
                    </div>
                </div>
                @endforeach
                @if($lessons->isEmpty())
                <div class="text-gray-400 italic text-center p-10">
                    No lessons available yet. Check back soon!
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mascot-container flex flex-col items-end">
        <div class="speech-bubble">
            Ahlan wa Sahlan ya Asdiqa'i!<br>
            <span class="text-xs font-normal text-gray-500">Welcome my friend!</span>
        </div>
        <img src="{{ asset('img/logo.png') }}" alt="i-Islam Logo" class="w-32 h-32 drop-shadow-2xl hover:scale-105 transition transform duration-300">
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <script>
        // Existing Click Sound Script
        const clickSound = new Audio("{{ asset('audio/click.mp3') }}");
        document.addEventListener('click', function(e) {
            // Updated to include .star-btn so the click sound works on stars too
            if (e.target.closest('a, button, .action-card, .glass-card, .star-btn')) { 
                const sound = clickSound.cloneNode();
                sound.volume = 0.4;
                sound.play().catch(err => {});
            }
        });

        // Existing Particle Script
        const particleContainer = document.getElementById('particles');
        for(let i=0; i<35; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + 'vw';
            const size = Math.random() * 6 + 3; 
            p.style.width = size + 'px';
            p.style.height = size + 'px';
            p.style.animationDuration = Math.random() * 8 + 4 + 's';
            p.style.animationDelay = Math.random() * 5 + 's';
            particleContainer.appendChild(p);
        }

        // --- NEW FEEDBACK LOGIC ---
        let currentRating = 0;

        function hoverRating(value) {
            if (currentRating > 0) return; // Lock hover if already selected
            const stars = document.querySelectorAll('.star-btn svg');
            const message = document.getElementById('rating-message');
            
            stars.forEach((star, index) => {
                if (index < value) {
                    star.classList.remove('text-gray-600');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-600');
                }
            });
            
            const messages = ["Needs Work", "Okay", "Good!", "Great!", "Awesome!"];
            message.innerText = messages[value - 1];
        }

        function resetRating() {
            if (currentRating === 0) {
                const stars = document.querySelectorAll('.star-btn svg');
                stars.forEach(star => {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-600');
                });
                document.getElementById('rating-message').innerText = "";
            }
        }

        function submitRating(value) {
            currentRating = value;
            
            // Visual Update immediately for better UX
            const stars = document.querySelectorAll('.star-btn svg');
            stars.forEach((star, index) => {
                if (index < value) {
                    star.classList.remove('text-gray-600');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-600');
                }
            });

            // Send to backend
            fetch('/feedback', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ rating: value })
            })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('star-container');
                container.classList.add('pointer-events-none', 'opacity-75');
                document.getElementById('rating-message').innerText = "Shukran! (Thank You)";
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('rating-message').innerText = "Error saving!";
                currentRating = 0; // Reset on error
            });
        }
    </script>

</body>
</html>