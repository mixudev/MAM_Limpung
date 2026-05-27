    <style>
        
        .font-inter { font-family: 'Inter', sans-serif; }

        /* Manual GPU-Accelerated Animate on Scroll Styles */
        .fade-up-init {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .fade-left-init {
            opacity: 0;
            transform: translateX(-20px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .fade-right-init {
            opacity: 0;
            transform: translateX(20px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .zoom-in-init {
            opacity: 0;
            transform: scale(0.96);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .animate-show {
            opacity: 1 !important;
            transform: none !important;
        }
    </style>