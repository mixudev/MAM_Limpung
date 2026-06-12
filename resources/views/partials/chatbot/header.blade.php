{{-- CHAT HEADER --}}
<div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-3.5 flex items-center justify-between shrink-0">
    <div class="flex items-center gap-3">
        {{-- Avatar --}}
        <div class="relative">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white text-lg">
                <i class="fa-solid fa-robot"></i>
            </div>
            <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-indigo-600 rounded-full"></span>
        </div>
        <div>
            <div class="text-white font-bold text-sm leading-tight">Asisten AI Sekolah</div>
            <div class="text-indigo-200 text-[11px] flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                Online · MAM Limpung
            </div>
        </div>
    </div>

    <div class="flex items-center gap-1.5">
        {{-- History / Sidebar toggle --}}
        <button @click="showSidebar = !showSidebar"
            :class="showSidebar ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10'"
            class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors cursor-pointer"
            title="Riwayat &amp; Topik">
            <i class="fa-solid fa-clock-rotate-left text-sm"></i>
        </button>
        {{-- WA --}}
        <a href="{{ $waUrl }}" target="_blank"
            class="text-white/70 hover:text-white w-8 h-8 rounded-xl hover:bg-white/10 flex items-center justify-center transition-colors cursor-pointer"
            title="WhatsApp Admin">
            <i class="fa-brands fa-whatsapp text-base"></i>
        </a>
        {{-- Close --}}
        <button @click="isOpen = false"
            class="text-white/70 hover:text-white w-8 h-8 rounded-xl hover:bg-white/10 flex items-center justify-center transition-colors cursor-pointer"
            title="Tutup">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
    </div>
</div>
