{{-- ═══════════════════════════════════════════
     MODAL: API KEY
═══════════════════════════════════════════ --}}
<x-app-modal id="keyModal" maxWidth="md"
    title="Kunci API AI"
    description="Tambah atau perbarui API Key untuk model AI chatbot.">

    <form id="keyForm" method="POST">
        @csrf
        <input type="hidden" name="_method" id="keyMethodInput" value="PUT">

        <div class="space-y-4">
            <div>
                <label for="keyProvider">Provider</label>
                <select id="keyProvider" name="provider" onchange="handleProviderChange()">
                    <option value="gemini">Google Gemini</option>
                    <option value="groq">Groq AI</option>
                    <option value="deepseek">DeepSeek</option>
                    <option value="openrouter">OpenRouter</option>
                </select>
            </div>

            <div>
                <label for="keyModelName">Nama Model</label>
                <input id="keyModelName" type="text" name="model_name" required
                    placeholder="Contoh: gemini-2.5-flash">
            </div>

            <div>
                <label id="keyApiLabel" for="keyApiInput">API Key</label>
                <div style="position:relative">
                    <input id="keyApiInput" type="password" name="api_key"
                        placeholder="Masukkan API Key Anda"
                        autocomplete="off">
                    <button type="button" onclick="toggleKeyVisibility()"
                        style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0">
                        <i id="keyEyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-5 mt-2 border-t border-slate-100 dark:border-zinc-800">
            <button type="button" onclick="AppModal.close('keyModal')" class="modal-btn-cancel">Batal</button>
            <button type="submit" id="keySubmitBtn" class="modal-btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Tambah API Key
            </button>
        </div>
    </form>
</x-app-modal>

{{-- ═══════════════════════════════════════════
     MODAL: KNOWLEDGE BASE
═══════════════════════════════════════════ --}}
<x-app-modal id="knowledgeModal" maxWidth="lg"
    title="Basis Pengetahuan"
    description="Informasi ini digunakan AI sebagai konteks saat menjawab pertanyaan pengguna.">

    <form id="knowledgeForm" method="POST">
        @csrf
        <input type="hidden" name="_method" id="knowledgeMethodInput" value="PUT">

        <div class="space-y-4">
            <div>
                <label for="kbIsActive">Status</label>
                <select id="kbIsActive" name="is_active">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <div>
                <label for="kbTitle">Judul Informasi</label>
                <input id="kbTitle" type="text" name="title" required
                    placeholder="Contoh: Syarat Pendaftaran PPDB 2025">
            </div>

            <div>
                <label for="kbContent">Isi Konten</label>
                <textarea id="kbContent" name="content" required rows="7"
                    placeholder="Tulis informasi secara lengkap dan jelas. AI akan membaca ini sebagai referensi jawaban..."></textarea>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-5 mt-2 border-t border-slate-100 dark:border-zinc-800">
            <button type="button" onclick="AppModal.close('knowledgeModal')" class="modal-btn-cancel">Batal</button>
            <button type="submit" id="knowledgeSubmitBtn" class="modal-btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Tambah Pengetahuan
            </button>
        </div>
    </form>
</x-app-modal>

{{-- ═══════════════════════════════════════════
     MODAL: FAQ
═══════════════════════════════════════════ --}}
<x-app-modal id="faqModal" maxWidth="lg"
    title="FAQ Cepat"
    description="Pertanyaan pintasan di chatbot, dijawab instan tanpa memanggil AI.">

    <form id="faqForm" method="POST">
        @csrf
        <input type="hidden" name="_method" id="faqMethodInput" value="PUT">

        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="faqOrder">Urutan Tampil</label>
                    <input id="faqOrder" type="number" name="order" min="0" value="0">
                    <p style="font-size:11px;color:#94a3b8;margin-top:4px">Angka kecil tampil lebih dulu.</p>
                </div>
                <div>
                    <label for="faqIsActive">Status</label>
                    <select id="faqIsActive" name="is_active">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="faqQuestion">Pertanyaan</label>
                <input id="faqQuestion" type="text" name="question" required
                    placeholder="Contoh: Bagaimana cara mendaftar PPDB online?">
            </div>

            <div>
                <label for="faqAnswer">Jawaban Instan</label>
                <textarea id="faqAnswer" name="answer" required rows="5"
                    placeholder="Jawaban yang langsung ditampilkan tanpa memanggil server AI..."></textarea>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-5 mt-2 border-t border-slate-100 dark:border-zinc-800">
            <button type="button" onclick="AppModal.close('faqModal')" class="modal-btn-cancel">Batal</button>
            <button type="submit" id="faqSubmitBtn" class="modal-btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Tambah FAQ
            </button>
        </div>
    </form>
