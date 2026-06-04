{{-- Premium Professional Page Preloader & Smooth Transition Loader (Bright Theme) --}}

<!-- Top Loading Progress Bar: Ultra-slim high-end line -->
<div id="topProgressBar"
    class="fixed top-0 left-0 right-0 h-[2px] bg-linear-to-r from-blue-900 via-amber-500 to-blue-900 z-9999 w-0 transition-all duration-300 ease-out pointer-events-none">
</div>

<!-- Fullscreen Loader Overlay: Bright & Crisp Academic Theme with soft light radial glow -->
<div id="pageLoader"
    class="fixed inset-0 bg-slate-50 z-9999 flex flex-col items-center justify-center pointer-events-auto transition-opacity duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">

    <!-- Soft Ambient Light Radial Glow in the center -->
    <div
        class="absolute w-[500px] h-[500px] bg-[radial-linear(circle_at_center,rgba(30,58,138,0.04),transparent_65%)] pointer-events-none">
    </div>

    <!-- Central Content Wrapper -->
    <div class="relative flex flex-col items-center select-none">

        <!-- Animated Concentric Box Ripples (NO SPINNING, ONLY SMOOTH SCALING) -->
        <div class="relative w-24 h-24 flex items-center justify-center">

            <!-- Soft Expanding Aura Glow -->
            <div class="absolute w-16 h-16 bg-blue-50/50 rounded-none animate-breath"></div>

            <!-- Outer Geometric Box Ripple 1 -->
            <div
                class="absolute w-16 h-16 border border-blue-900/10 rounded-none animate-ripple-1 will-change-transform">
            </div>

            <!-- Middle Geometric Box Ripple 2 -->
            <div
                class="absolute w-16 h-16 border border-amber-500/20 rounded-none animate-ripple-2 will-change-transform">
            </div>

            <!-- Logo Floating elegantly in center -->
            <img src="{{ asset('assets/img/logo.png') }}"
                class="w-10 h-10 object-contain relative z-10 animate-float will-change-transform" alt="Logo Loader">
        </div>

        <!-- Text & Loading Indicator -->
        <div class="mt-6 flex flex-col items-center">
            <!-- Title -->
            <h3 class="font-bold text-blue-950 text-[10px] uppercase tracking-[0.25em] leading-none mb-3">
                MAM Limpung
            </h3>

            <!-- Sleek micro progress dot-bar -->
            <div class="flex items-center space-x-1.5 h-1">
                <span class="w-1 h-1 bg-blue-900 animate-dot-1"></span>
                <span class="w-1 h-1 bg-amber-500 animate-dot-2"></span>
                <span class="w-1 h-1 bg-blue-900 animate-dot-3"></span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Smooth Easing & Keyframe Animations */

    /* Aura breathing */
    @keyframes aura-breath {

        0%,
        100% {
            transform: scale(0.85);
            opacity: 0.4;
        }

        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }

    .animate-breath {
        animation: aura-breath 2.5s ease-in-out infinite;
    }

    /* Floating center logo */
    @keyframes logo-float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-4px);
        }
    }

    .animate-float {
        animation: logo-float 3s ease-in-out infinite;
    }

    /* Smooth Boxy Ripples */
    @keyframes box-ripple {
        0% {
            transform: scale(0.7);
            opacity: 0.8;
        }

        100% {
            transform: scale(1.3);
            opacity: 0;
        }
    }

    .animate-ripple-1 {
        animation: box-ripple 2s cubic-bezier(0.16, 1, 0.3, 1) infinite;
    }

    .animate-ripple-2 {
        animation: box-ripple 2s cubic-bezier(0.16, 1, 0.3, 1) infinite 0.7s;
    }

    /* Sleek indicator dots pulsing */
    @keyframes dot-pulse {

        0%,
        100% {
            transform: scale(0.7);
            opacity: 0.3;
        }

        50% {
            transform: scale(1.2);
            opacity: 1;
        }
    }

    .animate-dot-1 {
        animation: dot-pulse 1s ease-in-out infinite;
    }

    .animate-dot-2 {
        animation: dot-pulse 1s ease-in-out infinite 0.2s;
    }

    .animate-dot-3 {
        animation: dot-pulse 1s ease-in-out infinite 0.4s;
    }

    /* Prevent body scroll during load */
    body.is-loading {
        overflow: hidden !important;
        height: 100vh !important;
    }
</style>

<script>
    (function() {
        const loader = document.getElementById('pageLoader');
        const progress = document.getElementById('topProgressBar');
        const body = document.body;

        // 1. Activate Loading State
        body.classList.add('is-loading');
        if (progress) {
            progress.style.width = '25%';
        }

        // 2. Hide Loader on Page Load Complete
        window.addEventListener('load', () => {
            if (progress) progress.style.width = '100%';
            setTimeout(completeLoader, 200);
        });

        // Backup timeout
        if (document.readyState === 'complete') {
            if (progress) progress.style.width = '100%';
            setTimeout(completeLoader, 200);
        }

        function completeLoader() {
            if (loader) {
                loader.style.opacity = '0';
                loader.style.pointerEvents = 'none';
            }
            if (progress) {
                progress.style.opacity = '0';
            }
            body.classList.remove('is-loading');

            setTimeout(() => {
                if (loader) loader.style.display = 'none';
                if (progress) progress.style.display = 'none';
            }, 500);
        }

        // 3. Navigation Click Transition Handlers
        document.addEventListener('click', (event) => {
            const anchor = event.target.closest('a');
            if (!anchor) return;

            const href = anchor.getAttribute('href');
            const target = anchor.getAttribute('target');

            if (
                !href ||
                href.startsWith('#') ||
                href.startsWith('javascript:') ||
                href.startsWith('tel:') ||
                href.startsWith('mailto:') ||
                target === '_blank' ||
                anchor.hasAttribute('download') ||
                event.ctrlKey ||
                event.shiftKey ||
                event.metaKey ||
                event.button !== 0
            ) {
                return;
            }

            const url = new URL(anchor.href, window.location.href);
            if (url.origin === window.location.origin) {
                if (loader) {
                    loader.style.display = 'flex';
                    loader.offsetHeight; // Reflow
                    loader.style.opacity = '1';
                    loader.style.pointerEvents = 'auto';
                }
                if (progress) {
                    progress.style.display = 'block';
                    progress.style.opacity = '1';
                    progress.style.width = '0%';
                    progress.offsetHeight; // Reflow
                    progress.style.width = '80%';
                }
                body.classList.add('is-loading');
            }
        });

        // bfcache recovery
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                if (loader) {
                    loader.style.display = 'none';
                    loader.style.opacity = '0';
                }
                if (progress) {
                    progress.style.display = 'none';
                    progress.style.opacity = '0';
                }
                body.classList.remove('is-loading');
            }
        });
    })();
</script>
