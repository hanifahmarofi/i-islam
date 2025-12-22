<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Pairs - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: #1a0b2e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            overflow-x: hidden;
            color: #fff;
        }

        /* --- 1. NIGHT SKY BACKGROUND --- */
        .starry-background {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #022c22 100%);
            z-index: -1;
        }
        .star { position: absolute; background: white; border-radius: 50%; animation: twinkle var(--duration) infinite; opacity: 0; }
        @keyframes twinkle { 0%, 100% { opacity: 0.2; transform: scale(1); } 50% { opacity: 1; transform: scale(1.5); box-shadow: 0 0 10px gold; } }

        /* --- 2. CARD STYLES --- */
        .perspective { perspective: 1000px; }
        .transform-style-3d { transform-style: preserve-3d; }
        .backface-hidden { backface-visibility: hidden; }
        .rotate-y-180 { transform: rotateY(180deg); }

        /* Card Front (Blue with ?) */
        .card-front {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border: 2px solid rgba(255,255,255,0.2);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
        }
        
        /* Card Back (White with Text) */
        .card-back {
            background: white;
            border: 4px solid #3b82f6;
            color: #1e3a8a;
        }
        
        /* Matched Card (Green Glow) */
        .card-matched {
            border-color: #22c55e !important;
            background: #f0fdf4 !important;
            color: #15803d !important;
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.6) !important;
        }

        /* Header Styles */
        .back-btn {
            color: #93c5fd; font-weight: bold; text-decoration: none;
            display: flex; align-items: center; gap: 5px; transition: 0.3s;
        }
        .back-btn:hover { color: #fff; text-shadow: 0 0 10px #60a5fa; }

        .game-title {
            font-size: 2.5rem; font-weight: 800; 
            background: linear-gradient(to right, #60a5fa, #3b82f6); -webkit-background-clip: text; color: transparent;
            filter: drop-shadow(0 0 15px rgba(59, 130, 246, 0.5));
        }
        
        /* Utility */
        .hidden { display: none !important; }
    </style>
</head>
<body>

    <div class="starry-background" id="starryBg"></div>

    <div class="w-full max-w-4xl flex flex-col md:flex-row justify-between items-center px-4 mb-6 mt-4 gap-4">
        <a href="{{ route('games.index') }}" class="back-btn self-start md:self-center"><span>←</span> Quit Game</a>
        
        <h1 class="game-title">🧩 Match Pairs</h1>
        
        <div class="flex gap-4">
            <div class="w-32 text-center font-bold text-yellow-300 bg-black/30 px-4 py-2 rounded-lg border border-yellow-500/30">
                ⏳ <span id="timer-display">03:00</span>
            </div>
            <div class="w-32 text-center font-bold text-blue-300 bg-black/30 px-4 py-2 rounded-lg border border-blue-500/30">
                Moves: <span id="move-count" class="text-white">0</span>
            </div>
        </div>
    </div>

    <div class="w-full max-w-4xl px-4 mb-20">
        <div id="game-grid" class="grid grid-cols-3 md:grid-cols-4 gap-4 md:gap-6">
            </div>
    </div>

    <div id="result-modal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white/10 border border-white/20 p-10 rounded-3xl shadow-2xl text-center transform scale-110 transition backdrop-blur-md max-w-md mx-4 w-full">
            
            <div id="modal-icon" class="text-7xl mb-4 drop-shadow-lg animate-bounce">🏆</div>
            
            <h2 id="modal-title" class="text-4xl font-bold text-white mb-2 drop-shadow-md">MashaAllah!</h2>
            
            <p id="modal-msg" class="text-blue-200 mb-2 text-lg">
                You matched all pairs!
            </p>
            
            <div id="reward-badge" class="mt-4 p-4 bg-yellow-500/20 border border-yellow-500 rounded-xl animate-pulse flex flex-col items-center justify-center gap-1 hidden">
                <span class="text-3xl">⭐⭐⭐⭐⭐</span>
                <span class="text-xl font-bold text-yellow-300">+5 Stars Earned!</span>
            </div>

            <div id="modal-buttons" class="flex flex-col gap-3 mt-6">
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

        // DATABASE
        const database = [
            { term: "Subuh", def: "Morning Prayer" },
            { term: "Zakat", def: "Charity" },
            { term: "Haji", def: "Pilgrimage" },
            { term: "Quran", def: "Holy Book" },
            { term: "Kaaba", def: "House of Allah" },
            { term: "Iman", def: "Faith" },
            { term: "Wudhu", def: "Ablution" },
            { term: "Sawm", def: "Fasting" },
            { term: "Jannah", def: "Paradise" },
            { term: "Jahannam", def: "Hellfire" },
            { term: "Sunnah", def: "Prophet's Way" },
            { term: "Halal", def: "Permissible" },
            { term: "Haram", def: "Forbidden" },
            { term: "Adhan", def: "Call to Prayer" },
            { term: "Mosque", def: "Masjid" },
            { term: "Sadaqah", def: "Voluntary Charity" },
            { term: "Dua", def: "Supplication" },
            { term: "Hijab", def: "Modest Dress" }
        ];

        let flippedCards = []; 
        let matchedPairs = 0;
        let moves = 0;
        let lockBoard = false; 
        let timerInterval;
        let timeLeft = 180; // 3 Minutes

        function initGame() {
            playSound();
            const grid = document.getElementById('game-grid');
            grid.innerHTML = '';
            flippedCards = [];
            matchedPairs = 0;
            moves = 0;
            lockBoard = false;
            timeLeft = 180; 
            
            document.getElementById('move-count').innerText = '0';
            document.getElementById('result-modal').classList.add('hidden');
            document.getElementById('reward-badge').classList.add('hidden');
            
            updateTimerDisplay();
            startTimer();

            // Pick 6 random pairs
            const selectedPairs = [...database].sort(() => 0.5 - Math.random()).slice(0, 6);

            let deck = [];
            selectedPairs.forEach((pair, index) => {
                deck.push({ id: index, text: pair.term, type: 'term' });
                deck.push({ id: index, text: pair.def, type: 'def' });
            });

            deck.sort(() => 0.5 - Math.random());

            deck.forEach((card) => {
                const cardElement = document.createElement('div');
                cardElement.className = "relative h-32 cursor-pointer perspective group";
                cardElement.dataset.id = card.id;
                cardElement.onclick = () => flipCard(cardElement);

                cardElement.innerHTML = `
                    <div class="card-inner w-full h-full duration-500 transform-style-3d transition-transform relative">
                        <div class="card-front absolute w-full h-full rounded-xl flex items-center justify-center backface-hidden">
                            <span class="text-4xl text-white opacity-50">?</span>
                        </div>
                        <div class="card-back absolute w-full h-full rounded-xl flex items-center justify-center backface-hidden rotate-y-180 p-2 text-center shadow-lg">
                            <span class="font-bold text-sm md:text-base leading-tight select-none">${card.text}</span>
                        </div>
                    </div>
                `;
                grid.appendChild(cardElement);
            });
        }

        function startTimer() {
            clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    handleLose();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            document.getElementById('timer-display').innerText = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        function flipCard(card) {
            if (lockBoard) return;
            if (card === flippedCards[0]) return; 

            playSound();

            card.querySelector('.card-inner').classList.add('rotate-y-180');
            flippedCards.push(card);

            if (flippedCards.length === 2) {
                moves++;
                document.getElementById('move-count').innerText = moves;
                checkForMatch();
            }
        }

        function checkForMatch() {
            const [card1, card2] = flippedCards;
            const isMatch = card1.dataset.id === card2.dataset.id;

            if (isMatch) {
                disableCards();
            } else {
                unflipCards();
            }
        }

        function disableCards() {
            flippedCards.forEach(card => {
                card.onclick = null; 
                const back = card.querySelector('.card-back');
                back.classList.add('card-matched'); 
            });
            
            matchedPairs++;
            flippedCards = [];

            if (matchedPairs === 6) {
                clearInterval(timerInterval);
                setTimeout(handleWin, 500);
            }
        }

        function unflipCards() {
            lockBoard = true;
            setTimeout(() => {
                flippedCards.forEach(card => {
                    card.querySelector('.card-inner').classList.remove('rotate-y-180');
                });
                flippedCards = [];
                lockBoard = false;
            }, 1000);
        }

        function handleWin() {
            document.getElementById('modal-icon').innerText = "🏆";
            document.getElementById('modal-title').innerText = "MashaAllah!";
            document.getElementById('modal-title').className = "text-4xl font-bold text-green-400 mb-2 drop-shadow-md";
            document.getElementById('modal-msg').innerText = `You finished in ${moves} moves!`;
            
            // Show Reward Badge
            document.getElementById('reward-badge').classList.remove('hidden');

            // Record Reward
            recordGameRound();

            // Buttons
            const btns = `
                <a href="{{ route('games.index') }}" onclick="playSound()" class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:scale-105 block">
                    Back to Arcade 🏠
                </a>
                <button onclick="initGame()" class="text-sm text-gray-400 hover:text-white underline mt-2">Play Again</button>
            `;
            
            document.getElementById('modal-buttons').innerHTML = btns;
            document.getElementById('result-modal').classList.remove('hidden');
        }

        function handleLose() {
            document.getElementById('modal-icon').innerText = "⏰";
            document.getElementById('modal-title').innerText = "Game Over";
            document.getElementById('modal-title').className = "text-4xl font-bold text-red-400 mb-2 drop-shadow-md";
            document.getElementById('modal-msg').innerText = "Time ran out!";
            document.getElementById('reward-badge').classList.add('hidden');

            const btns = `
                <button onclick="initGame()" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:scale-105 mb-2">
                    Try Again 🔄
                </button>
                <a href="{{ route('games.index') }}" onclick="playSound()" class="w-full bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:scale-105 block">
                    Back to Arcade 🏠
                </a>
            `;

            document.getElementById('modal-buttons').innerHTML = btns;
            document.getElementById('result-modal').classList.remove('hidden');
        }

        // --- REWARD LOGIC FUNCTION ---
        function recordGameRound() {
            // Note: We are sending +5 points for this game
            fetch("{{ route('games.complete') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ result: 'win_match' }) 
            })
            .then(response => response.json())
            .then(data => {
                console.log("Game Recorded. New Stars: " + data.total_stars);
            })
            .catch(error => console.error('Error recording game:', error));
        }

        // Start game on load
        initGame();
    </script>

</body>
</html>