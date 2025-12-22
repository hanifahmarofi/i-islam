<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Lesson - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-900 text-white font-sans pb-20">

    <nav class="bg-gray-800 p-4 border-b border-gray-700 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold text-green-400">📝 Create New Lesson</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-times"></i> Cancel
            </a>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto mt-8 px-6">
        
        @if ($errors->any())
            <div class="bg-red-900/50 border border-red-500 text-red-200 p-4 rounded mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.storeLesson') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
                <h2 class="text-lg font-bold text-purple-400 mb-4">1. Lesson Details</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-2">Lesson Title</label>
                        <input type="text" name="title" class="w-full bg-gray-900 border border-gray-600 rounded p-3 focus:border-green-500 focus:outline-none" placeholder="e.g. Introduction to Solat" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2">Description</label>
                        <textarea name="description" rows="2" class="w-full bg-gray-900 border border-gray-600 rounded p-3 focus:border-green-500 focus:outline-none" placeholder="Short summary of the lesson..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2">Full Content</label>
                        <textarea name="content" rows="5" class="w-full bg-gray-900 border border-gray-600 rounded p-3 focus:border-green-500 focus:outline-none" placeholder="Detailed reading material for the students..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2">XP Points Reward</label>
                        <input type="number" name="points" value="100" class="w-full bg-gray-900 border border-gray-600 rounded p-3 focus:border-green-500 focus:outline-none" required>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
                <h2 class="text-lg font-bold text-blue-400 mb-4">2. Lesson Slides (Images)</h2>
                
                <div class="border-2 border-dashed border-gray-600 rounded-lg p-8 text-center hover:border-blue-500 transition relative" id="drop-zone">
                    
                    <input type="file" name="slides[]" multiple id="file-input" 
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                    
                    <div id="upload-placeholder">
                        <i class="fa-solid fa-cloud-upload-alt text-3xl text-gray-500 mb-2"></i>
                        <p class="text-gray-400">Drag & drop images here or click to upload</p>
                        <p class="text-xs text-gray-600 mt-2">(Select multiple files at once)</p>
                    </div>

                    <div id="file-list" class="hidden text-left mt-2 z-10 relative">
                        <p class="text-green-400 font-bold mb-2"><i class="fa-solid fa-check"></i> Files Selected:</p>
                        <ul class="text-sm text-gray-300 list-disc pl-5" id="file-names">
                            </ul>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-yellow-400">3. Quiz Questions</h2>
                    <button type="button" onclick="addQuestion()" class="bg-yellow-600 hover:bg-yellow-500 text-white text-sm font-bold py-2 px-4 rounded transition">
                        + Add Question
                    </button>
                </div>

                <div id="questions-container" class="space-y-6">
                    </div>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-4 rounded-xl shadow-lg text-lg transition transform hover:scale-[1.01]">
                🚀 Publish Lesson
            </button>

        </form>
    </div>

    <script>
        // --- 1. SHOW SELECTED FILES (NOW WITH SORTING!) ---
        const fileInput = document.getElementById('file-input');
        const fileList = document.getElementById('file-list');
        const fileNamesUl = document.getElementById('file-names');
        const placeholder = document.getElementById('upload-placeholder');

        fileInput.addEventListener('change', function() {
            // Clear previous list
            fileNamesUl.innerHTML = '';

            if (this.files.length > 0) {
                // Hide placeholder, show list
                placeholder.classList.add('hidden');
                fileList.classList.remove('hidden');

                // FIX: Convert FileList to Array and Sort by Name
                // This ensures "1.png" comes before "2.png" regardless of click order
                const sortedFiles = Array.from(this.files).sort((a, b) => {
                    return a.name.localeCompare(b.name, undefined, { numeric: true, sensitivity: 'base' });
                });

                // Loop through the SORTED array
                sortedFiles.forEach(file => {
                    let li = document.createElement('li');
                    li.textContent = file.name;
                    fileNamesUl.appendChild(li);
                });

            } else {
                // Show placeholder if cancelled
                placeholder.classList.remove('hidden');
                fileList.classList.add('hidden');
            }
        });

        // --- 2. DYNAMIC QUIZ QUESTIONS ---
        let qIndex = 0;

        function addQuestion() {
            const container = document.getElementById('questions-container');
            
            const html = `
                <div class="bg-gray-900 p-4 rounded border border-gray-600 relative" id="q-${qIndex}">
                    <button type="button" onclick="removeQuestion('q-${qIndex}')" class="absolute top-2 right-2 text-red-500 hover:text-red-400">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    
                    <div class="mb-3">
                        <label class="text-xs text-gray-400 uppercase font-bold">Question Text</label>
                        <input type="text" name="questions[${qIndex}][text]" class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="text-xs text-gray-500">Option A</label>
                            <input type="text" name="questions[${qIndex}][a]" class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-sm" required>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Option B</label>
                            <input type="text" name="questions[${qIndex}][b]" class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-sm" required>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Option C</label>
                            <input type="text" name="questions[${qIndex}][c]" class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-sm" required>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Option D</label>
                            <input type="text" name="questions[${qIndex}][d]" class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-sm" required>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-green-500 font-bold">Correct Answer</label>
                        <select name="questions[${qIndex}][correct]" class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-sm text-white">
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

        // Add one empty question by default
        addQuestion();
    </script>

</body>
</html>