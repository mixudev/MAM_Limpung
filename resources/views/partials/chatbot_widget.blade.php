@php
    $waClean = preg_replace('/[^0-9]/', '', $siteSettings->whatsapp ?? '');
    if (str_starts_with($waClean, '08')) {
        $waClean = '628' . substr($waClean, 2);
    }
    if (empty($waClean)) {
        $waClean = '628123456789';
    }
    $waUrl = "https://wa.me/{$waClean}?text=" . urlencode('Halo Admin, saya ingin bertanya tentang sekolah...');
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
                let ids = JSON.parse(stored);
                if (ids.length > 3) {
                    ids = ids.slice(0, 3);
                    localStorage.setItem('school_chatbot_sessions', JSON.stringify(ids));
                }
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
            let data = await res.json();
            // Prune sessions to max 3
            this.sessions = data.slice(0, 3);
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
            
            // Limit session history list to max 3
            if (this.sessions.length > 3) {
                this.sessions = this.sessions.slice(0, 3);
            }
            
            const ids = this.sessions.map(s => s.id);
            localStorage.setItem('school_chatbot_sessions', JSON.stringify(ids));
        } catch (e) { console.error(e); } finally { this.isInitialLoading = false; }
        await this.loadFaqs();
    },

    async loadSession(session) {
        this.activeSession = session;
        this.activeTopic = session.topic;
        this.messages = (session.messages || []).map(m => {
            return {
                ...m,
                liked: false,
                disliked: false
            };
        });
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
            
            // Add custom local properties for interactive thumbs up/down
            const botMsg = {
                ...data.bot_message,
                liked: false,
                disliked: false
            };
            this.messages.push(botMsg);
            
            const idx = this.sessions.findIndex(s => s.id === this.activeSession.id);
            if (idx !== -1) {
                if (!this.sessions[idx].messages) this.sessions[idx].messages = [];
                this.sessions[idx].messages.push(userMessage, botMsg);
            }
        } catch (e) {
            this.messages.push({
                id: Date.now() + 1,
                sender: 'bot',
                message: 'Maaf, asisten AI kami sedang tidak merespons. Silakan hubungi kami via WhatsApp.',
                liked: false,
                disliked: false
            });
        } finally {
            this.isTyping = false;
            this.$nextTick(() => this.scrollToBottom());
        }
    },

    async clickFaq(faq) {
        if (!this.activeSession) await this.startNewChat(faq.topic);
        await this.sendMessage(faq.question);
    },

    async submitFeedback(type, msg = null) {
        if (msg) {
            if (type === 'like') {
                msg.liked = true;
                msg.disliked = false;
            } else {
                msg.disliked = true;
                msg.liked = false;
            }
        }
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

    {{-- TRIGGER BUTTON --}}
    @include('partials.chatbot.trigger')

    {{-- CHAT WINDOW --}}
    <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-6 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-6 scale-95"
        class="fixed z-50 inset-0 sm:inset-auto sm:bottom-5 sm:right-5 sm:w-[420px] sm:h-[680px] sm:max-h-[90vh]
                bg-white rounded-none sm:rounded-2xl shadow-2xl shadow-indigo-100/60 border border-gray-100
                flex flex-col overflow-hidden"
        style="display: none;">

        {{-- CHAT HEADER --}}
        @include('partials.chatbot.header')

        {{-- CHAT WINDOW BODY --}}
        <div class="flex-1 flex overflow-hidden min-h-0">

            {{-- SIDEBAR HISTORY --}}
            @include('partials.chatbot.sidebar')

            {{-- MAIN CHAT AREA --}}
            <div class="flex-1 flex flex-col min-w-0 min-h-0 bg-white">
                {{-- Sidebar backdrop overlay --}}
                <div x-show="showSidebar" @click="showSidebar = false" class="absolute inset-0 z-[5] bg-black/20"></div>

                {{-- MESSAGES --}}
                @include('partials.chatbot.messages')

                {{-- INPUT FORM --}}
                @include('partials.chatbot.input')
            </div>

        </div>
    </div>
</div>
