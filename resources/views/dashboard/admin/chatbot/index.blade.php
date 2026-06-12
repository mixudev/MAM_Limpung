@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bc = document.getElementById('breadcrumb');
        if (bc) bc.textContent = 'Konfigurasi AI Chatbot';
    });
</script>

<div class="space-y-5">

    {{-- Header --}}
    @include('dashboard.admin.chatbot.partials._header', [
        'title' => 'Konfigurasi',
        'subtitle' => 'Kelola API Key, basis pengetahuan, FAQ, dan pantau riwayat percakapan.'
    ])

    {{-- Flash / Errors --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 text-sm text-emerald-700 dark:text-emerald-400 font-mono">
        <i class="fa-solid fa-circle-check shrink-0"></i> {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="flex items-start gap-3 px-4 py-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 text-sm text-red-700 dark:text-red-400 font-mono">
        <i class="fa-solid fa-circle-xmark shrink-0 mt-0.5"></i>
        <ul class="list-disc list-inside space-y-0.5 text-xs">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- ══════════ TAB PANELS ══════════ --}}
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">

        <div id="panel-analytics">
            @include('dashboard.admin.chatbot.partials._analytics')
        </div>

        <div id="panel-apikeys" style="display:none">
            @include('dashboard.admin.chatbot.partials._apikeys')
        </div>

        <div id="panel-knowledge" style="display:none">
            @include('dashboard.admin.chatbot.partials._knowledge')
        </div>

        <div id="panel-faqs" style="display:none">
            @include('dashboard.admin.chatbot.partials._faqs')
        </div>

        <div id="panel-history" style="display:none">
            @include('dashboard.admin.chatbot.partials._history')
        </div>

        <div id="panel-logs" style="display:none">
            @include('dashboard.admin.chatbot.partials._logs')
        </div>

        <div id="panel-guide" style="display:none">
            @include('dashboard.admin.chatbot.partials._guide')
        </div>

    </div>

    {{-- Modals --}}
    @include('dashboard.admin.chatbot.partials._modals')
</div>

{{-- ══════════ SCRIPTS ══════════ --}}
<script>
// ─── TAB SWITCHING ────────────────────────────────────────────────────────────
const TABS = ['analytics', 'apikeys', 'knowledge', 'faqs', 'history', 'logs', 'guide'];

function chatbotTab(name) {
    TABS.forEach(function(t) {
        const panel = document.getElementById('panel-' + t);
        if (!panel) return;

        if (t === name) {
            panel.style.display = '';
        } else {
            panel.style.display = 'none';
        }
    });
}

// ─── CHATBOT MODALS ───────────────────────────────────────────────────────────

// API KEY MODAL
function openKeyModal(editMode, data) {
    var form = document.getElementById('keyForm');
    if (!form) return;

    var methodInput = document.getElementById('keyMethodInput');
    var apiKeyLabel = document.getElementById('keyApiLabel');
    var apiKeyInput = document.getElementById('keyApiInput');
    var submitBtn   = document.getElementById('keySubmitBtn');
    var titleEl     = document.getElementById('keyModalMode');

    if (editMode) {
        form.action = '{{ url('admin/chatbot/apikeys') }}/' + data.id;
        methodInput.value = 'PUT';
        methodInput.disabled = false;
        apiKeyLabel.textContent = 'API Key (kosongkan jika tidak diubah)';
        apiKeyInput.required = false;
        apiKeyInput.value = '';
        submitBtn.textContent = 'Simpan Perubahan';
        if (titleEl) titleEl.textContent = 'Edit API Key';
        document.getElementById('keyProvider').value = data.provider;
        document.getElementById('keyModelName').value = data.model_name;
    } else {
        form.action = '{{ route('admin.chatbot.apikeys.store') }}';
        methodInput.value = 'PUT';
        methodInput.disabled = true;
        apiKeyLabel.textContent = 'API Key';
        apiKeyInput.required = true;
        apiKeyInput.value = '';
        submitBtn.textContent = 'Tambah API Key';
        if (titleEl) titleEl.textContent = 'Tambah API Key';
        document.getElementById('keyProvider').value = 'gemini';
        document.getElementById('keyModelName').value = 'gemini-2.5-flash';
    }
    AppModal.open('keyModal');
}

// KNOWLEDGE MODAL
function openKnowledgeModal(editMode, data) {
    var form = document.getElementById('knowledgeForm');
    if (!form) return;

    var methodInput = document.getElementById('knowledgeMethodInput');
    var submitBtn   = document.getElementById('knowledgeSubmitBtn');

    if (editMode) {
        form.action = '{{ url('admin/chatbot/knowledge') }}/' + data.id;
        methodInput.disabled = false;
        submitBtn.textContent = 'Simpan Perubahan';
        document.getElementById('kbTopic').value    = data.topic;
        document.getElementById('kbIsActive').value = data.is_active;
        document.getElementById('kbTitle').value    = data.title;
        document.getElementById('kbContent').value  = data.content;
    } else {
        form.action = '{{ route('admin.chatbot.knowledge.store') }}';
        methodInput.disabled = true;
        submitBtn.textContent = 'Tambah Pengetahuan';
        document.getElementById('kbTopic').value    = 'umum';
        document.getElementById('kbIsActive').value = '1';
        document.getElementById('kbTitle').value    = '';
        document.getElementById('kbContent').value  = '';
    }
    AppModal.open('knowledgeModal');
}

// FAQ MODAL
function openFaqModal(editMode, data) {
    var form = document.getElementById('faqForm');
    if (!form) return;

    var methodInput = document.getElementById('faqMethodInput');
    var submitBtn   = document.getElementById('faqSubmitBtn');

    if (editMode) {
        form.action = '{{ url('admin/chatbot/faqs') }}/' + data.id;
        methodInput.disabled = false;
        submitBtn.textContent = 'Simpan Perubahan';
        document.getElementById('faqTopic').value    = data.topic;
        document.getElementById('faqOrder').value    = data.order;
        document.getElementById('faqIsActive').value = data.is_active;
        document.getElementById('faqQuestion').value = data.question;
        document.getElementById('faqAnswer').value   = data.answer;
    } else {
        form.action = '{{ route('admin.chatbot.faqs.store') }}';
        methodInput.disabled = true;
        submitBtn.textContent = 'Tambah FAQ';
        document.getElementById('faqTopic').value    = 'umum';
        document.getElementById('faqOrder').value    = '0';
        document.getElementById('faqIsActive').value = '1';
        document.getElementById('faqQuestion').value = '';
        document.getElementById('faqAnswer').value   = '';
    }
    AppModal.open('faqModal');
}

// TRANSCRIPT MODAL
function openTranscript(sessionId) {
    var loadingEl  = document.getElementById('transcriptLoading');
    var contentEl  = document.getElementById('transcriptContent');
    var bubblesEl  = document.getElementById('transcriptBubbles');
    var metaTopicEl = document.getElementById('transcriptTopic');
    var metaUserEl  = document.getElementById('transcriptUser');
    var metaTimeEl  = document.getElementById('transcriptTime');

    loadingEl.style.display = '';
    contentEl.style.display = 'none';
    bubblesEl.innerHTML = '';
    AppModal.open('transcriptModal');

    fetch('{{ url('admin/chatbot/sessions') }}/' + sessionId, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        loadingEl.style.display = 'none';
        contentEl.style.display = '';

        metaTopicEl.textContent = d.topic || '—';
        metaUserEl.textContent  = d.user ? d.user.name : ('Tamu · ' + (d.user_ip || '—'));
        metaTimeEl.textContent  = d.created_at ? new Date(d.created_at).toLocaleString('id-ID') : '';

        var msgs = d.messages || [];
        if (msgs.length === 0) {
            bubblesEl.innerHTML = '<div class="flex items-center justify-center h-24 text-slate-400 text-xs font-mono">Sesi ini tidak memiliki pesan.</div>';
            return;
        }
        msgs.forEach(function(msg) {
            var isUser = msg.sender === 'user';
            var bubble = document.createElement('div');
            bubble.className = 'flex items-end gap-2 ' + (isUser ? 'justify-end' : 'justify-start');
            bubble.innerHTML = (isUser ? '' :
                '<div class="w-6 h-6 bg-[#4f45b2] text-white flex items-center justify-center text-[10px] shrink-0 mb-0.5"><i class="fa-solid fa-robot"></i></div>')
                + '<div class="max-w-[78%]">'
                    + '<div class="px-4 py-2.5 text-xs leading-relaxed ' + (isUser ? 'bg-[#4f45b2] text-white' : 'bg-slate-100 dark:bg-zinc-800 text-slate-800 dark:text-zinc-200 border border-slate-200 dark:border-zinc-700') + '">'
                        + '<p class="whitespace-pre-wrap">' + escHtml(msg.message) + '</p>'
                    + '</div>'
                    + '<div class="text-[10px] text-slate-400 font-mono mt-1 px-1 ' + (isUser ? 'text-right' : 'text-left') + '">' + (isUser ? 'Pengguna' : 'AI Chatbot') + '</div>'
                + '</div>'
                + (isUser ? '<div class="w-6 h-6 bg-slate-200 dark:bg-zinc-700 text-slate-500 flex items-center justify-center text-[10px] shrink-0 mb-0.5"><i class="fa-solid fa-user"></i></div>' : '');
            bubblesEl.appendChild(bubble);
        });
        bubblesEl.scrollTop = bubblesEl.scrollHeight;
    })
    .catch(function() {
        loadingEl.style.display = 'none';
        contentEl.style.display = '';
        bubblesEl.innerHTML = '<div class="text-center text-xs text-red-500 font-mono py-4">Gagal memuat transkrip.</div>';
    });
}

