{{-- INPUT AREA --}}
<div class="border-t border-gray-100 bg-white px-3 pt-2.5 pb-3 shrink-0">
    <form @submit.prevent="sendMessage()" class="flex items-end gap-2">
        <div class="flex-1 relative">
            <input type="text" x-model="inputValue" @keydown.enter.prevent="sendMessage()"
                :placeholder="isInitialLoading ? 'Menghubungkan...' : 'Ketik pesan Anda...'"
                :disabled="isInitialLoading" maxLength="500"
                class="w-full bg-gray-50 border border-gray-200 focus:border-indigo-400 focus:bg-white text-gray-800 text-sm placeholder-gray-400 rounded-2xl px-4 py-3 pr-12 outline-none transition-all resize-none leading-relaxed" />
            {{-- Character hint --}}
            <span x-show="inputValue.length > 400" x-text="500 - inputValue.length"
                class="absolute right-3 bottom-2.5 text-[10px] text-gray-400"></span>
        </div>
        <button type="submit" :disabled="!inputValue.trim() || isInitialLoading"
            class="w-11 h-11 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-200 disabled:text-gray-400 text-white rounded-2xl flex items-center justify-center shadow-sm shadow-indigo-200 active:scale-95 transition-all cursor-pointer shrink-0">
            <i class="fa-solid fa-paper-plane text-sm"></i>
        </button>
    </form>

    {{-- Quick action chips --}}
    <div class="flex items-center gap-2 mt-2 flex-wrap">
        <button @click="activeSession = null; messages = []; loadFaqs()"
            class="text-[10px] text-gray-500 hover:text-indigo-600 bg-gray-100 hover:bg-indigo-50 border border-gray-200 hover:border-indigo-200 px-2.5 py-1 rounded-lg font-medium transition-all cursor-pointer flex items-center gap-1">
            <i class="fa-solid fa-house text-[9px]"></i> Beranda
        </button>
        <template x-if="activeSession">
            <button @click="startNewChat()"
                class="text-[10px] text-gray-500 hover:text-indigo-600 bg-gray-100 hover:bg-indigo-50 border border-gray-200 hover:border-indigo-200 px-2.5 py-1 rounded-lg font-medium transition-all cursor-pointer flex items-center gap-1">
                <i class="fa-solid fa-rotate text-[9px]"></i> Chat Baru
            </button>
        </template>
        <a href="{{ $waUrl }}" target="_blank"
            class="text-[10px] text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2.5 py-1 rounded-lg font-medium transition-all cursor-pointer flex items-center gap-1">
            <i class="fa-brands fa-whatsapp text-xs"></i> WhatsApp
        </a>
    </div>

    <p class="text-[9px] text-gray-400 text-center mt-2">
        Dijawab oleh AI · MAM Limpung
    </p>
</div>
