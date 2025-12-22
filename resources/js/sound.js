// Create Audio Context
const AudioContext = window.AudioContext || window.webkitAudioContext;
const audioCtx = new AudioContext();
let clickBuffer = null;

// Load the sound file into memory
async function loadSound() {
    try {
        // Make sure this path matches where you put the file
        const response = await fetch('/audio/click.mp3');
        const arrayBuffer = await response.arrayBuffer();
        clickBuffer = await audioCtx.decodeAudioData(arrayBuffer);
    } catch (error) {
        console.log("Error loading sound:", error);
    }
}

// Play the sound
function playClick() {
    // Resume context if suspended (browser policy fix)
    if (audioCtx.state === 'suspended') {
        audioCtx.resume();
    }

    if (clickBuffer) {
        const source = audioCtx.createBufferSource();
        source.buffer = clickBuffer;
        
        // Create a gain node (volume control)
        const gainNode = audioCtx.createGain();
        gainNode.gain.value = 0.9; // 30% volume
        
        source.connect(gainNode);
        gainNode.connect(audioCtx.destination);
        source.start(0);
    }
}

// Initialize
loadSound();

// Attach to all clickable elements
document.addEventListener('mousedown', function(e) {
    const target = e.target.closest('button, a, input[type="submit"], .cursor-pointer');
    if (target) {
        playClick();
    }
});