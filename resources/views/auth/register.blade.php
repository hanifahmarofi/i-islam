<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* --- 1. THE DEEP NIGHT SKY BACKGROUND --- */
        .starry-background {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            /* Vibrant Deep Emerald to Black Gradient */
            background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #022c22 100%);
            z-index: -1; overflow: hidden;
        }

        /* --- 2. STAR ANIMATIONS --- */
        .star {
            position: absolute; background: white; border-radius: 50%;
            animation: twinkle var(--duration) infinite; opacity: 0;
        }
        @keyframes twinkle {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.5); box-shadow: 0 0 10px gold; }
        }

        .shooting-star {
            position: absolute; height: 2px;
            background: linear-gradient(90deg, transparent, #ffd700, transparent); /* Gold Trail */
            animation: shoot 6s linear infinite; opacity: 0;
        }
        @keyframes shoot {
            0% { opacity: 0; transform: translateX(0) translateY(0); }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; transform: translateX(500px) translateY(500px); }
        }

        /* --- 3. GLASS CARD --- */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }
        
        /* Dropdown Style */
        select option {
            background: #064e3b; /* Match theme */
            color: white;
        }
    </style>
</head>
<body class="text-white min-h-screen flex items-center justify-center font-sans py-10 relative">

    <div class="starry-background" id="starryBg"></div>

    <div class="w-full max-w-md p-8 glass-card rounded-2xl relative z-10">
        
        <a href="{{ route('home') }}" class="text-emerald-400 hover:text-white mb-6 inline-block transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Back to Home
        </a>

        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-yellow-400 drop-shadow-md">Join Us!</h1>
            <p class="text-gray-300 text-sm">Create your account to start learning.</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4" onsubmit="return validatePassword()">
            @csrf
            
            <div>
                <label class="block text-sm font-bold mb-1 text-emerald-200">I am a...</label>
                <div class="relative">
                    <select name="role" class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/10 focus:border-yellow-400 focus:outline-none transition text-white appearance-none cursor-pointer">
                        <option value="student">👨‍🎓 Student</option>
                        <option value="teacher">👩‍🏫 Teacher</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-emerald-400">
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold mb-1 text-emerald-200">Full Name</label>
                <input type="text" name="name" required 
                    class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/10 focus:border-yellow-400 focus:outline-none transition text-white placeholder-white/30"
                    placeholder="Your Name">
            </div>

            <div>
                <label class="block text-sm font-bold mb-1 text-emerald-200">Email Address</label>
                <input type="email" name="email" required 
                    class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/10 focus:border-yellow-400 focus:outline-none transition text-white placeholder-white/30"
                    placeholder="name@school.edu">
            </div>

            <div class="relative">
                <label class="block text-sm font-bold mb-1 text-emerald-200">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required minlength="6"
                        class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/10 focus:border-yellow-400 focus:outline-none transition text-white placeholder-white/30 pr-10"
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword('password', 'eye-icon-1')" class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-white">
                        <i id="eye-icon-1" class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <p class="text-xs text-emerald-200/70 mt-1">
                    <i class="fa-solid fa-circle-info"></i> Minimum 6 characters (Letters & Numbers).
                </p>
            </div>

            <div class="relative">
                <label class="block text-sm font-bold mb-1 text-emerald-200">Confirm Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirm" required
                        class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/10 focus:border-yellow-400 focus:outline-none transition text-white placeholder-white/30 pr-10"
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword('password_confirm', 'eye-icon-2')" class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-white">
                        <i id="eye-icon-2" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-yellow-500 hover:bg-yellow-600 text-black font-bold rounded-lg shadow-[0_0_15px_rgba(234,179,8,0.5)] transition transform hover:scale-105 mt-4">
                Create Account ✨
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-400">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-yellow-400 font-bold hover:underline">Log in here</a>
        </div>
    </div>

    <script>
        // --- 1. STAR GENERATOR ---
        function createStars() {
            const bg = document.getElementById('starryBg');
            
            // Twinkling Stars
            for (let i = 0; i < 100; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                const size = Math.random() * 3 + 1;
                star.style.width = size + 'px'; star.style.height = size + 'px';
                star.style.setProperty('--duration', (Math.random() * 3 + 2) + 's');
                bg.appendChild(star);
            }

            // Shooting Stars
            for (let i = 0; i < 5; i++) {
                const shootingStar = document.createElement('div');
                shootingStar.className = 'shooting-star';
                shootingStar.style.left = Math.random() * 100 + '%';
                shootingStar.style.top = Math.random() * 50 + '%';
                shootingStar.style.width = (Math.random() * 100 + 100) + 'px';
                shootingStar.style.animationDelay = Math.random() * 5 + 's';
                shootingStar.style.animationDuration = (Math.random() * 2 + 4) + 's';
                bg.appendChild(shootingStar);
            }
        }
        createStars();

        // --- 2. PASSWORD TOGGLE LOGIC ---
        function togglePassword(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        function validatePassword() {
            const p1 = document.getElementById('password').value;
            const p2 = document.getElementById('password_confirm').value;
            if (p1 !== p2) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
    </script>

</body>
</html>