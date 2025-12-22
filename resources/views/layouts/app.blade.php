<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>i-Islam Teacher Panel</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white font-sans antialiased">

    <nav class="bg-gray-800 border-b border-gray-700 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg font-bold text-purple-400">
                i-Islam <span class="text-white text-sm font-normal">| Teacher Panel</span>
            </div>
            <div>
                <a href="{{ route('teacher.dashboard') }}" class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded text-sm transition">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto py-6">
        @if(session('success'))
            <div class="bg-green-600 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>