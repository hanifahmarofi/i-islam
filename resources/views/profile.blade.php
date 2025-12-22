<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f392b; /* Dark Green Background */
            color: white;
            overflow-x: hidden;
        }

        /* --- BACKGROUND PARTICLES --- */
        .particle {
            position: fixed;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            animation: floatUp linear infinite;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }
        @keyframes floatUp {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            50% { opacity: 0.8; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }

        /* --- GLASS CARD --- */
        .glass-card {
            background: rgba(6, 78, 59, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(52, 211, 153, 0.2);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            border-radius: 24px;
        }

        /* --- NAVBAR --- */
        .navbar {
            background: rgba(6, 78, 59, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* --- FORM INPUTS --- */
        .custom-input {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(52, 211, 153, 0.3);
            color: white;
            transition: 0.3s;
        }
        .custom-input:focus {
            border-color: #34d399;
            box-shadow: 0 0 15px rgba(52, 211, 153, 0.2);
            outline: none;
        }
        .custom-input:disabled {
            background: rgba(255, 255, 255, 0.05);
            color: #9ca3af;
            cursor: not-allowed;
            border-color: transparent;
        }

        /* --- BUTTONS --- */
        .btn-save {
            background: linear-gradient(to right, #059669, #10b981);
            transition: 0.3s;
        }
        .btn-save:hover {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.5);
            transform: translateY(-2px);
        }
        
        .back-btn {
            color: #a7f3d0; font-weight: bold; text-decoration: none;
            display: flex; align-items: center; gap: 8px; transition: 0.3s;
            background: rgba(0,0,0,0.2); padding: 8px 20px; border-radius: 20px;
            width: fit-content;
        }
        .back-btn:hover { background: #34d399; color: #064e3b; }

        .profile-img-cover { object-fit: cover; }
        
        /* Cropper container fix */
        .img-container img {
            max-width: 100%;
            display: block; /* This is important for CropperJS */
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
            <span class="hidden md:block text-sm text-green-200">Logged in as <span class="font-bold text-white">{{ $user->full_name ?? 'User' }}</span></span>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto mt-28 px-6 relative z-10">
        
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> Dashboard
            </a>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="cropped_image" id="cropped_image">

            <div class="glass-card overflow-hidden">
                
                <div class="bg-gradient-to-r from-emerald-900/80 to-teal-900/80 p-8 text-center border-b border-white/10 relative">
                    
                    <div class="relative w-32 h-32 mx-auto mb-4 group cursor-pointer">
                        <label for="profile_picture_input" class="cursor-pointer block w-full h-full">
                            <div class="w-full h-full rounded-full overflow-hidden shadow-[0_0_20px_rgba(52,211,153,0.3)] border-4 border-emerald-500/30 bg-emerald-700/50 flex items-center justify-center relative">
                                
                                @if($user->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" id="avatar-preview" class="w-full h-full profile-img-cover">
                                @else
                                    <img id="avatar-preview" class="hidden w-full h-full profile-img-cover">
                                    <i id="default-icon" class="fa-solid fa-user-astronaut text-emerald-100 text-5xl"></i>
                                @endif
                                
                                <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                    <i class="fa-solid fa-camera text-white text-2xl mb-1"></i>
                                    <span class="text-xs text-white font-bold">Change</span>
                                </div>
                            </div>
                        </label>
                        <input type="file" name="profile_picture" id="profile_picture_input" class="hidden" accept="image/*">
                    </div>
                    
                    <h1 class="text-3xl font-bold text-white mb-1">{{ $user->full_name ?? 'Unknown Student' }}</h1>
                    <p class="text-emerald-300 font-mono tracking-wider text-sm">{{ $user->matric_id ?? 'ID-XXXX' }}</p>
                    
                    <div class="mt-4">
                        <div class="bg-yellow-500 text-white px-4 py-2 rounded-full inline-block font-bold">
                            ⭐ Total Points: {{ auth()->user()->total_points }}
                        </div>
                        <div class="bg-purple-600 text-white px-4 py-2 rounded-full inline-block font-bold ml-2">
                            ⚡ {{ auth()->user()->xp }} XP
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    @if(session('success'))
                        <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 p-4 rounded-xl mb-6 text-center font-bold flex items-center justify-center gap-2">
                            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-red-500/20 border border-red-500/50 text-red-300 p-4 rounded-xl mb-6 text-center font-bold">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-emerald-200 text-sm font-bold mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-user"></i> Full Name
                            </label>
                            <input type="text" value="{{ $user->full_name ?? '' }}" disabled class="custom-input w-full px-4 py-3 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-emerald-200 text-sm font-bold mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-envelope"></i> Email Address
                            </label>
                            <input type="text" value="{{ $user->email ?? '' }}" disabled class="custom-input w-full px-4 py-3 rounded-xl">
                        </div>
                    </div>

                    <hr class="border-white/10 mb-6">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2"><i class="fa-solid fa-pen-to-square text-yellow-400"></i> Edit Your Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-emerald-200 text-sm font-bold mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-cake-candles"></i> My Age
                            </label>
                            <input type="number" name="age" value="{{ $user->age }}" placeholder="e.g. 10" required class="custom-input w-full px-4 py-3 rounded-xl font-bold text-lg">
                        </div>
                        <div>
                            <label class="block text-emerald-200 text-sm font-bold mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-burger"></i> Favourite Food
                            </label>
                            <input type="text" name="favourite_food" value="{{ $user->favourite_food }}" placeholder="e.g. Nasi Lemak" class="custom-input w-full px-4 py-3 rounded-xl">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-emerald-200 text-sm font-bold mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-person-dress"></i> Mother's Name
                        </label>
                        <input type="text" name="mother_name" value="{{ $user->mother_name }}" placeholder="Enter mother's name" class="custom-input w-full px-4 py-3 rounded-xl">
                    </div>

                    <div class="mb-6">
                        <label class="block text-emerald-200 text-sm font-bold mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-person"></i> Father's Name
                        </label>
                        <input type="text" name="father_name" value="{{ $user->father_name }}" placeholder="Enter father's name" class="custom-input w-full px-4 py-3 rounded-xl">
                    </div>

                    <div class="mb-8">
                        <label class="block text-emerald-200 text-sm font-bold mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-phone"></i> Parents Phone Number
                        </label>
                        <input type="text" name="parents_phone" value="{{ $user->parents_phone }}" placeholder="e.g. 012-3456789" class="custom-input w-full px-4 py-3 rounded-xl">
                    </div>

                    <button type="submit" class="btn-save w-full text-white font-bold py-4 rounded-xl shadow-lg flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Save Changes
                    </button>

                </div>
            </div>
        </form>
    </div>

    <div id="crop-modal" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl">
            <div class="p-4 border-b border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">Crop Profile Picture</h3>
                <button type="button" onclick="closeCropModal()" class="text-gray-400 hover:text-white">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-4 bg-black flex justify-center items-center h-[300px] md:h-[400px]">
                <div class="img-container w-full h-full">
                    <img id="image-to-crop" src="" class="max-w-full max-h-full">
                </div>
            </div>

            <div class="p-4 border-t border-gray-700 flex justify-end gap-3 bg-gray-900">
                <button type="button" onclick="closeCropModal()" class="px-4 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/10 transition">
                    Cancel
                </button>
                <button type="button" id="crop-btn" class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-500 text-white font-bold shadow-lg transition">
                    <i class="fa-solid fa-check mr-1"></i> Crop & Set
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script>
        // --- SOUND & PARTICLES ---
        const clickSound = new Audio("{{ asset('audio/click.mp3') }}");
        document.addEventListener('click', function(e) {
            if (e.target.closest('a, button')) {
                const sound = clickSound.cloneNode();
                sound.volume = 0.4;
                sound.play().catch(err => {});
            }
        });

        const particleContainer = document.getElementById('particles');
        for(let i=0; i<25; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + 'vw';
            const size = Math.random() * 5 + 2;
            p.style.width = size + 'px';
            p.style.height = size + 'px';
            p.style.animationDuration = Math.random() * 10 + 5 + 's';
            p.style.animationDelay = Math.random() * 5 + 's';
            particleContainer.appendChild(p);
        }

        // --- CROPPER LOGIC ---
        const avatarInput = document.getElementById('profile_picture_input');
        const avatarPreview = document.getElementById('avatar-preview');
        const defaultIcon = document.getElementById('default-icon');
        const cropModal = document.getElementById('crop-modal');
        const imageToCrop = document.getElementById('image-to-crop');
        const cropBtn = document.getElementById('crop-btn');
        let cropper = null;

        // 1. When user selects a file, open modal instead of showing immediately
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Set image source for cropper
                    imageToCrop.src = e.target.result;
                    // Show modal
                    cropModal.classList.remove('hidden');
                    
                    // Initialize Cropper (destroy old instance if exists)
                    if(cropper) cropper.destroy();
                    
                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1, // Force Square (1:1)
                        viewMode: 1,
                        autoCropArea: 1,
                        background: false,
                        zoomable: true,
                        movable: true,
                    });
                }
                reader.readAsDataURL(file);
            }
        });

        // 2. Handle "Crop & Set" Click
        // 2. Handle "Crop & Set" Click
        cropBtn.addEventListener('click', function() {
            if(!cropper) return;

            // Get cropped canvas
            const canvas = cropper.getCroppedCanvas({
                width: 400,
                height: 400
            });

            // --- THE FIX: Convert to Base64 String instead of Blob ---
            // This turns the image into text code that browsers handle easily
            const base64Image = canvas.toDataURL('image/jpeg');

            // Put this text into the hidden input we created
            document.getElementById('cropped_image').value = base64Image;

            // Update the visual preview
            avatarPreview.src = base64Image;
            avatarPreview.classList.remove('hidden');
            if(defaultIcon) defaultIcon.classList.add('hidden');

            // Close modal
            closeCropModal();
        });

        // 3. Helper to close modal
        function closeCropModal() {
            cropModal.classList.add('hidden');
            if(cropper) {
                cropper.destroy();
                cropper = null;
            }
            // If the user cancelled and didn't crop, we might want to clear input? 
            // Optional: But for now, keeping the input value is fine or we can clear it if needed.
        }
    </script>

</body>
</html>