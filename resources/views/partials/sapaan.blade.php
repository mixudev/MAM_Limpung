{{-- Opening Ceremony Curtain (Sapaan Screen) --}}
{{-- Only displayed ONCE per tab session (using sessionStorage) --}}

<div id="sapaanCurtain" class="fixed inset-0 z-[99999] flex flex-col items-center justify-center select-none pointer-events-auto transform translate-y-0 hidden transition-all duration-[1500ms] ease-[cubic-bezier(0.76,0,0.24,1)] origin-top bg-slate-50">
    
    <!-- Premium Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- Fast Slideshow Background (Timelapse) -->
    <div id="slideshowBg" class="absolute inset-0 w-full h-full z-0 pointer-events-none opacity-0 transition-opacity duration-1000 bg-cover bg-center"></div>
    
    <!-- Elegant light gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-b from-white/95 via-white/85 to-blue-50/95 z-0 pointer-events-none backdrop-blur-[4px]"></div>

    <!-- The Content Wrapper -->
    <div id="sapaanContent" class="relative z-10 flex flex-col items-center max-w-3xl px-6 text-center transition-all duration-[1200ms]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        
        <!-- Loading State -->
        <div id="loadingState" class="absolute inset-0 flex flex-col items-center justify-center bg-transparent z-20 transition-opacity duration-700">
            <div class="relative w-20 h-20 flex items-center justify-center mb-6">
                <div class="absolute inset-0 border-4 border-blue-900/10 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-blue-600 rounded-full border-t-transparent animate-spin"></div>
                <img src="{{ asset('assets/img/logo.png') }}" class="w-10 h-10 object-contain animate-pulse" alt="Loading">
            </div>
            <div class="flex flex-col items-center">
                <span class="text-blue-900 font-bold tracking-[0.3em] text-sm uppercase mb-2">Memuat Aset</span>
                <div class="flex space-x-1.5">
                    <div class="w-1.5 h-1.5 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                    <div class="w-1.5 h-1.5 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                    <div class="w-1.5 h-1.5 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                </div>
            </div>
        </div>

        <div id="mainGreeting" class="opacity-0 transition-opacity duration-1000 flex flex-col items-center pointer-events-none">
            <!-- Central Logo with a elegant glowing aura -->
            <div class="relative mb-10 flex items-center justify-center">
                <div class="absolute w-48 h-48 bg-amber-400/20 blur-[40px] rounded-full scale-75 animate-aurora"></div>
                <div class="absolute w-32 h-32 bg-blue-500/15 blur-[30px] rounded-full scale-100 animate-aurora" style="animation-delay: 2s;"></div>
                <img src="{{ asset('assets/img/logo.png') }}" class="w-28 h-28 sm:w-32 sm:h-32 object-contain relative z-10 animate-gentle-float animate-logo-pulse drop-shadow-xl" alt="Logo Opening">
            </div>

            <!-- Opening Ceremony Slogan & Typing Elements -->
            <div class="space-y-5">
                
                <!-- Typewriter Container -->
                <div class="h-6 flex items-center justify-center">
                    <span id="typewriterText" class="text-xs sm:text-sm font-bold tracking-[0.5em] uppercase text-amber-500 drop-shadow-sm"></span>
                    <span class="w-[2px] h-4 sm:h-5 bg-amber-500 ml-2 animate-cursor"></span>
                </div>

                <!-- Big Majestic Main Greeting -->
                <h1 id="sapaanTitle" class="text-4xl sm:text-6xl md:text-7xl font-bold tracking-tight text-blue-950 opacity-0 translate-y-8 transition-all duration-[1200ms] ease-out drop-shadow-md" style="font-family: 'Playfair Display', serif; line-height: 1.2;">
                    MAM Limpung
                </h1>

                <!-- Modern Word Swap Tagline (Slide Up Transition) -->
                <div id="taglineWrapper" class="opacity-0 translate-y-6 transition-all duration-[1000ms] ease-out flex flex-wrap items-center justify-center gap-x-2.5 text-base sm:text-xl text-slate-600 font-medium leading-relaxed mt-6">
                    <span>Membentuk Insan yang</span>
                    <div class="inline-block relative h-8 sm:h-10 overflow-hidden w-40 sm:w-56 text-left">
                        <span id="swapWord" class="absolute left-0 text-blue-700 font-bold transition-all duration-500 ease-[cubic-bezier(0.175,0.885,0.32,1.275)] transform translate-y-0 opacity-100 text-lg sm:text-2xl drop-shadow-sm">
                            Unggul
                        </span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<style>
    /* Styling & Custom Animation Timeline for Opening Ceremony */
    
    body.sapaan-active {
        overflow: hidden !important;
        height: 100vh !important;
    }

    .curtain-lift {
        transform: translateY(-100%);
        border-bottom-left-radius: 50% 15%;
        border-bottom-right-radius: 50% 15%;
    }

    .content-lift {
        transform: scale(0.9) translateY(-10vh);
        opacity: 0;
    }

    @keyframes aurora {
        0%, 100% { transform: scale(0.85); opacity: 0.4; }
        50% { transform: scale(1.3); opacity: 0.8; }
    }
    .animate-aurora {
        animation: aurora 5s ease-in-out infinite;
    }

    @keyframes gentle-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    .animate-gentle-float {
        animation: gentle-float 4s ease-in-out infinite;
    }

    @keyframes cursor-blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }
    .animate-cursor {
        animation: cursor-blink 0.8s step-end infinite;
    }
