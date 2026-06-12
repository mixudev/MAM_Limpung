{{-- ═══ CHATBOT ADMIN MAIN HEADER WITH TOGGLE SWITCH ═══ --}}
<div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-robot text-[#4f45b2]"></i> AI Chatbot
            <span class="text-slate-300 dark:text-zinc-600 font-light">·</span>
            <span class="text-lg text-[#4f45b2]">{{ $title }}</span>
        </h1>
        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">{{ $subtitle }}</p>
    </div>
    
    <div class="flex items-center gap-3 shrink-0 self-start sm:self-auto bg-slate-50 dark:bg-zinc-800/40 border border-slate-150 dark:border-zinc-800 px-4 py-2 rounded-lg">
        <div class="flex flex-col">
            <span class="text-[9px] font-mono text-slate-400 dark:text-zinc-500 uppercase tracking-wider font-bold">Status Chatbot</span>
            <span class="text-xs font-mono font-bold uppercase tracking-wider {{ ($siteSettings->is_chatbot_active ?? true) ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-zinc-500' }}">
                {{ ($siteSettings->is_chatbot_active ?? true) ? 'AKTIF (ON)' : 'MATI (OFF)' }}
            </span>
        </div>
        <form action="{{ route('admin.chatbot.toggle') }}" method="POST" class="inline-flex items-center">
            @csrf
            @method('PUT')
            <button type="submit" 
                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ ($siteSettings->is_chatbot_active ?? true) ? 'bg-[#4f45b2]' : 'bg-slate-200 dark:bg-zinc-700' }}" 
                role="switch" 
                aria-checked="{{ ($siteSettings->is_chatbot_active ?? true) ? 'true' : 'false' }}">
                <span aria-hidden="true" 
                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ ($siteSettings->is_chatbot_active ?? true) ? 'translate-x-5' : 'translate-x-0' }}"></span>
            </button>
        </form>
    </div>
</div>
