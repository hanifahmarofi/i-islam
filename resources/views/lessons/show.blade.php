<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lesson->title }} - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Smooth fade transition for slides */
        .carousel-item { transition: opacity 0.5s ease-in-out; }
    </style>
</head>
<body class="bg-[#0f392b] min-h-screen text-white font-sans pb-20">

    <div class="p-6">
        <a href="{{ route('lessons.index') }}" class="flex items-center gap-2 text-green-400 hover:text-white transition font-bold">
            <i class="fa-solid fa-arrow-left"></i> Back to Lessons
        </a>
    </div>

    <div class="max-w-4xl mx-auto px-6">
        
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-bold text-green-100 mb-4">{{ $lesson->title }}</h1>
            <span class="inline-block bg-yellow-500/20 text-yellow-300 px-4 py-1.5 rounded-full text-sm font-bold border border-yellow-500/30">
                <i class="fa-solid fa-star mr-1"></i> Reward: {{ $lesson->points }} XP
            </span>
        </div>

        <div class="bg-white/10 backdrop-blur-lg border border-white/10 rounded-3xl p-8 mb-12 shadow-2xl">
            <h2 class="text-2xl font-bold text-green-300 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-info-circle"></i> Overview
            </h2>
            <div class="prose prose-invert max-w-none text-lg leading-relaxed text-gray-200">
                {{ $lesson->description }}
            </div>
        </div>

        <div class="space-y-8 mb-16">
            <h2 class="text-2xl font-bold text-green-300 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-images"></i> Lesson Slides
            </h2>

            @if($lesson->slides && $lesson->slides->count() > 0)
                <div class="relative bg-black/20 p-2 rounded-3xl border border-white/10 shadow-2xl">
                    
                    <div id="carousel-wrapper" class="relative overflow-hidden rounded-2xl aspect-[4/3] md:aspect-[16/9]">
                        @foreach($lesson->slides as $index => $slide)
                            <div class="carousel-item absolute inset-0 w-full h-full {{ $index != 0 ? 'hidden opacity-0' : 'block opacity-100' }}" data-index="{{ $index }}">
                                <img src="{{ asset($slide) }}" 
                                     alt="Slide {{ $index + 1 }}" 
                                     class="w-full h-full object-contain bg-black/50">
                                
                                <div class="absolute bottom-4 right-4 bg-black/70 text-white px-4 py-2 rounded-full text-sm font-bold backdrop-blur-md">
                                    {{ $index + 1 }} / {{ $lesson->slides->count() }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button id="prev-slide" class="absolute top-1/2 -left-4 md:-left-6 transform -translate-y-1/2 bg-green-600/80 hover:bg-green-500 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition backdrop-blur-sm z-10">
                        <i class="fa-solid fa-chevron-left text-xl"></i>
                    </button>

                    <button id="next-slide" class="absolute top-1/2 -right-4 md:-right-6 transform -translate-y-1/2 bg-green-600/80 hover:bg-green-500 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition backdrop-blur-sm z-10">
                        <i class="fa-solid fa-chevron-right text-xl"></i>
                    </button>
                </div>
            @else
                <div class="bg-white/5 p-8 rounded-2xl text-center text-gray-400 border border-white/5 border-dashed">
                    <p>No slides available for this lesson.</p>
                </div>
            @endif
        </div>
        <div class="text-center mb-16">
            <a href="#quiz-section" class="inline-block bg-yellow-500 hover:bg-yellow-400 text-black font-extrabold text-xl px-10 py-4 rounded-full shadow-lg transform hover:scale-105 transition animate-pulse scroll-smooth">
                👇 I'm Ready! Take the Quiz
            </a>
        </div>

        <div id="quiz-section" class="bg-gradient-to-br from-green-900 to-emerald-900 border border-green-500/30 rounded-3xl p-8 shadow-2xl relative overflow-hidden scroll-mt-20">
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/10 rounded-full blur-3xl"></div>
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                <i class="fa-solid fa-clipboard-question"></i> Pop Quiz!
            </h2>

            @if($lesson->questions && $lesson->questions->count() > 0)
                <form action="{{ route('quiz.submit', ['id' => $lesson->id]) }}" method="POST"> 
                    @csrf
                    
                    @foreach($lesson->questions as $index => $question)
                        <div class="mb-8 p-6 rounded-2xl bg-black/20 border border-white/5">
                            <p class="font-bold text-lg mb-4 text-green-100">{{ $index + 1 }}. {{ $question->question_text }}</p>
                            <div class="space-y-3">
                                
                                <label class="flex items-center gap-3 cursor-pointer group p-2 rounded-lg hover:bg-white/5 transition">
                                    <input type="radio" name="answers[{{$question->id}}]" value="a" class="w-5 h-5 accent-green-500">
                                    <span class="group-hover:text-green-300 transition">{{ $question->option_a }}</span>
                                </label>
                                
                                <label class="flex items-center gap-3 cursor-pointer group p-2 rounded-lg hover:bg-white/5 transition">
                                    <input type="radio" name="answers[{{$question->id}}]" value="b" class="w-5 h-5 accent-green-500">
                                    <span class="group-hover:text-green-300 transition">{{ $question->option_b }}</span>
                                </label>
                                
                                <label class="flex items-center gap-3 cursor-pointer group p-2 rounded-lg hover:bg-white/5 transition">
                                    <input type="radio" name="answers[{{$question->id}}]" value="c" class="w-5 h-5 accent-green-500">
                                    <span class="group-hover:text-green-300 transition">{{ $question->option_c }}</span>
                                </label>
                                
                                <label class="flex items-center gap-3 cursor-pointer group p-2 rounded-lg hover:bg-white/5 transition">
                                    <input type="radio" name="answers[{{$question->id}}]" value="d" class="w-5 h-5 accent-green-500">
                                    <span class="group-hover:text-green-300 transition">{{ $question->option_d }}</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                    
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-4 rounded-xl text-lg transition shadow-lg transform hover:scale-[1.01]">
                        Submit Answers
                    </button>
                </form>
            @else
                <div class="text-center py-10 text-gray-400 bg-black/20 rounded-xl">
                    <i class="fa-solid fa-ghost text-4xl mb-3 opacity-50"></i>
                    <p>No quiz questions available for this lesson yet.</p>
                </div>
            @endif
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.carousel-item');
            const prevBtn = document.getElementById('prev-slide');
            const nextBtn = document.getElementById('next-slide');
            let currentIndex = 0;
            const totalSlides = slides.length;

            // Only run if slides exist
            if (totalSlides > 0) {
                // Function to show a specific slide
                function showSlide(index) {
                    // Ensure index is within bounds (looping effect)
                    if (index < 0) { currentIndex = totalSlides - 1; }
                    else if (index >= totalSlides) { currentIndex = 0; }
                    else { currentIndex = index; }

                    // Hide all slides first
                    slides.forEach(slide => {
                        slide.classList.add('hidden', 'opacity-0');
                        slide.classList.remove('block', 'opacity-100');
                    });

                    // Show the current slide
                    slides[currentIndex].classList.remove('hidden', 'opacity-0');
                    slides[currentIndex].classList.add('block', 'opacity-100');
                }

                // Event Listeners for buttons
                prevBtn.addEventListener('click', () => {
                    showSlide(currentIndex - 1);
                });

                nextBtn.addEventListener('click', () => {
                    showSlide(currentIndex + 1);
                });
            } else {
                // Hide buttons if no slides
                if(prevBtn) prevBtn.style.display = 'none';
                if(nextBtn) nextBtn.style.display = 'none';
            }
        });
    </script>

</body>
</html>