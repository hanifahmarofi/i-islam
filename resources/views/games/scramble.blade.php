<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Scramble - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: #1a0b2e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
            color: #fff;
        }

        /* --- 1. NIGHT SKY BACKGROUND --- */
        .starry-background {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #022c22 100%);
            z-index: -1; overflow: hidden;
        }
        .star { position: absolute; background: white; border-radius: 50%; animation: twinkle var(--duration) infinite; opacity: 0; }
        @keyframes twinkle { 0%, 100% { opacity: 0.2; transform: scale(1); } 50% { opacity: 1; transform: scale(1.5); box-shadow: 0 0 10px gold; } }

        /* --- 2. GLASS CARD --- */
        .glass-card {
            background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            border-radius: 24px; padding: 40px; max-width: 700px; width: 100%; text-align: center;
            position: relative;
        }

        /* --- 3. ANIMATIONS --- */
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-10px); } 75% { transform: translateX(10px); } }
        .shake-animation { animation: shake 0.3s ease-in-out; border-color: #ef4444 !important; box-shadow: 0 0 15px rgba(239, 68, 68, 0.6) !important; }

        /* --- 4. UI ELEMENTS --- */
        .back-btn {
            position: absolute; top: 20px; left: 20px;
            color: #fde047; font-weight: bold; text-decoration: none;
            display: flex; align-items: center; gap: 5px; transition: 0.3s;
        }
        .back-btn:hover { color: #fff; text-shadow: 0 0 10px #facc15; }

        .scramble-text {
            font-size: 3.5rem; font-weight: 800; letter-spacing: 0.2em; margin-bottom: 20px;
            background: linear-gradient(to right, #fde047, #ca8a04); -webkit-background-clip: text; color: transparent;
            filter: drop-shadow(0 0 15px rgba(234, 179, 8, 0.5));
        }

        .input-field {
            background: rgba(0, 0, 0, 0.3); border: 2px solid rgba(255, 255, 255, 0.2);
            color: #fff; font-size: 1.5rem; text-align: center; padding: 15px;
            border-radius: 12px; width: 100%; margin-bottom: 20px; transition: 0.3s;
            text-transform: uppercase; font-weight: bold; letter-spacing: 2px;
        }
        .input-field:focus { outline: none; border-color: #facc15; box-shadow: 0 0 20px rgba(250, 204, 21, 0.5); }

        .btn-primary {
            width: 100%; padding: 15px; font-size: 1.2rem; font-weight: bold;
            background: linear-gradient(to right, #eab308, #ca8a04); border: none; border-radius: 12px;
            color: white; cursor: pointer; transition: 0.3s;
        }
        .btn-primary:hover { box-shadow: 0 0 25px rgba(234, 179, 8, 0.6); transform: scale(1.02); }
        
        .hidden { display: none !important; }

        /* --- 5. MODAL STYLES (New) --- */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(8px);
            z-index: 100; display: none; justify-content: center; align-items: center;
        }
        .modal-content {
            background: #064e3b; border: 4px solid #34d399; padding: 40px; border-radius: 30px;
            text-align: center; max-width: 450px; width: 90%;
            box-shadow: 0 0 60px rgba(52, 211, 153, 0.6);
            transform: scale(0.8); opacity: 0; transition: all 0.3s ease-out;
        }
        .modal-overlay.show { display: flex; }
        .modal-overlay.show .modal-content { transform: scale(1); opacity: 1; }
        
        .btn-modal {
            width: 100%; padding: 15px; font-size: 1.2rem; font-weight: bold;
            border: none; border-radius: 12px; color: white; cursor: pointer; transition: 0.3s;
        }
        .btn-win { background: linear-gradient(to right, #10b981, #059669); }
        .btn-win:hover { box-shadow: 0 0 25px rgba(16, 185, 129, 0.5); transform: scale(1.05); }
    </style>
</head>
<body>

    <div class="starry-background" id="starryBg"></div>

    <div class="glass-card">
        <a href="{{ route('games.index') }}" class="back-btn"><span>←</span> Quit</a>
        
        <h1 class="text-2xl font-bold text-yellow-200 mb-2 mt-6 uppercase tracking-wider">Unscramble the Word</h1>
        
        <div class="flex justify-between text-sm text-gray-300 font-bold uppercase tracking-widest mb-8 px-10">
            <span>Question: <span id="q-count" class="text-yellow-400">1</span>/10</span>
            <span>Score: <span id="score" class="text-green-400">0</span></span>
        </div>

        <div id="scramble-display" class="scramble-text">LOADING...</div>

        <div class="mb-8 inline-block px-6 py-2 rounded-full bg-yellow-500/20 border border-yellow-500/40 text-yellow-200 text-sm font-bold shadow-[0_0_15px_rgba(234,179,8,0.2)]">
            💡 Hint: <span id="hint-display" class="text-white">...</span>
        </div>

        <div class="max-w-xs mx-auto">
            <input type="text" id="user-input" class="input-field" placeholder="TYPE HERE..." autocomplete="off">
            <button id="check-btn" onclick="checkAnswer()" class="btn-primary">Check Answer ✅</button>
        </div>

        <div id="message-area" class="mt-6 h-8 font-bold text-lg tracking-wide"></div>
    </div>

    <div id="customModal" class="modal-overlay">
        <div class="modal-content">
            <div id="modalIcon" class="text-7xl mb-4 animate-bounce">⭐</div>
            <h2 id="modalTitle" class="text-4xl font-extrabold text-green-400 mb-2 drop-shadow-lg">MashaAllah!</h2>
            <div id="modalText" class="text-gray-200 text-lg mb-2 font-medium"></div>
            
            <div id="modalButtons" class="flex flex-col gap-3 mt-4">
                <button id="modalBtn" onclick="nextLevel()" class="btn-modal btn-win">Next Word ➡️</button>
            </div>
        </div>
    </div>

    <script>
        // --- SOUND SETUP ---
        const clickSound = new Audio("{{ asset('audio/click.mp3') }}");
        function playSound() {
            const sound = clickSound.cloneNode();
            sound.volume = 0.4;
            sound.play().catch(e => console.log("Audio play blocked"));
        }

        // Create Stars
        const bg = document.getElementById('starryBg');
        for (let i = 0; i < 100; i++) {
            const star = document.createElement('div');
            star.className = 'star';
            star.style.left = Math.random() * 100 + '%';
            star.style.top = Math.random() * 100 + '%';
            const size = Math.random() * 3 + 1;
            star.style.width = size + 'px'; star.style.height = size + 'px';
            star.style.animationDuration = (Math.random() * 3 + 2) + 's';
            bg.appendChild(star);
        }

        // --- WORD LIST ---
        const levels = [
            { word: "ALLAH", hint: "The One and Only God" },
            { word: "ISLAM", hint: "Our beautiful religion" },
            { word: "QURAN", hint: "The Holy Book of Islam" },
            { word: "MUHAMMAD", hint: "The last Prophet of Allah (PBUH)" },
            { word: "MOSQUE", hint: "Place where Muslims pray" },
            { word: "MAKKAH", hint: "The holy city where the Kaaba is located" },
            { word: "KAABA", hint: "The black cube we face when praying" },
            { word: "MADINAH", hint: " The city of the Prophet" },
            { word: "SUBUH", hint: "The first prayer of the day (morning)" },
            { word: "ZUHUR", hint: "The noon prayer" },
            { word: "ASAR", hint: "The afternoon prayer" },
            { word: "MAGHRIB", hint: "The sunset prayer" },
            { word: "ISHA", hint: "The night prayer" },
            { word: "AZAN", hint: "The call to prayer" },
            { word: "WUDU", hint: "Washing body parts before prayer" },
            { word: "IMAM", hint: "The person who leads the prayer" },
            { word: "QIBLA", hint: "The direction of Mecca" },
            { word: "SUJUD", hint: "Prostrating (bowing down) to Allah" },
            { word: "RAMADAN", hint: "The holy month of fasting" },
            { word: "FASTING", hint: "No eating or drinking during the day" },
            { word: "SAHUR", hint: "The meal eaten before fasting begins" },
            { word: "IFTAR", hint: "The meal to break the fast" },
            { word: "EID", hint: "Festival celebrated by Muslims" },
            { word: "ZAKAT", hint: "Giving charity to the poor" },
            { word: "HAJI", hint: "Pilgrimage to Mecca once in a lifetime" },
            { word: "HALAL", hint: "Allowed/Permissible in Islam" },
            { word: "HARAM", hint: "Forbidden/Not allowed in Islam" },
            { word: "JANNAH", hint: "Paradise or Heaven" },
            { word: "DUA", hint: "Calling out to Allah for help" },
            { word: "SADAQAH", hint: "Voluntary charity or kindness" }
        ];

        // --- GAME VARIABLES ---
        let currentLevel = {};
        let score = 0;
        let questionCount = 1;
        const maxQuestions = 10;
        let gamePool = []; 
        const HISTORY_KEY = 'scramble_played_words';

        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
        }

        function startSession() {
            let playedWords = JSON.parse(localStorage.getItem(HISTORY_KEY)) || [];
            let availableWords = levels.filter(level => !playedWords.includes(level.word));

            if (availableWords.length < maxQuestions) {
                playedWords = []; 
                localStorage.setItem(HISTORY_KEY, JSON.stringify(playedWords));
                availableWords = [...levels]; 
            }

            gamePool = [...availableWords];
            shuffleArray(gamePool);
            
            initGame();
        }

        function initGame() {
            playSound();
            document.getElementById('customModal').classList.remove('show'); // Hide modal

            if (gamePool.length > 0) {
                currentLevel = gamePool.pop();
                saveWordToHistory(currentLevel.word);
            } else {
                currentLevel = levels[Math.floor(Math.random() * levels.length)];
            }

            // Scramble Logic
            let scrambled = currentLevel.word.split('').sort(() => 0.5 - Math.random()).join('');
            while (scrambled === currentLevel.word) {
                scrambled = currentLevel.word.split('').sort(() => 0.5 - Math.random()).join('');
            }

            document.getElementById('scramble-display').innerText = scrambled;
            document.getElementById('hint-display').innerText = currentLevel.hint;
            document.getElementById('user-input').value = '';
            document.getElementById('message-area').innerText = '';
            document.getElementById('user-input').disabled = false;
            document.getElementById('user-input').focus();
        }

        function saveWordToHistory(word) {
            let playedWords = JSON.parse(localStorage.getItem(HISTORY_KEY)) || [];
            if (!playedWords.includes(word)) {
                playedWords.push(word);
                localStorage.setItem(HISTORY_KEY, JSON.stringify(playedWords));
            }
        }

        function checkAnswer() {
            playSound();
            const input = document.getElementById('user-input').value.toUpperCase().trim();
            const msg = document.getElementById('message-area');
            const inputField = document.getElementById('user-input');

            if (input === currentLevel.word) {
                // Correct! Show Modal Reward
                score += 10;
                document.getElementById('score').innerText = score;
                showModal();
            } else {
                // Wrong
                msg.innerText = "❌ Try again!";
                msg.className = "mt-6 h-8 font-bold text-lg text-red-400 drop-shadow-lg";
                inputField.classList.add('shake-animation');
                setTimeout(() => { inputField.classList.remove('shake-animation'); }, 500);
            }
        }

        function showModal() {
            const modal = document.getElementById('customModal');
            const title = document.getElementById('modalTitle');
            const text = document.getElementById('modalText');
            const btn = document.getElementById('modalBtn');

            // Set Reward Content
            title.innerText = 'MashaAllah!';
            text.innerHTML = `You found the word: <span class="text-green-300 font-bold">${currentLevel.word}</span>
                <div class="mt-4 p-3 bg-yellow-500/20 border border-yellow-500 rounded-xl animate-pulse flex flex-col items-center justify-center gap-1">
                    <span class="text-3xl">⭐⭐</span>
                    <span class="text-xl font-bold text-yellow-300">+2 Stars Earned!</span>
                </div>`;

            // Check if last question
            if (questionCount >= maxQuestions) {
                btn.innerText = "Finish Session 🏆";
                btn.onclick = () => window.location.href = "{{ route('games.index') }}";
            } else {
                btn.innerText = "Next Word ➡️";
                btn.onclick = nextLevel;
            }

            // Save Reward to Database
            recordGameRound();

            modal.classList.add('show');
        }

        function nextLevel() {
            if (questionCount < maxQuestions) {
                questionCount++;
                document.getElementById('q-count').innerText = questionCount;
                initGame();
            }
        }

        document.getElementById('user-input').addEventListener("keypress", function(event) {
            if (event.key === "Enter") checkAnswer();
        });

        // --- REWARD LOGIC ---
        function recordGameRound() {
            fetch("{{ route('games.complete') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ result: 'win' })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Stars Updated: " + data.total_stars);
            })
            .catch(error => console.error('Error recording game:', error));
        }

        startSession();
    </script>

</body>
</html>