<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Lesson - Teacher Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 text-white pb-20">

    <nav class="bg-gray-800 p-4 border-b border-gray-700 sticky top-0 z-50 shadow-md">
        <div class="max-w-5xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="bg-green-500/20 text-green-400 p-2 rounded-lg"><i class="fa-solid fa-book-open"></i></span>
                <h1 class="text-xl font-bold text-white">Create New Lesson</h1>
            </div>
            <a href="{{ route('teacher.dashboard') }}" class="text-gray-400 hover:text-white transition flex items-center gap-2">
                <i class="fa-solid fa-times"></i> <span class="hidden sm:inline">Cancel</span>
            </a>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto mt-8 px-6">
        
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500 text-red-200 p-4 rounded-xl mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('teacher.lesson.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl">
                <h2 class="text-xl font-bold text-green-400 mb-6 border-b border-gray-700 pb-2">1. Lesson Details</h2>
                
                <div class="grid gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-300">Lesson Title</label>
                        <input type="text" name="title" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-4 text-white focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none transition" placeholder="e.g. Introduction to Solat" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-300">Short Description</label>
                        <textarea name="description" rows="2" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-4 text-white focus:border-green-500 focus:outline-none transition" placeholder="Brief summary displayed on the dashboard..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-300">Full Content / Reading Material</label>
                        <textarea name="content" rows="6" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-4 text-white focus:border-green-500 focus:outline-none transition" placeholder="Detailed content for the students to read..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-300">XP Reward</label>
                        <div class="relative">
                            <i class="fa-solid fa-bolt absolute left-4 top-4 text-yellow-500"></i>
                            <input type="number" name="points" value="100" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-4 pl-10 text-white focus:border-green-500 focus:outline-none transition" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl">
                <h2 class="text-xl font-bold text-blue-400 mb-6 border-b border-gray-700 pb-2">2. Lesson Slides</h2>
                
                <div class="border-2 border-dashed border-gray-600 rounded-xl p-10 text-center hover:border-blue-500 hover:bg-gray-700/30 transition relative cursor-pointer" id="drop-zone">
                    <input type="file" name="slides[]" multiple id="file-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                    
                    <div id="upload-placeholder" class="pointer-events-none">
                        <i class="fa-solid fa-images text-4xl text-blue-500/50 mb-4"></i>
                        <p class="text-gray-300 font-medium">Click or Drag images here</p>
                        <p class="text-xs text-gray-500 mt-2">Supports JPG, PNG (Multiple files allowed)</p>
                    </div>

                    <div id="file-list" class="hidden text-left mt-4 z-10 relative">
                        <p class="text-blue-400 font-bold mb-2"><i class="fa-solid fa-check-circle"></i> Selected Files:</p>
                        <ul class="text-sm text-gray-300 space-y-1 pl-1" id="file-names"></ul>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl">
                <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-2">
                    <h2 class="text-xl font-bold text-yellow-400">3. Quiz Questions</h2>
                    <button type="button" onclick="addQuestion()" class="bg-yellow-600/20 hover:bg-yellow-600 text-yellow-500 hover:text-white border border-yellow-600 text-sm font-bold py-2 px-4 rounded-lg transition">
                        <i class="fa-solid fa-plus"></i> Add Question
                    </button>
                </div>

                <div id="questions-container" class="space-y-6">
                    </div>
            </div>

            <div class="sticky bottom-4">
                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-500 hover:from-green-500 hover:to-green-400 text-white font-bold py-4 rounded-xl shadow-lg shadow-green-900/50 text-lg transition transform hover:-translate-y-1">
                    🚀 Publish Lesson Now
                </button>
            </div>

        </form>
    </div>

    <script>
        // --- FILE UPLOAD LOGIC ---
        const fileInput = document.getElementById('file-input');
        const fileList = document.getElementById('file-list');
        const fileNamesUl = document.getElementById('file-names');
        const placeholder = document.getElementById('upload-placeholder');

        fileInput.addEventListener('change', function() {
            fileNamesUl.innerHTML = '';
            if (this.files.length > 0) {
                placeholder.classList.add('opacity-50', 'scale-90'); 
                fileList.classList.remove('hidden');
                for (let i = 0; i < this.files.length; i++) {
                    let li = document.createElement('li');
                    li.innerHTML = `<i class="fa-regular fa-file-image text-gray-500 mr-2"></i> ${this.files[i].name}`;
                    fileNamesUl.appendChild(li);
                }
            } else {
                placeholder.classList.remove('opacity-50', 'scale-90');
                fileList.classList.add('hidden');
            }
        });

        // --- QUIZ BUILDER LOGIC ---
        let qIndex = 0;

        function addQuestion() {
            const container = document.getElementById('questions-container');
            const html = `
                <div class="bg-gray-900 p-6 rounded-xl border border-gray-700 relative animate-fade-in" id="q-${qIndex}">
                    <button type="button" onclick="removeQuestion('q-${qIndex}')" class="absolute top-4 right-4 text-red-500 hover:text-red-400 transition bg-gray-800 hover:bg-gray-700 p-2 rounded-lg">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    
                    <div class="mb-4">
                        <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">Question ${qIndex + 1}</label>
                        <input type="text" name="questions[${qIndex}][text]" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white mt-1 focus:border-yellow-500 focus:outline-none" placeholder="Enter question here..." required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        ${['A','B','C','D'].map(opt => `
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 font-bold">${opt}</span>
                                <input type="text" name="questions[${qIndex}][${opt.toLowerCase()}]" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2 text-sm focus:border-yellow-500 focus:outline-none" required>
                            </div>
                        `).join('')}
                    </div>

                    <div>
                        <label class="text-xs text-green-500 font-bold uppercase">Correct Answer</label>
                        <select name="questions[${qIndex}][correct]" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2 text-sm text-white mt-1 focus:border-green-500 focus:outline-none">
                            <option value="a">Option A</option>
                            <option value="b">Option B</option>
                            <option value="c">Option C</option>
                            <option value="d">Option D</option>
                        </select>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            qIndex++;
        }

        function removeQuestion(id) {
            document.getElementById(id).remove();
        }

        // Initialize with one question
        addQuestion();
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>

</body>
</html>