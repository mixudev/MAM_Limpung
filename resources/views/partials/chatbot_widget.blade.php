@php
    $waClean = preg_replace('/[^0-9]/', '', $siteSettings->whatsapp ?? '');
    if (str_starts_with($waClean, '08')) {
        $waClean = '628' . substr($waClean, 2);
    }
    if (empty($waClean)) {
        $waClean = '628123456789';
    }
    $waUrl = "https://wa.me/{$waClean}?text=" . urlencode("Halo Admin, saya ingin bertanya tentang sekolah...");
@endphp

<div x-data="{
    isOpen: false,
    showSidebar: false,
    activeSession: null,
    sessions: [],
    faqs: [],
    messages: [],
    activeTopic: 'umum',
    inputValue: '',
    isTyping: false,
    isInitialLoading: false,
    unreadCount: 0,

    async init() {
        const stored = localStorage.getItem('school_chatbot_sessions');
        if (stored) {
            try {
                const ids = JSON.parse(stored);
                if (ids.length > 0) {
                    await this.loadHistory(ids);
                }
            } catch (e) {
                console.error('Failed to parse chatbot history', e);
            }
        }
        await this.loadFaqs();
    },

    async loadHistory(sessionIds) {
        try {
            const res = await fetch('{{ route('frontend.chatbot.history') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ session_ids: sessionIds })
            });
            this.sessions = await res.json();
        } catch (e) { console.error(e); }
    },

    async loadFaqs() {
        try {
            const res = await fetch('{{ route('frontend.chatbot.faqs') }}?topic=' + this.activeTopic);
            this.faqs = await res.json();
        } catch (e) { console.error(e); }
    },

    async selectTopic(topic) {
        this.activeTopic = topic;
        this.activeSession = null;
        this.messages = [];
        this.showSidebar = false;
        await this.loadFaqs();
    },

    async startNewChat(topic = null) {
        if (topic) this.activeTopic = topic;
        this.isInitialLoading = true;
        try {
            const res = await fetch('{{ route('frontend.chatbot.sessions.start') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ topic: this.activeTopic })
            });
            const session = await res.json();
            this.activeSession = session;
            this.messages = [];
            this.sessions.unshift(session);
            const ids = this.sessions.map(s => s.id);
            localStorage.setItem('school_chatbot_sessions', JSON.stringify(ids));
        } catch (e) { console.error(e); } finally { this.isInitialLoading = false; }
        await this.loadFaqs();
    },

    async loadSession(session) {
        this.activeSession = session;
        this.activeTopic = session.topic;
        this.messages = session.messages || [];
        this.showSidebar = false;
        this.$nextTick(() => this.scrollToBottom());
        await this.loadFaqs();
    },

    async sendMessage(text = null) {
        const messageText = text || this.inputValue;
        if (!messageText.trim()) return;
        if (!this.activeSession) await this.startNewChat();
        const userMessage = { id: Date.now(), sender: 'user', message: messageText };
        this.messages.push(userMessage);
        if (!text) this.inputValue = '';
        this.$nextTick(() => this.scrollToBottom());
        this.isTyping = true;
        try {
            const res = await fetch(`/chatbot/sessions/${this.activeSession.id}/send`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ message: messageText })
            });
            const data = await res.json();
            this.messages.push(data.bot_message);
            const idx = this.sessions.findIndex(s => s.id === this.activeSession.id);
            if (idx !== -1) {
                if (!this.sessions[idx].messages) this.sessions[idx].messages = [];
                this.sessions[idx].messages.push(userMessage, data.bot_message);
            }
        } catch (e) {
            this.messages.push({ id: Date.now() + 1, sender: 'bot', message: 'Maaf, asisten AI kami sedang tidak merespons. Silakan coba kembali atau hubungi kami via WhatsApp.' });
        } finally {
            this.isTyping = false;
            this.$nextTick(() => this.scrollToBottom());
        }
    },

    async clickFaq(faq) {
        if (!this.activeSession) await this.startNewChat(faq.topic);
        await this.sendMessage(faq.question);
    },

    async submitFeedback(type) {
        if (!this.activeSession) return;
        try {
            await fetch(`/chatbot/sessions/${this.activeSession.id}/feedback`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ feedback: type })
            });
        } catch (e) { console.error(e); }
    },

    scrollToBottom() {
        const el = this.$refs.chatContainer;
        if (el) el.scrollTop = el.scrollHeight;
    },

    openChat() {
        this.isOpen = true;
        this.unreadCount = 0;
    }
}" x-init="init()" class="relative" @keydown.escape.window="isOpen = false">

    {{-- ═══════════════════════════════════════════
         TRIGGER BUTTON — Floating Action Button
    ═══════════════════════════════════════════ --}}
    <div x-show="!isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
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

    {{-- ═══════════════════════════════════════════
         CHAT WINDOW
    ═══════════════════════════════════════════ --}}
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-6 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-6 scale-95"
         class="fixed z-50 inset-0 sm:inset-auto sm:bottom-5 sm:right-5 sm:w-[420px] sm:h-[680px] sm:max-h-[90vh]
                bg-white rounded-none sm:rounded-2xl shadow-2xl shadow-indigo-100/60 border border-gray-100
                flex flex-col overflow-hidden"
         style="display: none;">

        {{-- ─── HEADER ─── --}}
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

        {{-- ─── BODY (Sidebar + Chat) ─── --}}
        <div class="flex-1 flex overflow-hidden min-h-0">

            {{-- ── SIDEBAR HISTORY (hidden by default, toggled via header button) ── --}}
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

                {{-- Topic Filters --}}
                <div class="px-3 pt-3 pb-1">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Topik</p>
                    <div class="grid grid-cols-2 gap-1.5">
                        @foreach([['umum','circle-nodes','indigo'],['ppdb','id-card-clip','sky'],['kegiatan','calendar-check','amber'],['bantuan','circle-question','emerald']] as [$t,$ic,$cl])
                        <button @click="selectTopic('{{ $t }}')"
                            :class="activeTopic === '{{ $t }}' ? 'bg-indigo-50 border-indigo-200 text-indigo-700 font-bold' : 'bg-white border-gray-200 text-gray-500 hover:border-indigo-200 hover:text-indigo-600'"
                            class="p-2 rounded-xl border text-center text-[10px] font-semibold transition-all cursor-pointer flex flex-col items-center gap-1">
                            <i class="fa-solid fa-{{ $ic }} text-sm text-{{ $cl }}-500"></i>
                            {{ ucfirst($t) }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Sessions List --}}
                <div class="flex-1 overflow-y-auto px-3 py-2 space-y-1 min-h-0">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 mt-1">Riwayat</p>
                    <template x-for="sess in sessions" :key="sess.id">
                        <button @click="loadSession(sess)"
                            :class="activeSession && activeSession.id === sess.id
                                ? 'bg-indigo-50 border-indigo-200 text-indigo-800'
                                : 'bg-white border-gray-100 text-gray-600 hover:bg-gray-50 hover:border-gray-200'"
                            class="w-full p-2.5 rounded-xl border text-left text-[11px] transition-all cursor-pointer">
                            <div class="font-semibold capitalize truncate flex items-center gap-1.5">
                                <i class="fa-solid fa-comment-dots text-indigo-400 text-[10px]"></i>
                                <span x-text="sess.topic"></span>
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

            {{-- ── MAIN CHAT AREA ── --}}
            <div class="flex-1 flex flex-col min-w-0 min-h-0 bg-white">

                {{-- Backdrop to close sidebar --}}
                <div x-show="showSidebar" @click="showSidebar = false"
                    class="absolute inset-0 z-[5] bg-black/20"></div>

                {{-- ── MESSAGES AREA ── --}}
                <div x-ref="chatContainer"
                    class="flex-1 overflow-y-auto px-3 py-4 space-y-4 min-h-0 scroll-smooth"
                    style="background: linear-gradient(180deg, #f8faff 0%, #ffffff 100%)">

                    {{-- WELCOME SCREEN --}}
                    <template x-if="!activeSession && messages.length === 0">
                        <div class="space-y-4">
                            {{-- Bot greeting bubble --}}
                            <div class="flex items-start gap-2">
                                <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-full flex items-center justify-center text-white text-xs shrink-0 mt-0.5">
                                    <i class="fa-solid fa-robot text-[10px]"></i>
                                </div>
                                <div class="bg-white rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm border border-gray-100 max-w-[88%]">
                                    <p class="text-sm text-gray-700 font-medium leading-relaxed">Halo! 👋 Saya asisten AI <strong>MAM Limpung</strong>.</p>
                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Pilih topik di bawah atau langsung ketik pertanyaan Anda.</p>
                                    <div class="mt-1 text-[10px] text-gray-400">Sekarang · Online</div>
                                </div>
                            </div>

                            {{-- Topic chips --}}
                            <div class="flex flex-wrap gap-2 ml-9">
                                @foreach([['umum','circle-nodes','indigo'],['ppdb','id-card-clip','sky'],['kegiatan','calendar-check','amber'],['bantuan','circle-question','emerald']] as [$t,$ic,$cl])
                                <button @click="selectTopic('{{ $t }}')"
                                    :class="activeTopic === '{{ $t }}' ? 'bg-indigo-100 text-indigo-700 border-indigo-300 font-bold' : 'bg-white text-gray-600 border-gray-200'"
                                    class="px-3 py-1.5 rounded-full border text-xs transition-all cursor-pointer flex items-center gap-1.5">
                                    <i class="fa-solid fa-{{ $ic }} text-{{ $cl }}-500 text-[10px]"></i>
                                    {{ ucfirst($t) }}
                                </button>
                                @endforeach
                            </div>

                            {{-- FAQ quick replies --}}
                            <template x-if="faqs.length > 0">
                                <div class="ml-9 space-y-1.5">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pertanyaan Populer</p>
                                    <template x-for="faq in faqs" :key="faq.id">
                                        <button @click="clickFaq(faq)"
                                            class="w-full text-left px-3.5 py-2.5 bg-white hover:bg-indigo-50 border border-gray-200 hover:border-indigo-200 rounded-xl text-xs text-gray-700 hover:text-indigo-700 transition-all cursor-pointer flex items-center justify-between group shadow-xs">
                                            <span x-text="faq.question" class="pr-2 leading-relaxed"></span>
                                            <i class="fa-solid fa-arrow-right text-[9px] text-gray-300 group-hover:text-indigo-400 group-hover:translate-x-0.5 transition-all shrink-0"></i>
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- CHAT MESSAGES --}}
                    <template x-if="activeSession || messages.length > 0">
                        <div class="space-y-3">
                            {{-- Context info strip --}}
                            <div class="flex items-center justify-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 border border-indigo-100 rounded-full text-[10px] text-indigo-600 font-medium">
                                    <i class="fa-solid fa-lock text-[9px]"></i>
                                    Percakapan terenkripsi · Topik: <span class="capitalize font-bold" x-text="activeTopic"></span>
                                </span>
                            </div>

                            <template x-for="(msg, index) in messages" :key="msg.id">
                                <div :class="msg.sender === 'user' ? 'justify-end' : 'justify-start'" class="flex items-end gap-2">

                                    {{-- Bot Avatar --}}
                                    <template x-if="msg.sender === 'bot'">
                                        <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-full flex items-center justify-center text-white text-[10px] shrink-0 mb-0.5">
                                            <i class="fa-solid fa-robot"></i>
                                        </div>
                                    </template>

                                    <div class="max-w-[80%] space-y-1">
                                        {{-- Bubble --}}
                                        <div :class="msg.sender === 'user'
                                                ? 'bg-indigo-600 text-white rounded-2xl rounded-br-sm'
                                                : 'bg-white text-gray-800 border border-gray-100 rounded-2xl rounded-tl-sm shadow-sm'"
                                            class="px-4 py-2.5 text-sm leading-relaxed">
                                            <p x-text="msg.message" class="whitespace-pre-wrap"></p>
                                        </div>

                                        {{-- Feedback row (last bot message) --}}
                                        <template x-if="msg.sender === 'bot' && index === messages.length - 1">
                                            <div class="flex items-center gap-2 px-1">
                                                <span class="text-[10px] text-gray-400">Membantu?</span>
                                                <button @click="submitFeedback('like')"
                                                    class="text-[11px] text-gray-400 hover:text-emerald-500 transition-colors cursor-pointer p-0.5 rounded">
                                                    <i class="fa-regular fa-thumbs-up"></i>
                                                </button>
                                                <button @click="submitFeedback('dislike')"
                                                    class="text-[11px] text-gray-400 hover:text-red-500 transition-colors cursor-pointer p-0.5 rounded">
                                                    <i class="fa-regular fa-thumbs-down"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    {{-- User Avatar --}}
                                    <template x-if="msg.sender === 'user'">
                                        <div class="w-7 h-7 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-[10px] shrink-0 mb-0.5">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            {{-- Typing indicator --}}
                            <div x-show="isTyping" class="flex items-end gap-2">
                                <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-full flex items-center justify-center text-white text-[10px] shrink-0">
                                    <i class="fa-solid fa-robot"></i>
                                </div>
                                <div class="bg-white border border-gray-100 rounded-2xl rounded-tl-sm shadow-sm px-4 py-3 flex items-center gap-1.5">
                                    <span class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay:0s"></span>
                                    <span class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay:0.15s"></span>
                                    <span class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay:0.30s"></span>
                                </div>
                            </div>

                            {{-- FAQ suggestions after conversation --}}
                            <template x-if="!isTyping && messages.length > 0 && messages[messages.length - 1].sender === 'bot'">
                                <div class="ml-9 space-y-1.5 pt-1">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Lanjutkan dengan:</p>
                                    <template x-for="faq in faqs.slice(0, 3)" :key="'suggest-' + faq.id">
                                        <button @click="clickFaq(faq)"
                                            class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-indigo-50 border border-gray-200 hover:border-indigo-200 rounded-xl text-[11px] text-gray-600 hover:text-indigo-700 transition-all cursor-pointer flex items-center justify-between group">
                                            <span x-text="faq.question" class="pr-2 truncate"></span>
                                            <i class="fa-solid fa-arrow-right text-[9px] text-gray-300 group-hover:text-indigo-400 shrink-0"></i>
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- ── INPUT AREA ── --}}
                <div class="border-t border-gray-100 bg-white px-3 pt-2.5 pb-3 shrink-0">
                    <form @submit.prevent="sendMessage()" class="flex items-end gap-2">
                        <div class="flex-1 relative">
                            <input type="text"
                                x-model="inputValue"
                                @keydown.enter.prevent="sendMessage()"
                                :placeholder="isInitialLoading ? 'Menghubungkan...' : 'Ketik pesan Anda...'"
                                :disabled="isInitialLoading"
                                maxlength="500"
                                class="w-full bg-gray-50 border border-gray-200 focus:border-indigo-400 focus:bg-white text-gray-800 text-sm placeholder-gray-400 rounded-2xl px-4 py-3 pr-12 outline-none transition-all resize-none leading-relaxed" />
                            {{-- Character hint --}}
                            <span x-show="inputValue.length > 400"
                                x-text="500 - inputValue.length"
                                class="absolute right-3 bottom-2.5 text-[10px] text-gray-400"></span>
                        </div>
                        <button type="submit"
                            :disabled="!inputValue.trim() || isInitialLoading"
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

            </div>{{-- end main chat area --}}
        </div>{{-- end body --}}
    </div>{{-- end chat window --}}

</div>
