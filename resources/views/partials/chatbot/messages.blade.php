{{-- MESSAGES AREA --}}
<div x-ref="chatContainer" class="flex-1 overflow-y-auto px-3 py-4 space-y-4 min-h-0 scroll-smooth"
    style="background: linear-gradient(180deg, #f8faff 0%, #ffffff 100%)">

    {{-- WELCOME SCREEN --}}
    <template x-if="!activeSession && messages.length === 0">
        <div class="space-y-4">
            {{-- Bot greeting bubble --}}
            <div class="flex items-start gap-2">
                <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-full flex items-center justify-center text-white text-xs shrink-0 mt-0.5">
                    <img src="{{ asset ('assets/img/chatbot.png') }}" alt="">
                </div>
                <div class="bg-white rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm border border-gray-100 max-w-[88%]">
                    <p class="text-sm text-gray-700 font-medium leading-relaxed">Halo! 👋 Saya asisten AI <strong>MAM Limpung</strong>.</p>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Silakan tanyakan apa saja mengenai sekolah kita atau pilih shortcut pertanyaan cepat di bawah ini.</p>
                    <div class="mt-1 text-[10px] text-gray-400">Sekarang · Online</div>
                </div>
            </div>

            {{-- FAQ quick replies (Configurable Recommendations from Dashboard) --}}
            <div class="ml-9 space-y-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pertanyaan Populer</p>
                <div class="space-y-1.5">
                    <template x-for="faq in faqs" :key="faq.id">
                        <button @click="clickFaq(faq)"
                            class="w-full text-left px-3.5 py-2.5 bg-white hover:bg-indigo-50 border border-gray-200 hover:border-indigo-200 rounded-xl text-xs text-gray-700 hover:text-indigo-700 transition-all cursor-pointer flex items-center justify-between group shadow-xs hover:scale-[1.01] active:scale-95 duration-200">
                            <span x-text="faq.question" class="pr-2 leading-relaxed"></span>
                            <i class="fa-solid fa-circle-chevron-right text-xs text-indigo-300 group-hover:text-indigo-600 transition-all shrink-0"></i>
                        </button>
                    </template>
                    <template x-if="faqs.length === 0">
                        <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl text-center text-xs text-gray-400 font-mono">
                            Belum ada rekomendasi untuk topik ini. Silakan ketik langsung pertanyaan Anda.
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>

    {{-- CHAT MESSAGES --}}
    <template x-if="activeSession || messages.length > 0">
        <div class="space-y-3">
            {{-- Context info strip --}}
            <div class="flex items-center justify-center">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 border border-indigo-100 rounded-full text-[10px] text-indigo-600 font-medium shadow-2xs">
                    <i class="fa-solid fa-lock text-[9px]"></i>
                    Percakapan Aktif dengan AI Sekolah
                </span>
            </div>

            <template x-for="(msg, index) in messages" :key="msg.id">
                <div :class="msg.sender === 'user' ? 'justify-end' : 'justify-start'" class="flex items-end gap-2">

                    {{-- Bot Avatar --}}
                    <template x-if="msg.sender === 'bot'">
                        <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-full flex items-center justify-center text-white text-[10px] shrink-0 mb-0.5">
                            <img src="{{ asset ('assets/img/chatbot.png') }}" alt="">
                        </div>
                    </template>

                    <div class="max-w-[80%] space-y-1">
                        {{-- Bubble --}}
                        <div :class="msg.sender === 'user' ?
                            'bg-indigo-600 text-white rounded-2xl rounded-br-sm' :
                            'bg-white text-gray-800 border border-gray-100 rounded-2xl rounded-bl-xs shadow-sm'"
                            class="px-4 py-2.5 text-sm leading-relaxed"
                            x-data="{ parsed: parseMessageButtons(msg.message) }">
                            <p x-text="parsed.text" class="whitespace-pre-wrap"></p>

                            {{-- Render dynamic links/buttons --}}
                            <template x-if="msg.sender === 'bot' && parsed.buttons.length > 0">
                                <div class="mt-3 flex flex-col gap-2">
                                    <template x-for="btn in parsed.buttons">
                                        <a :href="btn.url" target="_blank"
                                            class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-100 hover:scale-[1.02] active:scale-95 cursor-pointer w-full text-center">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                            <span x-text="btn.label"></span>
                                        </a>
                                    </template>
                                </div>
                            </template>

                            {{-- Render physical WhatsApp button if message includes WhatsApp advice and sender is bot --}}
                            <!-- <template x-if="msg.sender === 'bot' && !msg.disliked && (msg.message.includes('WhatsApp') || msg.message.includes('WhatsApp Admin') || msg.message.includes('hubungi admin'))">
                                <div class="mt-2.5">
                                    <a href="{{ $waUrl }}" target="_blank"
                                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-emerald-100 hover:scale-[1.03] active:scale-95 cursor-pointer">
                                        <i class="fa-brands fa-whatsapp text-sm"></i> Tanya WhatsApp Admin
                                    </a>
                                </div>
                            </template> -->

                            {{-- Apology & WhatsApp button if user clicks disliked (tidak membantu) --}}
                            <template x-if="msg.sender === 'bot' && msg.disliked">
                                <div class="mt-3 p-3 bg-rose-50 dark:bg-zinc-800 border border-rose-100 dark:border-zinc-700 rounded-xl space-y-2.5">
                                    <p class="text-xs text-rose-700 dark:text-zinc-300 leading-relaxed font-semibold">
                                        Maaf atas ketidaknyamanannya. Karena jawaban asisten AI kurang membantu, Anda bisa langsung berdiskusi dengan admin sekolah kami melalui WhatsApp.
                                    </p>
                                    <a href="{{ $waUrl }}" target="_blank"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition-all shadow-sm shadow-emerald-100 hover:scale-[1.03] active:scale-95 cursor-pointer">
                                        <i class="fa-brands fa-whatsapp text-sm"></i> Hubungi WhatsApp Admin
                                    </a>
                                </div>
                            </template>
                        </div>

                        {{-- Feedback row (last bot message) --}}
                        <template x-if="msg.sender === 'bot' && index === messages.length - 1">
                            <div class="flex items-center gap-2 px-1">
                                <span class="text-[10px] text-gray-400">Membantu?</span>
                                <button @click="submitFeedback('like', msg)"
                                    class="text-[11px] transition-all duration-300 hover:scale-125 cursor-pointer p-0.5 rounded"
                                    :class="msg.liked ? 'text-emerald-500 scale-110 font-bold' : 'text-gray-400 hover:text-emerald-500'">
                                    <i :class="msg.liked ? 'fa-solid fa-thumbs-up text-emerald-500 animate-bounce' : 'fa-regular fa-thumbs-up'"></i>
                                </button>
                                <button @click="submitFeedback('dislike', msg)"
                                    class="text-[11px] transition-all duration-300 hover:scale-125 cursor-pointer p-0.5 rounded"
                                    :class="msg.disliked ? 'text-rose-500 scale-110 font-bold' : 'text-gray-400 hover:text-rose-500'">
                                    <i :class="msg.disliked ? 'fa-solid fa-thumbs-down text-rose-500 animate-pulse' : 'fa-regular fa-thumbs-down'"></i>
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
                    <img src="{{ asset ('assets/img/chatbot.png') }}" alt="">
                </div>
                <div class="bg-white border border-gray-100 rounded-2xl rounded-tl-sm shadow-sm px-4 py-3 flex items-center gap-1.5">
                    <span class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay:0s"></span>
                    <span class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay:0.15s"></span>
                    <span class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay:0.30s"></span>
                </div>
            </div>
        </div>
    </template>
</div>
