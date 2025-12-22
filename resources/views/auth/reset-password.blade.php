<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .starry-background {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #022c22 100%);
            z-index: -1;
        }
        .glass-card { background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.15); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37); }
    </style>
</head>
<body class="font-sans antialiased text-white min-h-screen flex items-center justify-center relative">

    <div class="starry-background"></div>

    <div class="w-full max-w-md px-4">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-green-400">Reset Password</h2>
            <p class="text-gray-400 mt-2">Enter your new password below.</p>
        </div>

        <div class="glass-card p-8 rounded-3xl">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email -->
                <div class="mb-6">
                    <label class="block text-gray-300 text-sm font-bold mb-2">Email Address</label>
                    <input type="email" name="email" class="w-full px-4 py-3 rounded-xl bg-black/20 border border-gray-600 text-white focus:border-green-400 focus:outline-none" required>
                </div>

                <!-- New Password -->
                <div class="mb-6">
                    <label class="block text-gray-300 text-sm font-bold mb-2">New Password</label>
                    <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-black/20 border border-gray-600 text-white focus:border-green-400 focus:outline-none" required>
                </div>

                <!-- Confirm Password -->
                <div class="mb-8">
                    <label class="block text-gray-300 text-sm font-bold mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-xl bg-black/20 border border-gray-600 text-white focus:border-green-400 focus:outline-none" required>
                </div>

                <button class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-3 rounded-xl shadow-lg transition" type="submit">
                    Reset Password 🔐
                </button>
            </form>
        </div>
    </div>

</body>
</html>