</x-app-modal>

{{-- ═══════════════════════════════════════════
     MODAL: TRANSCRIPT
═══════════════════════════════════════════ --}}
<x-app-modal id="transcriptModal" maxWidth="lg"
    title="Transkrip Percakapan"
    description="Log percakapan antara pengguna dengan asisten AI.">

    {{-- Loading --}}
    <div id="transcriptLoading" class="flex flex-col items-center justify-center py-14 gap-3">
        <div style="width:32px;height:32px;border:2px solid #e2e8f0;border-top-color:#4f45b2;border-radius:50%;animation:spin 0.7s linear infinite"></div>
        <p class="text-xs text-slate-400 font-mono">Memuat transkrip...</p>
    </div>

    {{-- Content --}}
    <div id="transcriptContent" style="display:none">
        <div class="flex flex-wrap items-center gap-2 mb-4 pb-4 border-b border-slate-100 dark:border-zinc-800 text-[11px] font-mono">
            <span id="transcriptUser" class="text-slate-500 dark:text-zinc-400">—</span>
            <span id="transcriptTime" class="text-slate-300 dark:text-zinc-600 ml-auto">—</span>
        </div>
        <div id="transcriptBubbles" class="space-y-3 overflow-y-auto pr-1" style="max-height:48vh;min-height:180px"></div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="AppModal.close('transcriptModal')" class="modal-btn-cancel">Tutup</button>
    </x-slot>
</x-app-modal>

{{-- ═══════════════════════════════════════════
     MODAL: PAYLOAD DETAIL
═══════════════════════════════════════════ --}}
<x-app-modal id="payloadModal" maxWidth="xl"
    title="Detail Payload & Exception"
    description="Detail data payload JSON atau stack trace error dari aktifitas chatbot.">

    <div class="font-mono text-xs leading-relaxed">
        <pre id="payloadCode" class="bg-slate-50 dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 p-4 text-[10px] text-slate-700 dark:text-zinc-300 overflow-x-auto whitespace-pre-wrap break-all"></pre>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="AppModal.close('payloadModal')" class="modal-btn-cancel">Tutup</button>
    </x-slot>
</x-app-modal>

{{-- Spinner keyframe --}}
<style>@keyframes spin { to { transform: rotate(360deg) } }</style>

<script>
function handleProviderChange() {
    var provider = document.getElementById('keyProvider').value;
    var modelInput = document.getElementById('keyModelName');
    if (!modelInput) return;
    
    if (provider === 'gemini') {
        modelInput.value = 'gemini-2.5-flash';
        modelInput.placeholder = 'Contoh: gemini-2.5-flash';
    } else if (provider === 'groq') {
        modelInput.value = 'llama-3.3-70b-versatile';
        modelInput.placeholder = 'Contoh: llama-3.3-70b-versatile';
    } else if (provider === 'deepseek') {
        modelInput.value = 'deepseek-chat';
        modelInput.placeholder = 'Contoh: deepseek-chat';
    } else if (provider === 'openrouter') {
        modelInput.value = 'google/gemini-2.5-flash';
        modelInput.placeholder = 'Contoh: google/gemini-2.5-flash';
    }
}
</script>
