import Alpine from 'alpinejs';
import * as Turbo from '@hotwired/turbo';
import AOS from 'aos';
import 'aos/dist/aos.css';

// Make Alpine available globally (needed for inline x-data in blade files)
window.Alpine = Alpine;

// Start Alpine
Alpine.start();

// AOS — reinitialize on Turbo navigations so animations replay after page transitions
function initAOS() {
    AOS.init({
        duration: 800,
        offset: 100,
        once: true,
        easing: 'ease-in-out-sine',
        anchorPlacement: 'top-bottom',
    });
}

document.addEventListener('turbo:load', initAOS);
document.addEventListener('DOMContentLoaded', initAOS);