function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
}

// PAYLOAD MODAL
function openPayloadModal(payload) {
    var codeEl = document.getElementById('payloadCode');
    if (codeEl) {
        if (payload && payload.fallback_sequence && payload.fallback_sequence.length > 0) {
            var html = '<div class="space-y-4 text-left">';
            html += '<h3 class="text-rose-500 font-bold mb-2 uppercase tracking-wider text-xs flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> Rantai / Urutan Percobaan API Fallback:</h3>';
            payload.fallback_sequence.forEach(function(attempt, index) {
                html += '<div class="p-3.5 bg-rose-50/40 dark:bg-rose-950/10 border border-rose-200 dark:border-rose-900/40 font-mono text-[11px] leading-relaxed rounded">';
                html += '<div class="font-bold text-rose-700 dark:text-rose-400 flex justify-between"><span>Percobaan #' + (index + 1) + ' · API Key ID: ' + attempt.api_key_id + ' (' + String(attempt.provider).toUpperCase() + ')</span><span class="text-[10px] text-slate-400">' + new Date(attempt.occurred_at).toLocaleTimeString("id-ID") + '</span></div>';
                html += '<div class="mt-1.5"><span class="text-slate-400 font-semibold">Model Name:</span> <span class="text-slate-700 dark:text-zinc-300 font-semibold">' + attempt.model_name + '</span></div>';
                html += '<div class="mt-1"><span class="text-slate-400 font-semibold">Tipe Error:</span> <span class="px-1.5 py-0.5 bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-400 font-bold uppercase rounded text-[9px]">' + attempt.error_type + '</span></div>';
                html += '<div class="mt-1.5 text-rose-600 dark:text-rose-300 break-words"><span class="text-slate-400 font-semibold">Pesan:</span> ' + escHtml(attempt.error_message) + '</div>';
                html += '</div>';
            });
            html += '<div class="mt-4 pt-3 border-t border-slate-200 dark:border-zinc-800"><span class="font-bold text-slate-500 dark:text-zinc-400 font-mono uppercase text-[10px] tracking-wider">Payload Lengkap:</span></div>';
            html += '<pre class="mt-1 p-3 bg-slate-900 dark:bg-black border border-slate-200 dark:border-zinc-800 text-[10px] text-emerald-400 whitespace-pre-wrap overflow-x-auto rounded">' + escHtml(JSON.stringify(payload, null, 2)) + '</pre>';
            html += '</div>';
            codeEl.innerHTML = html;
        } else {
            codeEl.innerHTML = '<pre class="whitespace-pre-wrap overflow-x-auto text-[11px] text-slate-700 dark:text-zinc-300">' + escHtml(JSON.stringify(payload, null, 2)) + '</pre>';
        }
    }
    AppModal.open('payloadModal');
}

