<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arcade Zone - AI Quiz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Press Start 2P', cursive;
            background-color: #020617; /* Very dark slate/black */
            background-image: 
                linear-gradient(rgba(0, 255, 0, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 255, 0, 0.05) 1px, transparent 1px);
            background-size: 20px 20px;
        }
        
        /* The Neon Green Glow Effect */
        .neon-border {
            box-shadow: 0 0 10px #22c55e, 0 0 20px #22c55e inset;
        }
        .neon-text {
            text-shadow: 0 0 5px #4ade80;
        }

        /* Custom Scrollbar for retro feel */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #000; }
        ::-webkit-scrollbar-thumb { background: #22c55e; border: 1px solid #000; }
    </style>
</head>
<body class="h-screen flex items-center justify-center text-green-400 overflow-hidden relative">

    <audio id="sfx-correct" src="{{ asset('sounds/correct.mp3') }}"></audio>
    <audio id="sfx-wrong" src="{{ asset('sounds/wrong.mp3') }}"></audio>
    <audio id="sfx-win" src="{{ asset('sounds/win.mp3') }}"></audio>

    <div class="relative w-full max-w-3xl p-1">
        
        <div class="bg-black border-4 border-green-600 rounded-lg p-6 neon-border relative z-10 min-h-[500px] flex flex-col justify-center">
            
            <div class="absolute top-4 left-0 w-full px-6 flex justify-between items-center text-xs md:text-sm">
                <div class="text-green-600">SYS.ADMIN // I-ISLAM</div>
                <div class="text-yellow-400">SCORE: <span id="score">000</span></div>
            </div>

            <div id="loading-screen" class="text-center space-y-6">
                <h1 class="text-2xl md:text-4xl text-green-500 neon-text animate-pulse">CONNECTING...</h1>
                <div class="text-xs text-green-800 space-y-2 font-mono">
                    <p>> ESTABLISHING SECURE LINK TO GEMINI AI...</p>
                    <p>> DOWNLOADING ISLAMIC KNOWLEDGE BASE...</p>
                    <p>> DECRYPTING QUESTIONS...</p>
                    <span class="inline-block w-3 h-4 bg-green-500 animate-bounce"></span>
                </div>
            </div>

            <div id="quiz-screen" class="hidden w-full">
                <div class="flex justify-center mb-6">
                    <span id="topic-badge" class="bg-green-900 text-green-300 text-[10px] px-3 py-2 border border-green-500 rounded">
                        TOPIC: LOADING
                    </span>
                </div>

                <div class="mb-8 text-center">
                    <h2 id="question-text" class="text-sm md:text-lg leading-loose text-white">
                        </h2>
                </div>

                <div id="options-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    </div>

                <div class="mt-8 w-full bg-green-900 h-2 border border-green-700">
                    <div id="progress-bar" class="bg-green-400 h-full w-0 transition-all duration-300"></div>
                </div>
            </div>

            <div id="result-screen" class="hidden text-center">
                <h1 class="text-3xl text-yellow-400 mb-6 neon-text">MISSION REPORT</h1>
                
                <div class="border-2 border-green-700 p-4 mb-6 bg-green-900 bg-opacity-20 inline-block">
                    <p class="text-sm text-green-300 mb-2">ACCURACY</p>
                    <p class="text-4xl text-white" id="final-score">0/10</p>
                </div>

                <div class="flex flex-col gap-4 justify-center items-center">
                    <button onclick="location.reload()" class="w-64 bg-green-600 hover:bg-green-500 text-black py-4 border-b-4 border-green-800 hover:border-green-600 active:border-0 active:mt-1">
                        RESTART MISSION
                    </button>
                    <a href="/games" class="text-xs text-green-600 hover:text-green-400">
                        < EXIT TO MAIN MENU
                    </a>
                </div>
            </div>

        </div>
    </div>

    <script>
        let questions = [];
        let currentQuestionIndex = 0;
        let score = 0;
        let isAnswering = false; // Prevent double clicking

        // Audio Objects
        const sfxCorrect = document.getElementById('sfx-correct');
        const sfxWrong = document.getElementById('sfx-wrong');
        const sfxWin = document.getElementById('sfx-win');

        document.addEventListener('DOMContentLoaded', () => {
            fetchQuestions();
        });

        async function fetchQuestions() {
            try {
                // Adjust this URL if your route is different
                const response = await fetch("{{ route('arcade.quiz.generate') }}");
                const data = await response.json();
                
                if(data.error) { throw new Error(data.error); }

                questions = data;
                
                // Slight delay to show off the loading animation
                setTimeout(() => {
                    document.getElementById('loading-screen').classList.add('hidden');
                    document.getElementById('quiz-screen').classList.remove('hidden');
                    loadQuestion();
                }, 1500);

            } catch (error) {
                console.error(error);
                alert("CONNECTION FAILED. PLEASE REFRESH.");
            }
        }

        function loadQuestion() {
            if (currentQuestionIndex >= questions.length) {
                endGame();
                return;
            }

            isAnswering = false;
            const q = questions[currentQuestionIndex];
            
            // Update UI
            document.getElementById('question-text').innerText = q.question;
            document.getElementById('topic-badge').innerText = `TOPIC: ${q.topic.toUpperCase()}`;
            
            // Update Progress Bar
            const progress = ((currentQuestionIndex) / questions.length) * 100;
            document.getElementById('progress-bar').style.width = `${progress}%`;

            const optionsDiv = document.getElementById('options-container');
            optionsDiv.innerHTML = ''; 

            q.options.forEach(option => {
                const btn = document.createElement('button');
                // Base styles for the retro button
                btn.className = "w-full text-left text-xs md:text-sm p-4 border-2 border-green-700 hover:bg-green-900 hover:border-green-400 hover:text-white hover:shadow-[0_0_10px_#22c55e] transition-all duration-100 group";
                
                // Add a little arrow on hover using CSS content or span
                btn.innerHTML = `<span class="opacity-0 group-hover:opacity-100 mr-2">></span> ${option}`;
                
                btn.onclick = () => handleAnswer(btn, option, q.correct_answer);
                optionsDiv.appendChild(btn);
            });
        }

        function handleAnswer(btn, selected, correct) {
            if (isAnswering) return; // Block extra clicks
            isAnswering = true;

            if (selected === correct) {
                // Correct Logic
                score++;
                document.getElementById('score').innerText = score.toString().padStart(3, '0');
                btn.classList.remove('border-green-700', 'hover:bg-green-900');
                btn.classList.add('bg-green-500', 'text-black', 'border-green-400'); // Flash Green
                playSound(sfxCorrect);
            } else {
                // Wrong Logic
                btn.classList.remove('border-green-700', 'hover:bg-green-900');
                btn.classList.add('bg-red-600', 'text-white', 'border-red-500', 'shake'); // Flash Red
                
                // Highlight the correct one automatically so they learn
                const allBtns = document.getElementById('options-container').children;
                for (let b of allBtns) {
                    if (b.innerText.includes(correct)) {
                        b.classList.add('border-green-500', 'text-green-300');
                    }
                }
                playSound(sfxWrong);
            }

            // Wait 1.5 seconds then go to next
            setTimeout(() => {
                currentQuestionIndex++;
                loadQuestion();
            }, 1500);
        }

        function playSound(audioElement) {
            // Simple check to ensure audio is loaded/allowed
            if(audioElement) {
                audioElement.currentTime = 0;
                audioElement.play().catch(e => console.log("Audio play failed (user interaction needed first)"));
            }
        }

        function endGame() {
            document.getElementById('quiz-screen').classList.add('hidden');
            document.getElementById('result-screen').classList.remove('hidden');
            document.getElementById('final-score').innerText = `${score} / ${questions.length}`;
            playSound(sfxWin);
        }
    </script>
</body>
</html>