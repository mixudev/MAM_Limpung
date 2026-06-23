{{-- SIDEBAR HISTORY --}}
<div :class="showSidebar ? 'translate-x-0' : '-translate-x-full'"
    class="absolute inset-y-0 left-0 z-10 w-64 bg-gray-50 border-r border-gray-100
            flex flex-col transition-transform duration-300 ease-in-out shrink-0">

    {{-- New Chat Button --}}
    <div class="p-3 border-b border-gray-100">
        <button @click="activeSession = null; messages = []; loadFaqs(); showSidebar = false"
            class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all cursor-pointer shadow-sm shadow-indigo-200">
            <i class="fa-solid fa-plus text-xs"></i> Chat Baru
        </button>
    </div>

    {{-- Sessions List --}}
    <div class="flex-1 overflow-y-auto px-3 py-2 space-y-1 min-h-0">
        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 mt-1">Riwayat (Maks. 3)</p>
        <template x-for="sess in sessions" :key="sess.id">
            <button @click="loadSession(sess)"
                :class="activeSession && activeSession.id === sess.id ?
                    'bg-indigo-50 border-indigo-200 text-indigo-800' :
                    'bg-white border-gray-100 text-gray-600 hover:bg-gray-50 hover:border-gray-200'"
                class="w-full p-2.5 rounded-xl border text-left text-[11px] transition-all cursor-pointer">
                <div class="font-semibold capitalize truncate flex items-center gap-1.5">
                    <i class="fa-solid fa-comment-dots text-indigo-400 text-[10px]"></i>
                    <span>Percakapan AI</span>
                </div>
                <div class="text-[9px] text-gray-400 mt-0.5"
                    x-text="new Date(sess.created_at).toLocaleDateString('id-ID', {day:'numeric',month:'short',hour:'2-digit',minute:'2-digit'})">
                </div>
            </button>
        </template>
        <template x-if="sessions.length === 0">
            <div class="py-6 text-center text-[11px] text-gray-400">Belum ada riwayat.</div>
        </template>
    </div>

    {{-- WA Footer --}}
    <div class="p-3 border-t border-gray-100">
        <a href="{{ $waUrl }}" target="_blank"
            class="w-full py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-[11px] font-bold flex items-center justify-center gap-2 transition-all cursor-pointer">
            <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp Admin
        </a>
    </div>
</div>