// Show/hide API key password
function toggleKeyVisibility() {
    var inp  = document.getElementById('keyApiInput');
    var icon = document.getElementById('keyEyeIcon');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'fa-solid fa-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'fa-solid fa-eye';
    }
}

// ─── CHARTS ──────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    // Auto-activate tab from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    let tab = urlParams.get('tab');
    if (urlParams.has('logs_page')) {
        tab = 'logs';
    } else if (urlParams.has('page')) {
        tab = 'history';
    }
    if (tab && TABS.includes(tab)) {
        chatbotTab(tab);
    }

    var trafficCtx = document.getElementById('trafficChart');
    if (trafficCtx) {
        new Chart(trafficCtx, {
            type: 'line',
            data: {
                labels: [@foreach($traffic as $t)'{{ \Carbon\Carbon::parse($t->date)->format("d M") }}',@endforeach],
                datasets: [{
                    label: 'Jumlah Chat',
                    data: [@foreach($traffic as $t){{ $t->count }},@endforeach],
                    borderColor: '#4f45b2',
                    backgroundColor: 'rgba(79,69,178,0.07)',
                    borderWidth: 2, fill: true, tension: 0.4,
                    pointRadius: 4, pointBackgroundColor: '#4f45b2',
                    pointBorderColor: '#fff', pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { grid: { display: false } }, y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }

    var topicCtx = document.getElementById('topicChart');
    if (topicCtx) {
        new Chart(topicCtx, {
            type: 'doughnut',
            data: {
                labels: [@foreach($topicStats as $ts)'{{ strtoupper($ts->topic) }}',@endforeach],
                datasets: [{
                    data: [@foreach($topicStats as $ts){{ $ts->count }},@endforeach],
                    backgroundColor: ['#4f45b2','#06b6d4','#f59e0b','#10b981','#64748b'],
                    borderWidth: 2, borderColor: '#fff',
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 }, padding: 12 } } },
                cutout: '62%',
            }
        });
    }
});
</script>
@endsection
