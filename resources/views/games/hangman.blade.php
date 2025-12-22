<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domino Stack - i-Islam</title>
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

        /* --- STARRY BACKGROUND --- */
        .starry-background {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #022c22 100%);
            z-index: -1;
        }
        .star { position: absolute; background: white; border-radius: 50%; box-shadow: 0 0 4px white; }
        .star.twinkle { animation: twinkle 3s infinite; }
        .star.falling { animation: fall linear infinite; }
        @keyframes twinkle { 0%, 100% { opacity: 0.2; transform: scale(1); } 50% { opacity: 1; transform: scale(1.2); box-shadow: 0 0 8px white; } }
        @keyframes fall { 0% { opacity: 0; transform: translateY(0) rotate(0deg); } 10% { opacity: 1; } 90% { opacity: 1; } 100% { opacity: 0; transform: translateY(100vh) rotate(360deg); } }

        /* --- GAME CONTAINER --- */
        .game-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px;
            max-width: 900px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .back-btn {
            position: absolute; top: 20px; left: 20px;
            color: #a7f3d0; font-weight: bold; text-decoration: none;
            display: flex; align-items: center; gap: 5px; transition: 0.3s;
        }
        .back-btn:hover { color: #fff; text-shadow: 0 0 10px #34d399; }

        .game-title {
            font-size: 3rem; font-weight: 700; text-align: center; margin-bottom: 10px;
            background: linear-gradient(to right, #6ee7b7, #34d399); -webkit-background-clip: text; color: transparent;
            filter: drop-shadow(0 0 10px rgba(52, 211, 153, 0.4));
        }

        /* --- DOMINO & CHARACTER STYLES --- */
        .scene-area {
            display: flex; justify-content: center; align-items: flex-end;
            background: rgba(0, 0, 0, 0.2); border-radius: 16px; padding: 20px;
            min-height: 320px; border: 2px solid rgba(52, 211, 153, 0.2);
            position: relative; overflow: hidden;
            gap: 20px;
        }

        .domino {
            width: 35px; height: 70px;
            background: linear-gradient(145deg, #ffffff, #e6e6e6);
            border: 2px solid #333; border-radius: 4px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }
        .domino::before {
            content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 2px;
            background: #333; transform: translateY(-50%);
        }
        .domino-dot {
            width: 6px; height: 6px; background: #333;
            border-radius: 50%; position: absolute;
        }

        /* Animations */
        @keyframes shake-light { 0%, 100% { transform: translateX(0) rotate(0deg); } 25% { transform: translateX(-2px) rotate(-1deg); } 75% { transform: translateX(2px) rotate(1deg); } }
        @keyframes shake-medium { 0%, 100% { transform: translateX(0) rotate(0deg); } 25% { transform: translateX(-5px) rotate(-3deg); } 75% { transform: translateX(5px) rotate(3deg); } }
        @keyframes shake-heavy { 0%, 100% { transform: translateX(0) rotate(0deg); } 20% { transform: translateX(-8px) rotate(-5deg); } 40% { transform: translateX(8px) rotate(5deg); } 60% { transform: translateX(-8px) rotate(-5deg); } 80% { transform: translateX(8px) rotate(5deg); } }
        
        @keyframes fall-scatter {
            0% { transform: translateX(0) translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateX(var(--scatter-x)) translateY(var(--scatter-y)) rotate(var(--scatter-rotate)); opacity: 0; }
        }

        .shake-light { animation: shake-light 0.4s ease-in-out; }
        .shake-medium { animation: shake-medium 0.5s ease-in-out; }
        .shake-heavy { animation: shake-heavy 0.6s ease-in-out; }
        .fall-scatter { animation: fall-scatter 1s ease-out forwards; }

        /* Character Animations */
        @keyframes breathe { 0%, 100% { transform: scaleY(1); } 50% { transform: scaleY(1.02); } }
        .breathing-torso { animation: breathe 3s ease-in-out infinite; transform-origin: bottom center; }

        @keyframes blink-eyes { 0%, 48%, 52%, 100% { transform: scaleY(1); } 50% { transform: scaleY(0.1); } }
        .blinking-eyes { animation: blink-eyes 4s infinite; transform-origin: center; }

        @keyframes hair-sway { 0%, 100% { transform: rotate(0deg); } 50% { transform: rotate(2deg); } }
        .swaying-hair { animation: hair-sway 3s ease-in-out infinite; transform-origin: top center; }

        @keyframes happy-bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .celebrating { animation: happy-bounce 0.4s ease-in-out infinite; }

        @keyframes worry-shiver { 0% { transform: translateX(0); } 25% { transform: translateX(1px); } 75% { transform: translateX(-1px); } 100% { transform: translateX(0); } }
        .intense-worry-animation { animation: worry-shiver 0.2s linear infinite; }
        
        .dramatic-cry-animation { transform: translateY(10px); transition: transform 0.5s; }

        /* --- WORD DISPLAY --- */
        .word-display {
            display: flex; justify-content: center; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; margin-top: 20px;
        }
        .letter-box {
            width: 50px; height: 60px;
            border: 2px solid #34d399; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 32px; font-weight: 700; color: #fff;
            background: rgba(52, 211, 153, 0.1);
            box-shadow: 0 0 15px rgba(52, 211, 153, 0.1);
        }

        /* --- KEYBOARD --- */
        .keyboard {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(45px, 1fr)); gap: 10px; margin-top: 20px;
        }
        .key-button {
            padding: 15px 0; font-size: 18px; font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px;
            background: rgba(255, 255, 255, 0.05); color: #fff; cursor: pointer; transition: 0.2s;
        }
        .key-button:hover:not(:disabled) {
            background: rgba(52, 211, 153, 0.2); border-color: #34d399;
            box-shadow: 0 0 15px rgba(52, 211, 153, 0.4); transform: translateY(-2px);
        }
        .key-button:disabled { opacity: 0.3; cursor: not-allowed; }
        .key-button.correct { background: #10b981; border-color: #10b981; box-shadow: 0 0 20px rgba(16, 185, 129, 0.6); }
        .key-button.wrong { background: #ef4444; border-color: #ef4444; box-shadow: 0 0 20px rgba(239, 68, 68, 0.6); }

        /* --- STATS --- */
        .stats { display: flex; justify-content: center; gap: 30px; margin-top: 20px; }
        .stat-box { text-align: center; background: rgba(0,0,0,0.2); padding: 10px 20px; border-radius: 12px; }
        .stat-value { font-size: 1.5rem; font-weight: bold; color: #34d399; }
        .stat-label { font-size: 0.8rem; color: #9ca3af; }

        /* Confetti */
        .confetti {
            position: fixed; width: 10px; height: 10px; top: -10px;
            animation: confetti-fall 3s ease-out forwards; pointer-events: none; z-index: 1000;
        }
        @keyframes confetti-fall {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }

        /* --- POPUP MODAL --- */
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
        .btn-lose { background: linear-gradient(to right, #ef4444, #b91c1c); }
        .btn-lose:hover { box-shadow: 0 0 25px rgba(239, 68, 68, 0.5); transform: scale(1.05); }

        @media (max-width: 768px) {
            .game-board { grid-template-columns: 1fr; }
            .keyboard { gap: 5px; }
            .scene-area { flex-direction: column; align-items: center; }
        }
    </style>
</head>
<body>

    <div class="starry-background" id="starryBg"></div>

    <div class="game-container">
        <a href="{{ route('games.index') }}" class="back-btn"><span>←</span> Quit</a>
        
        <header class="text-center mb-6">
            <h1 class="game-title">Domino Stack</h1>
            <p class="text-gray-300">Guess the Islamic term before the dominoes fall!</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="scene-area">
                <div id="boy-container" class="boy-character">
                    <svg id="boy-svg" width="140" height="180" viewBox="0 0 140 180">
                        <defs>
                            <filter id="soft-shadow" x="-20%" y="-20%" width="140%" height="140%">
                                <feGaussianBlur in="SourceAlpha" stdDeviation="2"/>
                                <feOffset dx="0" dy="2" result="offsetblur"/>
                                <feMerge> 
                                    <feMergeNode in="offsetblur"/>
                                    <feMergeNode in="SourceGraphic"/> 
                                </feMerge>
                            </filter>
                        </defs>
                        <g class="breathing-torso">
                            <path d="M55 140 L55 170" stroke="#1f2937" stroke-width="14" stroke-linecap="round" />
                            <path d="M85 140 L85 170" stroke="#1f2937" stroke-width="14" stroke-linecap="round" />
                            <path d="M48 170 Q55 175 62 170" stroke="#333" stroke-width="8" stroke-linecap="round" fill="none" />
                            <path d="M78 170 Q85 175 92 170" stroke="#333" stroke-width="8" stroke-linecap="round" fill="none" />
                            <path d="M45 100 Q40 140 45 150 L95 150 Q100 140 95 100 L70 95 Z" fill="#3b82f6" stroke="#1e40af" stroke-width="2" />
                            <rect x="62" y="90" width="16" height="15" fill="#eab308" /> 
                            <g id="left-arm" style="transform-origin: 45px 105px;">
                                <path d="M45 105 Q30 130 35 145" stroke="#eab308" stroke-width="10" stroke-linecap="round" fill="none" />
                            </g>
                            <g id="right-arm" style="transform-origin: 95px 105px;">
                                <path d="M95 105 Q110 130 105 145" stroke="#eab308" stroke-width="10" stroke-linecap="round" fill="none" />
                            </g>
                            <g id="head" style="transform-origin: 70px 95px;">
                                <circle cx="70" cy="70" r="32" fill="#eab308" stroke="#333" stroke-width="2" />
                                <circle cx="38" cy="70" r="6" fill="#eab308" stroke="#333" stroke-width="2" />
                                <circle cx="102" cy="70" r="6" fill="#eab308" stroke="#333" stroke-width="2" />
                                <g id="hair" class="swaying-hair">
                                    <path d="M38 65 Q40 30 70 30 Q100 30 102 65 Q100 50 70 50 Q40 50 38 65" fill="#333" />
                                </g>
                                <g transform="translate(0, 5)">
                                    <g id="eyes-smile" class="blinking-eyes">
                                        <ellipse cx="60" cy="60" rx="4" ry="6" fill="#111" />
                                        <circle cx="62" cy="58" r="1.5" fill="#fff" />
                                        <ellipse cx="80" cy="60" rx="4" ry="6" fill="#111" />
                                        <circle cx="82" cy="58" r="1.5" fill="#fff" />
                                        <path d="M60 75 Q70 82 80 75" stroke="#333" stroke-width="2" fill="none" stroke-linecap="round" />
                                    </g>
                                    <g id="eyes-worried" style="display: none;">
                                        <path d="M55 55 Q60 52 65 55" stroke="#333" stroke-width="2" fill="none" />
                                        <path d="M75 55 Q80 52 85 55" stroke="#333" stroke-width="2" fill="none" />
                                        <circle cx="60" cy="62" r="3" fill="#111" />
                                        <circle cx="80" cy="62" r="3" fill="#111" />
                                        <path d="M60 80 Q70 75 80 80" stroke="#333" stroke-width="2" fill="none" stroke-linecap="round" />
                                    </g>
                                    <g id="eyes-crying" style="display: none;">
                                        <path d="M55 60 L65 60" stroke="#333" stroke-width="3" />
                                        <path d="M75 60 L85 60" stroke="#333" stroke-width="3" />
                                        <path d="M60 62 L60 75" stroke="#3b82f6" stroke-width="2" />
                                        <path d="M80 62 L80 75" stroke="#3b82f6" stroke-width="2" />
                                        <circle cx="60" cy="80" r="3" fill="#3b82f6" />
                                        <path d="M65 80 Q70 75 75 80" stroke="#333" stroke-width="2" fill="none" />
                                    </g>
                                </g>
                            </g>
                            <g id="sparkles" style="display: none;" class="celebrating">
                                <path d="M30 40 L35 30 L40 40 L35 50 Z" fill="#fbbf24" />
                                <path d="M100 40 L105 30 L110 40 L105 50 Z" fill="#fbbf24" />
                            </g>
                        </g>
                    </svg>
                </div>

                <div id="domino-stack" class="flex items-end justify-center gap-1 relative" style="padding-bottom: 0px;">
                    <div class="domino" data-index="0">
                        <div class="domino-dot" style="top: 15px; left: 11px;"></div>
                        <div class="domino-dot" style="top: 15px; right: 11px;"></div>
                        <div class="domino-dot" style="bottom: 15px; left: 50%; transform: translateX(-50%);"></div>
                    </div>
                    <div class="domino" data-index="1">
                        <div class="domino-dot" style="top: 15px; left: 50%; transform: translateX(-50%);"></div>
                        <div class="domino-dot" style="top: 35px; left: 11px;"></div>
                        <div class="domino-dot" style="bottom: 15px; left: 11px;"></div>
                    </div>
                    <div class="domino" data-index="2">
                        <div class="domino-dot" style="top: 15px; left: 11px;"></div>
                        <div class="domino-dot" style="top: 15px; right: 11px;"></div>
                        <div class="domino-dot" style="bottom: 15px; left: 50%; transform: translateX(-50%);"></div>
                    </div>
                    <div class="domino" data-index="3">
                        <div class="domino-dot" style="top: 15px; left: 11px;"></div>
                        <div class="domino-dot" style="bottom: 15px; right: 11px;"></div>
                        <div class="domino-dot" style="top: 35px; left: 50%; transform: translateX(-50%);"></div>
                    </div>
                    <div class="domino" data-index="4">
                        <div class="domino-dot" style="top: 15px; left: 50%; transform: translateX(-50%);"></div>
                        <div class="domino-dot" style="bottom: 15px; left: 11px;"></div>
                    </div>
                    <div class="domino" data-index="5">
                        <div class="domino-dot" style="top: 15px; left: 11px;"></div>
                        <div class="domino-dot" style="top: 35px; left: 50%; transform: translateX(-50%);"></div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col justify-center">
                <div class="word-display" id="wordDisplay"></div>
                
                <div class="text-center mb-6 h-16">
                    <button id="hintBtn" onclick="showHint()" class="bg-yellow-500/20 text-yellow-300 border border-yellow-500/50 px-4 py-2 rounded-full text-sm font-bold hover:bg-yellow-500/30 transition cursor-pointer">💡 Need a Hint?</button>
                    <p id="hintText" class="hidden text-yellow-200 font-medium text-sm bg-black/20 inline-block px-4 py-2 rounded-lg border border-yellow-500/20 animate-pulse"></p>
                </div>

                <div class="stats">
                    <div class="stat-box"><div class="stat-value" id="wrongCount">0</div><div class="stat-label">Mistakes</div></div>
                    <div class="stat-box"><div class="stat-value" id="remainingCount">6</div><div class="stat-label">Lives Left</div></div>
                </div>
            </div>
        </div>

        <div class="keyboard" id="keyboard"></div>
    </div>

    <div id="customModal" class="modal-overlay">
        <div class="modal-content">
            <div id="modalIcon" class="text-7xl mb-4 animate-bounce">🎉</div>
            <h2 id="modalTitle" class="text-4xl font-extrabold text-green-400 mb-2 drop-shadow-lg">MashaAllah!</h2>
            <div id="modalText" class="text-gray-200 text-lg mb-2 font-medium"></div>
            
            <div id="modalButtons" class="flex flex-col gap-3 mt-4">
                <button id="modalBtn" onclick="startSession()" class="btn-modal btn-win">Next Word ➡️</button>
            </div>
        </div>
    </div>

    <script>
        const levels = [
            { word: "RAMADAN", hint: "The holy month of fasting" },
            { word: "MOSQUE", hint: "Place where Muslims go to pray" },
            { word: "QURAN", hint: "The holy book of Islam" },
            { word: "SOLAT", hint: "We do this 5 times a day" },
            { word: "ZAKAT", hint: "Giving charity to the poor" },
            { word: "KAABAH", hint: "The black building in Mecca" },
            { word: "NABI", hint: "Messenger of Allah" },
            { word: "MAKKAH", hint: "The holiest city in Islam" },
            { word: "MADINAH", hint: "City of the Prophet (PBUH)" },
            { word: "HIJAB", hint: "Head covering worn by Muslim women" },
            { word: "IMAM", hint: "The leader of the prayer" },
            { word: "HALAL", hint: "Food allowed for Muslims to eat" },
            { word: "WUDHU", hint: "Washing before prayer" },
            { word: "TAJWID", hint: "Rules for reciting Quran correctly" },
            { word: "JUMMAAH", hint: "Friday prayer" },
            { word: "EID", hint: "Celebration after Ramadan" },
            { word: "SAHUR", hint: "Meal eaten before dawn" },
            { word: "IFTAR", hint: "Meal to break the fast" }
        ];

        let currentWord = '';
        let currentHint = '';
        let guessedLetters = [];
        let wrongGuesses = 0;
        const maxWrongGuesses = 6;
        let gameOver = false;
        
        const HISTORY_KEY = 'hangman_played_words';
        let gamePool = []; 

        const clickSound = new Audio("{{ asset('audio/click.mp3') }}");
        function playSound() {
            const sound = clickSound.cloneNode();
            sound.volume = 0.4;
            sound.play().catch(e => console.log("Sound blocked"));
        }

        function createStars() {
            const bg = document.getElementById('starryBg');
            for (let i = 0; i < 100; i++) {
                const star = document.createElement('div');
                star.className = `star ${Math.random() > 0.5 ? 'twinkle' : 'falling'}`;
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                const size = Math.random() * 3 + 1;
                star.style.width = size + 'px'; star.style.height = size + 'px';
                star.style.animationDuration = (Math.random() * 3 + 2) + 's';
                bg.appendChild(star);
            }
        }

        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
        }

        function startSession() {
            let playedWords = JSON.parse(localStorage.getItem(HISTORY_KEY)) || [];
            let availableWords = levels.filter(level => !playedWords.includes(level.word));

            if (availableWords.length === 0) {
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

            if (gamePool.length > 0) {
                const level = gamePool.pop();
                currentWord = level.word;
                currentHint = level.hint;
                saveWordToHistory(currentWord);
            } else {
                const level = levels[Math.floor(Math.random() * levels.length)];
                currentWord = level.word;
                currentHint = level.hint;
            }
            
            guessedLetters = [];
            wrongGuesses = 0;
            gameOver = false;

            document.getElementById('customModal').classList.remove('show');
            
            resetDominoes();
            setBoyExpression('smile');

            document.getElementById('hintBtn').classList.remove('hidden');
            document.getElementById('hintText').classList.add('hidden');
            document.getElementById('hintText').innerText = currentHint;
            
            updateDisplay();
            createKeyboard();
        }

        function saveWordToHistory(word) {
            let playedWords = JSON.parse(localStorage.getItem(HISTORY_KEY)) || [];
            if (!playedWords.includes(word)) {
                playedWords.push(word);
                localStorage.setItem(HISTORY_KEY, JSON.stringify(playedWords));
            }
        }

        function showHint() {
            playSound();
            document.getElementById('hintBtn').classList.add('hidden');
            document.getElementById('hintText').classList.remove('hidden');
        }

        function createKeyboard() {
            const keyboard = document.getElementById('keyboard');
            keyboard.innerHTML = '';
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('').forEach(letter => {
                const btn = document.createElement('button');
                btn.className = 'key-button';
                btn.innerText = letter;
                btn.onclick = () => handleGuess(letter, btn);
                keyboard.appendChild(btn);
            });
        }

        function handleGuess(letter, btn) {
            if (gameOver || guessedLetters.includes(letter)) return;
            
            playSound();
            guessedLetters.push(letter);
            btn.disabled = true;

            if (currentWord.includes(letter)) {
                btn.classList.add('correct');
                setBoyExpression('clap');
                setTimeout(() => { if (!gameOver) setBoyExpression('smile'); }, 1000);
                
                updateDisplay();
                checkWin();
            } else {
                btn.classList.add('wrong');
                wrongGuesses++;
                
                shakeDominoes();
                setBoyExpression('worried');
                setTimeout(() => { if (!gameOver) setBoyExpression('smile'); }, 1000);

                updateDisplay();
                checkLose();
            }
        }

        function setBoyExpression(expression) {
            const eyesSmile = document.getElementById('eyes-smile');
            const eyesWorried = document.getElementById('eyes-worried');
            const eyesCrying = document.getElementById('eyes-crying');
            const boyContainer = document.getElementById('boy-container');
            const sparkles = document.getElementById('sparkles');

            eyesSmile.style.display = 'none';
            eyesWorried.style.display = 'none';
            eyesCrying.style.display = 'none';
            sparkles.style.display = 'none';
            boyContainer.classList.remove('celebrating', 'intense-worry-animation', 'dramatic-cry-animation');

            switch(expression) {
                case 'smile': eyesSmile.style.display = 'block'; break;
                case 'clap': 
                    eyesSmile.style.display = 'block'; 
                    boyContainer.classList.add('celebrating');
                    break;
                case 'worried': 
                    eyesWorried.style.display = 'block'; 
                    boyContainer.classList.add('intense-worry-animation');
                    break;
                case 'cry': 
                    eyesCrying.style.display = 'block'; 
                    boyContainer.classList.add('dramatic-cry-animation');
                    break;
                case 'celebrate':
                    eyesSmile.style.display = 'block';
                    sparkles.style.display = 'block';
                    boyContainer.classList.add('celebrating');
                    createConfetti();
                    break;
            }
        }

        function shakeDominoes() {
            const dominoes = document.querySelectorAll('.domino');
            let shakeClass = wrongGuesses <= 2 ? 'shake-light' : (wrongGuesses <= 4 ? 'shake-medium' : 'shake-heavy');
            
            dominoes.forEach(domino => {
                domino.classList.remove('shake-light', 'shake-medium', 'shake-heavy');
                void domino.offsetWidth;
                domino.classList.add(shakeClass);
            });
            setTimeout(() => {
                dominoes.forEach(domino => domino.classList.remove(shakeClass));
            }, 600);
        }

        function scatterDominoes() {
            const dominoes = document.querySelectorAll('.domino');
            dominoes.forEach((domino, index) => {
                const scatterX = (Math.random() - 0.5) * 300;
                const scatterY = Math.random() * 200 + 100;
                const scatterRotate = (Math.random() - 0.5) * 720;
                
                domino.style.setProperty('--scatter-x', `${scatterX}px`);
                domino.style.setProperty('--scatter-y', `${scatterY}px`);
                domino.style.setProperty('--scatter-rotate', `${scatterRotate}deg`);
                
                setTimeout(() => domino.classList.add('fall-scatter'), index * 50);
            });
        }

        function resetDominoes() {
            const dominoes = document.querySelectorAll('.domino');
            dominoes.forEach(domino => {
                domino.classList.remove('fall-scatter');
                domino.style.transform = '';
                domino.style.opacity = '';
            });
        }

        function createConfetti() {
            const colors = ['#ff6b9d', '#ffd700', '#4a9eff', '#ff6347', '#9d4eff', '#50fa7b'];
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                    confetti.style.animationDelay = Math.random() * 0.5 + 's';
                    document.body.appendChild(confetti);
                    setTimeout(() => confetti.remove(), 3500);
                }, i * 30);
            }
        }

        function updateDisplay() {
            const display = document.getElementById('wordDisplay');
            display.innerHTML = '';
            currentWord.split('').forEach(letter => {
                const box = document.createElement('div');
                box.className = 'letter-box';
                box.innerText = guessedLetters.includes(letter) ? letter : '';
                display.appendChild(box);
            });
            document.getElementById('wrongCount').innerText = wrongGuesses;
            document.getElementById('remainingCount').innerText = maxWrongGuesses - wrongGuesses;
        }

        function showModal(win) {
            const modal = document.getElementById('customModal');
            const icon = document.getElementById('modalIcon');
            const title = document.getElementById('modalTitle');
            const text = document.getElementById('modalText');
            const buttonsContainer = document.getElementById('modalButtons');

            if (win) {
                icon.innerText = '⭐';
                title.innerText = 'Alhamdulillah!';
                title.className = 'text-4xl font-extrabold text-green-400 mb-2 drop-shadow-lg';
                
                text.innerHTML = `You found the word: <span class="text-green-300 font-bold">${currentWord}</span>
                    <div class="mt-4 p-3 bg-yellow-500/20 border border-yellow-500 rounded-xl animate-pulse flex flex-col items-center justify-center gap-1">
                        <span class="text-3xl">⭐⭐</span>
                        <span class="text-xl font-bold text-yellow-300">+2 Stars Earned!</span>
                    </div>`;
                
                recordGameRound();

                buttonsContainer.innerHTML = `
                    <button onclick="startSession()" class="btn-modal btn-win">Next Word ➡️</button>
                    <a href="{{ route('games.index') }}" onclick="playSound()" class="block text-center mt-3 text-sm text-green-300 hover:text-white underline">Back to Arcade 🏠</a>
                `;

            } else {
                icon.innerText = '😢';
                title.innerText = 'Inna lillahi...';
                title.className = 'text-4xl font-extrabold text-red-400 mb-2 drop-shadow-lg';
                text.innerText = 'The word was: ' + currentWord;

                buttonsContainer.innerHTML = `
                    <button onclick="startSession()" class="btn-modal btn-lose">Try Again 🔄</button>
                    <a href="{{ route('games.index') }}" onclick="playSound()" class="block text-center mt-3 text-gray-400 hover:text-white underline">Back to Arcade 🏠</a>
                `;
            }
            
            modal.classList.add('show');
        }

        function checkWin() {
            const hasWon = currentWord.split('').every(l => guessedLetters.includes(l));
            if (hasWon) {
                gameOver = true;
                setBoyExpression('celebrate');
                setTimeout(() => showModal(true), 1500);
            }
        }

        function checkLose() {
            if (wrongGuesses >= maxWrongGuesses) {
                gameOver = true;
                scatterDominoes();
                setBoyExpression('cry');
                setTimeout(() => showModal(false), 2000);
            }
        }

        function recordGameRound() {
            fetch("{{ route('games.complete') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    result: 'win'
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Stars Updated. New Total: " + data.total_stars);
            })
            .catch(error => console.error('Error recording game:', error));
        }

        createStars();
        startSession();
    </script>
</body>
</html>