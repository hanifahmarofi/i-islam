<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* --- THEME STYLES (Same as Login) --- */
        .starry-background {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #022c22 100%);
            z-index: -1; overflow: hidden;
        }
        .star {
            position: absolute; background: white; border-radius: 50%;
            animation: twinkle var(--duration) infinite; opacity: 0;
        }
        @keyframes twinkle {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.5); box-shadow: 0 0 10px gold; }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body class="text-white min-h-screen flex items-center justify-center font-sans relative">

    <div class="starry-background" id="starryBg"></div>

    <div class="w-full max-w-md p-8 glass-card rounded-2xl relative z-10">
        
        <!-- Back Button -->
        <a href="{{ route('login') }}" class="text-emerald-400 hover:text-white mb-6 inline-block transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Back to Login
        </a>

        <div class="text-center mb-8">
            <div class="text-5xl mb-4 animate-bounce">🔑</div>
            <h1 class="text-3xl font-bold text-emerald-400 drop-shadow-md">Forgot Password?</h1>
            <p class="text-gray-300 text-sm mt-2">No worries! Enter your email and we'll send you reset instructions.</p>
        </div>

        <!-- Success Message -->
        @if (session('status'))
            <div class="bg-green-500/20 border border-green-500/50 text-green-200 p-4 rounded-xl mb-6 text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold mb-2 text-emerald-200">Email Address</label>
                <input type="email" name="email" required 
                    class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/10 focus:border-emerald-400 focus:outline-none transition text-white placeholder-white/30"
                    placeholder="student@school.edu">
            </div>

            <button type="submit" class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-lg shadow-[0_0_15px_rgba(16,185,129,0.5)] transition transform hover:scale-105">
                Send Reset Link 📩
            </button>
        </form>

    </div>

    <script>
        // --- STAR GENERATOR ---
        function createStars() {
            const bg = document.getElementById('starryBg');
            for (let i = 0; i < 80; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                const size = Math.random() * 3 + 1;
                star.style.width = size + 'px'; star.style.height = size + 'px';
                star.style.setProperty('--duration', (Math.random() * 3 + 2) + 's');
                bg.appendChild(star);
            }
        }
        createStars();
    </script>

</body>
</html>