</style>

<script>
    (function () {
        const curtain = document.getElementById('sapaanCurtain');
        const typewriter = document.getElementById('typewriterText');
        const title = document.getElementById('sapaanTitle');
        const tagline = document.getElementById('taglineWrapper');
        const swapWord = document.getElementById('swapWord');
        const loadingState = document.getElementById('loadingState');
        const mainGreeting = document.getElementById('mainGreeting');
        const slideshowBg = document.getElementById('slideshowBg');
        const sapaanContent = document.getElementById('sapaanContent');
        const body = document.body;

        // Session check: only run the opening sapaan once per tab session
        const hasSeenSapaan = sessionStorage.getItem('hasSeenSapaan');

        if (!hasSeenSapaan) {
            // Show curtain and lock scroll
            curtain.classList.remove('hidden');
            body.classList.add('sapaan-active');

            // --- TIMELAPSE IMAGES PREPARATION ---
            // Using a mix of high quality educational/school/abstract images
            const images = [
                "https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1000&q=80",
                "https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1000&q=80",
                "https://images.unsplash.com/photo-1509062522246-3755977927d7?w=1000&q=80",
                "https://images.unsplash.com/photo-1546410531-b4acaef4960c?w=1000&q=80",
                "https://images.unsplash.com/photo-1511629091441-ee46146481b6?w=1000&q=80",
                "https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=1000&q=80",
                "https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=1000&q=80",
                "https://images.unsplash.com/photo-1577896851231-70ef18881754?w=1000&q=80"
            ];
            
            let currentImgIndex = 0;
            let timelapseInterval;

            function startTimelapse() {
                slideshowBg.style.backgroundImage = `url(${images[0]})`;
                slideshowBg.classList.remove('opacity-0');
                
                timelapseInterval = setInterval(() => {
                    currentImgIndex = (currentImgIndex + 1) % images.length;
                    slideshowBg.style.backgroundImage = `url(${images[currentImgIndex]})`;
                }, 180); // Fast switch like a timelapse
            }

            // --- LOADING ASSETS WAITING ---
            let sapaanStarted = false;
            const maxWaitTime = 6000; // max wait 6 seconds to avoid infinite load

            function initSapaanSequence() {
                if (sapaanStarted) return;
                sapaanStarted = true;

                // 1. Hide Loading Spinner
                loadingState.style.opacity = '0';
                
                setTimeout(() => {
                    loadingState.style.display = 'none';
                    
                    // 2. Show Greeting Elements & Start Timelapse
                    mainGreeting.classList.remove('opacity-0');
                    startTimelapse();

                    // 3. Start Typewriter
                    setTimeout(typeWelcome, 500);
                }, 800);
            }

            // Preload all timelapse images
            const preloadPromises = images.map(src => {
                return new Promise(resolve => {
                    const img = new Image();
                    img.onload = resolve;
                    img.onerror = resolve; // Resolve even on error to not block
                    img.src = src;
                });
            });

            // Wait for both document load AND our images
            Promise.all([
                ...preloadPromises,
                new Promise(resolve => {
                    if (document.readyState === 'complete') resolve();
                    else window.addEventListener('load', resolve);
                })
            ]).then(() => {
                initSapaanSequence();
            });

            // Fallback timeout in case something hangs
            setTimeout(initSapaanSequence, maxWaitTime);


            // --- CORE ANIMATIONS ---
            const welcomeStr = "SELAMAT DATANG";
            let charIndex = 0;

            function typeWelcome() {
                if (charIndex < welcomeStr.length) {
                    typewriter.textContent += welcomeStr.charAt(charIndex);
                    charIndex++;
                    setTimeout(typeWelcome, 80);
                } else {
                    const cursor = document.querySelector('.animate-cursor');
                    if (cursor) cursor.style.display = 'none';
                    
                    setTimeout(revealCoreContent, 300);
                }
            }

            function revealCoreContent() {
                if (title) {
                    title.classList.remove('opacity-0', 'translate-y-8');
                    title.classList.add('opacity-100', 'translate-y-0');
                }

                setTimeout(() => {
                    if (tagline) {
                        tagline.classList.remove('opacity-0', 'translate-y-6');
                        tagline.classList.add('opacity-100', 'translate-y-0');
                    }
                    setTimeout(startWordSwap, 300);
                }, 500);
            }

            const qualities = ["Unggul", "Cerdas", "Berprestasi", "Berakhlak Mulia", "Modern"];
            let qualityIndex = 1;

            function startWordSwap() {
                if (qualityIndex < qualities.length) {
                    setTimeout(() => {
                        swapWord.className = "absolute left-0 text-blue-700 font-bold transition-all duration-300 ease-in transform -translate-y-8 opacity-0 text-lg sm:text-2xl drop-shadow-sm";
                        
                        setTimeout(() => {
                            swapWord.innerText = qualities[qualityIndex];
                            swapWord.className = "absolute left-0 text-blue-700 font-bold transform translate-y-8 opacity-0 text-lg sm:text-2xl drop-shadow-sm";
                            
                            swapWord.offsetHeight; // reflow

                            swapWord.className = "absolute left-0 text-blue-700 font-bold transition-all duration-500 ease-[cubic-bezier(0.175,0.885,0.32,1.275)] transform translate-y-0 opacity-100 text-lg sm:text-2xl drop-shadow-sm";
                            
                            qualityIndex++;
                            startWordSwap();
                        }, 300);
                    }, 800); // Wait time before next swap
                } else {
                    // Ceremony Concludes
                    setTimeout(liftCurtain, 1200);
                }
            }

            function liftCurtain() {
                sessionStorage.setItem('hasSeenSapaan', 'true');
                
                // Clear the timelapse interval
                clearInterval(timelapseInterval);

                // Add elegant lift classes
                curtain.classList.add('curtain-lift');
                sapaanContent.classList.add('content-lift');
                
                body.classList.remove('sapaan-active');
                
                setTimeout(() => {
                    curtain.remove();
                }, 1600);
            }

        } else {
            // Already seen
            curtain.remove();
        }
    })();
</script>
