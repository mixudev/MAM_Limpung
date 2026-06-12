{{-- TRIGGER BUTTON — Floating Action Button --}}
<div x-show="!isOpen" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
    class="fixed bottom-5 right-5 z-50 flex flex-col items-end gap-2">

    {{-- Tooltip / Greeting Bubble --}}
    <div class="bg-white text-gray-700 text-xs font-semibold px-3.5 py-2 rounded-2xl rounded-br-sm shadow-lg border border-gray-100 max-w-[160px] text-center leading-snug animate-pulse-slow">
        Ada yang bisa dibantu? 👋
    </div>

    <button @click="openChat()"
        class="relative w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-700 text-white rounded-full shadow-xl hover:shadow-indigo-300/50 hover:scale-110 active:scale-95 transition-all duration-300 flex items-center justify-center cursor-pointer group"
        title="Buka AI Chatbot">
        <i class="fa-solid fa-robot text-xl group-hover:rotate-6 transition-transform duration-200"></i>
        {{-- Online indicator --}}
        <span class="absolute -top-0.5 -right-0.5 flex h-4 w-4">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white"></span>
        </span>
        {{-- Unread badge --}}
        <span x-show="unreadCount > 0" x-text="unreadCount"
            class="absolute -top-1.5 -left-1.5 min-w-[20px] h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1 shadow"></span>
    </button>
</div>
